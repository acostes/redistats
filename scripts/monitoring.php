<?php

namespace Scripts;

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Redistats\Server;
use Redistats\Configuration;

class Monitoring {

    public function run() {
        $monitors = new Server\MonitorCollection();
        $config = Configuration::getInstance()->get('monitoring');
        do {

            foreach ($monitors as $monitor) {
                $monitor->compute();
                sleep($config->refresh);
            }

        } while(true);
    }
}

$script = new Monitoring();
$script->run();