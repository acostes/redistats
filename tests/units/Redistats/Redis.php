<?php

namespace Redistats\tests\units;

require_once __DIR__ . '/../../../vendor/autoload.php';

use atoum;
use Redistats\Redis as testedClass;

class Redis extends atoum {

    public function test__construct() {
        $redis = new testedClass();
        $this->object($redis->getConnection())->isInstanceOf('Predis\Connection\SingleConnectionInterface');

        $parameters = $redis->getConnection()->getParameters();
        $this->string($parameters->host)->isEqualTo('127.0.0.1');
        $this->integer($parameters->port)->isEqualTo(6379);
    }

    public function testConnect() {
        $redis = new testedClass();
        $this->object($redis->connect())->isInstanceOf('Predis\Client');
    }
}