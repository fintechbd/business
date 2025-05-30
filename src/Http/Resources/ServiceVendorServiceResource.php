<?php

namespace Fintech\Business\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property-read Collection $services
 */
class ServiceVendorServiceResource extends JsonResource
{
    private $serviceTypes;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->serviceTypes = business()->serviceType()->list();

        $countryServices = $this->services?->pluck('id')->toArray() ?? [];

        $data = [];

        business()->service()
            ->list(['paginate' => false])
            ->each(function ($service) use (&$data, $countryServices) {
                $serviceTypesNames = [];
                $this->getServiceType($service->service_type_id, $serviceTypesNames);
                $data[] = [
                    'id' => $service->getKey(),
                    'name' => $service->service_name ?? null,
                    'name_tree' => array_reverse($serviceTypesNames),
                    'enabled' => in_array($service->getKey(), $countryServices),
                ];
            });

        return $data;
    }

    private function getServiceType($service_type_id = null, array &$serviceTypesNames = []): array
    {
        $serviceType = $this->serviceTypes->firstWhere('id', $service_type_id);

        $serviceTypesNames[] = ucwords(Str::lower($serviceType->service_type_name ?? 'N/A'));

        if ($serviceType->service_type_parent_id == null) {
            return $serviceTypesNames;
        }

        return $this->getServiceType($serviceType->service_type_parent_id, $serviceTypesNames);
    }
}
