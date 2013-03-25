<?php

namespace Redistats\Server;

use Redistats\Configuration;
use Redistats\Redis;

class Storage extends Redis {

    protected static $_instance;

    protected $_client;

    const PREFIX = 'storage';

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
     * @return string (Status reply: http://redis.io/topics/protocol#status-reply)
     */
    public function set($key, $data) {
        return $this->hmset($key, $data);
    }

    /**
     * Get values from a hash
     * 
     * @param string $key
     * @param array|string $data
     * 
     * @return array|string
     */
    public function get($key, $field = null) {
        if (is_null($field)) {
            return $this->hgetall($key);
        }

        if (is_array($field)) {
            $result = $this->hmget($key, $field);
            return array_combine($field, $result);
        }

        return $this->hget($key, $field);
    }
}