<?php

namespace Fintech\Business\Http\Resources;

use Fintech\Business\Facades\Business;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @property-read Collection $services
 */
class CountryServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $countryServices = $this->services->pluck('id')->toArray();

        $data = [];

        Business::service()
            ->list(['paginate' => false])
            ->each(function ($service) use (&$data, $countryServices) {
                $data[] = [
                    'id' => $service->getKey(),
                    'name' => $service->name,
                    'enabled' => in_array($service->getKey(), $countryServices),
                ];
            });

        return $data;
    }
}
