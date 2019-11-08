<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/cloudMQTT.php';

$service = new TKWW\Messaging\Services\CloudMQTT($config,"local-pub");
$service->publish("test/data",'{hello: "world"}');
$service->publish("test/data",'{"hello": "world"}');
$service->publish("test/data",'hello world');

