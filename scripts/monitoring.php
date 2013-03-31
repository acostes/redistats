<?php

namespace Scripts;

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Redistats\Server;
use Redistats\Configuration;
use Redistats\RedistatsException;

class Monitoring {

    public function run() {
        try {
            $monitors = new Server\MonitorCollection();
            $config = Configuration::getInstance()->get('monitoring');
            do {

                foreach ($monitors as $monitor) {
                    $monitor->compute();
                    sleep($config->refresh);
                }

            } while(true);  
        } catch(RedistatsException $e) {
            error_log($e->getMessage());
        }

    }
}

$script = new Monitoring();
$script->run();