<?php

namespace PHPApiClient\Model;

use InvalidArgumentException;

class OrderDetails
{
    private $externalId;

    private $totalAmount;

    private $totalItems;

    private $billingCountry;

    private $billingPostcode;

    private $billingCity;

    private $shippingCountry;

    private $shippingPostcode;

    private $shippingCity;

    private $currency;

    private $giftMessage;

    private $skus;

    private $productTitles;

    private $promoCodes;

    private $isSubscriptionReorder;

    public static function fromValues(
        ?string $externalId,
        float $totalAmount,
        int $totalItems,
        ?string $billingCountry,
        ?string $billingPostcode,
        ?string $billingCity,
        ?string $shippingCountry,
        ?string $shippingPostcode,
        ?string $shippingCity,
        ?string $currency,
        ?string $giftMessage,
        array $skus,
        array $productTitles,
        array $promoCodes,
        bool $isSubscriptionReorder = false
    ): self {
        if ($externalId === null) {
            throw new InvalidArgumentException('Order ID cannot be null when creating order details.');
        }

        return new self(
            $externalId,
            $totalAmount,
            $totalItems,
            $billingCountry ?? '',
            $billingPostcode ?? '',
            $billingCity ?? '',
            $shippingCountry ?? '',
            $shippingPostcode ?? '',
            $shippingCity ?? '',
            $currency ?? '',
            $giftMessage ?? '',
            $skus,
            $productTitles,
            $promoCodes,
            $isSubscriptionReorder
        );
    }

    public function toArray(): array
    {
        return [
            'external_id' => $this->externalId,
            'total_amount' => round($this->totalAmount, 2),
            'total_items' => $this->totalItems,
            'billing_country' => $this->billingCountry,
            'billing_postcode' => $this->billingPostcode,
            'billing_city' => $this->billingCity,
            'shipping_country' => $this->shippingCountry,
            'shipping_postcode' => $this->shippingPostcode,
            'shipping_city' => $this->shippingCity,
            'currency' => $this->currency,
            'gift_message' => $this->giftMessage,
            'skus' => $this->skus,
            'product_titles' => $this->productTitles,
            'promo_codes' => $this->promoCodes,
            'is_subscription_reorder' => $this->isSubscriptionReorder,
        ];
    }

    private function __construct(
        string $externalId,
        float $totalAmount,
        int $totalItems,
        string $billingCountry,
        string $billingPostcode,
        string $billingCity,
        string $shippingCountry,
        string $shippingPostcode,
        string $shippingCity,
        string $currency,
        string $giftMessage,
        array $skus,
        array $productTitles,
        array $promoCodes,
        bool $isSubscriptionReorder = false
    ) {
        $this->externalId = $externalId;
        $this->totalAmount = $totalAmount;
        $this->totalItems = $totalItems;
        $this->billingCountry = $billingCountry;
        $this->billingPostcode = $billingPostcode;
        $this->billingCity = $billingCity;
        $this->shippingCountry = $shippingCountry;
        $this->shippingPostcode = $shippingPostcode;
        $this->shippingCity = $shippingCity;
        $this->currency = $currency;
        $this->giftMessage = $giftMessage;
        $this->skus = $skus;
        $this->productTitles = $productTitles;
        $this->promoCodes = $promoCodes;
        $this->isSubscriptionReorder = $isSubscriptionReorder;
    }
}
