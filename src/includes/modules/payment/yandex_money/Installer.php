<?php

namespace YandexMoney;

use YaMoney\Model\PaymentMethodType;

class Installer
{
    const KEY_PREFIX = 'MODULE_PAYMENT_YANDEX_MONEY_';

    private function getConfigurationVariables()
    {
        $result = array(
            'SHOP_ID',
            'SHOP_PASSWORD',
            array(
                'name' => 'PAYMENT_MODE',
                'function' => "tep_cfg_select_option(array('".$this->getValue('true')."','".$this->getValue('false')."'),"
            )
        );

        $paymentMethods = PaymentMethodType::getEnabledValues();
        foreach ($paymentMethods as $value) {
            $result[] = array(
                'name' => 'PAYMENT_METHOD_' . strtoupper($value),
                'function' => "tep_cfg_select_option(array('".$this->getValue('true')."','".$this->getValue('false')."'),"
            );
        }

        $result[] = 'SORT_ORDER';
        $result[] = array(
            'name' => 'ORDER_STATUS',
            'function' => 'tep_cfg_pull_down_order_statuses(',
            'useFunction' => 'tep_get_order_status_name',
        );

        $result[] = array(
            'name' => 'SEND_RECEIPT',
            'function' => "tep_cfg_select_option(array('".$this->getValue('true')."','".$this->getValue('false')."'),"
        );
        $sql = "SELECT r.tax_rates_id, z.geo_zone_id, z.geo_zone_name, tc.tax_class_title, tc.tax_class_id, r.tax_priority, "
            . "r.tax_rate, r.tax_description, r.date_added, r.last_modified from " . TABLE_TAX_CLASS . " tc, "
            . TABLE_TAX_RATES . " r left join " . TABLE_GEO_ZONES . " z on r.tax_zone_id = z.geo_zone_id WHERE "
            . "r.tax_class_id = tc.tax_class_id";
        $dataSet = tep_db_query($sql);
        while ($taxRate = tep_db_fetch_array($dataSet)) {
            $result[] = array(
                'name' => 'TAXES_' . $taxRate['tax_rates_id'],
                'label' => $taxRate['tax_class_title'] . '(' . $taxRate['tax_rate'] . '%)',
                'description' => $taxRate['description'],
                'defaultValue' => '1',
                'function' => 'get_options_taxes(' . $taxRate['tax_rates_id'] . ',',
                'useFunction' => 'get_setted_taxes',
            );
        }

        $result[] = array(
            'name' => 'DISPLAY_TITLE',
            'description' => '',
            'defaultValue' => MODULE_PAYMENT_YANDEX_MONEY_DISPLAY_TITLE_DEFAULT_VALUE,
        );

        $result[] = array(
            'name' => 'ENABLE_LOG',
            'function' => "tep_cfg_select_option(array('".$this->getValue('true')."','".$this->getValue('false')."'),",
            'defaultValue' => $this->getValue('false'),
        );

        return $result;
    }

    private function getValue($key, $default = null)
    {
        $fullName = self::KEY_PREFIX . strtoupper($key);
        if (defined($fullName)) {
            return constant($fullName);
        }
        return $default;
    }

    public function install()
    {
        $this->processOrderStatus(MODULE_PAYMENT_YANDEX_MONEY_PAID_STATUS_TEXT);
        $this->createTables();
        foreach ($this->getConfigurationVariables() as $name => $params) {
            if (is_string($params)) {
                $params = array(
                    'name' => $params,
                );
            } elseif (!isset($params['name'])) {
                $params['name'] = $name;
            }
            $this->insertConfigurationVariable($params);
        }
    }

    public function uninstall()
    {
        $sql = 'DROP TABLE IF EXISTS `ym_payments`';
        tep_db_query($sql);
    }

    private function createTables()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `ym_payments` (
  `order_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_id` CHAR(36) NOT NULL,
  CONSTRAINT `ym_payments_pk` PRIMARY KEY (`order_id`),
  CONSTRAINT `ym_payments_unq_payment_id` UNIQUE (`payment_id`)
) ENGINE = InnoDB';
        tep_db_query($sql);
    }

    private function insertConfigurationVariable($variableConfig)
    {
        $columns = array(
            'configuration_title',
            'configuration_key',
            'configuration_value',
            'configuration_description',
            'configuration_group_id',
            'sort_order',
            'date_added',
        );
        if (!isset($variableConfig['label'])) {
            $variableConfig['label'] = $this->getValue($variableConfig['name'] . '_label', '');
        }
        if (!isset($variableConfig['description'])) {
            $variableConfig['description'] = $this->getValue($variableConfig['name'] . '_description', '');
        }
        if (!isset($variableConfig['defaultValue'])) {
            $variableConfig['defaultValue'] = $this->getValue($variableConfig['name'] . '_default_value', '');
        }
        $rowData = array(
            "'" . tep_db_input($variableConfig['label']) . "'",
            "'" . tep_db_input(self::KEY_PREFIX . $variableConfig['name']) . "'",
            "'" . tep_db_input($variableConfig['defaultValue']) . "'",
            "'" . tep_db_input($variableConfig['description']) . "'",
            6,
            isset($variableConfig['sortOrder']) ? $variableConfig['sortOrder'] : 0,
            'now()'
        );
        if (!empty($variableConfig['function'])) {
            $columns[] = 'set_function';
            $rowData[] = "'" . tep_db_input($variableConfig['function']) . "'";
        }
        if (!empty($variableConfig['useFunction'])) {
            $columns[] = 'use_function';
            $rowData[] = "'" . tep_db_input($variableConfig['useFunction']) . "'";
        }
        $sql = 'INSERT INTO `' . TABLE_CONFIGURATION . '`(`' . implode('`,`', $columns) . '`) VALUES ('
            . implode(',', $rowData) . ')';
        tep_db_query($sql);
    }

    private function processOrderStatus($name)
    {
        $q = tep_db_query("SELECT `orders_status_id` FROM `".TABLE_ORDERS_STATUS."` WHERE `orders_status_name` = '".$name."' limit 1");
        if (tep_db_num_rows($q) < 1){
            $q = tep_db_query("select max(orders_status_id) as status_id from ".TABLE_ORDERS_STATUS);
            $row = tep_db_fetch_array($q);
            $status_id = $row['status_id']+1;
            $languages = tep_get_languages();
            $qf = tep_db_query("describe " . TABLE_ORDERS_STATUS . " public_flag");
            if (tep_db_num_rows($qf) == 1) {
                foreach ($languages as $lang){
                    tep_db_query("insert into ".TABLE_ORDERS_STATUS." (orders_status_id, language_id, orders_status_name, public_flag) values ('".$status_id."', '".$lang['id']."', "."'".$name."', 1)");
                }
            }else{
                foreach ($languages as $lang){
                    tep_db_query("insert into ".TABLE_ORDERS_STATUS." (orders_status_id, language_id, orders_status_name) values ('".$status_id."', '".$lang['id']."', "."'".$name."')");
                }
            }
        } else {
            $check = tep_db_fetch_array($q);
            $status_id = $check['orders_status_id'];
        }
        return $status_id;
    }
}