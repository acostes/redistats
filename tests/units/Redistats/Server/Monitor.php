<?php

namespace Redistats\tests\units\Server;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use atoum;
use Redistats\Configuration;
use Redistats\Server\MonitorCollection;
use Redistats\Server\Monitor as testedClass;

class Monitor extends atoum {

    public function test__construct() {
        $configuration = Configuration::getInstance();
        foreach ($configuration->get('servers') as $key => $value) {

            $this->variable($value->host)->isNotNull();
            $this->variable($value->port)->isNotNull();
            $this->variable($value->name)->isNotNull();

            $server = testedClass::getInstance($key);
            $this->object($server)->isInstanceOf('Redistats\Server\Monitor');
        }        
    }

    public function testGetName() {
        $configuration = Configuration::getInstance();
        foreach ($configuration->get('servers') as $key => $value) {
            $server = testedClass::getInstance($key);            
            $this->string($key)->isEqualTo($server->getId());
        }   
    }

    public function testGetId() {
        $configuration = Configuration::getInstance();
        foreach ($configuration->get('servers') as $key => $value) {
            $server = testedClass::getInstance($key);            
            $this->string($key)->isEqualTo($server->getId());
        }  
    }

    public function testGetServerInfo() {
        $monitors = new MonitorCollection();
        foreach ($monitors as $monitor) {
            if ($monitor->isConnected()) {
                $this->array($monitor->getServerInfo())->hasKeys(array('Server', 'Clients', 'Memory', 'Persistence', 'Stats', 'Replication', 'CPU', 'Keyspace'));
            }
        }
    }
}