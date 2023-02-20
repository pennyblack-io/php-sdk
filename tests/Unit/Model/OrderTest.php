<?php

namespace Unit\Model;

use InvalidArgumentException;
use PennyBlack\Model\Customer;
use PennyBlack\Model\Order;
use PennyBlack\Model\OrderDetails;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testItCanBeConvertedToAnArray()
    {
        $order = Order::fromValues(
            '1',
            'XDF12345',
            '2022-11-28 13:26:22',
            Customer::fromValues(
                1,
                'Tim',
                'Apple',
                'example@example.com',
                'en',
                '1',
                12,
                [],
                12.39
            ),
            OrderDetails::fromValues(
                '1',
                12.5900001,
                2,
                'United Kingdom',
                'SW1 4FH',
                'London',
                'United Kingdom',
                'SO12 4YT',
                'Southampton',
                'GBP',
                'This is a gift message',
                ['12345', '4567'],
                ['Test Product 1', 'Test Product 2'],
                ['promo123'],
                false
            )
        );

        $this->assertEquals(
            [
                'external_order_id' => '1',
                'external_order_name' => 'XDF12345',
                'created_at' => '2022-11-28 13:26:22',
                'customer' => [
                    'vendor_customer_id' => 1,
                    'first_name' => 'Tim',
                    'last_name' => 'Apple',
                    'email' => 'example@example.com',
                    'language' => 'en',
                    'marketing_consent' => '1',
                    'total_orders' => 12,
                    'tags' => [],
                    'total_spent' => 12.39,
                ],
                'order' => [
                    'external_id' => '1',
                    'total_amount' => 12.59,
                    'total_items' => 2,
                    'billing_country' => 'United Kingdom',
                    'billing_postcode' => 'SW1 4FH',
                    'billing_city' => 'London',
                    'shipping_country' => 'United Kingdom',
                    'shipping_postcode' => 'SO12 4YT',
                    'shipping_city' => 'Southampton',
                    'currency' => 'GBP',
                    'gift_message' => 'This is a gift message',
                    'skus' => ['12345', '4567'],
                    'product_titles' => ['Test Product 1', 'Test Product 2'],
                    'promo_codes' => ['promo123'],
                    'is_subscription_reorder' => false,
                ],
            ],
            $order->toArray()
        );
    }

    public function testItThrowsAnExceptionIfOrderIdsAreNull()
    {
        $this->expectException(InvalidArgumentException::class);

        Order::fromValues(
            null,
            null,
            '2022-11-28 13:26:22',
            Customer::fromValues(
                1,
                'Tim',
                'Apple',
                'example@example.com',
                'en',
                '1',
                12,
                [],
                12.39
            ),
            OrderDetails::fromValues(
                '1',
                12.5900001,
                2,
                'United Kingdom',
                'SW1 4FH',
                'London',
                'United Kingdom',
                'SO12 4YT',
                'Southampton',
                'GBP',
                'This is a gift message',
                ['12345', '4567'],
                ['Test Product 1', 'Test Product 2'],
                ['promo123'],
                false
            )
        );
    }
}
