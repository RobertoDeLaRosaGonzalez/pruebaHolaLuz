<?php

namespace App\Application\Battery\GetBatteryHistory;

use App\Infrastructure\Service\BatteryHistoryService;
use App\Application\Battery\GetBatteryHistory\GetBatteryHistoryRequest;

class GetBatteryHistoryUseCase
{

    public function __construct()
    {
    }

    public function execute(GetBatteryHistoryRequest $request, BatteryHistoryService $batteryHistoryService): array
    {
        return $batteryHistoryService->getHistory();
    }
}
