<?php

namespace Fintech\Business\Http\Resources;

use Fintech\Core\Supports\Constant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceSettingCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($serviceSetting) {
            $data = [
                'id' => $serviceSetting->getKey() ?? null,
                'service_setting_type' => $serviceSetting->service_setting_type ?? null,
                'service_setting_name' => $serviceSetting->service_setting_name ?? null,
                'service_setting_field_name' => $serviceSetting->service_setting_field_name ?? null,
                'service_setting_type_field' => $serviceSetting->service_setting_type_field ?? null,
                'service_setting_feature' => $serviceSetting->service_setting_feature ?? null,
                'enabled' => $serviceSetting->enabled ?? null,
                'links' => $serviceSetting->links,
                'created_at' => $serviceSetting->created_at,
                'updated_at' => $serviceSetting->updated_at,
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
