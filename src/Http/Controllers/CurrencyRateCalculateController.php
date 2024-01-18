<?php

namespace Fintech\Business\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Fintech\Business\Facades\Business;
use Fintech\Business\Http\Requests\ServiceCurrencyRateRequest;
use Fintech\Business\Http\Resources\ServiceCostResource;
use Illuminate\Http\JsonResponse;

class CurrencyRateCalculateController extends Controller
{
    /**
     * @lrd:start
     */
    public function __invoke(ServiceCurrencyRateRequest $request): JsonResponse|ServiceCostResource
    {
        $inputs = $request->all();

        try {
            $roles = config('fintech.auth.customer_roles', [7]);

            $inputs['role_id'] = array_shift($roles);

            $exchangeRate = Business::serviceStat()->cost($inputs);

            return new ServiceCostResource($exchangeRate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }
}
