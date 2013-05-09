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

            $this->exception(
                function () {
                    testedClass::getInstance('serverNotFound');
                }
            )->isInstanceOf('Redistats\ConfigurationException');
        }        
    }

    public function testGetName() {
        $configuration = Configuration::getInstance();
        foreach ($configuration->get('servers') as $key => $value) {
            $server = testedClass::getInstance($key);            
            $this->string($value->name)->isEqualTo($server->getName());
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

    public function testGetHostName() {
        $monitors = new MonitorCollection();
        foreach ($monitors as $monitor) {
            if ($monitor->isConnected()) {
                $parameters = $monitor->getConnection()->getParameters();
                $hostname = $parameters->host . ':' . $parameters->port;
                $this->string((string)$monitor->getHostName())->isEqualTo($hostname);
            }
        }
    }

    public function testIsConnected() {
        $monitors = new MonitorCollection();
        foreach ($monitors as $monitor) {
            $this->boolean($monitor->isConnected())->isTrue();
        }

        $monitorOff = new testedClass('servOff', 'localhost', 6378);
        $this->boolean($monitorOff->isConnected())->isFalse();
    }

    public function testGet() {
        $monitors = new MonitorCollection();
        foreach ($monitors as $monitor) {
            $monitor->compute();
            $configuration = Configuration::getInstance()->get('monitoring');
            foreach ($configuration->monitor as $groups => $types) {
                foreach ($types as $name => $param) {
                    $this->array($monitor->get(0, 1, $param->id))->isNotEmpty();
                }
            }
        }
    }
}