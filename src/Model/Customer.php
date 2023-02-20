<?php

namespace PennyBlack\Model;

use InvalidArgumentException;

class Customer
{
    private $vendorCustomerId;

    private $firstName;

    private $lastName;

    private $email;

    private $language;

    private $marketingConsent;

    private $totalOrders;

    private $tags;

    private $totalSpent;

    public static function fromValues(
        ?int $vendorCustomerId,
        ?string $firstName,
        ?string $lastName,
        ?string $email,
        ?string $language,
        ?string $marketingConsent,
        int $totalOrders,
        array $tags,
        float $totalSpent
    ): self {
        if ($vendorCustomerId === null || $vendorCustomerId === 0) {
            throw new InvalidArgumentException('Missing vendor customer ID.');
        }

        return new self(
            $vendorCustomerId,
            $firstName ?? '',
            $lastName ?? '',
            $email ?? '',
            $language ?? '',
            (bool) $marketingConsent,
            $totalOrders,
            $tags,
            $totalSpent
        );
    }

    public function toArray(): array
    {
        return [
            'vendor_customer_id' => $this->vendorCustomerId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'language' => $this->language,
            'marketing_consent' => $this->marketingConsent,
            'total_orders' => $this->totalOrders,
            'tags' => $this->tags,
            'total_spent' => round($this->totalSpent, 2),
        ];
    }

    private function __construct(
        int $vendorCustomerId,
        string $firstName,
        string $lastName,
        string $email,
        string $language,
        bool $marketingConsent,
        int $totalOrders,
        array $tags,
        float $totalSpent
    ) {
        $this->vendorCustomerId = $vendorCustomerId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->language = $language;
        $this->marketingConsent = $marketingConsent;
        $this->totalOrders = $totalOrders;
        $this->tags = $tags;
        $this->totalSpent = $totalSpent;
    }
}
