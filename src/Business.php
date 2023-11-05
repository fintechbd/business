<?php

namespace Fintech\Business;

use Fintech\Business\Models\ServicePackage;
use Fintech\Business\Models\ServiceSetting;
use Fintech\Business\Models\ServiceState;
use Fintech\Business\Models\ServiceType;
use Fintech\Business\Models\ServiceVendor;
use Fintech\Business\Services\ChargeBreakDownService;
use Fintech\Business\Services\PackageTopChartService;
use Fintech\Business\Services\ServiceService;

class Business
{
    public function chargeBreakDown()
    {
        return app(ChargeBreakDownService::class);
    }

    public function packageTopChart()
    {
        return app(PackageTopChartService::class);
    }

    public function service()
    {
        return app(ServiceService::class);
    }

    public function servicePackage()
    {
        return app(ServicePackage::class);
    }

    public function serviceSetting()
    {
        return app(ServiceSetting::class);
    }

    public function serviceState()
    {
        return app(ServiceState::class);
    }

    public function serviceType()
    {
        return app(ServiceType::class);
    }

    public function serviceVendor()
    {
        return app(ServiceVendor::class);
    }

    //** Crud Service Method Point Do not Remove **//
}
