<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class SmogBuster extends Module
{
    public function __construct()
    {
        $this->name = 'smogbuster';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'Fancybox';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_,
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Smog Buster');
        $this->description = $this->l('Module for synchronizstion air quaility data from powietrze.gios.gov.pl');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('SMOGBUSTER_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }

    /**
     * Install module.
     *
     * @return bool
     */
    public function install()
    {
        if (!parent::install() || !$this->installSQL()) {
            return false;
        }

        return true;
    }

    /**
     * Uninstall module.
     */
    public function uninstall()
    {
        return $this->uninstallSQL() && parent::uninstall();
    }

    /**
     * Install SQL.
     *
     * @return bool
     */
    private function installSQL()
    {
        $sql = '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'smogbuster`
            (
                `id`         INT NOT NULL auto_increment,
                `station_id` INT NULL DEFAULT NULL,
                `name`       VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `latitude`   DECIMAL(11, 7) NULL DEFAULT NULL,
                `longitude`  DECIMAL(11, 7) NULL DEFAULT NULL,
                `city`       VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `address`    VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `st`         INT NULL DEFAULT NULL,
                `so2`        INT NULL DEFAULT NULL,
                `no2`        INT NULL DEFAULT NULL,
                `co`         INT NULL DEFAULT NULL,
                `pm10`       INT NULL DEFAULT NULL,
                `pm25`       INT NULL DEFAULT NULL,
                `o3`         INT NULL DEFAULT NULL,
                `c6h6`       INT NULL DEFAULT NULL,
                `created_at` DATETIME NOT NULL,
                `updated_at` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            )
        ';

        return Db::getInstance()->execute($sql);
    }

    /**
     * Uninstall SQL.
     *
     * @return bool
     */
    private function uninstallSQL()
    {
        $sql = '
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'smogbuster`
        ';

        return Db::getInstance()->execute($sql);
    }
}
