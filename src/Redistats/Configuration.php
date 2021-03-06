<?php

namespace Redistats;

Class Configuration {

    protected static $_instance;

    protected $_configPath;

    /**
     * Constructor
     */
    public function __construct($configFile = null) {
        if (is_null($configFile)) {
            $this->_configPath = dirname(dirname(__FILE__)) . '/../config/config.json';
        } else {
            $this->_configPath = $configFile;
        }

        if (!file_exists($this->_configPath)) {
            throw new ConfigurationException('Unable to find config/config.json');
        }
    }

    /**
     * Return an object instance
     * 
     * @return Redistats\Configuration
     */
    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Get the config of $key
     * 
     * @param string $key
     * 
     * @return object
     */
    public function get($key) {
        $config = $this->_getConfig();
        if (!isset($config->$key)) {
            throw new ConfigurationException('Invalid config key: ' . $key);
        }
        return $config->$key;
    }

    /**
     * Get the all config
     * 
     * @return object
     */
    protected function _getConfig() {
        return json_decode(file_get_contents($this->_configPath));
    }
}