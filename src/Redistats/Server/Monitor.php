<?php

namespace Redistats\Server;

use Redistats\Redis;

class Monitor {

    protected $_client;

    /**
     * Constructor
     * 
     * @param string $host
     * @param int $port
     */
    public function __construct($host, $port) {
        $this->_client = new Redis($host, $port);
    }
}