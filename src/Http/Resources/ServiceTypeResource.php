<?php

namespace Fintech\Business\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property integer $service_type_parent_id
 * @property mixed $serviceTypeParent
 * @property string $service_type_name
 * @property string $service_type_slug
 * @property string $service_type_is_parent
 * @property string $service_type_is_description
 * @property integer $service_type_step
 * @property array $service_type_data
 * @property boolean $enabled
 * @property mixed $all_parent_list
 * @method getKey()
 */
class ServiceTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
          'id' => $this->getKey() ?? null,
          'service_type_parent_id' => $this->service_type_parent_id ?? null,
          'service_type_parent_name' => $this->serviceTypeParent->service_type_name ?? null,
          'service_type_parent_list' => $this->all_parent_list ?? null,
          'service_type_name' => $this->service_type_name ?? null,
          'service_type_slug' => $this->service_type_slug ?? null,
          'service_type_is_parent' => $this->service_type_is_parent ?? null,
          'service_type_is_description' => $this->service_type_is_description ?? null,
          'service_type_step' => $this->service_type_step ?? null,
          'service_type_data' => $this->service_type_data ?? null,
          'service_type_log_svg' => $this->getFirstMediaUrl('logo_svg') ?? null,
          'service_type_log_png' => $this->getFirstMediaUrl('logo_png') ?? null,
          'enabled' => $this->enabled ?? null,
        ];
        return $data;
    }
}
