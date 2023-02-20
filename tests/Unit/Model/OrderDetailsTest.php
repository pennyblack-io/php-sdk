<?php

namespace Unit\Model;

use InvalidArgumentException;
use PennyBlack\Model\OrderDetails;
use PHPUnit\Framework\TestCase;

class OrderDetailsTest extends TestCase
{
    public function testItCanBeConvertedToAnArray()
    {
        $orderDetails = OrderDetails::fromValues(
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
        );

        $this->assertEquals(
            [
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
            $orderDetails->toArray()
        );
    }

    public function testItThrowsAnExceptionIfExternalIdIsNull()
    {
        $this->expectException(InvalidArgumentException::class);

        OrderDetails::fromValues(
            null,
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
        );
    }

    public function testItHandlesNullValues()
    {
        $orderDetails = OrderDetails::fromValues(
            '1',
            12.5900001,
            2,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            [],
            [],
            [],
            false
        );

        $this->assertEquals(
            [
                'external_id' => '1',
                'total_amount' => 12.59,
                'total_items' => 2,
                'billing_country' => '',
                'billing_postcode' => '',
                'billing_city' => '',
                'shipping_country' => '',
                'shipping_postcode' => '',
                'shipping_city' => '',
                'currency' => '',
                'gift_message' => '',
                'skus' => [],
                'product_titles' => [],
                'promo_codes' => [],
                'is_subscription_reorder' => false,
            ],
            $orderDetails->toArray()
        );
    }
}
