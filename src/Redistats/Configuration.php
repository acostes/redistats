<?php

namespace Redistats;

Class Configuration {

    protected static $_instance;

    protected $_configPath;

    public function __construct() {
        $this->_configPath = dirname(dirname(__FILE__)) . '/../config/config.json';
        if (!file_exists($this->_configPath)) {
            throw new ConfigurationException('Unable to find config/config.json');
        }
    }

    /**
     * 
     */
    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 
     */
    public function get($key) {
        $config = $this->_getConfig();
        if (!isset($config->$key)) {
            throw new ConfigurationException('Invalid config key: ' . $key);
        }
        return $config->$key;
    }

    protected function _getConfig() {
        return json_decode(file_get_contents($this->_configPath));
    }
}