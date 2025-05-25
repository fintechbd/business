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
use Fintech\Core\Abstracts\BaseModel;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|ServicePackageService servicePackage(array $filters = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|ServiceSettingService serviceSetting(array $filters = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|ServiceStatService serviceStat(array $filters = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|ServiceTypeService serviceType(array $filters = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|ServiceVendorService serviceVendor(array $filters = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|ChargeBreakDownService chargeBreakDown(array $filters = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|PackageTopChartService PackageTopChart(array $filters = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|ServiceService service(array $filters = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|CurrencyRateService currencyRate(array $filters = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|ServiceFieldService serviceField(array $filters = null)
 * @method static ServiceTypeGenerator serviceTypeManager(array $attributes = [], string|int|BaseModel|null $parentId = null)
 *                                                                                                                            // Crud Service Method Point Do not Remove //
 *
 * @see \Fintech\Business\Business
 */
class Business extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Fintech\Business\business()->class;
    }
}
