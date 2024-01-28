<?php

namespace Fintech\Business\Seeders;

use Fintech\Business\Facades\Business;
use Illuminate\Database\Seeder;

class ServiceVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->data();

        foreach (array_chunk($data, 200) as $block) {
            set_time_limit(2100);
            foreach ($block as $entry) {
                if ($entry['logo_png'] != null) {
                    $image_png = __DIR__.'/../../resources/img/service_vendor_logo_png/'.$entry['logo_png'];
                    $entry['logo_png'] = 'data:image/png;base64,'.base64_encode(file_get_contents($image_png));
                }
                if ($entry['logo_svg'] != null) {
                    $image_svg = __DIR__.'/../../resources/img/service_vendor_logo_svg/'.$entry['logo_svg'];
                    $entry['logo_svg'] = 'data:image/svg+xml;base64,'.base64_encode(file_get_contents($image_svg));
                }
                Business::serviceVendor()->create($entry);
            }
        }
    }

    private function data()
    {
        return [
            [
                'id' => '1',
                'service_vendor_name' => 'MT TECHNOLOGIES LTD',
                'service_vendor_slug' => 'mt_technologies_ltd',
                'service_vendor_data' => [],
                'logo_svg' => 'mt-technology-ltd-logo.svg',
                'logo_png' => 'mt-technology-ltd-logo.png',
                'enabled' => true,
                ],
            ];
    }
}
