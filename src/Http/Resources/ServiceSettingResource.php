<?php

namespace Fintech\Business\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $service_setting_type
 * @property string $service_setting_name
 * @property string $service_setting_field_name
 * @property string $service_setting_type_field
 * @property string $service_setting_feature
 * @property string $service_setting_rule
 * @property string $service_setting_value
 * @property bool $enabled
 * @property mixed $links
 * @property mixed $created_at
 * @property mixed $updated_at
 *
 * @method getKey()
 */
class ServiceSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey() ?? null,
            'service_setting_type' => $this->service_setting_type ?? null,
            'service_setting_name' => $this->service_setting_name ?? null,
            'service_setting_field_name' => $this->service_setting_field_name ?? null,
            'service_setting_type_field' => $this->service_setting_type_field ?? null,
            'service_setting_feature' => $this->service_setting_feature ?? null,
            'service_setting_rule' => $this->service_setting_rule ?? null,
            'service_setting_value' => $this->service_setting_value ?? null,
            'enabled' => $this->enabled ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
