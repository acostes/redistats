<?php

namespace Redistats\Server;

use Redistats\Configuration;
use Redistats\Redis;

class Storage extends Redis {

    protected static $_instance;

    protected $_client;

    /**
     * Constructor
     */
    public function __construct() {
        $config = Configuration::getInstance()->get('storage');
        $this->_client = new Redis($config->host, $config->port);
    }

    /**
     * Return an object instance
     * 
     * @return Redistats\Server\Storage
     */
    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Set values into a hash
     * 
     * @param string $key
     * @param array $data
     * 
     * @return int (number of elements add)
     */
    public function set($key, $data) {
        return $this->zadd($key, $data);
    }

    /**
     * Get values from a hash
     * 
     * @param string $key
     * @param array|string $data
     * 
     * @return array
     */
    public function get($key, $limit = 10, $offset = 0) {
        return $this->zrange($key, $offset, $limit, 'WITHSCORES');
    }
}