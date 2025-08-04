<?php

namespace App\Infrastructure\Service;

use App\Domain\Entity\Battery;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class BatteryHistoryService
{
    private CacheInterface $cache;
    private const BATTERY_KEY = 'battery_state';
    private const HISTORY_KEY = 'battery_history';

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function charge(float $amount): float
    {
        $battery = $this->getBattery();
        $battery->charge($amount);
        $this->addToHistory('charge', $amount);
        $this->saveBattery($battery);
        return $battery->getCurrentEnergy();
    }

    public function discharge(float $amount): float
    {
        $battery = $this->getBattery();
        $battery->discharge($amount);
        $this->addToHistory('discharge', $amount);
        $this->saveBattery($battery);
        return $battery->getCurrentEnergy();
    }

    private function addToHistory(string $type, float $amount): void
    {
        $history = $this->getHistory();
        $history[] = [
            'type' => $type,
            'amount' => $amount,
            'timestamp' => (new \DateTimeImmutable())->format(DATE_ATOM),
        ];
        $history = array_slice($history, -20);

        $this->cache->delete(self::HISTORY_KEY);
        $this->cache->get(self::HISTORY_KEY, fn() => $history);
    }


    public function getStatus(): array
    {
        return $this->getBattery()->getStatus();
    }

    public function getHistory(): array
    {
        return $this->cache->get(self::HISTORY_KEY, function () {
            return [];
        });
    }

    private function getBattery(): Battery
    {
        return $this->cache->get(self::BATTERY_KEY, function (ItemInterface $item) {
            return Battery::create();
        });
    }

    private function saveBattery(Battery $battery): void
    {
        $this->cache->delete(self::BATTERY_KEY);
        $this->cache->get(self::BATTERY_KEY, fn() => $battery);
    }

    public function getBatteryInstance(): Battery
    {
        return $this->getBattery();
    }
}
