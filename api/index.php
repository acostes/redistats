<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Respect\Rest\Router;
use Redistats\Server;
use Redistats\RedistatsException;

$api = new Router('/api');

$api->get('/', function() {
    return 'Redistats API';
});

$api->get('/get/*/type/*/from/*/to/*', function($monitor, $type, $from, $to) {
    $monitor = Redistats\Server\Monitor::getInstance($monitor);
    return json_encode($monitor->get($from, $to, $type));
});


$api->get('/info/*', function ($monitor) {
    $monitor = Redistats\Server\Monitor::getInstance($monitor);
    return json_encode($monitor->getServerInfo());
});

$api->get('/types', function () {
    $types = Redistats\Server\Monitor::getTypes();
    return json_encode($types);
});

try {
    echo $api->run();
} catch (RedistatsException $e) {
    echo json_encode(array('error' => $e->getMessage()));
}
