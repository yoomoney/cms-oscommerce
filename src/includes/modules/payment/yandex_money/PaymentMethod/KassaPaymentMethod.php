<?php

namespace YandexMoney\PaymentMethod;

use YaMoney\Client\YandexMoneyApi;
use YaMoney\Model\ConfirmationType;
use YaMoney\Model\PaymentInterface;
use YaMoney\Model\PaymentMethodType;
use YaMoney\Model\PaymentStatus;
use YaMoney\Request\Payments\CreatePaymentRequest;
use YaMoney\Request\Payments\Payment\CreateCaptureRequest;

/**
 * Class KassaPaymentMethod
 *
 * @package YandexMoneyModule\PaymentMethod
 */
class KassaPaymentMethod
{
    /**
     * @var \Yandex_Money
     */
    private $module;

    public function __construct(\Yandex_Money $module)
    {
        $this->module = $module;
    }

    /**
     * @return string
     */
    public function getShopId()
    {
        return MODULE_PAYMENT_YANDEX_MONEY_SHOP_ID;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return MODULE_PAYMENT_YANDEX_MONEY_SHOP_PASSWORD;
    }

    /**
     * @return bool
     */
    public function sendReceipt()
    {
        return defined('MODULE_PAYMENT_YANDEX_MONEY_SEND_RECEIPT')
            && (MODULE_PAYMENT_YANDEX_MONEY_SEND_RECEIPT == MODULE_PAYMENT_YANDEX_MONEY_TRUE);
    }

    public function createPayment($order, $paymentType, $returnUrl)
    {
        try {
            $builder = CreatePaymentRequest::builder();
            $builder->setAmount($order->info['total'])
                ->setCapture(false);
            $confirmation = array(
                'type' => ConfirmationType::REDIRECT,
                'returnUrl' => $returnUrl,
            );
            if (!empty($paymentType)) {
                if ($paymentType === PaymentMethodType::ALFABANK) {
                    $confirmation = ConfirmationType::EXTERNAL;
                    $paymentType = array(
                        'type' => $paymentType,
                        'login' => $_SESSION['ym_alfa_login'],
                    );
                } elseif ($paymentType === PaymentMethodType::QIWI) {
                    $paymentType = array(
                        'type' => $paymentType,
                        'phone' => $_SESSION['ym_qiwi_phone'],
                    );
                }
                $builder->setPaymentMethodData($paymentType);
            }
            $builder->setConfirmation($confirmation);
            if ($this->sendReceipt()) {
                $this->getReceipt($builder, $order);
            }
            $builder->setMetadata(array(
                'order_id' => $order->info['order_id'],
                'cms_name' => 'ya_api_oscommerce',
                'module_version' => \Yandex_Money::MODULE_VERSION,
            ));
            $request = $builder->build();
        } catch (\Exception $e) {
            $this->module->log('error', 'Failed to build payment request: ' . $e->getMessage());
            return null;
        }

        try {
            $tries = 0;
            $key = microtime(true);
            do {
                $payment = $this->getClient()->createPayment($request, $key);
                if ($payment === null) {
                    $tries++;
                    if ($tries > 3) {
                        break;
                    }
                    sleep(2);
                }
            } while ($payment === null);
        } catch (\Exception $e) {
            $this->module->log('error', 'Failed to create payment: ' . $e->getMessage());
            $payment = null;
        }

        if ($payment !== null) {
            $this->insertPayment($payment->getId(), $order->info['order_id']);
        }

        return $payment;
    }

    public function fetchPaymentIdByOrderId($orderId)
    {
        $sql = 'SELECT `payment_id` FROM `ym_payments` WHERE `order_id` = ' . (int)$orderId;
        $dataSet = tep_db_query($sql);
        $row = tep_db_fetch_array($dataSet);
        if (empty($row)) {
            return false;
        }
        return $row['payment_id'];
    }

    private function insertPayment($paymentId, $orderId)
    {
        $sql = 'INSERT INTO `ym_payments` (`order_id`, `payment_id`) VALUES ('
            . $orderId . ',\'' . tep_db_input($paymentId)
            . '\') ON DUPLICATE KEY UPDATE `payment_id` = VALUES(`payment_id`)';
        tep_db_query($sql);
    }

