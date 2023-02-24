<?php

namespace Unit\Model;

use InvalidArgumentException;
use PennyBlack\Model\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public function testItCanBeConvertedToAnArray()
    {
        $customer = Customer::fromValues(
            (int) '1',
            'Tim',
            'Apple',
            'example@example.com',
            'en',
            '1',
            12,
            [],
            12.39
        );

        $this->assertEquals(
            [
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
            $customer->toArray()
        );
    }

    public function testItAcceptsNullAsACustomerId()
    {
        $customer = Customer::fromValues(
            null,
            'Tim',
            'Apple',
            'example@example.com',
            'en',
            '1',
            12,
            [],
            12.39
        );

        $this->assertEquals(
            [
                'vendor_customer_id' => null,
                'first_name' => 'Tim',
                'last_name' => 'Apple',
                'email' => 'example@example.com',
                'language' => 'en',
                'marketing_consent' => '1',
                'total_orders' => 12,
                'tags' => [],
                'total_spent' => 12.39,
            ],
            $customer->toArray()
        );
    }

    public function testItReplacesNullValuesWithEmptyStrings()
    {
        $customer = Customer::fromValues(
            (int) '1',
            'Tim',
            null,
            null,
            null,
            null,
            12,
            [],
            12.39
        );

        $this->assertEquals(
            [
                'vendor_customer_id' => 1,
                'first_name' => 'Tim',
                'last_name' => '',
                'email' => '',
                'language' => '',
                'marketing_consent' => false,
                'total_orders' => 12,
                'tags' => [],
                'total_spent' => 12.39,
            ],
            $customer->toArray()
        );
    }
}
