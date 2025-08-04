<?php

namespace App\Application\Battery\GetBatteryHistory;

class GetBatteryHistoryRequest
{

    public function __construct()
    {
    }

    public static function create(
    ): self {
        return new self();
    }

}
