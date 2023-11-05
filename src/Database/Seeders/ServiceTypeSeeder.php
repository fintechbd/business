<?php

namespace Fintech\Business\Database\Seeders;

use Illuminate\Database\Seeder;
use Fintech\Business\Facades\Business;

class ServiceTypeSeeder extends Seeder
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
                Business::serviceType()->create($entry);
            }
        }
    }

    private function data()
    {
        return array(
            array('id' => '1','service_type_parent_id' => NULL,'service_type_name' => 'Fund Deposit','service_type_slug' => 'fund deposit','logo_svg' => null,'logo_png' => null,'service_type_is_parent' => 'yes','service_type_is_description' => 'no','service_type_step' => '1','enabled' => '1','destroyer_id' => '0','creator_id' => '1','editor_id' => 0,'deleted_at' => NULL,'created_at' => date('Y-m-d H:i:s'),'updated_at' => null),
            array('id' => '2','service_type_parent_id' => '1','service_type_name' => 'Cash and Deposit Machine','service_type_slug' => 'cash_and_deposit_machine','service_type_logo' => null,'logo_png' => null,'service_type_is_parent' => 'yes','service_type_is_description' => 'no','service_type_step' => '2','enabled' => '1','destroyer_id' => '0','creator_id' => '1','editor_id' => '0','deleted_at' => NULL,'created_at' => date('Y-m-d H:i:s'),'updated_at' => null),
            array('id' => '3','service_type_parent_id' => '3','service_type_name' => 'Maybank','service_type_slug' => 'maybank','service_type_logo' => null,'service_type_logo_png' => null,'service_type_is_parent' => 'no','service_type_is_description' => 'no','service_type_step' => '3','enabled' => '1','destroyer_id' => '0','creator_id' => '1','editor_id' => '0','deleted_at' => NULL,'created_at' => date('Y-m-d H:i:s'),'updated_at' => null),
            array('id' => '4','service_type_parent_id' => '3','service_type_name' => 'Hong Leong Bank','service_type_slug' => 'hong_long_bank','service_type_logo' => null,'service_type_logo_png' => null,'service_type_is_parent' => 'no','service_type_is_description' => 'no','service_type_step' => '3','enabled' => '1','destroyer_id' => '0','creator_id' => '1','editor_id' => '0','deleted_at' => NULL,'created_at' => date('Y-m-d H:i:s'),'updated_at' => null),
            array('id' => '5','service_type_parent_id' => '3','service_type_name' => 'CIMB Bank','service_type_slug' => 'cimb_bank','service_type_logo' => null,'service_type_logo_png' => null,'service_type_is_parent' => 'no','service_type_is_description' => 'no','service_type_step' => '3','enabled' => '1','destroyer_id' => '0','creator_id' => '1','editor_id' => '0','deleted_at' => NULL,'created_at' => date('Y-m-d H:i:s'),'updated_at' => null),
            array('id' => '6','service_type_parent_id' => '3','service_type_name' => 'RHB Bank','service_type_slug' => 'rhb_bank','service_type_logo' => null,'service_type_logo_png' => null,'service_type_is_parent' => 'no','service_type_is_description' => 'no','service_type_step' => '3','enabled' => '1','destroyer_id' => '0','creator_id' => '1','editor_id' => '0','deleted_at' => NULL,'created_at' => date('Y-m-d H:i:s'),'updated_at' => null),
            array('id' => '7','service_type_parent_id' => '3','service_type_name' => 'Public Bank','service_type_slug' => 'public_bank','service_type_logo' => null,'service_type_logo_png' => null,'service_type_is_parent' => 'no','service_type_is_description' => 'no','service_type_step' => '3','enabled' => '1','destroyer_id' => '0','creator_id' => '1','editor_id' => '0','deleted_at' => NULL,'created_at' => date('Y-m-d H:i:s'),'updated_at' => null),
            array('id' => '8','service_type_parent_id' => '3','service_type_name' => 'MAYBANK (SINGAPORE)','service_type_slug' => 'may_bank_singapore','service_type_logo' => null,'service_type_logo_png' => null,'service_type_is_parent' => 'no','service_type_is_description' => 'no','service_type_step' => '3','enabled' => 'IN-1','destroyer_id' => '0','creator_id' => '1','editor_id' => '0','deleted_at' => NULL,'created_at' => date('Y-m-d H:i:s'),'updated_at' => null),
            array('id' => '9','service_type_parent_id' => '3','service_type_name' => 'UOB Bank','service_type_slug' => 'uob_bank','service_type_logo' => null,'service_type_logo_png' => null,'service_type_is_parent' => 'no','service_type_is_description' => 'no','service_type_step' => '3','enabled' => 'IN-1','destroyer_id' => '0','creator_id' => '1','editor_id' => '0','deleted_at' => NULL,'created_at' => date('Y-m-d H:i:s'),'updated_at' => null),
            array('id' => '10','service_type_parent_id' => '3','service_type_name' => 'DBS BANK LTD','service_type_slug' => 'dbs_bank_ltd','service_type_logo' => null,'service_type_logo_png' => null,'service_type_is_parent' => 'no','service_type_is_description' => 'no','service_type_step' => '3','enabled' => '1','destroyer_id' => '0','creator_id' => '1','editor_id' => '0','deleted_at' => NULL,'created_at' => date('Y-m-d H:i:s'),'updated_at' => null),
            array('id' => '11','service_type_parent_id' => '2','service_type_name' => 'PAYNOW','service_type_slug' => 'pay_now','service_type_logo' => null,'service_type_logo_png' => null,'service_type_is_parent' => 'no','service_type_is_description' => 'no','service_type_step' => '2','enabled' => '1','destroyer_id' => '0','creator_id' => '1','editor_id' => '0','deleted_at' => NULL,'created_at' => date('Y-m-d H:i:s'),'updated_at' => null),
            array('id' => '12','service_type_parent_id' => '2','service_type_name' => 'E-NETS','service_type_slug' => 'e_nets','service_type_logo' => null,'service_type_logo_png' => null,'service_type_is_parent' => 'no','service_type_is_description' => 'no','service_type_step' => '2','enabled' => '1','destroyer_id' => '0','creator_id' => '1','editor_id' => 0,'deleted_at' => NULL,'created_at' => date('Y-m-d H:i:s'),'updated_at' => null),
            array('id' => '13','service_type_parent_id' => null,'service_type_name' => 'Wallet to Wallet','service_type_slug' => 'wallet_to_wallet','service_type_logo' => null,'service_type_logo_png' => null,'service_type_is_parent' => 'no','service_type_is_description' => 'no','service_type_step' => '1','enabled' => '1','destroyer_id' => '0','creator_id' => '1','editor_id' => '0','deleted_at' => NULL,'created_at' => date('Y-m-d H:i:s'),'updated_at' => null),

        );
    }
}
