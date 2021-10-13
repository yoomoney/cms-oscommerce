<?php
/*
*/

define('MODULE_PAYMENT_YOOMONEY_TEXT_TITLE', 'ЮMoney');
define('MODULE_PAYMENT_YOOMONEY_TEXT_PUBLIC_TITLE', 'ЮMoney');

define('MODULE_PAYMENT_YOOMONEY_TEXT_PUBLIC_DESCRIPTION', 'Выберите способ оплаты');
define('MODULE_PAYMENT_YOOMONEY_TEXT_DESCRIPTION', '<p>ЮKassa — сервис, который позволяет включить прием платежей на сайте.</p>
<ul> 
    <li><strong>Подходит для юрлиц и ИП</strong>: потребуется заключить договор
    <li><strong>Деньги зачисляются на расчетный счет</strong> — на следующий день после платежа
    <li><strong>Способы приема платежей</strong>: банковские карты, ЮMoney, интернет-банки, наличные, счет мобильного и другие
    <li><strong>Комиссия берется с успешных платежей</strong>, размер зависит от способа оплаты и тарифа
</ul>
<p><a href="https://yookassa.ru/" target="_blank">Перейти на сайт ЮKassa</a></p>
<br>
<strong>Адрес для уведомлений</strong><br />
{notification_url}<br>
');

define('MODULE_PAYMENT_YOOMONEY_TRUE', 'on');
define('MODULE_PAYMENT_YOOMONEY_FALSE', 'off');

define('MODULE_PAYMENT_YOOMONEY_PAID_STATUS_TEXT', 'Paid [YooMoney]');

define('MODULE_PAYMENT_YOOMONEY_SHOP_ID_LABEL', 'shopId');
define('MODULE_PAYMENT_YOOMONEY_SHOP_ID_DESCRIPTION', 'Скопируйте shopId из личного кабинета ЮKassa');
define('MODULE_PAYMENT_YOOMONEY_SHOP_PASSWORD_LABEL', 'Секретный ключ');
define('MODULE_PAYMENT_YOOMONEY_SHOP_PASSWORD_DESCRIPTION', 'Выпустите и активируйте секретный ключ в личном кабинете ЮKassa. Потом скопируйте его сюда.');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_MODE_LABEL', 'Выбор способа оплаты на стороне магазина');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_BANK_CARD_LABEL', 'Банковские карты');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_SBERBANK_LABEL', 'SberPay');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_WEBMONEY_LABEL', 'Webmoney');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_ALFABANK_LABEL', 'Альфа-Клик');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_TINKOFF_BANK_LABEL', 'Интернет-банк Тинькофф');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_QIWI_LABEL', 'QIWI Wallet');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_CASH_LABEL', 'Оплата наличными через терминалы');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_YOO_MONEY_LABEL', 'ЮMoney');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_INSTALLMENTS_LABEL', 'Заплатить по частям');

define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_BANK_CARD_TEXT', 'Банковские карты');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_SBERBANK_TEXT', 'SberPay');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_WEBMONEY_TEXT', 'Webmoney');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_ALFABANK_TEXT', 'Альфа-Клик');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_TINKOFF_BANK_TEXT', 'Интернет-банк Тинькофф');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_QIWI_TEXT', 'QIWI Wallet');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_CASH_TEXT', 'Оплата наличными через терминалы');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_YOO_MONEY_TEXT', 'ЮMoney');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_METHOD_INSTALLMENTS_TEXT', 'Заплатить по частям (%s ₽ в месяц)');

define('MODULE_PAYMENT_YOOMONEY_SORT_ORDER_LABEL', 'Сортировка');
define('MODULE_PAYMENT_YOOMONEY_ORDER_STATUS_LABEL', 'Статус заказа после создания');
define('MODULE_PAYMENT_YOOMONEY_SEND_RECEIPT_LABEL', 'Отправлять в ЮKassa данные для чеков (54-ФЗ)');
define('MODULE_PAYMENT_YOOMONEY_DISPLAY_TITLE_LABEL', 'Название платежного сервиса');
define('MODULE_PAYMENT_YOOMONEY_DISPLAY_TITLE_DESCRIPTION', 'Это название увидит пользователь');
define('MODULE_PAYMENT_YOOMONEY_DISPLAY_TITLE_DEFAULT_VALUE', 'ЮKassa (банковские карты, электронные деньги и другое)');
define('MODULE_PAYMENT_YOOMONEY_ENABLE_LOG_LABEL', 'Debug log');
define('MODULE_PAYMENT_YOOMONEY_ENABLE_LOG_DESCRIPTION', 'Подробное логгирование процесса проведения оплаты');

define('MODULE_PAYMENT_YOOMONEY_PAYMENT_DESCRIPTION_LABEL_LNG', 'Описание платежа');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_DESCRIPTION_DESC_LNG', 'Это описание транзакции, которое пользователь увидит при оплате, а вы — в личном кабинете ЮKassa.');
define('MODULE_PAYMENT_YOOMONEY_PAYMENT_DESCRIPTION_DEFAULT_LNG', 'Оплата заказа №%order_id%');

