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

    const CONNECTED_CLIENTS             = 'cc';
    const BLOCKED_CLIENTS               = 'bc';

    const USED_MEMORY                   = 'um';
    const USED_MEMORY_RSS               = 'umr';
    const USED_MEMORY_PEAK              = 'ump';
    const USED_MEMORY_LUA               = 'uml';
    const MEM_FRAGMENTATION_RATIO       = 'mf';
    
    const KEYS                          = 'k';
    const EXPIRES                       = 'e';

    protected static $_types = array(
        self::CONNECTED_CLIENTS             => 'connected_clients',
        self::BLOCKED_CLIENTS               => 'blocked_clients',
        self::USED_MEMORY                   => 'used_memory',
        self::USED_MEMORY_RSS               => 'used_memory_rss',
        self::USED_MEMORY_PEAK              => 'used_memory_peak',
        self::USED_MEMORY_LUA               => 'used_memory_lua',
        self::MEM_FRAGMENTATION_RATIO       => 'mem_fragmentation_ratio',
        self::KEYS                          => 'keys',
        self::EXPIRES                       => 'expires',
    );

    const GROUP_CLIENTS     = 'Clients';
    const GROUP_MEMORY      = 'Memory';
    const GROUP_KEYSPACE    = 'Keyspace'; 

    protected $_groupInfo = array(
        self::GROUP_CLIENTS => array(
            self::CONNECTED_CLIENTS,
            self::BLOCKED_CLIENTS,
        ),
        self::GROUP_MEMORY => array(
            self::USED_MEMORY,
            self::USED_MEMORY_RSS,
            self::USED_MEMORY_PEAK,
            self::USED_MEMORY_LUA,
            self::MEM_FRAGMENTATION_RATIO,
        ),
        self::GROUP_KEYSPACE => array(
            self::KEYS,
            self::EXPIRES,
        ),
    );

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
        foreach ($monitors as $id => $monitor) {
            if ($id == $this->_id) {
                return $monitor->name;
            }
        }
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
        foreach ($this->_groupInfo as $groups => $types) {
            foreach ($types as $type) {
                $key = 'stats:' . $this->_id . ':' . $type;
                $result[$date] = 0;
                if ($type == self::KEYS || $type == self::EXPIRES) {
                    if (isset($info[$groups]['db' . $this->_database])) {
                        $result[$date] = $info[$groups]['db' . $this->_database][self::$_types[$type]];
                    }                
                } else {
                    $result[$date] = $info[$groups][self::$_types[$type]];
                }
                Storage::getInstance()->set($key, $result);
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