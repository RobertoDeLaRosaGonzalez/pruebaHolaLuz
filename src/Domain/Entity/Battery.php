<?php

namespace App\Domain\Entity;

class Battery
{
    private float $maxEnergy;
    private float $currentEnergy;
    private string $lastUpdated;

    private function __construct(float $maxEnergy, float $currentEnergy)
    {
        $this->maxEnergy = $maxEnergy;
        $this->currentEnergy = $currentEnergy;
        $this->lastUpdated = (new \DateTime())->format(DATE_ATOM);
    }

    public static function create(
        float $maxEnergy = 100.0,
        float $currentEnergy = 0.0
    ): self {
        return new self(
            maxEnergy: $maxEnergy,
            currentEnergy: $currentEnergy
        );
    }

    public function charge(float $amount): void
    {
        $this->currentEnergy += $amount;
        $this->updateTimestamp();
    }

    public function discharge(float $amount): void
    {
        if ($this->currentEnergy - $amount < 0) {
            throw new \InvalidArgumentException("Not enough battery to discharge");
        }
        $this->currentEnergy -= $amount;
        $this->updateTimestamp();
    }

    public function getMaxEnergy(): float
    {
        return $this->maxEnergy;
    }

    public function getCurrentEnergy(): float
    {
        return $this->currentEnergy;
    }

    public function getStatus(): array
    {
        return [
            'percentage' => round(($this->currentEnergy / $this->maxEnergy) * 100, 2),
            'maxEnergy' => $this->maxEnergy,
            'lastUpdated' => $this->lastUpdated
        ];
    }

    public function getLastUpdated(): string
    {
        return $this->lastUpdated;
    }

    private function updateTimestamp(): void
    {
        $this->lastUpdated = (new \DateTime())->format(DATE_ATOM);
    }

    public function isChargeExceeded(float $amount): bool
    {
        return $this->currentEnergy + $amount > $this->maxEnergy;
    }

    public function isDischargeExceeded(float $amount): bool
    {
        return $this->currentEnergy - $amount < 0;
    }
}
