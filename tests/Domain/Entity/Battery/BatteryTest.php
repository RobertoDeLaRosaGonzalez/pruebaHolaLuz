<?php

namespace App\Tests\Domain\Entity;

use PHPUnit\Framework\TestCase;
use App\Domain\Entity\Battery;

class BatteryTest extends TestCase
{
    public function testCreateInitialValues()
    {
        $battery = Battery::create(100.0, 50.0);
        $this->assertEquals(50.0, $battery->getCurrentEnergy());
    }

    public function testChargeIncreasesEnergy()
    {
        $battery = Battery::create(100.0, 40.0);
        $battery->charge(10.0);
        $this->assertEquals(50.0, $battery->getCurrentEnergy());
    }

    public function testDischargeDecreasesEnergy()
    {
        $battery = Battery::create(100.0, 40.0);
        $battery->discharge(15.0);
        $this->assertEquals(25.0, $battery->getCurrentEnergy());
    }

    public function testIsChargeExceeded()
    {
        $battery = Battery::create(100.0, 90.0);
        $this->assertTrue($battery->isChargeExceeded(20.0));
        $this->assertFalse($battery->isChargeExceeded(5.0));
    }

    public function testIsDischargeExceeded()
    {
        $battery = Battery::create(100.0, 30.0);
        $this->assertTrue($battery->isDischargeExceeded(40.0));
        $this->assertFalse($battery->isDischargeExceeded(10.0));
    }
}
