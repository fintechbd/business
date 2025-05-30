<?php

namespace Fintech\Business\Commands;

use Fintech\Core\Traits\HasCoreSetting;
use Illuminate\Console\Command;

class ServiceJsonFieldFixCommand extends Command
{
    use HasCoreSetting;

    public $signature = 'business:service-json-field-fix';

    public $description = 'Run a casting program to fix json field issue';

    private string $module = 'Business';

    /**
     * @throws \Throwable
     */
    public function handle(): int
    {
        $services = business()->service()->list();

        foreach ($services as $service) {
            $this->task("Fixing <fg=blue>{$service->service_name}</> service casting issue", function () use (&$service) {
                $serviceData = $service->service_data;
                $serviceData['beneficiary_type_id'] = (empty($serviceData['beneficiary_type_id']))
                    ? intval($serviceData['beneficiary_type_id'])
                    : null;
                $service->service_data = $serviceData;
                $service->save();
            });
        }

        return self::SUCCESS;
    }
}
