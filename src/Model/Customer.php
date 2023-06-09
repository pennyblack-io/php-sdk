<?php

namespace PennyBlack\Model;

use PennyBlack\Exception\PennyBlackException;

class Customer
{
    private const REQUIRED_FIELDS = ['firstName', 'lastName', 'email'];

    private string $firstName;
    private string $lastName;
    private string $email;
    private ?string $vendorCustomerId;
    private ?string $language;
    private ?bool $marketingConsent;
    private ?int $totalOrders;
    private ?array $tags;
    private ?float $totalSpent;

    public function setVendorCustomerId(string $vendorCustomerId): self
    {
        $this->vendorCustomerId = $vendorCustomerId;

        return $this;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function setMarketingConsent(bool $marketingConsent): self
    {
        $this->marketingConsent = $marketingConsent;

        return $this;
    }

    public function setTotalOrders(int $totalOrders): self
    {
        $this->totalOrders = $totalOrders;

        return $this;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function setTotalSpent(float $totalSpent): self
    {
        $this->totalSpent = $totalSpent;

        return $this;
    }

    /**
     * @throws PennyBlackException
     */
    public function toArray(): array
    {
        $this->validateRequiredFields();

        $output = [
            "first_name" => $this->firstName,
            "last_name" => $this->lastName,
            "email" => $this->email,
        ];

        $optionalFieldsWhenEmpty = [
            'vendor_customer_id' => 'vendorCustomerId',
            'language' => 'language',
            'tags' => 'tags',
        ];

        foreach ($optionalFieldsWhenEmpty as $outputKey => $thisProp) {
            if (!empty($this->{$thisProp})) {
                $output[$outputKey] = $this->{$thisProp};
            }
        }

        if (isset($this->marketingConsent)) {
            $output["marketing_consent"] = $this->marketingConsent;
        }
        if (isset($this->totalOrders)) {
            $output["total_orders"] = $this->totalOrders;
        }
        if (isset($this->totalSpent)) {
            $output["total_spent"] = $this->totalSpent;
        }

        return $output;
    }

    /**
     * @throws PennyBlackException
     */
    private function validateRequiredFields(): void
    {
        foreach (self::REQUIRED_FIELDS as $requiredField) {
            if (!isset($this->{$requiredField})) {
                throw new PennyBlackException('Required field "' . $requiredField . '" must be set');
            }
        }
    }
}
