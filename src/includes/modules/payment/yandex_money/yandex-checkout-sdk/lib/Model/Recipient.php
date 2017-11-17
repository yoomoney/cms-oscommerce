<?php

namespace YaMoney\Model;

use YaMoney\Common\AbstractObject;
use YaMoney\Common\Exceptions\EmptyPropertyValueException;
use YaMoney\Common\Exceptions\InvalidPropertyValueTypeException;
use YaMoney\Helpers\TypeCast;

/**
 * Recipient - Получатель платежа
 *
 * @property string $accountId Идентификатор магазина
 * @property string $gatewayId Идентификатор товара
 */
class Recipient extends AbstractObject implements RecipientInterface
{
    /**
     * @var string Идентификатор магазина
     */
    private $_accountId;

    /**
     * @var string Идентификатор товара
     */
    private $_gatewayId;

    /**
     * Возвращает идентификатор магазина
     * @return string Идентификатор магазина
     */
    public function getAccountId()
    {
        return $this->_accountId;
    }

    /**
     * Устанавливает идентификатор магазина
     * @param string $value Идентификатор магазина
     * @throws EmptyPropertyValueException Выбрасывается если было передано пустое значение
     * @throws InvalidPropertyValueTypeException Выбрасывается если было передано не строковое значение
     */
    public function setAccountId($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty accountId value in Recipient', 0, 'Recipient.accountId');
        } elseif (TypeCast::canCastToString($value)) {
            $this->_accountId = (string)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid accountId value type in Recipient', 0, 'Recipient.accountId', $value
            );
        }
    }

    /**
     * Возвращает идентификатор товара
     * @return string Идентификатор товара
     */
    public function getGatewayId()
    {
        return $this->_gatewayId;
    }

    /**
     * Устанавливает идентификатор товара
     * @param string $value Идентификатор товара
     * @throws EmptyPropertyValueException Выбрасывается если было передано пустое значение
     * @throws InvalidPropertyValueTypeException Выбрасывается если было передано не строковое значение
     */
    public function setGatewayId($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException(
                'Empty gatewayId value in Recipient', 0, 'Recipient.gatewayId'
            );
        } elseif (TypeCast::canCastToString($value)) {
            $this->_gatewayId = (string)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid gatewayId value type in Recipient', 0, 'Recipient.gatewayId', $value
            );
        }
    }
}
