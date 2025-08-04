<?php

namespace App\Application\Battery\ChargeBattery;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Domain\Entity\Battery;
use App\Infrastructure\Service\BatteryHistoryService;

class ChargeBatteryUseCase
{

    public function __construct()
    {
    }

    public function execute(ChargeBatteryRequest $request, BatteryHistoryService $batteryHistoryService): float
    {
        if ($batteryHistoryService->getBatteryInstance()->isChargeExceeded($request->getAmount())) {
            throw new \InvalidArgumentException("Exceeds battery capacity");
        }
        return $batteryHistoryService->charge($request->getAmount());
    }
}
