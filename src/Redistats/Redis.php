<?php

namespace Redistats;

use Predis;

class Redis {

    protected $_client;

    /**
     * Constructor
     * 
     * @param string $host
     * @param int $port
     */
    public function __construct($host = '127.0.0.1', $port = '6379', $database = 0) {
        $this->_client = new Predis\Client(array(
            'scheme'    => 'tcp',
            'host'      => $host,
            'port'      => $port,
            'database'  => $database,
        ));
    }

    public function connect() {
        try {
            if (!$this->_client->isConnected()) {
                $this->_client->connect();
            }
            return $this->_client;
        } catch (Predis\PredisException $e) {
            throw new RedisException('Unable to connect to ' . $this->_client->getConnection());
        }

    }

    /**
     * Magic call
     * 
     * @param string $name
     * @param array $args
     */
    public function __call($name, $args) {
        try {            
            $result = call_user_func_array(array($this->connect(), $name), $args);
            return $result;
        } catch (Predis\PredisException $e) {
            $this->disconnect();
            throw new RedisException($e->getMessage());            
        }
    }
}