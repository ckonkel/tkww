<?php

error_reporting(E_ALL ^ E_DEPRECATED);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/cloudMQTT.php';

//PAIN IN THE ARSE DISCOVERY: YOU CAN NOT HAVE the same client id for the publisher and the subscriber...
//$service = new TKWW\Messaging\Services\CloudMQTT($config, "local-pub");

$service = new TKWW\Messaging\Services\CloudMQTT($config,"local-sub");
$service->subscribe("test/data");
