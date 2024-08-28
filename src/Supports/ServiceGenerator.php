<?php

namespace Fintech\Business\Supports;

use Fintech\Business\Facades\Business;
use Fintech\Core\Facades\Core;

class ServiceGenerator
{
    public array $attributes = [];

    public $parent = null;

    public array $children = [];

    public int $level = 1;

    public $instance = null;

    public $roles = [];

    public $logoSvg;
    public $logoPng;

    public function __construct(array $data, ?int $parentId = null)
    {
        $this->loadParent($parentId);

        $this->loadData($data);

        //get all role except `Super Admin`
        if (Core::packageExists('Auth')) {
            $this->roles = \Fintech\Auth\Facades\Auth::role()->list(['id_not_in' => [1]])->pluck('id')->toArray();
        }
    }

    private function loadData($data): void
    {
        if (!empty($data['children'])) {
            $this->children = $data['children'];
            $data['service_type_is_parent'] = 'yes';
            unset($data['children']);
        }

        $data['enabled'] = $data['enabled'] ?? false;

        if ($data['logo_svg'] && $this->verifyImage($data['logo_svg'], ['image/svg+xml'])) {
            $this->logoSvg = 'data:image/svg+xml;base64,'.base64_encode(file_get_contents($data['logo_svg']));
        }

        if ($data['logo_png'] && $this->verifyImage($data['logo_png'], ['image/png'])) {
            $this->logoPng = 'data:image/png;base64,'.base64_encode(file_get_contents($data['logo_png']));
        }

    }


    private function verifyImage(string $path, array $accepts = null): bool
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
            $this->level = ($parent->service_type_step ?? 0) + 1;
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

    public function execute(): bool
    {
        return true;
    }
}
