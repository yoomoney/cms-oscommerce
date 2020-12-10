<?php

use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\NotificationEventType;
use YooKassa\Model\PaymentStatus;

if (!$_POST) {
    die("ERROR: Empty POST");
}

require('./includes/application_top.php');
$in = include_once('./includes/languages/'.$language.'/modules/payment/yoomoney.php');
require('./includes/modules/payment/yoomoney.php');

$callbackParams = $_POST;

/** @var YooMoneyObj $model */
$model = $GLOBALS['YooMoneyObject'];
if (MODULE_PAYMENT_YOOMONEY_MODE == MODULE_PAYMENT_YOOMONEY_MODE1) {
    $data          = file_get_contents('php://input');
    $paymentMethod = new YooMoney();
    if (empty($data)) {
        $paymentMethod->log('notice', 'Empty body in capture notification');

        return;
    }
    $json = @json_decode($data, true);
    if (empty($json)) {
        if (json_last_error() === JSON_ERROR_NONE) {
            $paymentMethod->log('notice', 'Empty object in body in capture notification');
        } else {
            $paymentMethod->log('notice', 'Invalid body in capture notification '.json_last_error_msg());
        }

        return;
    }
    if ($json['event'] == NotificationEventType::PAYMENT_WAITING_FOR_CAPTURE) {
        $notification = new NotificationWaitingForCapture($json);
    } else {
        $notification = new NotificationSucceeded($json);
    }

    $metadata = $notification->getObject()->getMetadata();
    if ($metadata === null || !$metadata->offsetExists('order_id')) {
        $paymentMethod->log('error', 'Empty metadata or empty order_id in metadata');
        header('HTTP/1.1 404 Order not exists');

        return;
    }
    $orderId = $metadata->offsetGet('order_id');
    if ($orderId > 0) {
        if ($notification->getEvent() == NotificationEventType::PAYMENT_SUCCEEDED) {
            $this->log(
                'info',
                'Notification succeseded init, notification body: '
                .json_encode($json)
            );
            $paymentObject = $notification->getObject();
            $payment       = $paymentMethod->getKassa()->fetchPayment($paymentObject->getId());
            if ($payment->getStatus() == PaymentStatus::SUCCEEDED) {
                $statusId = (int)DEFAULT_ORDERS_STATUS_ID;
                if (defined('MODULE_PAYMENT_YOOMONEY_ORDER_STATUS_ID') && MODULE_PAYMENT_YOOMONEY_ORDER_STATUS_ID > 0) {
                    $statusId = (int)MODULE_PAYMENT_YOOMONEY_ORDER_STATUS_ID;
                }
                updateOrderStatus($orderId, $payment->getAmount()->getValue(), $statusId, $payment->getId());
            }
        } else {
            $this->log(
                'info',
                'Notification waiting for capture init, notification body: '
                .json_encode($json)
            );
            $payment = $paymentMethod->getKassa()->capturePayment($notification->getObject());
            if ($payment !== null) {
                $statusId = (int)DEFAULT_ORDERS_STATUS_ID;
                if (defined('MODULE_PAYMENT_YOOMONEY_ORDER_STATUS_ID') && MODULE_PAYMENT_YOOMONEY_ORDER_STATUS_ID > 0) {
                    $statusId = (int)MODULE_PAYMENT_YOOMONEY_ORDER_STATUS_ID;
                }
                updateOrderStatus($orderId, $payment->getAmount()->getValue(), $statusId, $payment->getId());
            } else {
                header('HTTP/1.1 500 Internal server error');
            }
        }
    } else {
        header('HTTP/1.1 404 Order not exists');
    }
} else {
    $model->password  = MODULE_PAYMENT_YOOMONEY_PASSWORD;
    $model->shopid    = MODULE_PAYMENT_YOOMONEY_SHOPID;
    $model->test_mode = (MODULE_PAYMENT_YOOMONEY_TEST == MODULE_PAYMENT_YOOMONEY_TRUE);

    if (MODULE_PAYMENT_YOOMONEY_MODE == MODULE_PAYMENT_YOOMONEY_MODE2) {
        // YooMoney
        $check = $model->individualCheck($callbackParams);
        if (!$check) {
            exit;
        } else {
            $orderId = (int)$callbackParams['label'];
        }
    }

    if ($orderId > 0 && $callbackParams['action'] === 'paymentAviso') {

        $statusId = (int)DEFAULT_ORDERS_STATUS_ID;
        if (MODULE_PAYMENT_YOOMONEY_ORDER_STATUS_ID > 0) {
            $statusId = (int)MODULE_PAYMENT_YOOMONEY_ORDER_STATUS_ID;
        }
        updateOrderStatus($orderId, $callbackParams['orderSumAmount'], $statusId);
    }
}

function updateOrderStatus($orderId, $amount, $statusId, $comment = '')
{
    $where = "`orders_id` = '".tep_db_input($orderId)."'";
    $sql   = "SELECT `orders_status` FROM `".TABLE_ORDERS."` WHERE {$where} LIMIT 1";
    $query = tep_db_query($sql);

    if (tep_db_num_rows($query) > 0) {
        $order = tep_db_fetch_array($query);

        if ($statusId != $order['orders_status']) {
            $parameters = array(
                'orders_status' => $statusId,
                'last_modified' => 'now()',
            );
            tep_db_perform(TABLE_ORDERS, $parameters, 'update', $where);
        }

        $commentStatus = '';
        if (!empty($comment)) {
            $commentStatus = 'Payment id '.$comment;
        }
        $commentStatus .= ' ('.number_format($amount, 2, '.', '').')';
        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, array(
            'orders_id'         => $orderId,
            'orders_status_id'  => $statusId,
            'date_added'        => 'now()',
            'customer_notified' => '0',
            'comments'          => 'YooMoney Verified ['.$commentStatus.']',
        ));
    }
}