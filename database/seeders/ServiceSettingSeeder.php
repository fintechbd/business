<?php

namespace Fintech\Business\Seeders;

use Fintech\Business\Facades\Business;
use Illuminate\Database\Seeder;

class ServiceSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->data() as $entry) {
            Business::serviceSetting()->create($entry);
        }
    }

    /**
     * @return array[]
     */
    private function data(): array
    {
        return [
            ['service_setting_type' => 'service_stat', 'service_setting_name' => 'Lower Limit', 'service_setting_field_name' => 'lower_limit', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'Lower Limit', 'enabled' => true],
            ['service_setting_type' => 'service_stat', 'service_setting_name' => 'Higher Limit', 'service_setting_field_name' => 'higher_limit', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'Higher Limit', 'enabled' => true],
            ['service_setting_type' => 'service_stat', 'service_setting_name' => 'Local Currency Higher Limit', 'service_setting_field_name' => 'local_currency_higher_limit', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'Local Currency Higher Limit', 'enabled' => true],
            ['service_setting_type' => 'service_stat', 'service_setting_name' => 'Charge', 'service_setting_field_name' => 'charge', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'Charge', 'enabled' => true],
            ['service_setting_type' => 'service_stat', 'service_setting_name' => 'Discount', 'service_setting_field_name' => 'discount', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'Discount', 'enabled' => true],
            ['service_setting_type' => 'service_stat', 'service_setting_name' => 'Commission', 'service_setting_field_name' => 'commission', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'Commission', 'enabled' => true],
            ['service_setting_type' => 'service_stat', 'service_setting_name' => 'Charge Refund', 'service_setting_field_name' => 'charge_refund', 'service_setting_type_field' => 'select', 'service_setting_feature' => 'Charge Refund', 'enabled' => true],
            ['service_setting_type' => 'service_stat', 'service_setting_name' => 'Discount Refund', 'service_setting_field_name' => 'discount_refund', 'service_setting_type_field' => 'select', 'service_setting_feature' => 'Discount Refund', 'enabled' => true],
            ['service_setting_type' => 'service_stat', 'service_setting_name' => 'Commission Refund', 'service_setting_field_name' => 'commission_refund', 'service_setting_type_field' => 'select', 'service_setting_feature' => 'Commission Refund', 'enabled' => true],
            ['service_setting_type' => 'service_stat', 'service_setting_name' => 'Cost', 'service_setting_field_name' => 'cost', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'Cost', 'enabled' => true],
            ['service_setting_type' => 'service_stat', 'service_setting_name' => 'SSLVR Unique Auth Key', 'service_setting_field_name' => 'utility_auth_key', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'SSLVR Unique Auth Key', 'enabled' => true],
            ['service_setting_type' => 'service_stat', 'service_setting_name' => 'SSLVR Unique Secret Key', 'service_setting_field_name' => 'utility_secret_key', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'SSLVR Unique Secret Key', 'enabled' => true],
            ['service_setting_type' => 'service', 'service_setting_name' => 'Visible Website', 'service_setting_field_name' => 'visible_website', 'service_setting_type_field' => 'select', 'service_setting_feature' => 'Visible Website', 'enabled' => true],
            ['service_setting_type' => 'service', 'service_setting_name' => 'Visible Android APP', 'service_setting_field_name' => 'visible_android_app', 'service_setting_type_field' => 'select', 'service_setting_feature' => 'Visible Android APP', 'enabled' => true],
            ['service_setting_type' => 'service', 'service_setting_name' => 'Visible IOS APP', 'service_setting_field_name' => 'visible_ios_app', 'service_setting_type_field' => 'select', 'service_setting_feature' => 'Visible IOS APP', 'enabled' => true],
            ['service_setting_type' => 'service', 'service_setting_name' => 'Account Name', 'service_setting_field_name' => 'account_name', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'Account Name', 'enabled' => true],
            ['service_setting_type' => 'service', 'service_setting_name' => 'Account Number', 'service_setting_field_name' => 'account_number', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'Account Number', 'enabled' => true],
            ['service_setting_type' => 'service', 'service_setting_name' => 'Transactional Currency', 'service_setting_field_name' => 'transactional_currency', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'Transactional Currency', 'enabled' => true],
            ['service_setting_type' => 'service', 'service_setting_name' => 'Beneficiary Type ID', 'service_setting_field_name' => 'beneficiary_type_id', 'service_setting_type_field' => 'select', 'service_setting_feature' => 'Beneficiary Type ID', 'enabled' => true],
            ['service_setting_type' => 'service', 'service_setting_name' => 'Operator Short Code', 'service_setting_field_name' => 'operator_short_code', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'Operator Short Code', 'enabled' => true],
            ['service_setting_type' => 'service', 'service_setting_name' => 'Service Feature List', 'service_setting_field_name' => 'service_feature_list', 'service_setting_type_field' => 'textarea', 'service_setting_feature' => 'Service Feature List', 'enabled' => true],
            ['service_setting_type' => 'service', 'service_setting_name' => 'Amount Range', 'service_setting_field_name' => 'amount_range', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'Amount Range', 'enabled' => true],
            ['service_setting_type' => 'service', 'service_setting_name' => 'Routing Number', 'service_setting_field_name' => 'routing_number', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'Routing Number', 'enabled' => true],
            ['service_setting_type' => 'service', 'service_setting_name' => 'Interac', 'service_setting_field_name' => 'interac', 'service_setting_type_field' => 'text', 'service_setting_feature' => 'Interac', 'enabled' => true],
        ];
    }
}
