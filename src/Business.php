<?php

namespace Fintech\Business;

use Fintech\Business\Services\ServicePackageService;
use Fintech\Business\Services\ServiceSettingService;
use Fintech\Business\Services\ServiceStateService;
use Fintech\Business\Services\ServiceTypeService;
use Fintech\Business\Services\ServiceVendorService;
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
        return app(ServicePackageService::class);
    }

    public function serviceSetting()
    {
        return app(ServiceSettingService::class);
    }

    public function serviceState()
    {
        return app(ServiceStateService::class);
    }

    public function serviceType()
    {
        return app(ServiceTypeService::class);
    }

    public function serviceVendor()
    {
        return app(ServiceVendorService::class);
    }

    //** Crud Service Method Point Do not Remove **//
}
