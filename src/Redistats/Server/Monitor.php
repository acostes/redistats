<?php

namespace Redistats\Server;

use Redistats\Redis;
use Redistats\Server\Storage;
use Redistats\Configuration;
use Redistats\ConfigurationException;
use Redistats\RedistatsException;

class Monitor extends Redis {

    protected $_client;
    protected $_id;

    protected static $_instance;

    /**
     * Constructor
     * 
     * @param string $host
     * @param int $port
     */
    public function __construct($id, $host, $port, $database = 0) {
        $this->_client = new Redis($host, $port);
        $this->_id = $id;
        $this->_database = $database;
    }

    /**
     * Return an object instance
     * 
     * @return Redistats\Server\Monitor
     */
    public static function getInstance($name) {
        if (!self::$_instance || (self::$_instance && self::$_instance->getId() != $name)) {
            $config = Configuration::getInstance()->get('servers');
            if (!array_key_exists($name, $config)) {
                throw new ConfigurationException('Unable to find ' . $name . ' in your declare server');
            }
            self::$_instance = new self($name, $config->$name->host, $config->$name->port);
        }

        return self::$_instance;
    }

    /**
     * Return monitor id
     * 
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * Return monitor name
     * 
     * @return string
     */
    public function getName() {
        $monitors = Configuration::getInstance()->get('servers');
        $monitor = $monitors->{$this->getId()};
        return $monitor->name;
    }

    /**
     * Return monitor hostname
     * 
     * @return string
     */
    public function getHostname() {
        return $this->_client->getConnection();
    }

    /**
     * Check if server is up
     * 
     * @return boolean
     */
    public function isConnected() {
        try {
            return $this->_client->isConnected();
        } catch(RedistatsException $e) {
            return false;
        }
    }

    /**
     * Get stats
     * 
     * @param string $from
     * @param string $to
     * @param string $type
     * 
     * @return array
     */
    public function get($from, $to, $type) {
        $key = 'stats:' . $this->_id . ':' . $type;
        $data = Storage::getInstance()->get($key, $from, $to);
        $result = array();
        foreach ($data as $values) {
            $result[$values[0]] = $values[1];
        }
        return $result;
    }

    /**
     * Retrieve server info
     * 
     * @return array
     */
    public function getServerInfo() {
        return $this->info('all');
    }

    /**
     * Compute stats
     * 
     * @return void
     */
    public function compute() {
        $info = $this->info();
        $result = array();
        $date = time();
        $config = Configuration::getInstance()->get('monitoring');

        if (!isset($config->monitor)) {
            throw new RedistatsException('Nothing to monitor, please fill your configuration file.');
        }

        foreach ($config->monitor as $groups => $types) {
            if (!isset($info[ucfirst($groups)])) {
                throw new RedistatsException('Something wrong with you configuration file. Impossible to find group ' . $groups);
            }

            foreach ($types as $name => $param) {
                $key = 'stats:' . $this->_id . ':' . $param->id;
                $result = array();
                if ($name == 'keys' || $name == 'expires') {
                    if (isset($info[ucfirst($groups)]['db' . $this->_database])) {
                        $result[$date] = $info[ucfirst($groups)]['db' . $this->_database][$name];
                    }
                } else {
                    if (!isset($info[ucfirst($groups)][$name])) {
                        throw new RedistatsException('Something wrong with you configuration file. Impossible to find the property ' . $name);
                    }

                    $result[$date] = $info[ucfirst($groups)][$name];
                }

                if (!empty($result)) {
                    Storage::getInstance()->set($key, $result);
                }
            }
        }
    }

    /**
     * Get stats type available
     * 
     * @return array
     */
    public static function getTypes() {
        return self::$_types;
    }
}