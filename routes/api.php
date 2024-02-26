<?php

use Fintech\Business\Http\Controllers\ChargeBreakDownController;
use Fintech\Business\Http\Controllers\CountryServiceController;
use Fintech\Business\Http\Controllers\CurrencyRateCalculateController;
use Fintech\Business\Http\Controllers\CurrencyRateController;
use Fintech\Business\Http\Controllers\PackageTopChartController;
use Fintech\Business\Http\Controllers\RoleServiceController;
use Fintech\Business\Http\Controllers\ServiceController;
use Fintech\Business\Http\Controllers\ServiceFieldController;
use Fintech\Business\Http\Controllers\ServicePackageController;
use Fintech\Business\Http\Controllers\ServiceSettingController;
use Fintech\Business\Http\Controllers\ServiceStatController;
use Fintech\Business\Http\Controllers\ServiceTypeController;
use Fintech\Business\Http\Controllers\ServiceVendorController;
use Fintech\Business\Http\Controllers\ServiceVendorServiceController;
use Fintech\Business\Http\Controllers\ServingCountryController;
use Fintech\Core\Facades\Core;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "API" middleware group. Enjoy building your API!
|
*/
if (Config::get('fintech.business.enabled')) {
    Route::prefix('business')->name('business.')
        ->middleware(config('fintech.auth.middleware'))
        ->group(function () {

            Route::get('service-settings/types', [ServiceSettingController::class, 'serviceSettingTypes'])->name('service-settings.types');
            Route::get('service-settings/type-fields', [ServiceSettingController::class, 'serviceSettingTypeFields'])->name('service-settings.type-fields');
            Route::apiResource('service-settings', ServiceSettingController::class);
            Route::post('service-settings/{service_setting}/restore', [ServiceSettingController::class, 'restore'])->name('service-settings.restore');

            Route::get('service-types/service-type-list', [ServiceTypeController::class, 'serviceTypeList'])->name('service-types.service-type-list');
            Route::apiResource('service-types', ServiceTypeController::class);
            Route::post('service-types/{service_type}/restore', [ServiceTypeController::class, 'restore'])->name('service-types.restore');

            Route::apiResource('services', ServiceController::class);
            Route::post('services/{service}/restore', [ServiceController::class, 'restore'])->name('services.restore');
            Route::post('services/calculate-cost', [ServiceController::class, 'cost'])->name('services.cost');

            Route::get('service-stats/destination-country-list', [ServiceStatController::class, 'serviceStatWiseCountry'])->name('service-stats.destination-country-list');
            Route::apiResource('service-stats', ServiceStatController::class);
            Route::post('service-stats/{service_stat}/restore', [ServiceStatController::class, 'restore'])->name('service-stats.restore');

            Route::apiResource('service-packages', ServicePackageController::class);
            Route::post('service-packages/{service_package}/restore', [ServicePackageController::class, 'restore'])->name('service-packages.restore');

            Route::apiResource('charge-break-downs', ChargeBreakDownController::class);
            Route::post('charge-break-downs/{charge_break_down}/restore', [ChargeBreakDownController::class, 'restore'])->name('charge-break-downs.restore');

            Route::apiResource('service-vendors', ServiceVendorController::class);
            Route::post('service-vendors/{service_vendor}/restore', [ServiceVendorController::class, 'restore'])->name('service-vendors.restore');

            Route::apiResource('package-top-charts', PackageTopChartController::class);
            Route::post('package-top-charts/{package_top_chart}/restore', [PackageTopChartController::class, 'restore'])->name('package-top-charts.restore');

            Route::apiResource('currency-rates', CurrencyRateController::class);
            Route::post('currency-rates/{currency_rate}/restore', [CurrencyRateController::class, 'restore'])->name('currency-rates.restore');

            if (Core::packageExists('Auth')) {
                Route::apiResource('role-services', RoleServiceController::class)->only(['show', 'update']);
            }
            if (Core::packageExists('MetaData')) {
                Route::apiResource('country-services', CountryServiceController::class)->only(['show', 'update']);
                Route::get('serving-countries', ServingCountryController::class)->name('services.serving-countries');
            }

            Route::apiResource('service-vendor-services', ServiceVendorServiceController::class)->only(['show', 'update']);
            Route::apiResource('service-fields', ServiceFieldController::class);
            Route::post('service-fields/{service_field}/restore', [ServiceFieldController::class, 'restore'])->name('service-fields.restore');

            //DO NOT REMOVE THIS LINE//
        });

    Route::prefix('dropdown')->name('business.')->group(function () {
        Route::get('currency-convert-rate', CurrencyRateCalculateController::class)->name('currency-convert-rate');
    });
}
