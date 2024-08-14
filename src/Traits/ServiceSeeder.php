<?php

namespace Fintech\Business\Traits;

use Fintech\Auth\Facades\Auth;
use Fintech\Business\Facades\Business;

trait ServiceSeeder
{
    public function serviceStat($source_countries = [], $destination_countries = []): array
    {
        $serviceStats = [];
        $roles = Auth::role()->list(['id_not_in_array' => [1]])->pluck('id')->toArray();
        if (!empty($roles) && !empty($source_countries) && !empty($destination_countries)) {
            foreach ($this->service() as $service) {
                $serviceModel = Business::service()->list(['service_slug' => $service['service_slug']])->first();
                $serviceStats[] = [
                    'role_id' => $roles,
                    'service_id' => $serviceModel->getKey(),
                    'service_slug' => $serviceModel->service_slug,
                    'source_country_id' => $source_countries,
                    'destination_country_id' => $destination_countries,
                    'service_vendor_id' => config('fintech.business.default_vendor', 1),
                    'service_stat_data' => [
                        [
                            'lower_limit' => '10.00',
                            'higher_limit' => '5000.00',
                            'local_currency_higher_limit' => '25000.00',
                            'charge' => mt_rand(1, 7) . '%',
                            'discount' => mt_rand(1, 7) . '%',
                            'commission' => mt_rand(1, 7) . '%',
                            'cost' => '0.00',
                            'charge_refund' => 'yes',
                            'discount_refund' => 'yes',
                            'commission_refund' => 'yes',
                        ],
                    ],
                    'enabled' => true,
                ];
            }
        }

        return $serviceStats;

    }
}

