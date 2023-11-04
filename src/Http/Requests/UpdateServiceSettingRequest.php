<?php

namespace Fintech\Business\Http\Requests;

use Fintech\Business\Models\ServiceSetting;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceSettingRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        /** @phpstan-ignore-next-line */
        $service_setting_id = (int) collect(request()->segments())->last(); //id of the resource
        $uniqueRule = 'unique:'.config('fintech.business.service_setting_model', ServiceSetting::class).',service_setting_field_name,'.$service_setting_id.',id,service_setting_type,'.$this->input('service_setting_type').',deleted_at,NULL';

        return [
            'service_setting_type' => ['string', 'required', 'max:255'],
            'service_setting_name' => ['string', 'required', 'max:255'],
            'service_setting_field_name' => ['string', 'required', $uniqueRule],
            'service_setting_type_field' => ['string', 'required'],
            'service_setting_feature' => ['string', 'required', 'max:255'],
            'enabled' => ['boolean', 'nullable', 'min:1'],
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
