<?php

namespace Redistats\tests\units;

require_once __DIR__ . '/../../../vendor/autoload.php';

use atoum;
use Redistats\Configuration as testedClass;

class Configuration extends atoum {

    public function test__construct() {
        $this->object(testedClass::getInstance())->isInstanceOf('Redistats\Configuration');
    }

    public function testGet() {
        $configuration = testedClass::getInstance();

        $this->variable($configuration->get('storage')->host)->isNotNull();
        $this->variable($configuration->get('storage')->port)->isNotNull();

        $this->variable($configuration->get('monitoring')->refresh)->isNotNull();
    }
}