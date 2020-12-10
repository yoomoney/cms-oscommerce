<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

define('YOOMONEY_MODULE_PATH', dirname(__FILE__));

function yooMoneyLoadClass($className)
{
    if (strncmp('YooMoney', $className, 8) === 0) {
        $path = YOOMONEY_MODULE_PATH;
        $length = 8;
    } else {
        return;
    }
    if (DIRECTORY_SEPARATOR === '/') {
        $path .= str_replace('\\', '/', substr($className, $length));
    } else {
        $path .= substr($className, $length);
    }
    $path .= '.php';
    if (file_exists($path)) {
        include_once $path;
    }
}

spl_autoload_register('yooMoneyLoadClass');
