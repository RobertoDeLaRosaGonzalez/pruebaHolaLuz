<?php

namespace App\Application\Battery\GetBatteryStatus;

class GetBatteryStatusRequest
{

    public function __construct()
    {
    }

    public static function create(
    ): self {
        return new self();
    }

}
