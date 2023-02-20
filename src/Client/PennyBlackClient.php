<?php

namespace PHPApiClient\Client;

use PHPApiClient\Exception\AuthenticationException;
use PHPApiClient\Exception\RequestException;

class PennyBlackClient
{
    private const PROD_URL = 'https://api.pennyblack.io/ingest/';
    private const TEST_URL = 'https://api.test.pennyblack.io/ingest/';

    private const SUCCESS_RESPONSE_CODES = [201, 202, 204];

    private $apiKey;

    private $baseUrl;

    public function __construct(string $apiKey, bool $isTest)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $isTest ? self::TEST_URL : self::PROD_URL;
    }

    /**
     * @return mixed
     *
     * @throws AuthenticationException
     * @throws RequestException
     */
    public function jsonRequest(string $method, string $path, array $jsonParams)
    {
        return $this->request($method, $path, [], $jsonParams, []);
    }

    /**
     * @return mixed
     *
     * @throws AuthenticationException
     * @throws RequestException
     */
    public function request(
        string $method,
        string $path,
        array $queryParams,
        array $jsonParams,
        array $postFormParams
    ) {
        $setOptArray = $this->getCurlOptions($method, $path, $queryParams, $jsonParams, $postFormParams);

        $curl = curl_init();
        curl_setopt_array($curl, $setOptArray);

        $response = curl_exec($curl);
        $phpVersionHttpCode = version_compare(phpversion(), '5.5.0', '>') ? CURLINFO_RESPONSE_CODE : CURLINFO_HTTP_CODE;
        $statusCode = curl_getinfo($curl, $phpVersionHttpCode);
        curl_close($curl);

        return $this->handleResponse($response, $statusCode);
    }

    private function getCurlOptions(
        string $method,
        string $path,
        array $queryParams = [],
        array $jsonParams = [],
        array $postFormParams = []
    ): array {
        $url = $this->baseUrl . $path;
        if ($queryParams) {
            $url .= '?' . http_build_query($queryParams);
        }

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => ['X-Api-Key: ' . $this->apiKey],
        ];

        if ('POST' == $method) {
            $options[CURLOPT_POST] = 1;
        }

        if ($jsonParams) {
            $options[CURLOPT_POSTFIELDS] = json_encode($jsonParams);
            $options[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
        } elseif ($postFormParams) {
            $options[CURLOPT_POSTFIELDS] = http_build_query($postFormParams);
        }

        return $options;
    }

    /**
     * Handle response from API call
     */
    private function handleResponse($response, int $statusCode)
    {
        if ($statusCode == 401) {
            throw new AuthenticationException();
        }

        if (!in_array($statusCode, self::SUCCESS_RESPONSE_CODES)) {
            throw new RequestException($response, $statusCode);
        }

        return json_decode($response, true);
    }
}
