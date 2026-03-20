<?php

namespace App\Entities;

class PriceEntity implements \JsonSerializable
{
    private float $amount;
    private string $currency;

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }
    public function getCurrency(): string
    {
        return $this->currency;
    }
    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
        ];
    }
}
