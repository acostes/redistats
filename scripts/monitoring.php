<?php

namespace Scripts;

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Redistats\Log;
use Redistats\Server;
use Redistats\Configuration;
use Redistats\RedistatsException;

class Monitoring {

    public function run() {
        try {
            $monitors = new Server\MonitorCollection();
            $config = Configuration::getInstance()->get('monitoring');

            Log::MONITORING('Processing monitoring for ' . $monitors->count() . ' server(s)', Log::INFO);

            do {

                foreach ($monitors as $monitor) {
                    Log::MONITORING('Compute stats for ' . $monitor->getHostname(), Log::INFO);
                    $monitor->compute();
                }

                Log::MONITORING('Wait ' . $config->refresh . ' secs before next computing', Log::INFO);
                sleep($config->refresh);

            } while(true);  
        } catch(RedistatsException $e) {
            Log::MONITORING($e->getMessage(), Log::ERROR);
        }

    }
}

$script = new Monitoring();
$script->run();