<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Respect\Rest\Router;
use Redistats\Server;

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
    return json_encode($monitor->serverInfo());
});


echo $api->run();