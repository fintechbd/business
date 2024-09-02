<?php

namespace Fintech\Business\Supports;

use Fintech\Auth\Facades\Auth;
use Fintech\Business\Facades\Business;
use Fintech\Core\Abstracts\BaseModel;
use Fintech\MetaData\Facades\MetaData;
use Illuminate\Support\Str;

class ServiceTypeGenerator
{
    private array $attributes = [];

    private array $serviceAttributes = [];

    private ?BaseModel $parent = null;

    private array $children = [];

    private int $level = 1;

    private ?BaseModel $instance = null;

    private ?BaseModel $serviceInstance = null;

    private array $roles = [];

    private string $logoSvg;

    private string $logoPng;

    private array $srcCountries = [];

    private array $dstCountries = [];

    private bool $hasService = false;

    private array $serviceSettings = [];

    public ?int $vendorId = null;

    private bool $enabled = false;

    /**
     * @throws \Exception
     */
    public function __construct(array $data, ?int $parentId = null)
    {
        if (!empty($data['service_type_parent_id']) && $parentId == null) {
            $parentId = $data['service_type_parent_id'];
            unset($data['service_type_parent_id']);
        }

        if (!empty($parentId)) {
            $this->loadParent($parentId);
        }

        $this->loadData($data);
    }

    /**************************************************************************************/
    private function loadParent($parentId): void
    {
        if ($parent = Business::serviceType()->find($parentId)) {
            $this->parent = $parent;
            $this->level = intval($parent->service_type_step ?? 0) + 1;
        }
    }

    /**
     * @throws \Exception
     */
    private function loadData($data): void
    {
        $servingCountries = MetaData::country()->list(['is_serving' => true])->pluck('id')->toArray();

        if (isset($data['children']) && count($data['children']) > 0) {
            $this->children = $data['children'];
            $data['service_type_is_parent'] = 'yes';
            unset($data['children']);
        }

        if (isset($data['source_country']) && count($data['source_country']) > 0) {
            $this->srcCountries($data['source_country']);
            unset($data['source_country']);
        } else {
            $this->srcCountries($servingCountries);
        }

        if (isset($data['destination_country']) && count($data['destination_country']) > 0) {
            $this->distCountries($data['destination_country']);
            unset($data['destination_country']);
        } else {
            $this->distCountries($servingCountries);
        }

        if (isset($data['roles']) && count($data['roles']) > 0) {
            $this->serviceStatRoles($data['roles']);
            unset($data['roles']);
        } else {
            $this->serviceStatRoles(Auth::role()->list(['id_not_in' => 1])->pluck('id')->toArray());
        }

        if (isset($data['logo_svg'])) {
            $this->logoSvg($data['logo_svg']);
        }

        if (isset($data['logo_png'])) {
            $this->logoPng($data['logo_png']);
        }

        if (isset($data['service_type_is_parent']) && $data['service_type_is_parent'] == 'no') {
            $this->hasService = true;
        }

        if (!empty($data['service_vendor_id'])) {
            $this->vendor($data['service_vendor_id']);
            unset($data['service_vendor_id']);
        } else {
            $this->vendor(config('fintech.business.default_vendor_id', 1));
        }

        $this->enabled = $data['enabled'] ?? false;

        $this->attributes = $data;
    }

    private function verifyImage(string $path, ?array $accepts = null): bool
    {
        if (empty($accepts)) {
            return true;
        }

        $mime = mime_content_type($path);

        if (in_array($mime, $accepts)) {
            return true;
        }

        return false;
    }

    private function createOrUpdateServiceType(): void
    {
        $attributes = [];
        $attributes['service_type_parent_id'] = $this->parent?->getKey() ?? null;
        $attributes['service_type_name'] = $this->attributes['service_type_name'];
        $attributes['service_type_slug'] = $this->attributes['service_type_slug'] ?? Str::slug($this->attributes['service_type_name']);
        $attributes['logo_svg'] = $this->logoSvg;
        $attributes['logo_png'] = $this->logoPng;
        $attributes['service_type_is_parent'] = $this->attributes['service_type_is_parent'] ?? 'no';
        $attributes['service_type_is_description'] = $this->attributes['service_type_is_description'] ?? 'no';
        $attributes['service_type_step'] = $this->level;
        $attributes['enabled'] = $this->enabled;

        if ($instance = Business::serviceType()->list(['service_type_slug' => $attributes['service_type_slug']])->first()) {
            $this->instance = $instance;
        } else {
            $this->instance = Business::serviceType()->create($attributes);
        }
    }

    private function createOrUpdateService(): void
    {
        $attributes = array_merge([
            'service_type_id' => $this->instance->getKey(),
            'service_vendor_id' => $this->vendorId,
            'service_name' => $this->instance->service_type_name,
            'service_slug' => $this->instance->service_type_slug,
            'logo_svg' => $this->logoSvg,
            'logo_png' => $this->logoPng,
            'service_notification' => 'yes',
            'service_delay' => 'yes',
            'service_stat_policy' => 'yes',
            'service_serial' => 1,
            'service_data' => $this->injectDefaultServiceSettings(),
            'enabled' => $this->enabled,
        ], $this->serviceAttributes);

        if ($instance = Business::service()->list(['service_slug' => $attributes['service_slug']])->first()) {
            $this->serviceInstance = $instance;
        } else {
            $this->serviceInstance = Business::service()->create($attributes);
        }

        $this->createOrUpdateServiceStat();
    }

