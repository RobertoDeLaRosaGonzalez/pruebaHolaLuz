<?php

namespace App\Application\Battery\ChargeBattery;

use App\Infrastructure\Service\BatteryHistoryService;
use App\Application\Battery\ChargeBattery\ChargeBatteryRequest;


class ChargeBatteryUseCase
{

    public function __construct()
    {
    }

    public function execute(ChargeBatteryRequest $request, BatteryHistoryService $batteryHistoryService): float
    {
        if ($batteryHistoryService->getBatteryInstance()->isChargeExceeded($request->getAmount())) {
            //TODO crear excepcion personalizada
            throw new \InvalidArgumentException("Exceeds battery capacity");
        }
        return $batteryHistoryService->charge($request->getAmount());
    }
}