define('MODULE_PAYMENT_YOOMONEY_QIWI_PHONE_LABEL', 'Телефон, который привязан к Qiwi Wallet');
define('MODULE_PAYMENT_YOOMONEY_QIWI_PHONE_DESCRIPTION', 'Укажите телефон');
define('MODULE_PAYMENT_YOOMONEY_ALFA_LOGIN_LABEL', 'Укажите логин, и мы выставим счет в Альфа-Клике. После этого останется подтвердить платеж на сайте интернет-банка.');
define('MODULE_PAYMENT_YOOMONEY_ALFA_LOGIN_DESCRIPTION', 'Укажите логин в альфа-клике');

define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE", "Способ оплаты");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_PC", "Кошелек ЮMoney");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_AC", "Банковская карта");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_GP", "Наличными через кассы и терминалы");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_MC", "Счет мобильного телефона");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_WM", "Кошелек WebMoney");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_AB", "Альфа-Клик");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_SB", "Сбербанк: оплата по SMS или SberPay");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_MA", "MasterPass");
define("MODULE_PAYMENT_YOOMONEY_TEXT_PAYMENT_TYPE_QW", "QIWI Wallet");

define('MODULE_PAYMENT_YOOMONEY_WITHOUT_VAT_LNG', 'Без НДС');
define('MODULE_PAYMENT_YOOMONEY_VAT_0_LNG', '0%');
define('MODULE_PAYMENT_YOOMONEY_VAT_10_LNG', '10%');
define('MODULE_PAYMENT_YOOMONEY_VAT_20_LNG', '20%');
define('MODULE_PAYMENT_YOOMONEY_VAT_10_110_LNG', 'Расчетная ставка 10/110');
define('MODULE_PAYMENT_YOOMONEY_VAT_20_120_LNG', 'Расчетная ставка 20/120');

define('MODULE_PAYMENT_YOOMONEY_MODE1', 'На расчетный счет организации с заключением договора с ЮMoney');
define('MODULE_PAYMENT_YOOMONEY_MODE2', 'На счет физического лица в электронной валюте ЮMoney');

define('MODULE_PAYMENT_YOOMONEY_ORDER_NAME', 'Заказ');

define('MODULE_PAYMENT_YOOMONEY_TEST_LANG', 'Использовать в тестовом режиме?');
define('MODULE_PAYMENT_YOOMONEY_STATUS_LNG', 'Использовать ЮMoney для оплаты?');
define('MODULE_PAYMENT_YOOMONEY_MODE_LNG', 'Как вы хотите получать средства?');
define('MODULE_PAYMENT_YOOMONEY_ONLY_ORG_LNG', '');
define('MODULE_PAYMENT_YOOMONEY_ONLY_IND_LNG', '');

define('MODULE_PAYMENT_YOOMONEY_ACCEPT_YOOMONEY_LNG', 'Оплата из кошелька ЮMoney?');
define('MODULE_PAYMENT_YOOMONEY_ACCEPT_CASH_LNG', 'Оплата наличными через кассы и терминалы? ');
define('MODULE_PAYMENT_YOOMONEY_ACCEPT_MOBILE_LNG', 'Платеж со счета мобильного телефона? ');
define('MODULE_PAYMENT_YOOMONEY_ACCEPT_CARDS_LNG', 'Оплата с произвольной банковской карты? ');
define('MODULE_PAYMENT_YOOMONEY_WEBMONEY_LNG', 'Оплата из кошелька в системе WebMoney?');
define('MODULE_PAYMENT_YOOMONEY_AB_LNG', 'Оплата через Альфа-Клик?');
define('MODULE_PAYMENT_YOOMONEY_SB_LNG', 'Оплата через Сбербанк: оплата по SMS или SberPay?');
define('MODULE_PAYMENT_YOOMONEY_MA_LNG', 'Оплата через MasterPass?');
define('MODULE_PAYMENT_YOOMONEY_PB_LNG', 'Оплата через интернет-банк Промсвязьбанка?');
define('MODULE_PAYMENT_YOOMONEY_QW_LNG', 'Оплата через QIWI Wallet?');
define('MODULE_PAYMENT_YOOMONEY_QP_LNG', 'Оплата через доверительный платеж (Куппи.ру)?');

define('MODULE_PAYMENT_YOOMONEY_PASSWORD_LNG', 'Секретное слово (shopPassword) для обмена сообщениями');
define('MODULE_PAYMENT_YOOMONEY_SHOPID_LNG', 'Идентификатор вашего магазина в ЮMoney - ShopID  ');
define('MODULE_PAYMENT_YOOMONEY_ARTICLEID_LNG', 'Идентификатор товара вашего магазина в ЮMoney - shoparticleid ');
define('MODULE_PAYMENT_YOOMONEY_ACCOUNT_LNG', 'Номер счета в платежной системе ЮMoney ' );
define('MODULE_PAYMENT_YOOMONEY_SCID_LNG', 'Идентификатор витрины вашего магазина в ЮMoney - scid');
define('MODULE_PAYMENT_YOOMONEY_SORT_LNG', 'Сортировка');
define('MODULE_PAYMENT_YOOMONEY_SORT2_LNG', '(Чем меньше - тем выше)');
define('MODULE_PAYMENT_YOOMONEY_ORDER_STATUS_LNG','Выберите статус заказа, который будет присвоен после оплаты через этот модуль' );
define('MODULE_PAYMENT_YOOMONEY_PAID_STATUS_LNG','Оплачен [YooMoney]' );

