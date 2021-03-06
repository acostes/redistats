<?php

namespace Redistats\tests\units\Server;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use atoum;
use Redistats\Server\Storage as testedClass;

class Storage extends atoum {

    public function test__construct() {
        $this->object(testedClass::getInstance())->isInstanceOf('Redistats\Server\Storage');
    }

    public function testSet() {
        $storage = testedClass::getInstance();
        $data = array('set1' => 2, 'set2' => 7, 'set3' => 23);
        $storage->del('test:set');
        $this->integer($storage->set('test:set', $data))->isEqualTo(sizeof($data));
    }

    public function testGet() {
        $storage = testedClass::getInstance();
        $values = array('set1' => 2, 'set2' => 7, 'set3' => 23);
        $storage->set('test:get', $values);

        $data = array(
            array('set1', 2),
            array('set2', 7),
            array('set3', 23),
        );

        $data1 = array(
            array('set1', 2),
            array('set2', 7),
        );

        $data2 = array(
            array('set1', 2),
        );

        $this->array($storage->get('test:get'))->hasSize(3);
        $this->array($storage->get('test:get'))->isEqualTo($data);

        $this->array($storage->get('test:get', 0, 1))->hasSize(2);
        $this->array($storage->get('test:get', 0, 1))->isEqualTo($data1);

        $this->array($storage->get('test:get', 0, 0))->hasSize(1);
        $this->array($storage->get('test:get', 0, 0))->isEqualTo($data2);
    }
}