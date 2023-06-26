<?php

include __DIR__ . "/../vendor/autoload.php";

use PennyBlack\Api;
use PennyBlack\Exception\PennyBlackException;
use PennyBlack\Model\Order;
use PennyBlack\Model\Customer;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Client;

$httpClient = new Client();
$streamFactory = new HttpFactory();
$requestFactory = new HttpFactory();

$apiKey = "YOUR-API-KEY";
$isTest = true;

$api = new Api($httpClient, $requestFactory, $streamFactory, $apiKey, $isTest);

$order = new Order();
$order
    ->setId("1")
    ->setNumber('#100001')
    ->setCreatedAt(new \DateTime())
    ->setCurrency('GBP')
    ->setTotalAmount(123.45)
    ->setTotalItems(2)
    ->setBillingCity('London')
    ->setBillingCountry('GB')
    ->setBillingPostcode('SE15AB')
    ->setShippingCity('London')
    ->setShippingCountry('GB')
    ->setShippingPostcode('SE15AB')
    ->setGiftMessage('I hope you enjoy the socks, love Mum. xxx')
    ->setProductTitles(['Red Socks', 'Blue Socks'])
    ->setPromoCodes(['15OFF_SOCKS'])
    ->setSkus(['SK-R-1', 'SK-B-1'])
    ->setSubscriptionReorder(false)
    ->setTags(['tiktok order']);

$customer = new Customer();
$customer->setEmail('john.doe@example.com')
    ->setFirstName('John')
    ->setLastName('Doe')
    ->setLanguage('en')
    ->setMarketingConsent(true)
    ->setVendorCustomerId("89714912")
    ->setTags(['VIP', 'Loyal Customer'])
    ->setTotalOrders(5)
    ->setTotalSpent(1234.56);

try {
    $api->sendOrder($order, $customer, 'magento');
} catch (PennyBlackException $e) {
    print "OOPS! Something went wrong: " . $e->getMessage();
}
