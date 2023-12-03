<?php

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

            Route::get('service-settings/types', [\Fintech\Business\Http\Controllers\ServiceSettingController::class, 'serviceSettingTypes'])->name('service-settings.types');
            Route::get('service-settings/type-fields', [\Fintech\Business\Http\Controllers\ServiceSettingController::class, 'serviceSettingTypeFields'])->name('service-settings.type-fields');
            Route::apiResource('service-settings', \Fintech\Business\Http\Controllers\ServiceSettingController::class);
            Route::post('service-settings/{service_setting}/restore', [\Fintech\Business\Http\Controllers\ServiceSettingController::class, 'restore'])->name('service-settings.restore');

            Route::get('service-types/service-type-list', [\Fintech\Business\Http\Controllers\ServiceTypeController::class, 'serviceTypeList'])->name('service-types.service-type-list');
            Route::apiResource('service-types', \Fintech\Business\Http\Controllers\ServiceTypeController::class);
            Route::post('service-types/{service_type}/restore', [\Fintech\Business\Http\Controllers\ServiceTypeController::class, 'restore'])->name('service-types.restore');

            Route::apiResource('services', \Fintech\Business\Http\Controllers\ServiceController::class);
            Route::post('services/{service}/restore', [\Fintech\Business\Http\Controllers\ServiceController::class, 'restore'])->name('services.restore');
            Route::post('services/calculate-cost', [\Fintech\Business\Http\Controllers\ServiceController::class, 'cost'])->name('services.cost');

            Route::apiResource('service-stats', \Fintech\Business\Http\Controllers\ServiceStatController::class);
            Route::post('service-stats/{service_stat}/restore', [\Fintech\Business\Http\Controllers\ServiceStatController::class, 'restore'])->name('service-stats.restore');

            Route::apiResource('service-packages', \Fintech\Business\Http\Controllers\ServicePackageController::class);
            Route::post('service-packages/{service_package}/restore', [\Fintech\Business\Http\Controllers\ServicePackageController::class, 'restore'])->name('service-packages.restore');

            Route::apiResource('charge-break-downs', \Fintech\Business\Http\Controllers\ChargeBreakDownController::class);
            Route::post('charge-break-downs/{charge_break_down}/restore', [\Fintech\Business\Http\Controllers\ChargeBreakDownController::class, 'restore'])->name('charge-break-downs.restore');

            Route::apiResource('service-vendors', \Fintech\Business\Http\Controllers\ServiceVendorController::class);
            Route::post('service-vendors/{service_vendor}/restore', [\Fintech\Business\Http\Controllers\ServiceVendorController::class, 'restore'])->name('service-vendors.restore');

            Route::apiResource('package-top-charts', \Fintech\Business\Http\Controllers\PackageTopChartController::class);
            Route::post('package-top-charts/{package_top_chart}/restore', [\Fintech\Business\Http\Controllers\PackageTopChartController::class, 'restore'])->name('package-top-charts.restore');

            Route::apiResource('currency-rates', \Fintech\Business\Http\Controllers\CurrencyRateController::class);
            Route::post('currency-rates/{currency_rate}/restore', [\Fintech\Business\Http\Controllers\CurrencyRateController::class, 'restore'])->name('currency-rates.restore');

            if (\Fintech\Core\Facades\Core::packageExists('Auth')) {
                Route::apiResource('role-services', \Fintech\Business\Http\Controllers\RoleServiceController::class)->only(['show', 'update']);
            }
            if (\Fintech\Core\Facades\Core::packageExists('MetaData')) {
                Route::apiResource('country-services', \Fintech\Business\Http\Controllers\CountryServiceController::class)->only(['show', 'update']);
                Route::get('serving-countries', \Fintech\Business\Http\Controllers\ServingCountryController::class)->name('services.serving-countries');
            }

            //DO NOT REMOVE THIS LINE//
        });
}
