<?php

namespace PennyBlack;

use PennyBlack\Exception\ApiException;
use PennyBlack\Exception\AuthenticationException;
use PennyBlack\Exception\PennyBlackException;
use PennyBlack\Exception\ServerErrorException;
use PennyBlack\Exception\ServiceUnavailableException;
use PennyBlack\Model\Customer;
use PennyBlack\Model\Order;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Api
{
    private const PROD_URL = 'https://api.pennyblack.io/';
    private const TEST_URL = 'https://api.test.pennyblack.io/';

    private const MAX_RETRIES = 3;
    private const SUCCESS_RESPONSE_CODES = [200, 201, 202, 204];

    private ClientInterface $httpClient;
    private RequestFactoryInterface $requestFactory;
    private StreamFactoryInterface $streamFactory;
    private string $apiKey;
    private string $baseUrl;

    public function __construct(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        string $apiKey,
        bool $isTest = false
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->apiKey = $apiKey;
        $this->baseUrl = $isTest ? self::TEST_URL : self::PROD_URL;
    }

    /**
     * @throws PennyBlackException
     */
    public function installStore(string $shopUrl): void
    {
        $this->sendPostRequest('ingest/install', ['shop_url' => $shopUrl]);
    }

    /**
     * Send an order with a retry mechanism for errors that are possibly down to network transmission.
     *
     * @throws PennyBlackException
     */
    public function sendOrder(Order $order, Customer $customer, string $origin): void
    {
        $content = [
            'order' => $order->toArray(),
            'customer' => $customer->toArray(),
            'origin' => $origin
        ];

        $this->sendPostRequestWithRetries('ingest/order', $content);
    }

    /**
     * @throws PennyBlackException
     */
    public function requestPrint(string $orderId, string $merchantId = '', string $printLocation = ''): void
    {
        // TODO: Next PR
    }

    /**
     * @throws PennyBlackException
     */
    public function requestBatchPrint(): void
    {
        // TODO: Next PR
    }

    /**
     * @throws PennyBlackException
     */
    public function getOrderPrintStatus(string $merchantId, string $orderId): array
    {
        // TODO: Next PR
        return $this->sendGetRequest('fulfilment/orders/' . $merchantId . '/' . $orderId);
    }

    private function sendPostRequestWithRetries(string $path, $content): array
    {
        $numAttempts = 0;

        while (1) {
            try {
                return $this->sendPostRequest($path, $content);
            } catch (ServerErrorException | ServiceUnavailableException $e) {
                ++$numAttempts;

                if ($numAttempts >= self::MAX_RETRIES) {
                    throw $e;
                }
            }
        }
    }

    /**
     * @throws ApiException
     * @throws AuthenticationException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     */
    private function sendPostRequest(string $path, $content): array
    {
        $body = $this->streamFactory->createStream(json_encode($content));
        $request = $this->requestFactory->createRequest('POST', $this->baseUrl . ltrim($path, '/'))
            ->withHeader('X-Api-Key', $this->apiKey)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        return $this->sendRequest($request);
    }

    /**
     * @throws ApiException
     * @throws AuthenticationException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     */
    private function sendGetRequest($path): array
    {
        $request = $this->requestFactory->createRequest('GET', $this->baseUrl . ltrim($path, '/'))
            ->withHeader('X-Api-Key', $this->apiKey);

        return $this->sendRequest($request);
    }

    /**
     * @throws ServiceUnavailableException
     * @throws ServerErrorException
     * @throws AuthenticationException
     * @throws ApiException
     */
    private function sendRequest(RequestInterface $request): array
    {
        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode > 500) {
            throw new ServiceUnavailableException($response->getBody()->getContents(), $statusCode);
        }

        if ($statusCode === 500) {
            throw new ServerErrorException($response->getBody()->getContents());
        }

        if ($statusCode === 401 || $statusCode === 403) {
            throw new AuthenticationException($statusCode);
        }

        if (in_array($statusCode, self::SUCCESS_RESPONSE_CODES)) {
            return json_decode($response->getBody()->getContents(), true);
        }

        throw new ApiException($response->getBody()->getContents(), $statusCode);
    }
}
