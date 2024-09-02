<?php

namespace Fintech\Business\Facades;

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
use Illuminate\Support\Facades\Facade;

/**
 * @method static ServicePackageService servicePackage()
 * @method static ServiceSettingService serviceSetting()
 * @method static ServiceStatService serviceStat()
 * @method static ServiceTypeService serviceType()
 * @method static ServiceVendorService serviceVendor()
 * @method static ChargeBreakDownService chargeBreakDown()
 * @method static PackageTopChartService PackageTopChart()
 * @method static ServiceService service()
 * @method static CurrencyRateService currencyRate()
 * @method static ServiceFieldService serviceField()
 * @method static ServiceTypeGenerator serviceTypeManager(array $attributes = [])
 *                                                                                // Crud Service Method Point Do not Remove //
 *
 * @see \Fintech\Business\Business
 */
class Business extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Fintech\Business\Business::class;
    }
}
