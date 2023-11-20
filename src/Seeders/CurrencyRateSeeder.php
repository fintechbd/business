<?php

namespace Fintech\Business\Seeders;

use Fintech\Business\Facades\Business;
use Illuminate\Database\Seeder;

class CurrencyRateSeeder extends Seeder
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
                Business::currencyRate()->create($entry);
            }
        }
    }

    private function data()
    {
        return [];
    }
}
