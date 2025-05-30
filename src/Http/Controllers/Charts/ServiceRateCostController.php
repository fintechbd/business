<?php

namespace Fintech\Business\Http\Controllers\Charts;

use Fintech\Business\Http\Resources\Charts\ServiceRateCostCollection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ServiceRateCostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): ServiceRateCostCollection
    {
        $rates = collect([
            ['service_type' => 'Bank Transfer', 'currency' => 'BDT', 'currency_rate' => '85.4', 'charge' => '2.50'],
            ['service_type' => 'Cash Pickup', 'currency' => 'BDT', 'currency_rate' => '85.7', 'charge' => '2.40'],
            ['service_type' => 'Wallet', 'currency' => 'BDT', 'currency_rate' => '86.00', 'charge' => '2.60'],
            ['service_type' => 'Bill Payment', 'currency' => 'BDT', 'currency_rate' => '86.4', 'charge' => '2.70'],
        ]);

        return new ServiceRateCostCollection($rates);
    }
}
