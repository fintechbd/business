<?php

namespace Fintech\Business\Http\Resources;

use Fintech\Core\Facades\Core;
use Fintech\Core\Supports\Constant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CurrencyRateCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($currencyRate) {

            $data = [
                'id' => $currencyRate->getKey(),
                'source_country_id' => $currencyRate->source_country_id,
                'source_country_name' => null,
                'destination_country_id' => $currencyRate->destination_country_id,
                'destination_country_name' => null,
                'service_id' => $currencyRate->service_id,
                'service_name' => $currencyRate->service?->service_name ?? null,
                'rate' => $currencyRate->rate,
                'is_default' => $currencyRate->is_default,
                'currency_rate_data' => $currencyRate->currency_rate_data,
                'links' => $currencyRate->links,
                'created_at' => $currencyRate->created_at,
                'updated_at' => $currencyRate->updated_at
            ];

            if (Core::packageExists('MetaData')) {
                $data['source_country_name'] = $currencyRate->sourceCountry?->name ?? null;
                $data['destination_country_name'] = $currencyRate->destinationCountry?->name ?? null;
            }

            return $data;
        })->toArray();
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param Request $request
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
