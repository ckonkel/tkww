# Requirements

php 7.3+

# Install

composer up

Create a config for CloudMQTT in project root
config/cloudMQTT.php
```
<?php

$config = [
    "server" => "xxxx.cloudmqtt.com",
    "port" => 17877,
    "username" => "userthis",
    "password" => "password123",
];
```

To listen to the queue
`php listen.php`

To publish to the queue
`php listen.php`