<?php

namespace Redistats\Server;

use Redistats\Configuration;
use Redistats\Redis;

class Storage extends Redis {

    protected static $_instance;

    const PREFIX = 'storage';

    /**
     * 
     */
    public static function getInstance() {
        if (!self::$_instance) {
            $config = Configuration::getInstance()->get('storage');
            self::$_instance = new Redis($config->host, $config->port);
        }
        return self::$_instance;
    }
}