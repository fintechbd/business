<?php

namespace Fintech\Business\Database\Seeders;

use Fintech\Business\Facades\Business;
use Illuminate\Database\Seeder;

class ServiceSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->data();

        foreach (array_chunk($data, 200) as $block) {
            set_time_limit(2100);
            foreach ($block as $entry) {
                Business::serviceSetting()->create($entry);
            }
        }
    }

    private function data()
    {
        return [
            ['id' => '1', 'service_setting_type' => 'service_stat', 'service_setting_name' => 'Lower Limit', 'service_setting_field_name' => 'lower_limit', 'service_setting_type_name' => 'text', 'service_setting_feature' => 'Lower Limit', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '2', 'service_setting_type' => 'service_stat', 'service_setting_name' => 'Higher Limit', 'service_setting_field_name' => 'higher_limit', 'service_setting_type_name' => 'text', 'service_setting_feature' => 'Higher Limit', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '3', 'service_setting_type' => 'service_stat', 'service_setting_name' => 'Local Currency Higher Limit', 'service_setting_field_name' => 'local_currency_higher_limit', 'service_setting_type_name' => 'text', 'service_setting_feature' => 'Local Currency Higher Limit', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '4', 'service_setting_type' => 'service_stat', 'service_setting_name' => 'Charge', 'service_setting_field_name' => 'charge', 'service_setting_type_name' => 'text', 'service_setting_feature' => 'Charge', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '5', 'service_setting_type' => 'service_stat', 'service_setting_name' => 'Discount', 'service_setting_field_name' => 'discount', 'service_setting_type_name' => 'text', 'service_setting_feature' => 'Discount', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '6', 'service_setting_type' => 'service_stat', 'service_setting_name' => 'Commission', 'service_setting_field_name' => 'commission', 'service_setting_type_name' => 'text', 'service_setting_feature' => 'Commission', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '7', 'service_setting_type' => 'service_stat', 'service_setting_name' => 'Charge Refund', 'service_setting_field_name' => 'charge_refund', 'service_setting_type_name' => 'select', 'service_setting_feature' => 'Charge Refund', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '8', 'service_setting_type' => 'service_stat', 'service_setting_name' => 'Discount Refund', 'service_setting_field_name' => 'discount_refund', 'service_setting_type_name' => 'select', 'service_setting_feature' => 'Discount Refund', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '9', 'service_setting_type' => 'service_stat', 'service_setting_name' => 'Commission Refund', 'service_setting_field_name' => 'commission_refund', 'service_setting_type_name' => 'select', 'service_setting_feature' => 'Commission Refund', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '10', 'service_setting_type' => 'service_stat', 'service_setting_name' => 'Cost', 'service_setting_field_name' => 'cost', 'service_setting_type_name' => 'text', 'service_setting_feature' => 'Cost', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '6', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2022-02-17 17:43:05', 'updated_at' => '2022-02-17 17:43:05'],
            ['id' => '11', 'service_setting_type' => 'service', 'service_setting_name' => 'Visible Website', 'service_setting_field_name' => 'visible_website', 'service_setting_type_name' => 'select', 'service_setting_feature' => 'Visible Website', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '12', 'service_setting_type' => 'service', 'service_setting_name' => 'Visible Android APP', 'service_setting_field_name' => 'visible_android_app', 'service_setting_type_name' => 'select', 'service_setting_feature' => 'Visible Android APP', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '13', 'service_setting_type' => 'service', 'service_setting_name' => 'Visible IOS APP', 'service_setting_field_name' => 'visible_ios_app', 'service_setting_type_name' => 'select', 'service_setting_feature' => 'Visible IOS APP', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '14', 'service_setting_type' => 'service', 'service_setting_name' => 'Account Name', 'service_setting_field_name' => 'account_name', 'service_setting_type_name' => 'text', 'service_setting_feature' => 'Account Name', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '15', 'service_setting_type' => 'service', 'service_setting_name' => 'Account Number', 'service_setting_field_name' => 'account_number', 'service_setting_type_name' => 'text', 'service_setting_feature' => 'Account Number', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '16', 'service_setting_type' => 'service', 'service_setting_name' => 'Transactional Currency', 'service_setting_field_name' => 'transactional_currency', 'service_setting_type_name' => 'text', 'service_setting_feature' => 'Transactional Currency', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '17', 'service_setting_type' => 'service', 'service_setting_name' => 'Beneficiary Type ID', 'service_setting_field_name' => 'beneficiary_type_id', 'service_setting_type_name' => 'select', 'service_setting_feature' => 'Beneficiary Type ID', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '18', 'service_setting_type' => 'service', 'service_setting_name' => 'Operator Short Code', 'service_setting_field_name' => 'operator_short_code', 'service_setting_type_name' => 'text', 'service_setting_feature' => 'Operator Short Code', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '1', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2020-08-28 23:48:41', 'updated_at' => '2020-08-28 23:48:41'],
            ['id' => '19', 'service_setting_type' => 'service', 'service_setting_name' => 'Service Feature List', 'service_setting_field_name' => 'service_feature_list', 'service_setting_type_name' => 'textarea', 'service_setting_feature' => 'Service Feature List', 'enabled' => '1', 'deleted_by' => '0', 'created_by' => '6', 'updated_by' => '0', 'deleted_at' => null, 'created_at' => '2021-03-02 15:41:49', 'updated_at' => '2021-03-02 15:41:49'],
        ];
    }
}
