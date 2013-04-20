<?php

namespace Redistats\tests\units\Server;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use atoum;
use Redistats\Configuration;
use Redistats\Server\MonitorCollection as testedClass;

class MonitorCollection extends atoum {

    public function test__construct() {
        $collection = new testedClass();
        $this->object($collection)->isInstanceOf('ArrayObject');

        $config = Configuration::getInstance()->get('servers');

        $this->object($collection)->hasSize(sizeof((array)$config));
    }
}