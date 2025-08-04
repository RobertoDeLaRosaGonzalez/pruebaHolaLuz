<?php

namespace App\Application\Battery\DischargeBattery;

use App\Infrastructure\Service\BatteryHistoryService;
use App\Application\Battery\DischargeBattery\DischargeBatteryRequest;

class DischargeBatteryUseCase
{

    public function __construct()
    {
    }

    public function execute(DischargeBatteryRequest $request, BatteryHistoryService $batteryHistoryService): float
    {
        if ($batteryHistoryService->getBatteryInstance()->isDischargeExceeded($request->getAmount())) {
            //TODO crear excepcion personalizada
            throw new \InvalidArgumentException("Exceeds battery capacity");
        }
        return $batteryHistoryService->discharge($request->getAmount());
    }
}
