<?php

namespace Redistats\Server;

use Redistats\Redis;

class Monitor{

    protected $_redis;

    public function __construct($host, $port) {
        $this->_redis = new Redis($host, $port);
    }
}