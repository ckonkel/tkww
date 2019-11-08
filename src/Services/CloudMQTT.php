<?php

declare(strict_types=1);

namespace TKWW\Messaging\Services;

use Bluerhinos\phpMQTT;
use TKWW\Messaging\ServiceInterface;

class CloudMQTT implements ServiceInterface
{
    /**
     * @var array
     */
    private $config;
    /**
     * @var string
     */
    private $client_id;
    /**
     * @var phpMQTT
     */
    private $service;

    /**
     * CloudMQTT constructor.
     * @param array $config
     * @param string $client_id
     */
    public function __construct(array $config, string $client_id)
    {
        $this->config = $config;
        $this->client_id = $client_id;
        $this->service = new phpMQTT($this->config["server"], $this->config["port"], $this->client_id);
    }

    /**
     * @param string $topic
     * @param string $message
     */
    public function publish(string $topic, string $message): void
    {
        if ($this->service->connect(true, null, $this->config["username"], $this->config["password"])) {
            $this->service->publish($topic, $message, 0);
            $this->service->close();
        } else {
            echo "Timeout!\n";
        }
    }

    /**
     * @param string $topic
     */
    public function subscribe(string $topic): void
    {
        // TODO
    }
}
