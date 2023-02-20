<?php

namespace Unit\Api;

use PennyBlack\Api;
use PennyBlack\Client\PennyBlackClient;
use PennyBlack\Exception\RequestException;
use PennyBlack\Model\Order;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PennyBlackApiTest extends TestCase
{
    /** @var MockObject|PennyBlackClient */
    private $mockClient;

    public function setUp(): void
    {
        $this->mockClient = $this->createMock(PennyBlackClient::class);
    }

    public function testItSendsAnInstallRequest()
    {
        $api = new Api($this->mockClient);

        $this->mockClient
            ->expects($this->once())
            ->method('jsonRequest')
            ->with('POST', 'install', ['shop_url' => 'https://test-url']);

        $api->install('https://test-url');
    }

    public function testItSendsAnOrderRequest()
    {
        $api = new Api($this->mockClient);

        $mockOrder = $this->createMock(Order::class);
        $mockOrder->method('toArray')->willReturn([
            'external_order_id' => '1',
            'external_order_name' => 'XDF12345',
            'created_at' => '2022-11-28 13:26:22',
        ]);

        $this->mockClient
            ->expects($this->once())
            ->method('jsonRequest')
            ->with('POST', 'order', [
                'items' => [[
                    'payload' => [
                        'external_order_id' => '1',
                        'external_order_name' => 'XDF12345',
                        'created_at' => '2022-11-28 13:26:22',
                    ],
                ]],
                'source' => 'prestashop',
            ]);

        $api->sendOrder($mockOrder, 'prestashop');
    }

    public function testItSendsAnOrderRequestAfterRetrying()
    {
        $api = new Api($this->mockClient);

        $mockOrder = $this->createMock(Order::class);
        $mockOrder->method('toArray')->willReturn([
            'external_order_id' => '1',
            'external_order_name' => 'XDF12345',
            'created_at' => '2022-11-28 13:26:22',
        ]);

        $expectedCallCount = $this->exactly(2);

        $this->mockClient
            ->expects($this->once())
            ->method('jsonRequest')
            ->with('POST', 'order', [
                'items' => [[
                    'payload' => [
                        'external_order_id' => '1',
                        'external_order_name' => 'XDF12345',
                        'created_at' => '2022-11-28 13:26:22',
                    ],
                ]],
                'source' => 'prestashop',
            ])
            ->will($this->returnCallback(function () use ($expectedCallCount) {
                if ($expectedCallCount->getInvocationCount() === 1) {
                    throw new RequestException('oops');
                }
            }));

        $api->sendOrder($mockOrder, 'prestashop');
    }

    public function testItThrowsAnExceptionIfOrderSendingFailsMoreThanThreeTimes()
    {
        $api = new Api($this->mockClient);

        $mockOrder = $this->createMock(Order::class);
        $mockOrder->method('toArray')->willReturn([
            'external_order_id' => '1',
            'external_order_name' => 'XDF12345',
            'created_at' => '2022-11-28 13:26:22',
        ]);

        $this->mockClient
            ->expects($this->exactly(3))
            ->method('jsonRequest')
            ->with('POST', 'order', [
                'items' => [[
                    'payload' => [
                        'external_order_id' => '1',
                        'external_order_name' => 'XDF12345',
                        'created_at' => '2022-11-28 13:26:22',
                    ],
                ]],
                'source' => 'prestashop',
            ])
            ->willThrowException(new RequestException('oops'));

        $this->expectException(RequestException::class);
        $api->sendOrder($mockOrder, 'prestashop');
    }
}
