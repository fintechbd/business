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
use Fintech\Core\Abstracts\BaseModel;

class Business
{
    public function chargeBreakDown($filters = null)
{
	return \singleton(ChargeBreakDownService::class, $filters);
    }

    public function packageTopChart($filters = null)
{
	return \singleton(PackageTopChartService::class, $filters);
    }

    public function service($filters = null)
{
	return \singleton(ServiceService::class, $filters);
    }

    public function servicePackage($filters = null)
{
	return \singleton(ServicePackageService::class, $filters);
    }

    public function serviceSetting($filters = null)
{
	return \singleton(ServiceSettingService::class, $filters);
    }

    public function serviceStat($filters = null)
{
	return \singleton(ServiceStatService::class, $filters);
    }

    public function serviceType($filters = null)
{
	return \singleton(ServiceTypeService::class, $filters);
    }

    public function serviceVendor($filters = null)
{
	return \singleton(ServiceVendorService::class, $filters);
    }

    /**
     * @return CurrencyRateService
     */
    public function currencyRate($filters = null)
{
	return \singleton(CurrencyRateService::class, $filters);
    }

    /**
     * @return ServiceFieldService
     */
    public function serviceField($filters = null)
{
	return \singleton(ServiceFieldService::class, $filters);
    }

    public function serviceTypeManager(array $attributes = [], int|BaseModel|string|null $parent = null): ServiceTypeGenerator
    {
        return new ServiceTypeGenerator($attributes, $parent);
    }
    //** Crud Service Method Point Do not Remove **//

}
