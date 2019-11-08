<?php

declare(strict_types=1);

namespace TKWW\Messaging\Services;

use Bluerhinos\phpMQTT;
use Services_JSON;
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
            echo "Time out!\n";
        }
    }

    /**
     * @param string $topic
     */
    public function subscribe(string $topic): void
    {
        if (!$this->service->connect(true, null, $this->config["username"], $this->config["password"])) {
            echo "Time out!\n";
            exit(1);
        }

        $topics[$topic] = array("qos" => 0, "function" => array($this, 'output'));
        $this->service->subscribe($topics, 0);

        while ($this->service->proc()) {
        }

        $this->service->close();
    }

    /**
     * @param string $topic
     * @param $msg
     */
    public function output(string $topic, $msg): void
    {
        $invalid_json_fixed_message = "Not required";
        $final_msg = $msg;

        $valid_json_found = $this->isJSON($msg);

        $valid_json_initial_msg = json_last_error_msg();
        $valid_json_initial_error = json_last_error();

        if (!$valid_json_found) {
            try {
                $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
                $fix_decoded_msg = $json->decode($msg);

                if (!is_array($fix_decoded_msg)) {
                    throw new \Exception("Could not fix gracefully");
                }

                $invalid_json_fixed_message = "Yes";
                $final_msg = json_encode($fix_decoded_msg);
            } catch (\Exception $e) {
                $invalid_json_fixed_message = $e->getMessage();
            }
        }

        $json_valid_message =
            !$valid_json_found ? $valid_json_initial_msg . " - " . $valid_json_initial_error : $valid_json_initial_msg;

        echo "Received: " . date("r") . "\n";
        echo "Valid: " . $json_valid_message . "\n";
        echo "Fixed: " . $invalid_json_fixed_message . "\n";
        echo "Topic: {$topic}\n";
        echo "Original Message:\n";
        echo $msg . "\n";
        echo "Sent Message:\n";
        echo $final_msg . "\n\n";
    }

    public function isJSON($value)
    {
        return !is_numeric($value) && is_array(json_decode($value, true))
        && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}
