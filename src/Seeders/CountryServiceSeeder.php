<?php

namespace Fintech\Business\Seeders;

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
        $services = \Fintech\Business\Facades\Business::service()->list()->pluck('id')->toArray();
        foreach ($services as $service) {
            \Fintech\Business\Facades\Business::service()->update($service, ['countries' => $countries]);
        }
    }
}
