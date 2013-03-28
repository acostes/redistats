<?php

namespace Redistats\Server;

use Redistats\Redis;
use Redistats\Server\Storage;

class Monitor extends Redis {

    protected $_client;
    protected $_id;

    const CONNECTED_CLIENTS             = 'cc';
    const BLOCKED_CLIENTS               = 'bc';
    const USED_MEMORY                   = 'um';
    const USED_MEMORY_PEAK              = 'ump';
    const MEM_FRAGMENTATION_RATIO       = 'mf';
    const KEYS                          = 'k';
    const EXPIRES                       = 'e';

    protected $_types = array(
        self::CONNECTED_CLIENTS             => 'connected_clients',
        self::BLOCKED_CLIENTS               => 'blocked_clients',
        self::USED_MEMORY                   => 'used_memory',
        self::USED_MEMORY_PEAK              => 'used_memory_peak',
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
            self::USED_MEMORY_PEAK,
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
     * Get stats
     * 
     * @param string $startDate
     * @param string $endDate
     * @param string $type
     * 
     * @return array
     */
    public function get($startDate, $endDate, $type) {

    }

    public function compute() {
        $info = $this->info();
        $key = 'stats:' . $this->_id . ':' . time();
        $result = array();
        foreach ($this->_groupInfo as $groups => $types) {
            foreach ($types as $type) {
                $result[$type] = 0;
                if ($type == self::KEYS || $type == self::EXPIRES) {
                    if (isset($info[$groups]['db' . $this->_database])) {
                        $result[$type] = $info[$groups]['db' . $this->_database][$this->_types[$type]];
                    }                
                } else {
                    $result[$type] = $info[$groups][$this->_types[$type]];
                }
                
            }
        }
        Storage::getInstance()->set($key, $result);
    }
}