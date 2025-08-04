<?php

namespace App\Tests\Application\Battery\DischargeBattery;

use PHPUnit\Framework\TestCase;
use App\Application\Battery\DischargeBattery\DischargeBatteryUseCase;
use App\Application\Battery\DischargeBattery\DischargeBatteryRequest;
use App\Infrastructure\Service\BatteryHistoryService;
use App\Domain\Entity\Battery;

class DischargeBatteryUseCaseTest extends TestCase
{
    public function testExecuteWithValidDischarge()
    {
        $amount = 15.0;

        $request = $this->createMock(DischargeBatteryRequest::class);
        $request->method('getAmount')->willReturn($amount);

        $batteryMock = $this->createMock(Battery::class);
        $batteryMock->method('isDischargeExceeded')->with($amount)->willReturn(false);

        $batteryHistoryService = $this->createMock(BatteryHistoryService::class);
        $batteryHistoryService->method('getBatteryInstance')->willReturn($batteryMock);
        $batteryHistoryService->method('discharge')->with($amount)->willReturn($amount);

        $useCase = new DischargeBatteryUseCase();
        $result = $useCase->execute($request, $batteryHistoryService);

        $this->assertEquals($amount, $result);
    }

    public function testExecuteWithExceededDischarge()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Exceeds battery capacity');

        $amount = 200.0;

        $request = $this->createMock(DischargeBatteryRequest::class);
        $request->method('getAmount')->willReturn($amount);

        $batteryMock = $this->createMock(Battery::class);
        $batteryMock->method('isDischargeExceeded')->with($amount)->willReturn(true);

        $batteryHistoryService = $this->createMock(BatteryHistoryService::class);
        $batteryHistoryService->method('getBatteryInstance')->willReturn($batteryMock);

        $useCase = new DischargeBatteryUseCase();
        $useCase->execute($request, $batteryHistoryService);
    }
}
