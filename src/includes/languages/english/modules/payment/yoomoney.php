<?php
/*
*/

define('MODULE_PAYMENT_YOOMONEY_TEXT_TITLE', 'ЮMoney');
define('MODULE_PAYMENT_YOOMONEY_TEXT_PUBLIC_TITLE', 'ЮMoney');

define('MODULE_PAYMENT_YOOMONEY_TEXT_PUBLIC_DESCRIPTION', 'Choose payment way');
define('MODULE_PAYMENT_YOOMONEY_TEXT_DESCRIPTION', '<p>ЮKassa — the service that allows you to enable payment acceptance on the site.</p>
<ul> 
    <li><strong>Suitable for legal entities and individual entrepreneurs</strong>: you will need to sign a contract
    <li><strong>The money is credited to the current account</strong> — the day after the payment
    <li><strong>Payment methods</strong>: bank cards, ЮMoney, internet-banks, cash, mobile billing, etc
    <li><strong>The commission is taken from successful payments</strong>, the amount depends on the payment method and tariff
</ul>
<p><a href="https://yookassa.ru/en/" target="_blank">Go to the YooKassa website</a></p>
<br>
<strong>Address for notifications</strong><br />
{notification_url}<br>
');

define('MODULE_PAYMENT_YOOMONEY_TRUE', 'on');
define('MODULE_PAYMENT_YOOMONEY_FALSE', 'off');

define('MODULE_PAYMENT_YOOMONEY_PAID_STATUS_TEXT', 'Paid [YooMoney]');

define('MODULE_PAYMENT_YOOMONEY_SHOP_ID_LABEL', 'shopId');
define('MODULE_PAYMENT_YOOMONEY_SHOP_ID_DESCRIPTION', 'Copy your shopId from your YooKassa\'s Merchant Profile');
define('MODULE_PAYMENT_YOOMONEY_SHOP_PASSWORD_LABEL', 'Secret key');
define('MODULE_PAYMENT_YOOMONEY_SHOP_PASSWORD_DESCRIPTION', 'Issue and activate a secret key under your YooKassa\'s Merchant Profile. Then copy it here.');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_MODE_LABEL', 'Select payment method');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_BANK_CARD_LABEL', 'Bank cards');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_SBERBANK_LABEL', 'Sberbank Online');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_WEBMONEY_LABEL', 'Webmoney');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_ALFABANK_LABEL', 'Alfa-Click');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_TINKOFF_BANK_LABEL', 'Tinkoff online banking');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_QIWI_LABEL', 'QIWI Wallet');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_CASH_LABEL', 'Cash via payment kiosks');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_YOO_MONEY_LABEL', 'YooMoney');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_INSTALLMENTS_LABEL', 'Installments');

define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_BANK_CARD_TEXT', 'Bank cards');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_SBERBANK_TEXT', 'Sberbank Online');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_WEBMONEY_TEXT', 'Webmoney');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_ALFABANK_TEXT', 'Alfa-Click');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_TINKOFF_BANK_TEXT', 'Tinkoff online banking');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_QIWI_TEXT', 'QIWI Wallet');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_CASH_TEXT', 'Cash via payment kiosks');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_YOO_MONEY_TEXT', 'YooMoney');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_INSTALLMENTS_TEXT', 'Installments (%s ₽ per month)');

define('MODULE_PAYMENT_YOOMONEY_SORT_ORDER_LABEL', 'Сортировка');
define('MODULE_PAYMENT_YOOMONEY_ORDER_STATUS_LABEL', 'Order status after the payment');
define('MODULE_PAYMENT_YOOMONEY_SEND_RECEIPT_LABEL', 'Transmit details for receipts to YooKassa (Federal Law 54-FZ)');
define('MODULE_PAYMENT_YOOMONEY_DISPLAY_TITLE_LABEL', 'Название платежного сервиса');
define('MODULE_PAYMENT_YOOMONEY_DISPLAY_TITLE_DESCRIPTION', 'Это название увидит пользователь');
define('MODULE_PAYMENT_YOOMONEY_DISPLAY_TITLE_DEFAULT_VALUE', 'ЮKassa (банковские карты, электронные деньги и другое)');
define('MODULE_PAYMENT_YOOMONEY_ENABLE_LOG_LABEL', 'Debug log');
define('MODULE_PAYMENT_YOOMONEY_ENABLE_LOG_DESCRIPTION', 'Detailed logging of the payment process');

define('MODULE_PAYMENT_YOOMONEY_PAYMENT_DESCRIPTION_LABEL_LNG', 'Transaction data');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_DESCRIPTION_DESC_LNG', 'Full description of the transaction that the user will see during the checkout process. You can find it in your YooKassa Merchant Profile. For example, "Payment for order No. 72 by user@yoomoney.ru". Limitations: no more than 128 symbols.');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_DESCRIPTION_DEFAULT_LNG', 'Payment for order No. %order_id%');

