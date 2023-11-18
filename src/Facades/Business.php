<?php

namespace Fintech\Business\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Fintech\Business\Services\ServicePackageService servicePackage()
 * @method static \Fintech\Business\Services\ServiceSettingService serviceSetting()
 * @method static \Fintech\Business\Services\ServiceStatService serviceStat()
 * @method static \Fintech\Business\Services\ServiceTypeService serviceTYpe()
 * @method static \Fintech\Business\Services\ServiceVendorService serviceVendor()
 * @method static \Fintech\Business\Services\ChargeBreakDownService chargeBreakDown()
 * @method static \Fintech\Business\Services\PackageTopChartService PackageTopChart()
 * @method static \Fintech\Business\Services\ServiceService service()
 * // Crud Service Method Point Do not Remove //
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