    private function createOrUpdateServiceStat()
    {
        return [
            'role_id' => $this->roles,
            'service_id' => $this->serviceInstance->getKey(),
            'service_slug' => $this->serviceInstance->service_slug,
            'source_country_id' => $this->srcCountries,
            'destination_country_id' => $this->dstCountries,
            'service_vendor_id' => config('fintech.business.default_vendor', 1),
            'service_stat_data' => [
                'lower_limit' => config('fintech.business.service_stat_settings.lower_limit', '10.00'),
                'higher_limit' => config('fintech.business.service_stat_settings.lower_limit', '5000.00'),
                'local_currency_higher_limit' => config('fintech.business.service_stat_settings.local_currency_higher_limit', '25000.00'),
                'charge' => config('fintech.business.service_stat_settings.charge', '1%'),
                'discount' => config('fintech.business.service_stat_settings.discount', '0'),
                'commission' => config('fintech.business.service_stat_settings.commission', '0'),
                'cost' => config('fintech.business.service_stat_settings.cost', '0'),
                'charge_refund' => config('fintech.business.service_stat_settings.charge_refund', 'yes'),
                'discount_refund' => config('fintech.business.service_stat_settings.discount_refund', 'yes'),
                'commission_refund' => config('fintech.business.service_stat_settings.commission_refund', 'yes'),
            ],
            'enabled' => $this->enabled,
        ];
    }

    private function injectDefaultServiceSettings(): array
    {
        $defaultSettings = [
            'visible_website' => 'yes',
            'visible_android_app' => 'yes',
            'visible_ios_app' => 'yes',
            'account_name' => '',
            'account_number' => '',
            'transactional_currency' => '',
            'beneficiary_type_id' => 1,
        ];

        Business::serviceSetting()->list([
            'enabled' => true,
            'service_setting_field_name_not_in' => array_keys($defaultSettings),
            'service_setting_type' => 'service',
        ])->each(function ($item) use (&$defaultSettings) {
            $defaultSettings[$item->service_setting_field_name] = ($item->service_setting_type_field == 'text') ? '' : null;
        });

        return array_merge($defaultSettings, $this->serviceSettings);
    }

    /**************************************************************************************/

    public function srcCountries(array $countries): static
    {
        $countries = array_filter(array_unique($countries), 'ctype_digit');

        $this->srcCountries = $countries;

        return $this;
    }

    public function distCountries(array $countries): static
    {
        $countries = array_filter(array_unique($countries), 'ctype_digit');

        $this->dstCountries = $countries;

        return $this;
    }

    public function serviceStatRoles(array $roles): static
    {
        $roles = array_filter(array_unique($roles), 'ctype_digit');

        if ($index = array_search(1, $roles)) {
            unset($roles[$index]);
        }

        $this->roles = array_map('intval', $roles);

        return $this;

    }

    public function logoSvg(string $path): static
    {
        if (file_exists($path) && is_readable($path)) {
            if ($this->verifyImage($path, ['image/svg+xml'])) {
                $this->logoSvg = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($path));
            } else {
                throw new \Exception('File is a has invalid mime format');
            }
        } else {
            throw new \Exception("Invalid Logo SVG Path[{$path}]");
        }

        return $this;
    }

    public function logoPng(string $path): static
    {
        if (file_exists($path) && is_readable($path)) {
            if ($this->verifyImage($path, ['image/png'])) {
                $this->logoPng = 'data:image/png;base64,' . base64_encode(file_get_contents($path));
            } else {
                throw new \Exception('File is a has invalid mime format');
            }
        } else {
            throw new \Exception("Invalid Logo PNG Path[{$path}]");
        }

        return $this;
    }

    public function service(array $attributes): static
    {
        $this->serviceAttributes = $attributes;

        return $this;
    }

    public function serviceSettings(array $settings): static
    {
        $this->serviceSettings = array_merge($this->serviceSettings, $settings);

        return $this;
    }

    public function enabled(): static
    {
        $this->enabled = true;

        return $this;
    }

    public function hasService(): static
    {
        $this->hasService = true;

        return $this;
    }

    public function vendor($vendor): static
    {
        if ($vendor instanceof BaseModel) {
            $this->vendorId = $vendor->getKey();
        } else {
            $this->vendorId = $vendor;
        }

        return $this;
    }

    public function execute(): bool
    {
        try {

            $this->createOrUpdateServiceType();

            if ($this->hasService) {
                $this->createOrUpdateService();
            }

            foreach ($this->children as $child) {
                (new static($child))->execute();
            }

            return true;
        } catch (\Exception $exception) {
            logger()->error($exception);
            return false;
        }
    }
}
