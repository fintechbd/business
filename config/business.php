<?php

// config for Fintech/Business
use Fintech\Business\Models\ChargeBreakDown;
use Fintech\Business\Models\CurrencyRate;
use Fintech\Business\Models\PackageTopChart;
use Fintech\Business\Models\Service;
use Fintech\Business\Models\ServiceField;
use Fintech\Business\Models\ServicePackage;
use Fintech\Business\Models\ServiceSetting;
use Fintech\Business\Models\ServiceStat;
use Fintech\Business\Models\ServiceType;
use Fintech\Business\Models\ServiceVendor;
use Fintech\Business\Repositories\Eloquent\ChargeBreakDownRepository;
use Fintech\Business\Repositories\Eloquent\CurrencyRateRepository;
use Fintech\Business\Repositories\Eloquent\PackageTopChartRepository;
use Fintech\Business\Repositories\Eloquent\ServiceFieldRepository;
use Fintech\Business\Repositories\Eloquent\ServicePackageRepository;
use Fintech\Business\Repositories\Eloquent\ServiceRepository;
use Fintech\Business\Repositories\Eloquent\ServiceSettingRepository;
use Fintech\Business\Repositories\Eloquent\ServiceStatRepository;
use Fintech\Business\Repositories\Eloquent\ServiceTypeRepository;
use Fintech\Business\Repositories\Eloquent\ServiceVendorRepository;

return [

    /*
    |--------------------------------------------------------------------------
    | Enable Module APIs
    |--------------------------------------------------------------------------
    | this setting enable the api will be available or not
    */
    'enabled' => env('PACKAGE_BUSINESS_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | System Default Vendor
    |--------------------------------------------------------------------------
    | this setting enable the api will be available or not
    */
    'default_vendor_name' => env('PACKAGE_BUSINESS_DEFAULT_VENDOR', 'Fintech Bangladesh'),
    'default_vendor' => 1,

    /*
    |--------------------------------------------------------------------------
    | Default Service Stat Settings
    |--------------------------------------------------------------------------
    | this setting values will be used for service stat setting value
    */
    'service_stat_settings' => [
        'lower_limit' => '10.00',
        'higher_limit' => '5000.00',
        'local_currency_lower_limit' => '10.00',
        'local_currency_higher_limit' => '25000.00',
        'charge' => '1%',
        'discount' => '1%',
        'commission' => '0',
        'cost' => '0.00',
        'charge_refund' => 'yes',
        'discount_refund' => 'yes',
        'commission_refund' => 'yes',
    ],
    /*
    |--------------------------------------------------------------------------
    | Business Group Root Prefix
    |--------------------------------------------------------------------------
    |
    | This value will be added to all your routes from this package
    | Example: APP_URL/{root_prefix}/api/business/action
    |
    | Note: while adding prefix add closing ending slash '/'
    */

    'root_prefix' => '/',

    /*
    |--------------------------------------------------------------------------
    | ServiceSetting Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'service_setting_model' => ServiceSetting::class,

    /*
    |--------------------------------------------------------------------------
    | ServiceType Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'service_type_model' => ServiceType::class,

    /*
    |--------------------------------------------------------------------------
    | Service Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'service_model' => Service::class,

    /*
    |--------------------------------------------------------------------------
    | ServiceStat Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'service_stat_model' => ServiceStat::class,

    /*
    |--------------------------------------------------------------------------
    | ServicePackage Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'service_package_model' => ServicePackage::class,

    /*
    |--------------------------------------------------------------------------
    | ChargeBreakDown Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'charge_break_down_model' => ChargeBreakDown::class,

    /*
    |--------------------------------------------------------------------------
    | PackageTopChart Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'package_top_chart_model' => PackageTopChart::class,

    /*
    |--------------------------------------------------------------------------
    | ServiceVendor Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'service_vendor_model' => ServiceVendor::class,

    /*
    |--------------------------------------------------------------------------
    | CurrencyRate Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'currency_rate_model' => CurrencyRate::class,

    /*
    |--------------------------------------------------------------------------
    | ServiceField Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'service_field_model' => ServiceField::class,

    //** Model Config Point Do not Remove **//

    /*
    |--------------------------------------------------------------------------
    | Currency Rate Vendor Configuration
    |--------------------------------------------------------------------------
    |
    | This value will be used across systems where a repository instance is needed
    */

    'currency_rate_vendor' => [
        'free_currency_api' => [
            'base_url' => 'https://api.freecurrencyapi.com/v1/',
            'api_key' => env('FREE_CURRENCY_API_KEY', null),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Constant
    |--------------------------------------------------------------------------
    |
    | This value will be used across systems where a constant instance is needed
    */

    'service_setting_types' => [
        'service_stat' => 'Service Stat',
        'service' => 'Service',
    ],

    'service_setting_type_fields' => [
        'text' => 'TEXT',
        'select' => 'SELECT',
        'textarea' => 'TEXT AREA',
        'date' => 'DATE',
        'radio' => 'RADIO',
        'email' => 'EMAIL',
        'number' => 'NUMBER',
        'tel' => 'TEL',
        'url' => 'URL',
        'select-beneficiary-type' => 'SELECT BENEFICIARY TYPE',
    ],

    /*
    |--------------------------------------------------------------------------
    | Repositories
    |--------------------------------------------------------------------------
    |
    | This value will be used across systems where a repository instance is needed
    */

    'repositories' => [
        \Fintech\Business\Interfaces\ServiceSettingRepository::class => ServiceSettingRepository::class,

        \Fintech\Business\Interfaces\ServiceTypeRepository::class => ServiceTypeRepository::class,

        \Fintech\Business\Interfaces\ServiceRepository::class => ServiceRepository::class,

        \Fintech\Business\Interfaces\ServiceStatRepository::class => ServiceStatRepository::class,

        \Fintech\Business\Interfaces\ServicePackageRepository::class => ServicePackageRepository::class,

        \Fintech\Business\Interfaces\ChargeBreakDownRepository::class => ChargeBreakDownRepository::class,

        \Fintech\Business\Interfaces\ServiceVendorRepository::class => ServiceVendorRepository::class,

        \Fintech\Business\Interfaces\PackageTopChartRepository::class => PackageTopChartRepository::class,

        \Fintech\Business\Interfaces\CurrencyRateRepository::class => CurrencyRateRepository::class,

        \Fintech\Business\Interfaces\ServiceFieldRepository::class => ServiceFieldRepository::class,

        //** Repository Binding Config Point Do not Remove **//
    ],

];
