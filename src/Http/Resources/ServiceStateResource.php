<?php

namespace Fintech\Business\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $service_vendor_id
 * @property mixed $serviceVendor
 * @property mixed $service_id
 * @property mixed $service
 * @property mixed $service_slug
 * @property mixed $source_country_id
 * @property mixed $sourceCountry
 * @property mixed $destination_country_id
 * @property mixed $destinationCountry
 * @property mixed $service_state_data
 * @property mixed $enabled
 * @property mixed $links
 * @property mixed $created_at
 * @property mixed $updated_at
 *
 * @method getKey()
 */
class ServiceStateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->getKey() ?? null,
            'service_vendor_id' => $this->service_vendor_id ?? null,
            'service_vendor_name' => isset($this->serviceVendor) ? $this->serviceVendor->service_vendor_name : null,
            'service_id' => $this->service_id ?? null,
            'service_name' => $this->service->service_name ?? null,
            'service_slug' => $this->service_slug ?? null,
            'source_country_id' => $this->source_country_id ?? null,
            'source_country' => $this->sourceCountry->name ?? null,
            'destination_country_id' => $this->destination_country_id ?? null,
            'destination_country' => $this->destinationCountry->name ?? null,
            'service_state_data' => $this->service_state_data ?? null,
            'enabled' => $this->enabled ?? null,
            'links' => $this->links,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        return $data;
    }
}
