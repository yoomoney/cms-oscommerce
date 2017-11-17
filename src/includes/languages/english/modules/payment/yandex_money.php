<?php
/*
*/

define('MODULE_PAYMENT_YANDEX_MONEY_TEXT_TITLE', 'Яндекс.Деньги 2.0');
define('MODULE_PAYMENT_YANDEX_MONEY_TEXT_PUBLIC_TITLE', 'Яндекс.Деньги 2.0');

define('MODULE_PAYMENT_YANDEX_MONEY_TEXT_PUBLIC_DESCRIPTION', 'Choose payment way');
define('MODULE_PAYMENT_YANDEX_MONEY_TEXT_DESCRIPTION', '<p>Яндекс.Касса — сервис, который позволяет включить прием платежей на сайте.</p>
<ul> 
    <li><strong>Подходит для юрлиц и ИП</strong>: потребуется заключить договор
    <li><strong>Деньги зачисляются на расчетный счет</strong> — на следующий день после платежа
    <li><strong>Способы приема платежей</strong>: банковские карты, Яндекс.Деньги, интернет-банки, наличные, счет мобильного и другие
    <li><strong>Комиссия берется с успешных платежей</strong>, размер зависит от способа оплаты и тарифа
</ul>
<p><a href="https://kassa.yandex.ru/" target="_blank">Перейти на сайт Яндекс.Кассы</a></p>
<br>
<strong>Адрес для уведомлений</strong><br />
{notification_url}<br>
');

define('MODULE_PAYMENT_YANDEX_MONEY_TRUE', 'on');
define('MODULE_PAYMENT_YANDEX_MONEY_FALSE', 'off');

define('MODULE_PAYMENT_YANDEX_MONEY_PAID_STATUS_TEXT', 'Paid [YandexMoney]');

define('MODULE_PAYMENT_YANDEX_MONEY_SHOP_ID_LABEL', 'shopId');
define('MODULE_PAYMENT_YANDEX_MONEY_SHOP_ID_DESCRIPTION', 'Скопируйте shopId из личного кабинета Яндекс.Кассы');
define('MODULE_PAYMENT_YANDEX_MONEY_SHOP_PASSWORD_LABEL', 'Секретный ключ');
define('MODULE_PAYMENT_YANDEX_MONEY_SHOP_PASSWORD_DESCRIPTION', 'Выпустите и активируйте секретный ключ в личном кабинете Яндекс.Кассы. Потом скопируйте его сюда.');
define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_MODE_LABEL', 'Выбор способа оплаты на стороне магазина');
define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_METHOD_BANK_CARD_LABEL', 'Банковские карты');
define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_METHOD_SBERBANK_LABEL', 'Сбербанк Онлайн');
define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_METHOD_WEBMONEY_LABEL', 'Webmoney');
define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_METHOD_ALFABANK_LABEL', 'Альфа-Клик');
define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_METHOD_QIWI_LABEL', 'QIWI Wallet');
define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_METHOD_CASH_LABEL', 'Оплата наличными через терминалы');
define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_METHOD_YANDEX_MONEY_LABEL', 'Яндекс.Деньги');

define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_METHOD_BANK_CARD_TEXT', 'Банковские карты');
define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_METHOD_SBERBANK_TEXT', 'Сбербанк Онлайн');
define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_METHOD_WEBMONEY_TEXT', 'Webmoney');
define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_METHOD_ALFABANK_TEXT', 'Альфа-Клик');
define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_METHOD_QIWI_TEXT', 'QIWI Wallet');
define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_METHOD_CASH_TEXT', 'Оплата наличными через терминалы');
define('MODULE_PAYMENT_YANDEX_MONEY_PAYMENT_METHOD_YANDEX_MONEY_TEXT', 'Яндекс.Деньги');

define('MODULE_PAYMENT_YANDEX_MONEY_SORT_ORDER_LABEL', 'Сортировка');
define('MODULE_PAYMENT_YANDEX_MONEY_ORDER_STATUS_LABEL', 'Статус заказа после создания');
define('MODULE_PAYMENT_YANDEX_MONEY_SEND_RECEIPT_LABEL', 'Отправлять данные чеков');
define('MODULE_PAYMENT_YANDEX_MONEY_DISPLAY_TITLE_LABEL', 'Название платежного сервиса');
define('MODULE_PAYMENT_YANDEX_MONEY_DISPLAY_TITLE_DESCRIPTION', 'Это название увидит пользователь');
define('MODULE_PAYMENT_YANDEX_MONEY_DISPLAY_TITLE_DEFAULT_VALUE', 'Яндекс.Касса (банковские карты, электронные деньги и другое)');
define('MODULE_PAYMENT_YANDEX_MONEY_ENABLE_LOG_LABEL', 'Debug log');
define('MODULE_PAYMENT_YANDEX_MONEY_ENABLE_LOG_DESCRIPTION', 'одробное логгирование процесса проведения оплаты');

define('MODULE_PAYMENT_YANDEX_MONEY_QIWI_PHONE_LABEL', 'Телефон, который привязан к Qiwi Wallet');
define('MODULE_PAYMENT_YANDEX_MONEY_QIWI_PHONE_DESCRIPTION', 'Укажите телефон');
define('MODULE_PAYMENT_YANDEX_MONEY_ALFA_LOGIN_LABEL', 'Укажите логин, и мы выставим счет в Альфа-Клике. После этого останется подтвердить платеж на сайте интернет-банка.');
define('MODULE_PAYMENT_YANDEX_MONEY_ALFA_LOGIN_DESCRIPTION', 'Укажите логин в альфа-клике');

define("MODULE_PAYMENT_YANDEXMONEY_TEXT_PAYMENT_TYPE", "Payment way");
define("MODULE_PAYMENT_YANDEXMONEY_TEXT_PAYMENT_TYPE_PC", "Yandex.Money e-wallet");
define("MODULE_PAYMENT_YANDEXMONEY_TEXT_PAYMENT_TYPE_AC", "Any bank card");
define("MODULE_PAYMENT_YANDEXMONEY_TEXT_PAYMENT_TYPE_GP", "Cash via retailers and payment kiosks");
define("MODULE_PAYMENT_YANDEXMONEY_TEXT_PAYMENT_TYPE_MC", "Mobile phone balance");
define("MODULE_PAYMENT_YANDEXMONEY_TEXT_PAYMENT_TYPE_WM", "WebMoney e-wallet");
define("MODULE_PAYMENT_YANDEXMONEY_TEXT_PAYMENT_TYPE_AB", "Alfa-Click");
define("MODULE_PAYMENT_YANDEXMONEY_TEXT_PAYMENT_TYPE_SB", "Sberbank: payment by text messages or Sberbank Online");
define("MODULE_PAYMENT_YANDEXMONEY_TEXT_PAYMENT_TYPE_MA", "MasterPass");
define("MODULE_PAYMENT_YANDEXMONEY_TEXT_PAYMENT_TYPE_PB", "Promsvyazbank");
define("MODULE_PAYMENT_YANDEXMONEY_TEXT_PAYMENT_TYPE_QW", "QIWI Wallet");
define("MODULE_PAYMENT_YANDEXMONEY_TEXT_PAYMENT_TYPE_QP", "Trust payment (Qppi.ru)");

define('MODULE_PAYMENT_YANDEXMONEY_MODE1', 'To the account of the organization with the conclusion of the contract with Yandex.Money');
define('MODULE_PAYMENT_YANDEXMONEY_MODE2', 'On account of the individual user Yandex.Money');
define('MODULE_PAYMENT_YANDEXMONEY_MODE3', 'Yandex.Billing (bank card, e-wallets)');

define('MODULE_PAYMENT_YANDEXMONEY_ORDER_NAME', 'Order');

define('MODULE_PAYMENT_YANDEXMONEY_TEST_LANG', 'Do you want use gateway in test mode?');
define('MODULE_PAYMENT_YANDEXMONEY_STATUS_LNG', 'Use Yandex.Money for payments?');
define('MODULE_PAYMENT_YANDEXMONEY_MODE_LNG', 'How you want to recieve payments?');
define('MODULE_PAYMENT_YANDEXMONEY_ONLY_ORG_LNG', '');
define('MODULE_PAYMENT_YANDEXMONEY_ONLY_IND_LNG', '');

define('MODULE_PAYMENT_YANDEXMONEY_ACCEPT_YANDEXMONEY_LNG', 'Оплата из кошелька в Яндекс.Деньгах?');
define('MODULE_PAYMENT_YANDEXMONEY_ACCEPT_CASH_LNG', 'Оплата наличными через кассы и терминалы? ');
define('MODULE_PAYMENT_YANDEXMONEY_ACCEPT_MOBILE_LNG', 'Платеж со счета мобильного телефона? ');
define('MODULE_PAYMENT_YANDEXMONEY_ACCEPT_CARDS_LNG', 'Оплата с произвольной банковской карты? ');
define('MODULE_PAYMENT_YANDEXMONEY_WEBMONEY_LNG', 'Оплата из кошелька в системе WebMoney?');
define('MODULE_PAYMENT_YANDEXMONEY_AB_LNG', 'Оплата через Альфа-Клик?');
define('MODULE_PAYMENT_YANDEXMONEY_SB_LNG', 'Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн?');
define('MODULE_PAYMENT_YANDEXMONEY_MA_LNG', 'Оплата через MasterPass?');
define('MODULE_PAYMENT_YANDEXMONEY_PB_LNG', 'Оплата через интернет-банк Промсвязьбанка?');
define('MODULE_PAYMENT_YANDEXMONEY_QW_LNG', 'Оплата через QIWI Wallet?');
define('MODULE_PAYMENT_YANDEXMONEY_QP_LNG', 'Оплата через доверительный платеж (Куппи.ру)?');

define('MODULE_PAYMENT_YANDEXMONEY_PASSWORD_LNG', 'Secret word (shopPassword) for exchange messages');
define('MODULE_PAYMENT_YANDEXMONEY_SHOPID_LNG', 'Id of your shop in YandexMoney - ShopID  ');
define('MODULE_PAYMENT_YANDEXMONEY_ARTICLEID_LNG', 'Id of your article in YandexMoney - shoparticleid ');
define('MODULE_PAYMENT_YANDEXMONEY_ACCOUNT_LNG', 'YandexMoney account ' );
define('MODULE_PAYMENT_YANDEXMONEY_SCID_LNG', 'Id of your showcase in YandexMoney - scid');
define('MODULE_PAYMENT_YANDEXMONEY_SORT_LNG', 'Sort order');
define('MODULE_PAYMENT_YANDEXMONEY_SORT2_LNG', '(The smaller - the higher)');
define('MODULE_PAYMENT_YANDEXMONEY_ORDER_STATUS_LNG','Select the order status that will be assigned after the payment through this module' );
define('MODULE_PAYMENT_YANDEXMONEY_PAID_STATUS_LNG','Paid [YandexMoney]' );

define('MODULE_PAYMENT_YANDEXMONEY_BILLING_STATUS_LNG', 'Activate payments via Yandex.Billing');
define('MODULE_PAYMENT_YANDEXMONEY_BILLING_ID_LNG', 'Form ID');
define('MODULE_PAYMENT_YANDEXMONEY_BILLING_PURPOSE_LNG', 'Payment purpose');
define('MODULE_PAYMENT_YANDEXMONEY_BILLING_PURPOSE_DESC_LNG', 'Payment purpose is added to the payment order: specify whatever will help identify the order paid via Yandex.Billing');
define('MODULE_PAYMENT_YANDEXMONEY_BILLING_PURPOSE_DEF_LNG', 'Order No. %order_is% Payment via Yandex.Billing');
define('MODULE_PAYMENT_YANDEXMONEY_BILLING_ORDER_STATUS_ID_LNG', 'Order status');
define('MODULE_PAYMENT_YANDEXMONEY_BILLING_ORDER_STATUS_ID_DESC_LNG', 'Order status shows the payment result is unknown: you can only learn whether the client made payment or not from an email notification or in your bank');
define('MODULE_PAYMENT_YANDEXMONEY_BILLING_FIO_LABEL', 'Payer\'s full name');
define('MODULE_PAYMENT_YANDEXMONEY_BILLING_TITLE', 'Yandex.Billing (bank card, e-wallets)');
