<?php

namespace PennyBlack;

use PennyBlack\Client\PennyBlackClient;
use PennyBlack\Exception\RequestException;
use PennyBlack\Model\Order;

class Api
{
    private const MAX_RETRIES = 3;

    private $client;

    public function __construct(PennyBlackClient $client)
    {
        $this->client = $client;
    }

    /**
     * @throws Exception\AuthenticationException
     * @throws RequestException
     */
    public function install(string $shopUrl): void
    {
        $this->client->jsonRequest('POST', 'install', ['shop_url' => $shopUrl]);
    }

    /**
     * @throws Exception\AuthenticationException
     * @throws RequestException
     */
    public function sendOrder(Order $order, string $origin): void
    {
        $sent = false;
        $numAttempts = 0;

        while (!$sent) {
            try {
                $this->client->jsonRequest('POST', 'order', [
                    'items' => [[
                        'payload' => $order->toArray(),
                    ]],
                    'source' => $origin,
                ]);

                $sent = true;
            } catch (RequestException $e) {
                ++$numAttempts;

                if ($numAttempts == self::MAX_RETRIES) {
                    throw $e;
                }
            }
        }
    }
}
