<?php

namespace PHPApiClient\Model;

use InvalidArgumentException;

class Order
{
    private $externalId;

    private $externalOrderName;

    private $createdAt;

    private $customer;

    private $orderDetails;

    public static function fromValues(
        ?string $externalId,
        ?string $externalOrderName,
        string $createdAt,
        Customer $customer,
        OrderDetails $orderDetails
    ): self {
        if ($externalId === null && $externalOrderName === null) {
            throw new InvalidArgumentException('Unable to build Penny Black order, missing order IDs.');
        }

        return new self(
            $externalId,
            $externalOrderName,
            $createdAt,
            $customer,
            $orderDetails
        );
    }

    public function toArray(): array
    {
        return [
            'external_order_id' => $this->externalId,
            'external_order_name' => $this->externalOrderName,
            'created_at' => $this->createdAt,
            'customer' => $this->customer->toArray(),
            'order' => $this->orderDetails->toArray(),
        ];
    }

    private function __construct(
        string $externalId,
        string $externalOrderName,
        string $createdAt,
        Customer $customer,
        OrderDetails $orderDetails
    ) {
        $this->customer = $customer;
        $this->orderDetails = $orderDetails;
        $this->externalId = $externalId;
        $this->externalOrderName = $externalOrderName;
        $this->createdAt = $createdAt;
    }
}
