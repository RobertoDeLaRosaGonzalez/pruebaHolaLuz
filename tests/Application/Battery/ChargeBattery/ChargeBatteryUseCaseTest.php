<?php

namespace App\Tests\Application\Battery\ChargeBattery;

use PHPUnit\Framework\TestCase;
use App\Application\Battery\ChargeBattery\ChargeBatteryUseCase;
use App\Application\Battery\ChargeBattery\ChargeBatteryRequest;
use App\Infrastructure\Service\BatteryHistoryService;
use App\Domain\Entity\Battery;

class ChargeBatteryUseCaseTest extends TestCase
{
    public function testExecuteWithValidCharge()
    {
        $amount = 10.5;

        $request = $this->createMock(ChargeBatteryRequest::class);
        $request->method('getAmount')->willReturn($amount);

        $batteryMock = $this->createMock(Battery::class);
        $batteryMock->method('isChargeExceeded')->with($amount)->willReturn(false);

        $batteryHistoryService = $this->createMock(BatteryHistoryService::class);
        $batteryHistoryService->method('getBatteryInstance')->willReturn($batteryMock);
        $batteryHistoryService->method('charge')->with($amount)->willReturn($amount);

        $useCase = new ChargeBatteryUseCase();
        $result = $useCase->execute($request, $batteryHistoryService);

        $this->assertEquals($amount, $result);
    }

    public function testExecuteWithExceededCharge()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Exceeds battery capacity');

        $amount = 120.0;

        $request = $this->createMock(ChargeBatteryRequest::class);
        $request->method('getAmount')->willReturn($amount);

        $batteryMock = $this->createMock(Battery::class);
        $batteryMock->method('isChargeExceeded')->with($amount)->willReturn(true);

        $batteryHistoryService = $this->createMock(BatteryHistoryService::class);
        $batteryHistoryService->method('getBatteryInstance')->willReturn($batteryMock);

        $useCase = new ChargeBatteryUseCase();
        $useCase->execute($request, $batteryHistoryService);
    }
}
