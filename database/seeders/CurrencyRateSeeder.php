<?php

namespace Fintech\Business\Seeders;

use Fintech\Business\Facades\Business;
use Illuminate\Database\Seeder;

class CurrencyRateSeeder extends Seeder
{
    private array $currencyRates = [];

    /**
     * Run the database seeds.
     */
    public function run(...$countries): void
    {
        $this->loadCurrencyRates();

        foreach ($this->data($countries) as $entry) {
            Business::currencyRate()->create($entry);
        }
    }

    private function data($countries)
    {
        $data = [];

        $services = Business::service()->list();

        foreach ($services as $service) {
            foreach ($countries as $sourceCountry) {
                foreach ($countries as $destCountry) {
                    $temp = [
                        'service_id' => $service->id,
                        'source_country_id' => $sourceCountry,
                        'destination_country_id' => $destCountry,
                        'rate' => $this->calculateRate($sourceCountry, $destCountry),
                        'is_default' => ($sourceCountry == $destCountry),
                        'currency_rate_data' => [
                            'input' => $this->currencyRates[$sourceCountry]['code'],
                            'input_partner' => null,
                            'output' => $this->currencyRates[$destCountry]['code'],
                            'output_partner' => null,
                            'rate' => null,
                            'markup' => mt_rand(1, 5) . '%',
                            'usd_to_input' => $this->currencyRates[$sourceCountry]['rate'],
                            'usd_to_output' => $this->currencyRates[$destCountry]['rate'],
                        ],
                    ];
                    $temp['currency_rate_data']['rate'] = $temp['rate'];
                    $temp['currency_rate_data']['markup_amount'] = calculate_flat_percent($temp['rate'], $temp['currency_rate_data']['markup']);
                    $temp['currency_rate_data']['customer_rate'] = (float)$temp['rate'] - calculate_flat_percent($temp['rate'], $temp['currency_rate_data']['markup']);
                    $data[] = $temp;
                }
            }
        }

        return $data;
    }

    private function calculateRate($sourceCountry, $destCountry): float
    {
        if ($sourceCountry == $destCountry) {
            return 1.00000;
        }

        $srcRate = $this->currencyRates[$sourceCountry]['rate'];
        $destRate = $this->currencyRates[$destCountry]['rate'];

        return (string)round(($destRate / $srcRate), 5);

    }

    private function loadCurrencyRates(): void
    {
        $path = file_exists(database_path('seeders/currency_rates.json'))
            ? database_path('seeders' . DIRECTORY_SEPARATOR . 'currency_rates.json')
            : __DIR__ . DIRECTORY_SEPARATOR . 'currency_rates.json';

        $this->currencyRates = json_decode(file_get_contents($path), true);
    }
}
