<?php

namespace Fintech\Business\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceVendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->getKey() ?? null,
            'service_vendor_name' => isset($this->serviceVendor) ? $this->serviceVendor->service_vendor_name : null,
            'service_vendor_slug' => $this->service_vendor_slug ?? null,
            'service_vendor_data' => $this->service_vendor_data ?? null,
            'service_vendor_logo_svg' => $this->getFirstMediaUrl('logo_svg') ?? null,
            'service_vendor_logo_png' => $this->getFirstMediaUrl('logo_png') ?? null,
            'enabled' => $this->enabled ?? null,
            'links' => $this->links,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
