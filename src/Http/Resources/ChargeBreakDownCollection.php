<?php

namespace Fintech\Business\Http\Resources;

use Fintech\Core\Supports\Constant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ChargeBreakDownCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($serviceSlap) {

            return [
                'id' => $serviceSlap->getKey(),
                'service_stat_id' => $serviceSlap->serviceStat_id,
                'service_stat_name' => $serviceSlap->serviceStat?->service?->service_name ?? null,
                'service_slug' => $serviceSlap->service_slug,
                'charge_break_down_lower' => $serviceSlap->charge_break_down_lower,
                'charge_break_down_higher' => $serviceSlap->charge_break_down_higher,
                'charge_break_down_charge' => $serviceSlap->charge_break_down_charge,
                'charge_break_down_discount' => $serviceSlap->charge_break_down_discount,
                'charge_break_down_commission' => $serviceSlap->charge_break_down_commission,
                'enabled' => $serviceSlap->enabled,
                'links' => $serviceSlap->links,
                'created_at' => $serviceSlap->created_at,
                'updated_at' => $serviceSlap->updated_at,
            ];
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