define('MODULE_PAYMENT_YOOMONEY_QIWI_PHONE_LABEL', 'Телефон, который привязан к Qiwi Wallet');
define('MODULE_PAYMENT_YOOMONEY_QIWI_PHONE_DESCRIPTION', 'Укажите телефон');
define('MODULE_PAYMENT_YOOMONEY_ALFA_LOGIN_LABEL', 'Укажите логин, и мы выставим счет в Альфа-Клике. После этого останется подтвердить платеж на сайте интернет-банка.');
define('MODULE_PAYMENT_YOOMONEY_ALFA_LOGIN_DESCRIPTION', 'Укажите логин в альфа-клике');

define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE", "Payment way");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_PC", "YooMoney e-wallet");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_AC", "Any bank card");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_GP", "Cash via retailers and payment kiosks");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_MC", "Mobile phone balance");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_WM", "WebMoney e-wallet");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_AB", "Alfa-Click");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_SB", "Sberbank: payment by text messages or Sberbank Online");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_MA", "MasterPass");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_QW", "QIWI Wallet");

define('MODULE_PAYMENT_YOOMONEY_WITHOUT_VAT_LNG', 'Without VAT');
define('MODULE_PAYMENT_YOOMONEY_VAT_0_LNG', '0%');
define('MODULE_PAYMENT_YOOMONEY_VAT_10_LNG', '10%');
define('MODULE_PAYMENT_YOOMONEY_VAT_20_LNG', '20%');
define('MODULE_PAYMENT_YOOMONEY_VAT_10_110_LNG', 'Applicable rate 10/110');
define('MODULE_PAYMENT_YOOMONEY_VAT_20_120_LNG', 'Applicable rate 20/120');

define('MODULE_PAYMENT_YOOMONEY_MODE1', 'To the account of the organization with the conclusion of the contract with YooMoney');
define('MODULE_PAYMENT_YOOMONEY_MODE2', 'On account of the individual user YooMoney');

define('MODULE_PAYMENT_YOOMONEY_ORDER_NAME', 'Order');

define('MODULE_PAYMENT_YOOMONEY_TEST_LANG', 'Do you want use gateway in test mode?');
define('MODULE_PAYMENT_YOOMONEY_STATUS_LNG', 'Use YooMoney for payments?');
define('MODULE_PAYMENT_YOOMONEY_MODE_LNG', 'How you want to recieve payments?');
define('MODULE_PAYMENT_YOOMONEY_ONLY_ORG_LNG', '');
define('MODULE_PAYMENT_YOOMONEY_ONLY_IND_LNG', '');

define('MODULE_PAYMENT_YOOMONEY_ACCEPT_YOOMONEY_LNG', 'Оплата из кошелька в ЮMoney?');
define('MODULE_PAYMENT_YOOMONEY_ACCEPT_CASH_LNG', 'Оплата наличными через кассы и терминалы? ');
define('MODULE_PAYMENT_YOOMONEY_ACCEPT_MOBILE_LNG', 'Платеж со счета мобильного телефона? ');
define('MODULE_PAYMENT_YOOMONEY_ACCEPT_CARDS_LNG', 'Оплата с произвольной банковской карты? ');
define('MODULE_PAYMENT_YOOMONEY_WEBMONEY_LNG', 'Оплата из кошелька в системе WebMoney?');
define('MODULE_PAYMENT_YOOMONEY_AB_LNG', 'Оплата через Альфа-Клик?');
define('MODULE_PAYMENT_YOOMONEY_SB_LNG', 'Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн?');
define('MODULE_PAYMENT_YOOMONEY_MA_LNG', 'Оплата через MasterPass?');
define('MODULE_PAYMENT_YOOMONEY_PB_LNG', 'Оплата через интернет-банк Промсвязьбанка?');
define('MODULE_PAYMENT_YOOMONEY_QW_LNG', 'Оплата через QIWI Wallet?');
define('MODULE_PAYMENT_YOOMONEY_QP_LNG', 'Оплата через доверительный платеж (Куппи.ру)?');

define('MODULE_PAYMENT_YOOMONEY_PASSWORD_LNG', 'Secret word (shopPassword) for exchange messages');
define('MODULE_PAYMENT_YOOMONEY_SHOPID_LNG', 'Id of your shop in YooMoney - ShopID  ');
define('MODULE_PAYMENT_YOOMONEY_ARTICLEID_LNG', 'Id of your article in YooMoney - shoparticleid ');
define('MODULE_PAYMENT_YOOMONEY_ACCOUNT_LNG', 'YooMoney account ' );
define('MODULE_PAYMENT_YOOMONEY_SCID_LNG', 'Id of your showcase in YooMoney - scid');
define('MODULE_PAYMENT_YOOMONEY_SORT_LNG', 'Sort order');
define('MODULE_PAYMENT_YOOMONEY_SORT2_LNG', '(The smaller - the higher)');
define('MODULE_PAYMENT_YOOMONEY_ORDER_STATUS_LNG','Select the order status that will be assigned after the payment through this module' );
define('MODULE_PAYMENT_YOOMONEY_PAID_STATUS_LNG','Paid [YooMoney]' );

