<?php

namespace Fintech\Business\Http\Resources;

use Fintech\Core\Supports\Constant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($serviceType) {
            $data = [
                'id' => $serviceType->getKey() ?? null,
                'service_type_parent_id' => $serviceType->service_type_parent_id ?? null,
                'service_type_parent_name' => $serviceType->serviceTypeParent->service_type_name ?? null,
                //'service_type_parent_list' => $serviceType->all_parent_list ?? null,
                'service_type_name' => $serviceType->service_type_name ?? null,
                'service_type_slug' => $serviceType->service_type_slug ?? null,
                'service_type_is_parent' => $serviceType->service_type_is_parent ?? null,
                'service_type_is_description' => $serviceType->service_type_is_description ?? null,
                'service_type_step' => $serviceType->service_type_step ?? null,
                'service_type_data' => $serviceType->service_type_data ?? null,
                'service_type_log_svg' => $serviceType->getMedia('logo_svg') ?? null,
                'service_type_log_png' => $serviceType->getMedia('logo_png') ?? null,
                'enabled' => $serviceType->enabled ?? null,
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
