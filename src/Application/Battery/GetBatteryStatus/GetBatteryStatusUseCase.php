<?php

namespace App\Application\Battery\GetBatteryStatus;

use App\Infrastructure\Service\BatteryHistoryService;
use App\Application\Battery\GetBatteryStatus\GetBatteryStatusRequest;

class GetBatteryStatusUseCase
{

    public function __construct()
    {
    }

    public function execute(GetBatteryStatusRequest $request, BatteryHistoryService $batteryHistoryService): array
    {
        return $batteryHistoryService->getStatus();
    }
}
