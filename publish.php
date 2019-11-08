<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/cloudMQTT.php';

$service = new TKWW\Messaging\Services\CloudMQTT($config,"local-pub");
$service->publish("test/data",'{"hello": 1 ] }');
$service->publish("test/data",'{hello: "world"}');
$service->publish("test/data",'{"hello": "world"}');
$service->publish("test/data",'hello world');
$service->publish("test/data",001);
$service->publish("test/data","end");
$service->publish("test/data","1234");
