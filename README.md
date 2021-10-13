# yoomoney-cms-oscommerce

С помощью модуля можно настроить прием платежей через ЮKassa

[Инструкция по настройке](https://yookassa.ru/docs/support/payments/onboarding/integration/cms-module/oscommerce)

Для установки данного модуля необходимо переместить папки `ext`, `includes` и файл `yoomoney_callback.php` из папки `src` [архива](https://github.com/yoomoney/cms-oscommerce/archive/master.zip) в корень Вашего сайта.

По умолчанию модуль устанавливается для работы с ЮKassa, для того чтобы его изменить в файле [src/ext/modules/payment/yoomoney/yoomoney.php](src/ext/modules/payment/yoomoney/yoomoney.php) найдите строки:
```php
// Устанавливаем режим работы:
// MODULE_PAYMENT_YOOMONEY_MODE1 - ЮKassa
// MODULE_PAYMENT_YOOMONEY_MODE2 - ЮMoney
define('MODULE_PAYMENT_YOOMONEY_MODE', MODULE_PAYMENT_YOOMONEY_MODE1);
```
И замените объявление константы MODULE_PAYMENT_YOOMONEY_MODE:
* `define('MODULE_PAYMENT_YOOMONEY_MODE', MODULE_PAYMENT_YOOMONEY_MODE2);` для того чтобы подключить оплату через ЮMoney;

Далее рекомендуем следовать пунктам [инструкции](https://yookassa.ru/docs/support/payments/onboarding/integration/cms-module/oscommerce).

Пожалуйста, обязательно делайте бекапы!

### О ЮKassa
Сервис, который позволяет включить прием платежей на сайте.

[Сайт ЮKassa](http://yookassa.ru/)

#### Условия
* подходит для юрлиц и ИП,
* деньги приходят на расчетный счет, 
* комиссия берется с каждого успешного платежа.

Для использования нужно [подключиться к ЮKassa](https://yookassa.ru/joinups) и получить в личном кабинете на сайте ЮKassa параметры **shopId** и **Секретный ключ**.

### Способы приема платежей
Вы можете выбрать любое количество способов из списка:

* Банковские карты — Visa, Mastercard и Maestro, «Мир»;
* ЮMoney;
* Webmoney;
* QIWI Wallet;
* Наличные;
* Альфа-Клик;
* SberPay;
* Баланс мобильного — Билайн, Мегафон, МТС, Tele2.

### Дополнительные возможности

**Оплата на стороне ЮKassa**

Включите в модуле оплату на стороне ЮKassa — и не придется размещать на своем сайте все способы оплаты. Вместо этого останется одна кнопка «Заплатить».
 
[Пример в демо-магазине ЮKassa](https://demo.yookassa.ru/)

**Отправка данных для чеков по 54-фз**

Если вы подключите решение ЮKassa для 54-фз, модуль будет отправлять в ЮKassa данные для чека вместе с информацией о заказе.
 
[Подробности на сайте ЮKassa](https://yookassa.ru/features/) 

### Контакты
Если у вас есть вопросы или идеи для модуля, напишите нам: cms@yoomoney.ru

В письме укажите:
* версию платформы,
* версию модуля (его можно посмотреть на странице настроек),
* идею или проблему,
* снимок экрана, о котором говорите.
