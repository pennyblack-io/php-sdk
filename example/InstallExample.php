<?php

include __DIR__ . '/../vendor/autoload.php';

use PennyBlack\Api;
use PennyBlack\Exception\PennyBlackException;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Client;

$httpClient = new Client();
$streamFactory = new HttpFactory();
$requestFactory = new HttpFactory();

$apiKey = 'YOUR-API-KEY';
$isTest = true;

$api = new Api($httpClient, $requestFactory, $streamFactory, $apiKey, $isTest);

try {
    $api->installStore('store.example.com');
} catch (PennyBlackException $e) {
    print 'OOPS! Something went wrong: ' . $e->getMessage();
}
