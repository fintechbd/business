<?php

namespace Fintech\Business\Commands;

use Fintech\Core\Traits\HasCoreSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    use HasCoreSetting;

    public $signature = 'business:install';

    public $description = 'Configure the system for the `fintech/business` module';

    private string $module = 'Business';

    private array $serviceSettings = [
        [
            'service_setting_type' => 'service_stat',
            'service_setting_name' => 'Lower Limit',
            'service_setting_field_name' => 'lower_limit',
            'service_setting_type_field' => 'number',
            'service_setting_feature' => 'Lower Limit',
            'service_setting_rule' => 'nullable|integer|min:0|max:999999999',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service_stat',
            'service_setting_name' => 'Higher Limit',
            'service_setting_field_name' => 'higher_limit',
            'service_setting_type_field' => 'number',
            'service_setting_feature' => 'Higher Limit',
            'service_setting_rule' => 'nullable|integer|min:0|max:999999999',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service_stat',
            'service_setting_name' => 'Local Currency Lower Limit',
            'service_setting_field_name' => 'local_currency_lower_limit',
            'service_setting_type_field' => 'number',
            'service_setting_feature' => 'Local Currency Higher Limit',
            'service_setting_rule' => 'nullable|integer|min:0|max:999999999',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service_stat',
            'service_setting_name' => 'Local Currency Higher Limit',
            'service_setting_field_name' => 'local_currency_higher_limit',
            'service_setting_type_field' => 'number',
            'service_setting_feature' => 'Local Currency Higher Limit',
            'service_setting_rule' => 'nullable|integer|min:0|max:999999999',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service_stat',
            'service_setting_name' => 'Charge',
            'service_setting_field_name' => 'charge',
            'service_setting_type_field' => 'text',
            'service_setting_feature' => 'Charge',
            'service_setting_rule' => 'nullable|string|min:0|max:255',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service_stat',
            'service_setting_name' => 'Interac-E-Transfer Charge',
            'service_setting_field_name' => 'interac_charge',
            'service_setting_type_field' => 'text',
            'service_setting_feature' => 'Interac-E-Transfer Charge',
            'service_setting_rule' => 'nullable|string|min:0|max:255',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service_stat',
            'service_setting_name' => 'Discount',
            'service_setting_field_name' => 'discount',
            'service_setting_type_field' => 'text',
            'service_setting_feature' => 'Discount',
            'service_setting_rule' => 'nullable|string|min:0|max:255',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service_stat',
            'service_setting_name' => 'Commission',
            'service_setting_field_name' => 'commission',
            'service_setting_type_field' => 'text',
            'service_setting_feature' => 'Commission',
            'service_setting_rule' => 'nullable|string|min:0|max:255',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service_stat',
            'service_setting_name' => 'Cost',
            'service_setting_field_name' => 'cost',
            'service_setting_type_field' => 'text',
            'service_setting_feature' => 'Cost',
            'service_setting_rule' => 'nullable|string|min:0|max:255',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service_stat',
            'service_setting_name' => 'Charge Refund',
            'service_setting_field_name' => 'charge_refund',
            'service_setting_type_field' => 'select',
            'service_setting_feature' => 'Charge Refund',
            'service_setting_rule' => 'nullable|string|in:yes,no',
            'service_setting_value' => 'no',
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service_stat',
            'service_setting_name' => 'Discount Refund',
            'service_setting_field_name' => 'discount_refund',
            'service_setting_type_field' => 'select',
            'service_setting_feature' => 'Discount Refund',
            'service_setting_rule' => 'nullable|string|in:yes,no',
            'service_setting_value' => 'no',
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service_stat',
            'service_setting_name' => 'Commission Refund',
            'service_setting_field_name' => 'commission_refund',
            'service_setting_type_field' => 'select',
            'service_setting_feature' => 'Commission Refund',
            'service_setting_rule' => 'nullable|string|in:yes,no',
            'service_setting_value' => 'no',
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service',
            'service_setting_name' => 'Visible Website',
            'service_setting_field_name' => 'visible_website',
            'service_setting_type_field' => 'select',
            'service_setting_feature' => 'Visible Website',
            'service_setting_rule' => 'nullable|string|in:yes,no',
            'service_setting_value' => 'no',
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service',
            'service_setting_name' => 'Visible Android APP',
            'service_setting_field_name' => 'visible_android_app',
            'service_setting_type_field' => 'select',
            'service_setting_feature' => 'Visible Android APP',
            'service_setting_rule' => 'nullable|string|in:yes,no',
            'service_setting_value' => 'no',
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service',
            'service_setting_name' => 'Visible IOS APP',
            'service_setting_field_name' => 'visible_ios_app',
            'service_setting_type_field' => 'select',
            'service_setting_feature' => 'Visible IOS APP',
            'service_setting_rule' => 'nullable|string|in:yes,no',
            'service_setting_value' => 'no',
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service',
            'service_setting_name' => 'Account Name',
            'service_setting_field_name' => 'account_name',
            'service_setting_type_field' => 'text',
            'service_setting_feature' => 'Account Name',
            'service_setting_rule' => 'nullable|string|min:0|max:255',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service',
            'service_setting_name' => 'Account Number',
            'service_setting_field_name' => 'account_number',
            'service_setting_type_field' => 'text',
            'service_setting_feature' => 'Account Number',
            'service_setting_rule' => 'nullable|string|min:0|max:255',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service',
            'service_setting_name' => 'Transactional Currency',
            'service_setting_field_name' => 'transactional_currency',
            'service_setting_type_field' => 'text',
            'service_setting_feature' => 'Transactional Currency',
            'service_setting_rule' => 'nullable|string|size:3',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service',
            'service_setting_name' => 'Beneficiary Type ID',
            'service_setting_field_name' => 'beneficiary_type_id',
            'service_setting_type_field' => 'select',
            'service_setting_feature' => 'Beneficiary Type ID',
            'service_setting_rule' => 'integer|nullable|min:1',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service',
            'service_setting_name' => 'Operator Short Code',
            'service_setting_field_name' => 'operator_short_code',
            'service_setting_type_field' => 'text',
            'service_setting_feature' => 'Operator Short Code',
            'service_setting_rule' => 'nullable|string|min:0|max:255',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service',
            'service_setting_name' => 'Service Feature List',
            'service_setting_field_name' => 'service_feature_list',
            'service_setting_type_field' => 'textarea',
            'service_setting_feature' => 'Service Feature List',
            'service_setting_rule' => 'nullable|string|min:0|max:255',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service',
            'service_setting_name' => 'Amount Range',
            'service_setting_field_name' => 'amount_range',
            'service_setting_type_field' => 'text',
            'service_setting_feature' => 'Amount Range',
            'service_setting_rule' => 'nullable|string|min:0|max:255',
            'service_setting_value' => null,
            'enabled' => true,
        ],
        [
            'service_setting_type' => 'service',
            'service_setting_name' => 'Routing Code',
            'service_setting_field_name' => 'routing_code',
            'service_setting_type_field' => 'text',
            'service_setting_feature' => 'Routing Code',
            'service_setting_rule' => 'nullable|string|min:0|max:255',
            'service_setting_value' => null,
            'enabled' => true,
        ],
    ];

    public function handle(): int
    {
        $this->infoMessage('Module Installation', 'RUNNING');

        $this->task('Module Installation', function () {

            $this->addServiceSettings();

            $this->addDefaultServiceVendor();

            $this->enableServingCountries();

        });

        return self::SUCCESS;
    }

    private function addServiceSettings(): void
    {
        $this->task('Populating service settings', function () {
            foreach ($this->serviceSettings as $serviceSetting) {
                business()->serviceSetting()->create($serviceSetting);
            }
        });
    }

    private function addDefaultServiceVendor(): void
    {
        $this->task('Creating service default vendor', function () {
            $vendor = [
                'id' => config('fintech.business.default_vendor', 1),
                'service_vendor_name' => ucwords(config('fintech.business.default_vendor_name')),
                'service_vendor_slug' => Str::slug(config('fintech.business.default_vendor_name'), '_'),
                'service_vendor_data' => [],
                'enabled' => true,
            ];

            $image_png = __DIR__.'/../../resources/img/service_vendor_logo_png/mt-technology-ltd-logo.png';
            $vendor['logo_png'] = 'data:image/png;base64,'.base64_encode(file_get_contents($image_png));

            $image_svg = __DIR__.'/../../resources/img/service_vendor_logo_svg/mt-technology-ltd-logo.svg';
            $vendor['logo_svg'] = 'data:image/svg+xml;base64,'.base64_encode(file_get_contents($image_svg));

            business()->serviceVendor()->create($vendor);
        });
    }

    private function enableServingCountries() {}
}
