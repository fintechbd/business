<?php

namespace Fintech\Business\Seeders;

use Fintech\Core\Exceptions\UpdateOperationException;
use Illuminate\Database\Seeder;

class RoleServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws UpdateOperationException
     */
    public function run(): void
    {
        $roles = [1, 3, 7];
        $services = \Fintech\Business\Facades\Business::service()->list()->pluck('id')->toArray();
        foreach ($services as $service) {
            \Fintech\Business\Facades\Business::service()->update($service, ['roles' => $roles]);
        }
    }
}
