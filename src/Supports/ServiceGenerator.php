<?php

namespace Fintech\Business\Supports;

use Fintech\Business\Facades\Business;
use Fintech\Core\Abstracts\BaseModel;
use Illuminate\Support\Str;

class ServiceGenerator
{
    public array $attributes = [];

    /**
     * @var null|BaseModel
     */
    public $parent = null;

    public array $children = [];

    public int $level = 1;

    /**
     * @var null|BaseModel
     */
    public $instance = null;

    public array $roles = [];

    public string $logoSvg;

    public string $logoPng;

    public array $srcCountries = [];

    public array $dstCountries = [];

    public function __construct(array $data, ?int $parentId = null)
    {
        if (! empty($data['service_type_parent_id'])) {
            $parentId = $data['service_type_parent_id'];
            unset($data['service_type_parent_id']);
        }

        $this->loadParent($parentId);

        $this->loadData($data);
    }

    private function loadData($data): void
    {
        if (isset($data['children']) && count($data['children']) > 0) {
            $this->children = $data['children'];
            $data['service_type_is_parent'] = 'yes';
            unset($data['children']);
        }

        if (isset($data['source_country']) && count($data['source_country']) > 0) {
            $this->sourceCountries($data['source_country']);
            unset($data['source_country']);
        }

        if (isset($data['destination_country']) && count($data['destination_country']) > 0) {
            $this->destinationCountries($data['destination_country']);
            unset($data['destination_country']);
        }

        if (isset($data['roles']) && count($data['roles']) > 0) {
            $this->roles($data['roles']);
            unset($data['roles']);
        }

        if ($data['logo_svg'] && $this->verifyImage($data['logo_svg'], ['image/svg+xml'])) {
            $this->logoSvg = 'data:image/svg+xml;base64,'.base64_encode(file_get_contents($data['logo_svg']));
        }

        if ($data['logo_png'] && $this->verifyImage($data['logo_png'], ['image/png'])) {
            $this->logoPng = 'data:image/png;base64,'.base64_encode(file_get_contents($data['logo_png']));
        }

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

    private function loadParent($parentId): void
    {
        if ($parent = Business::serviceType()->find($parentId)) {
            $this->parent = $parent;
            $this->level = intval($parent->service_type_step ?? 0) + 1;
        }
    }

    private function setupService()
    {
        //        [
        //            'service_type_id' => Business::serviceType()->list(['service_type_slug' => 'bank_transfer'])->first()->id,
        //            'service_vendor_id' => $vendor_id,
        //            'service_name' => 'Bank Transfer',
        //            'service_slug' => 'bank_transfer',
        //            'logo_svg' => 'data:image/svg+xml;base64,'.base64_encode(file_get_contents($image_svg.'bank_transfer.svg')),
        //            'logo_png' => 'data:image/png;base64,'.base64_encode(file_get_contents($image_png.'bank_transfer.png')),
        //            'service_notification' => 'yes',
        //            'service_delay' => 'yes',
        //            'service_stat_policy' => 'yes',
        //            'service_serial' => 1,
        //            'service_data' => [
        //                'visible_website' => 'yes',
        //                'visible_android_app' => 'yes',
        //                'visible_ios_app' => 'yes',
        //                'account_name' => '',
        //                'account_number' => '',
        //                'transactional_currency' => '',
        //                'beneficiary_type_id' => 1,
        //                'operator_short_code' => null
        //            ],
        //            'enabled' => true
        //        ]
    }

    private function setupServiceStat()
    {
        //        [
        //            'role_id' => $roles,
        //            'service_id' => $service->getKey(),
        //            'service_slug' => $service->service_slug,
        //            'source_country_id' => $source_countries,
        //            'destination_country_id' => [19, 39, 101, 132, 133, 167, 192, 231],
        //            'service_vendor_id' => config('fintech.business.default_vendor', 1),
        //            'service_stat_data' => [
        //                [
        //                    'lower_limit' => '10.00',
        //                    'higher_limit' => '5000.00',
        //                    'local_currency_higher_limit' => '25000.00',
        //                    'charge' => mt_rand(1, 7).'%',
        //                    'discount' => mt_rand(1, 7).'%',
        //                    'commission' => '0',
        //                    'cost' => '0.00',
        //                    'charge_refund' => 'yes',
        //                    'discount_refund' => 'yes',
        //                    'commission_refund' => 'yes',
        //                ],
        //            ],
        //            'enabled' => true,
        //        ]
    }

    private function setupServiceType()
    {
        $attributes = [];
        $attributes['service_type_parent_id'] = $this->parent->getKey();
        $attributes['service_type_name'] = $this->attributes['service_type_name'];
        $attributes['service_type_slug'] = $this->attributes['service_type_slug'] ?? Str::slug($this->attributes['service_type_name']);
        $attributes['logo_svg'] = $this->logoSvg;
        $attributes['logo_png'] = $this->logoPng;
        $attributes['service_type_is_parent'] = $this->attributes['service_type_is_parent'] ?? 'no';
        $attributes['service_type_is_description'] = $this->attributes['service_type_is_description'] ?? 'no';
        $attributes['service_type_step'] = $this->level;
        $attributes['enabled'] = $this->attributes['enabled'] ?? false;

        if ($instance = Business::serviceType()->list(['service_type_slug' => $attributes['service_type_slug']])->first()) {
            $this->instance = $instance;
        } else {
            $this->instance = Business::serviceType()->create($attributes);
        }
    }

    public function logoSvg(string $path): self
    {
        if (file_exists($path) && is_readable($path)) {
            if ($this->verifyImage($path, ['image/svg+xml'])) {
                $this->logoSvg = 'data:image/svg+xml;base64,'.base64_encode(file_get_contents($path));
            } else {
                throw new \Exception('File is a has invalid mime format');
            }
        } else {
            throw new \Exception("Invalid Logo SVG Path[{$path}]");
        }

        return $this;
    }

    public function logoPng(string $path): self
    {
        if (file_exists($path) && is_readable($path)) {
            if ($this->verifyImage($path, ['image/png'])) {
                $this->logoPng = 'data:image/png;base64,'.base64_encode(file_get_contents($path));
            } else {
                throw new \Exception('File is a has invalid mime format');
            }
        } else {
            throw new \Exception("Invalid Logo PNG Path[{$path}]");
        }

        return $this;
    }

    public function sourceCountries(array $countries): self
    {
        $countries = array_filter(array_unique($countries), 'ctype_digit');

        $this->srcCountries = $countries;

        return $this;
    }

    public function destinationCountries(array $countries): self
    {
        $countries = array_filter(array_unique($countries), 'ctype_digit');

        $this->dstCountries = $countries;

        return $this;
    }

    public function roles(array $roles): self
    {
        $roles = array_filter(array_unique($roles), 'ctype_digit');

        if ($index = array_search(1, $roles)) {
            unset($roles[$index]);
        }

        $this->roles = $roles;

        return $this;

    }

    public function execute(): bool
    {
        $this->setupServiceType();

        return true;
    }
}
