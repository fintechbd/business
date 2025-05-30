<?php

namespace Fintech\Business\Http\Requests;

use Fintech\Core\Rules\PercentNumber;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceStatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'role_id' => ['array', 'required'],
            'service_id' => ['integer', 'required'],
            'service_slug' => ['string', 'required'],
            'source_country_id' => ['array', 'required', 'master_currency'],
            'source_country_id.*' => ['integer', 'required'],
            'destination_country_id' => ['array', 'required', 'master_currency'],
            'destination_country_id.*' => ['integer', 'required'],
            'service_vendor_id' => ['integer', 'required'],
            'enabled' => ['boolean', 'nullable'],
            'service_stat_data' => ['array', 'required'],
            'service_stat_data.lower_limit' => ['required', 'numeric'],
            'service_stat_data.higher_limit' => ['required', 'numeric'],
            'service_stat_data.local_currency_lower_limit' => ['required', 'numeric'],
            'service_stat_data.local_currency_higher_limit' => ['required', 'numeric'],
            'service_stat_data.charge' => ['string', 'required', new PercentNumber],
            'service_stat_data.discount' => ['string', 'required', new PercentNumber],
            'service_stat_data.commission' => ['string', 'required', new PercentNumber],
            'service_stat_data.cost' => ['string', 'required', new PercentNumber],
            'service_stat_data.interac_charge' => ['string', 'required', new PercentNumber],
            'service_stat_data.charge_refund' => ['string', 'required', 'in:yes,no'],
            'service_stat_data.discount_refund' => ['string', 'required', 'in:yes,no'],
            'service_stat_data.commission_refund' => ['string', 'required', 'in:yes,no'],
        ];

        business()->serviceSetting()->list([
            'service_setting_type' => 'service_stat',
            'enabled' => true,
            'paginate' => false])
            ->each(function ($item) use (&$rules) {
                if (! isset($rules['service_stat_data.'.$item->service_setting_field_name])) {
                    $rules['service_stat_data.'.$item->service_setting_field_name] = $item->service_setting_rule ?? 'nullable';
                }
            });

        return $rules;
    }

    /**
     * Get the validation attributes that apply to the request.
     */
    public function attributes(): array
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            //
        ];
    }

    protected function prepareForValidation()
    {
        $service = business()->service()->find($this->input('service_id'));

        $this->merge(['service_slug' => $service->service_slug]);
    }
}
