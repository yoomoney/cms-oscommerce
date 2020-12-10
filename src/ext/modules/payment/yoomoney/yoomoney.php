<?php

define('YM_PC', 'PC');
define('YM_AC', 'AC');
define('YM_GP', 'GP');
define('YM_MC', 'MC');
define('YM_WM', 'WM');
define('YM_AB', 'AB');
define('YM_SB', 'SB');
define('YM_MA', 'MA');
define('YM_PB', 'PB');
define('YM_QW', 'QW');
define('YM_QP', 'QP');

if (!defined('MODULE_PAYMENT_YOOMONEY_MODE1') && $cfgModules && $language){
    $module_language_directory = $cfgModules->get('payment', 'language_directory');
    $in = include_once($module_language_directory.$language.'/modules/payment/yoomoney.php');
}

// Устанавливаем режим работы:
// MODULE_PAYMENT_YOOMONEY_MODE1 - ЮKassa
// MODULE_PAYMENT_YOOMONEY_MODE2 - ЮMoney
define('MODULE_PAYMENT_YOOMONEY_MODE', MODULE_PAYMENT_YOOMONEY_MODE1);

Class YooMoneyObj
{
    public $test_mode;
    public $mode;

    public $order_id;

    public $reciver;
    public $formcomment;
    public $short_dest;
    public $writable_targets = 'false';
    public $comment_needed = 'true';
    public $label;
    public $quickpay_form = 'shop';
    public $payment_type = '';
    public $targets;
    public $sum;
    public $comment;
    public $need_fio = 'true';
    public $need_email = 'true';
    public $need_phone = 'true';
    public $need_address = 'true';

    public $shopid;
    public $password;
    public $epl;

    /*constructor*/
    public function __construct()
    {

    }

    public function getFormUrl()
    {
        if ($this->mode === YooMoney::MODE_MONEY) {
            return $this->individualGetFormUrl();
        }

        return '';
    }

    public function individualGetFormUrl()
    {
        return 'https://yoomoney.ru/quickpay/confirm.xml';
    }

    public function checkSign($callbackParams)
    {
        $string = $callbackParams['action'] . ';' . $callbackParams['orderSumAmount'] . ';' . $callbackParams['orderSumCurrencyPaycash'] . ';' . $callbackParams['orderSumBankPaycash'] . ';' . $callbackParams['shopId'] . ';' . $callbackParams['invoiceId'] . ';' . $callbackParams['customerNumber'] . ';' . $this->password;
        $md5 = strtoupper(md5($string));
        return (strtoupper($callbackParams['md5']) === $md5);
    }

    public function checkOrder($callbackParams, $sum = 0)
    {
        if ($this->checkSign($callbackParams)) {
            if (number_format($sum, 2) == number_format($callbackParams['orderSumAmount'], 2)) {
                $code = 0;
            } else {
                $code = 100;
            }
        } else {
            $code = 1;
        }
        return $code;
    }

    public function sendCode($callbackParams, $code)
    {
        header("Content-type: text/xml; charset=utf-8");
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <'.$callbackParams['action'].'Response performedDatetime="'.date("c").'" code="'.$code.'" invoiceId="'.$callbackParams['invoiceId'].'" shopId="'.$this->shopid.'"/>';
        echo $xml;
    }

    public function individualCheck($callbackParams)
    {
        $string = $callbackParams['notification_type'].'&'.$callbackParams['operation_id'].'&'.$callbackParams['amount'].'&'.$callbackParams['currency'].'&'.$callbackParams['datetime'].'&'.$callbackParams['sender'].'&'.$callbackParams['codepro'].'&'.$this->password.'&'.$callbackParams['label'];
        $check = (sha1($string) == $callbackParams['sha1_hash']);
        if (!$check){
            header('HTTP/1.0 401 Unauthorized');
            return false;
        }
        return true;
    }

    /**
     * @param string $tpl
     * @param int $id
     * @param order $order
     * @return string
     */
    public function parsePlaceholders($tpl, $id, $order)
    {
        $replace = array(
            '%order_id%' => $id,
        );
        foreach ($order->info as $key => $value) {
            if (is_scalar($value)) {
                $replace['%' . $key . '%'] = $value;
            }
        }
        foreach ($order as $key => $value) {
            if ((is_array($value) || is_object($value)) && $key != 'info') {
                foreach ($value as $k => $v) {
                    if (is_scalar($v)) {
                        $replace['%' . $key . '_' . $k . '%'] = $v;
                    }
                }
            }
        }
        return strtr($tpl, $replace);
    }
}
