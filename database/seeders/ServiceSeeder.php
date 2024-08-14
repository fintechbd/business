<?php

namespace Fintech\Business\Seeders;

use Fintech\Business\Facades\Business;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
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
                Business::service()->create($entry);
            }
        }
    }

    /**
     * @return array[]
     */
    private function data(): array
    {
        $vendor_id = config('fintech.business.default_vendor', 1);

        return [
            ['id' => 1, 'service_type_id' => 3, 'service_vendor_id' => $vendor_id, 'service_name' => 'May Bank', 'service_slug' => 'may_bank', 'logo_svg' => null, 'logo_png' => null, 'service_notification' => 'yes', 'service_delay' => 'yes', 'service_stat_policy' => 'yes', 'service_serial' => 1, 'service_data' => ['visible_website' => 'yes', 'visible_android_app' => 'yes', 'visible_ios_app' => 'yes', 'account_name' => null, 'account_number' => null, 'transactional_currency' => 'BDT', 'beneficiary_type_id' => null, 'operator_short_code' => null], 'enabled' => true],
            ['id' => 2, 'service_type_id' => 11, 'service_vendor_id' => $vendor_id, 'service_name' => 'PAy Now', 'service_slug' => 'pay_now', 'logo_svg' => null, 'logo_png' => null, 'service_notification' => 'yes', 'service_delay' => 'yes', 'service_stat_policy' => 'yes', 'service_serial' => 1, 'service_data' => ['visible_website' => 'yes', 'visible_android_app' => 'yes', 'visible_ios_app' => 'yes', 'account_name' => null, 'account_number' => null, 'transactional_currency' => 'BDT', 'beneficiary_type_id' => null, 'operator_short_code' => null], 'enabled' => true],
            ['id' => 3, 'service_type_id' => 12, 'service_vendor_id' => $vendor_id, 'service_name' => 'E-nets', 'service_slug' => 'e_nets', 'logo_svg' => null, 'logo_png' => null, 'service_notification' => 'yes', 'service_delay' => 'yes', 'service_stat_policy' => 'yes', 'service_serial' => 1, 'service_data' => ['visible_website' => 'yes', 'visible_android_app' => 'yes', 'visible_ios_app' => 'yes', 'account_name' => null, 'account_number' => null, 'transactional_currency' => 'BDT', 'beneficiary_type_id' => null, 'operator_short_code' => null], 'enabled' => true],
            ['id' => 4, 'service_type_id' => 13, 'service_vendor_id' => $vendor_id, 'service_name' => 'Wallet to Wallet Transfer', 'service_slug' => 'fund_Rising', 'logo_svg' => null, 'logo_png' => null, 'service_notification' => 'yes', 'service_delay' => 'yes', 'service_stat_policy' => 'yes', 'service_serial' => 1, 'service_data' => ['visible_website' => 'yes', 'visible_android_app' => 'yes', 'visible_ios_app' => 'yes', 'account_name' => null, 'account_number' => null, 'transactional_currency' => 'BDT', 'beneficiary_type_id' => null, 'operator_short_code' => null], 'enabled' => true],
        ];
    }
}
