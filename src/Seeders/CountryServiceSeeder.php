<?php

namespace Fintech\Business\Seeders;

use Fintech\Business\Facades\Business;
use Fintech\Core\Exceptions\UpdateOperationException;
use Illuminate\Database\Seeder;

class CountryServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws UpdateOperationException
     */
    public function run(): void
    {
        $countries = [1, 19, 39];
        $services = Business::service()->list()->pluck('id')->toArray();
        foreach ($services as $service) {
            Business::service()->update($service, ['countries' => $countries]);
        }
    }
}
