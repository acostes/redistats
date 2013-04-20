<?php

namespace Redistats\tests\units;

require_once __DIR__ . '/../../../vendor/autoload.php';

use atoum;
use Redistats\Configuration as testedClass;

class Configuration extends atoum {

    public function test__construct() {
        $configuration = testedClass::getInstance();
        $this->object($configuration)->isInstanceOf('Redistats\Configuration');

        $this->exception(
            function () {
                $configuration = new testedClass('configNotFound');
            }
        )->isInstanceOf('Redistats\ConfigurationException');
    }

    public function testGet() {
        $configuration = testedClass::getInstance();

        $this->variable($configuration->get('storage')->host)->isNotNull();
        $this->variable($configuration->get('storage')->port)->isNotNull();

        $this->variable($configuration->get('monitoring')->refresh)->isNotNull();

        $this->exception(
            function () use ($configuration) {
                $configuration->get('invalidKey');
            }
        )->isInstanceOf('Redistats\ConfigurationException');
    }
}