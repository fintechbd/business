<?php

namespace Fintech\Business\Http\Resources;

use Fintech\Core\Supports\Constant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

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
class ServiceStateCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($serviceState) {
            $data = [
                'id' => $serviceState->getKey() ?? null,
                'service_vendor_id' => $serviceState->service_vendor_id ?? null,
                'service_vendor_name' => isset($serviceState->serviceVendor) ? $serviceState->serviceVendor->service_vendor_name : null,
                'service_id' => $serviceState->service_id ?? null,
                'service_name' => $serviceState->service->service_name ?? null,
                'service_slug' => $serviceState->service_slug ?? null,
                'source_country_id' => $serviceState->source_country_id ?? null,
                'source_country' => $serviceState->sourceCountry->name ?? null,
                'destination_country_id' => $serviceState->destination_country_id ?? null,
                'destination_country' => $serviceState->destinationCountry->name ?? null,
                'service_state_data' => $serviceState->service_state_data ?? null,
                'enabled' => $serviceState->enabled ?? null,
                'links' => $serviceState->links,
                'created_at' => $serviceState->created_at,
                'updated_at' => $serviceState->updated_at,
            ];

            return $data;
        })->toArray();
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'options' => [
                'dir' => Constant::SORT_DIRECTIONS,
                'per_page' => Constant::PAGINATE_LENGTHS,
                'sort' => ['id', 'name', 'created_at', 'updated_at'],
            ],
            'query' => $request->all(),
        ];
    }
}
