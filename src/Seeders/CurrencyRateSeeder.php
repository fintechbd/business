<?php

namespace Fintech\Business\Seeders;

use Fintech\Business\Facades\Business;
use Fintech\Core\Facades\Core;
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
        $data = [];

        if (Core::packageExists('MetaData')) {
            $countries = \MetaData::country()->list(['is_serving' => true]);
            $services = Business::service()->list();
            foreach ($services as $service) {
                foreach ($countries as $sourceCountry) {
                    foreach ($countries as $destCountry) {
                        $temp = [
                            'service_id' => $service->id,
                            'source_country_id' => $sourceCountry->id,
                            'destination_country_id' => $destCountry->id,
                            'rate' => ($sourceCountry->id == $destCountry->id) ? 1 : mt_rand(50, 120).'.'.mt_rand(10, 99),
                            'is_default' => ($sourceCountry->id == $destCountry->id),
                            'currency_rate_data' => [
                                'input' => $sourceCountry->currency,
                                'input_partner' => null,
                                'output' => $destCountry->currency,
                                'output_partner' => null,
                                'rate' => null,
                                'markup' => mt_rand(1, 5).'%',
                            ],
                        ];
                        $temp['currency_rate_data']['rate'] = $temp['rate'];
                        $temp['currency_rate_data']['markup_amount'] = calculate_flat_percent($temp['rate'], $temp['currency_rate_data']['markup']);
                        $temp['currency_rate_data']['customer_rate'] = (float) $temp['rate'] - calculate_flat_percent($temp['rate'], $temp['currency_rate_data']['markup']);

                        $data[] = $temp;
                    }
                }
            }
        }

        return $data;
    }
}