    /**
     * @param \YaMoney\Model\PaymentInterface $payment
     * @param bool $fetch
     * @return \YaMoney\Model\PaymentInterface|null
     */
    public function capturePayment($payment, $fetch = true)
    {
        if ($fetch) {
            $sourcePayment = $this->fetchPayment($payment->getId());
            if ($sourcePayment === null) {
                return null;
            }
        } else {
            $sourcePayment = $payment;
        }

        if ($sourcePayment->getStatus() !== PaymentStatus::WAITING_FOR_CAPTURE) {
            if ($sourcePayment->getStatus() !== PaymentStatus::SUCCEEDED) {
                $this->log(
                    'error',
                    'Notification about payment with wrong status, required: '
                    . PaymentStatus::WAITING_FOR_CAPTURE . ', received: ' . $sourcePayment->getStatus()
                );
                return null;
            }
            return $payment;
        }

        try {
            $builder = CreateCaptureRequest::builder();
            $builder->setAmount($sourcePayment->getAmount());
            $request = $builder->build();
        } catch (\Exception $e) {
            $this->module->log('error', 'Failed to build payment request: ' . $e->getMessage());
            return null;
        }

        try {
            $tries = 0;
            $key = microtime(true);
            do {
                $response = $this->getClient()->capturePayment($request, $payment->getId(), $key);
                if ($response === null) {
                    $tries++;
                    if ($tries > 3) {
                        break;
                    }
                    sleep(2);
                }
            } while ($response === null);
        } catch (\Exception $e) {
            $this->module->log('error', 'Failed to capture payment: ' . $e->getMessage());
            $response = null;
        }
        return $response;
    }

    /**
     * @param string $paymentId
     * @return PaymentInterface|null
     */
    public function fetchPayment($paymentId)
    {
        try {
            $payment = $this->getClient()->getPaymentInfo($paymentId);
        } catch (\Exception $e) {
            $payment = null;
        }
        return $payment;
    }

    /**
     * @param \YaMoney\Request\Payments\CreatePaymentRequestBuilder $builder
     * @param $order
     * @return mixed
     */
    private function getReceipt($builder, $order)
    {
        $taxes = array();
        $q = tep_db_query('SELECT * FROM '.TABLE_CONFIGURATION.' WHERE configuration_key LIKE \'MODULE_PAYMENT_YANDEXMONEY_TAXES_%\'');
        while ($rows = tep_db_fetch_array($q)) {
            $id = str_replace('MODULE_PAYMENT_YANDEXMONEY_TAXES_', '', $rows['configuration_key']);
            $taxes[(int)$id] = $rows['configuration_value'];
        }
        $builder->setReceiptEmail($order->customer['email_address'])
            ->setTaxSystemCode(1);

        foreach ($order->products as $product) {
            $tax_query = tep_db_query("
SELECT
    tr.tax_class_id,
    tr.tax_rate,
    tr.tax_rates_id
FROM " . TABLE_TAX_RATES . " tr
    LEFT JOIN " . TABLE_ZONES_TO_GEO_ZONES . " za ON tr.tax_zone_id = za.geo_zone_id
    LEFT JOIN " . TABLE_GEO_ZONES . " tz ON tz.geo_zone_id = tr.tax_zone_id
    LEFT JOIN ".TABLE_PRODUCTS." tp ON tp.products_tax_class_id = tr.tax_class_id 
WHERE (
        za.zone_country_id IS NULL
        OR za.zone_country_id = '0'
        OR za.zone_country_id = '" . (int)$order->delivery['country_id'] . "'
    )
    AND (za.zone_id IS NULL OR za.zone_id = '0' OR za.zone_id = '" . (int)$order->delivery['zone_id'] . "') 
    AND tp.products_id = '" . (int)$product['id'] . "'
GROUP BY tr.tax_priority"
            );

            if ($tax = tep_db_fetch_array($tax_query)) {
                if (isset($taxes[$tax['tax_rates_id']])) {
                    $taxId = $taxes[$tax['tax_rates_id']];
                    $builder->addReceiptItem($product['name'], $product['final_price'], $product['qty'], $taxId);
                } else {
                    $builder->addReceiptItem($product['name'], $product['final_price'], $product['qty']);
                }
            } else {
                $builder->addReceiptItem($product['name'], $product['final_price'], $product['qty']);
            }
        }

        if ($order->info && $order->info['shipping_cost'] > 0) {
            $builder->addReceiptShipping('Доставка - ' . $order->info['shipping_method'], $order->info['shipping_cost']);
        }
    }

    /**
     * @var \YaMoney\Client\YandexMoneyApi
     */
    private $client;

    /**
     * @return \YaMoney\Client\YandexMoneyApi
     */
    private function getClient()
    {
        if ($this->client === null) {
            $this->client = new YandexMoneyApi();
            $this->client->setAuth($this->getShopId(), $this->getPassword());
            $this->client->setLogger($this->module);
        }
        return $this->client;
    }
}