<?php

// config for Fintech/Business
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
    'service_setting_model' => \Fintech\Business\Models\ServiceSetting::class,

    /*
    |--------------------------------------------------------------------------
    | ServiceType Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'service_type_model' => \Fintech\Business\Models\ServiceType::class,

    /*
    |--------------------------------------------------------------------------
    | Service Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'service_model' => \Fintech\Business\Models\Service::class,

    /*
    |--------------------------------------------------------------------------
    | ServiceState Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'service_state_model' => \Fintech\Business\Models\ServiceState::class,

    /*
    |--------------------------------------------------------------------------
    | ServicePackage Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'service_package_model' => \Fintech\Business\Models\ServicePackage::class,

    /*
    |--------------------------------------------------------------------------
    | ChargeBreakDown Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'charge_break_down_model' => \Fintech\Business\Models\ChargeBreakDown::class,

    /*
    |--------------------------------------------------------------------------
    | Vendor Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'vendor_model' => \Fintech\Business\Models\Vendor::class,

    /*
    |--------------------------------------------------------------------------
    | PackageTopChart Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'package_top_chart_model' => \Fintech\Business\Models\PackageTopChart::class,

    /*
    |--------------------------------------------------------------------------
    | ServiceVendor Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'service_vendor_model' => \Fintech\Business\Models\ServiceVendor::class,

    //** Model Config Point Do not Remove **//

    /*
    |--------------------------------------------------------------------------
    | Constant
    |--------------------------------------------------------------------------
    |
    | This value will be used across systems where a constant instance is needed
    */

    'service_setting_type' => [
        'service_state' => 'Service State',
        'service' => 'Service',
    ],

    'service_setting_type_field' => [
        'text' => 'TEXT',
        'select' => 'SELECT',
        'textarea' => 'TEXT AREA',
        'date' => 'DATE',
        'radio' => 'RADIO',
        'email' => 'EMAIL',
        'number' => 'NUMBER',
        'tel' => 'TEL',
        'url' => 'URL',
    ],

    /*
    |--------------------------------------------------------------------------
    | Repositories
    |--------------------------------------------------------------------------
    |
    | This value will be used across systems where a repositoy instance is needed
    */

    'repositories' => [
        \Fintech\Business\Interfaces\ServiceSettingRepository::class => \Fintech\Business\Repositories\Eloquent\ServiceSettingRepository::class,

        \Fintech\Business\Interfaces\ServiceTypeRepository::class => \Fintech\Business\Repositories\Eloquent\ServiceTypeRepository::class,

        \Fintech\Business\Interfaces\ServiceRepository::class => \Fintech\Business\Repositories\Eloquent\ServiceRepository::class,

        \Fintech\Business\Interfaces\ServiceStateRepository::class => \Fintech\Business\Repositories\Eloquent\ServiceStateRepository::class,

        \Fintech\Business\Interfaces\ServicePackageRepository::class => \Fintech\Business\Repositories\Eloquent\ServicePackageRepository::class,

        \Fintech\Business\Interfaces\ChargeBreakDownRepository::class => \Fintech\Business\Repositories\Eloquent\ChargeBreakDownRepository::class,

        \Fintech\Business\Interfaces\VendorRepository::class => \Fintech\Business\Repositories\Eloquent\VendorRepository::class,

        \Fintech\Business\Interfaces\PackageTopChartRepository::class => \Fintech\Business\Repositories\Eloquent\PackageTopChartRepository::class,

        \Fintech\Business\Interfaces\ServiceVendorRepository::class => \Fintech\Business\Repositories\Eloquent\ServiceVendorRepository::class,

        //** Repository Binding Config Point Do not Remove **//
    ],

];
