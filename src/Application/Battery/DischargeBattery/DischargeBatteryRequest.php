<?php

namespace App\Application\Battery\DischargeBattery;

class DischargeBatteryRequest
{
    private float $amount;

    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    public static function create(
        float $amount
    ): self {
        return new self($amount);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
