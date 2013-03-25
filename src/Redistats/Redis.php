<?php

namespace Redistats;

class Redis {

    protected $_client;

    /**
     * Constructor
     * 
     * @param string $host
     * @param int $port
     */
    public function __construct($host = '127.0.0.1', $port = '6379') {
        $this->_client = new \Predis\Client(array(
            'scheme' => 'tcp',
            'host'   => $host,
            'port'   => $port,
        ));
    }

    /**
     * Magic call
     * 
     * @param string $name
     * @param array $args
     */
    public function __call($name, $args) {
        try {
            $result = call_user_func_array(array($this->_client, $name), $args);
            return $result;
        } catch (\Predis\ClientException $e) {
            error_log($e->getMessage());
            $this->disconnect();
        }
    }
}