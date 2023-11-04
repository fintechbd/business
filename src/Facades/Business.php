<?php

namespace Fintech\Business\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Fintech\Business\Models\ServicePackage servicePackage()
 * @method static \Fintech\Business\Models\ServiceSetting serviceSetting()
 * @method static \Fintech\Business\Models\ServiceState serviceState()
 * @method static \Fintech\Business\Models\ServiceVendor serviceVendor()
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
