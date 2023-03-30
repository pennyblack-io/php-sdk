<?php

include __DIR__ . "/../vendor/autoload.php";

use PennyBlack\Api;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Client;

$httpClient = new Client();
$streamFactory = new HttpFactory();
$requestFactory = new HttpFactory();

$apiKey = "YOUR-API-KEY";
$isTest = true;

$api = new Api($httpClient, $requestFactory, $streamFactory, $apiKey, $isTest);

$api->installStore("store.example.com");
