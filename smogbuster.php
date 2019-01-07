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
            ALTER TABLE '._DB_PREFIX_.'product_lang
            ADD COLUMN IF NOT EXISTS extra_product_info VARCHAR(255) NULL
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
            ALTER TABLE '._DB_PREFIX_.'product_lang
            DROP COLUMN IF EXISTS extra_product_info
        ';

        return Db::getInstance()->execute($sql);
    }
}
