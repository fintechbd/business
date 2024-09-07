<?php

namespace Fintech\Business\Seeders;

use Fintech\Business\Facades\Business;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\MetaData\Facades\MetaData;
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
        $countries = MetaData::country()->list(['is_serving' => true])->pluck('id')->toArray();
        $services = Business::service()->list()->pluck('id')->toArray();
        foreach ($services as $service) {
            Business::service()->update($service, ['countries' => $countries]);
        }
    }
}
