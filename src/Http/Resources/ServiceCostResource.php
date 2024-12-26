<?php

namespace Fintech\Business\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function currency;

/**
 * @property-read float $rate
 * @property-read string $base_currency
 * @property-read float $input
 * @property-read string $input_unit
 * @property-read float $output
 * @property-read string $output_unit
 * @property-read float $amount
 * @property-read float $converted
 * @property-read float $charge_amount
 * @property-read float $discount_amount
 * @property-read float $commission_amount
 * @property-read float $total_amount
 */
class ServiceCostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource = (object) $this->resource;

        return [
            'rate' => (string) ($this->rate ?? 1),

            'input' => $this->input,
            'input_unit' => $this->input_unit,

            'output' => $this->output,
            'output_unit' => $this->output_unit,

            'amount' => (string) currency($this->amount, $this->input)->float(),
            'amount_formatted' => (string) currency($this->amount, $this->input),

            'converted' => (string) currency($this->converted, $this->output)->float(),
            'converted_formatted' => (string) currency($this->converted, $this->output),

            'charge_amount' => (string) currency($this->charge_amount, $this->base_currency)->float(),
            'charge_amount_formatted' => (string) currency($this->charge_amount, $this->base_currency),

            'interac_charge' => (string) currency($this->charge_amount, $this->base_currency)->float(),
            'interac_charge_formatted' => (string) currency($this->charge_amount, $this->base_currency),

            'discount_amount' => (string) currency($this->discount_amount, $this->base_currency)->float(),
            'discount_amount_formatted' => (string) currency($this->discount_amount, $this->base_currency),

            'commission_amount' => (string) currency($this->commission_amount, $this->base_currency)->float(),
            'commission_amount_formatted' => (string) currency($this->commission_amount, $this->base_currency),

            'total_amount' => (string) currency($this->total_amount, $this->base_currency)->float(),
            'total_amount_formatted' => (string) currency($this->total_amount, $this->base_currency),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'options' => [],
            'meta' => [
                'charge' => $this->charge,
                'discount' => $this->discount,
                'commission' => $this->commission,
                'charge_break_down_data' => [
                    'id' => $this->charge_break_down_id ?? null,
                    'lower_limit' => null,
                    'higher_limit' => null,
                ],
                'service_stat_id' => $this->service_stat_id ?? null,
                'vendor_info' => $this->resource?->vendor_info ?? (object) [],
                'offers' => $this->resource?->offers ?? (object) [],
            ],
            'query' => $request->all(),
        ];
    }
}
