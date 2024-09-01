<?php

namespace Fintech\Business;

use Fintech\Business\Services\ChargeBreakDownService;
use Fintech\Business\Services\CurrencyRateService;
use Fintech\Business\Services\PackageTopChartService;
use Fintech\Business\Services\ServiceFieldService;
use Fintech\Business\Services\ServicePackageService;
use Fintech\Business\Services\ServiceService;
use Fintech\Business\Services\ServiceSettingService;
use Fintech\Business\Services\ServiceStatService;
use Fintech\Business\Services\ServiceTypeService;
use Fintech\Business\Services\ServiceVendorService;
use Fintech\Business\Supports\ServiceTypeGenerator;

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

    public function serviceStat()
    {
        return app(ServiceStatService::class);
    }

    public function serviceType()
    {
        return app(ServiceTypeService::class);
    }

    public function serviceVendor()
    {
        return app(ServiceVendorService::class);
    }

    /**
     * @return CurrencyRateService
     */
    public function currencyRate()
    {
        return app(CurrencyRateService::class);
    }

    /**
     * @return ServiceFieldService
     */
    public function serviceField()
    {
        return app(ServiceFieldService::class);
    }

    /**
     * @param array $attributes
     * @return ServiceTypeGenerator
     */
    public function serviceTypeManager(array $attributes = []): ServiceTypeGenerator
    {
        return new ServiceTypeGenerator($attributes);
    }
    //** Crud Service Method Point Do not Remove **//

}
