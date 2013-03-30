<?php
 
namespace Redistats\Server;

use Redistats\Configuration;

class MonitorCollection extends \ArrayObject {

    public function __construct() {
        $servers = array();
        $config = Configuration::getInstance()->get('servers');
        foreach ($config as $name => $server) {
            $servers[] = Monitor::getInstance($name);
        }
        parent::__construct($servers, self::ARRAY_AS_PROPS);
    }
}