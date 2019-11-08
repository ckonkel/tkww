<?php

namespace TKWW\Messaging;

interface ServiceInterface
{
    /**
     * @param $topic
     */
    public function subscribe(string $topic): void;

    /**
     * @param string $topic
     * @param string $message
     */
    public function publish(string $topic, string $message): void;
}
