<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Facades\Business;
use Fintech\Business\Http\Requests\ServiceCurrencyRateRequest;
use Fintech\Business\Http\Resources\ServiceCostResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

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

            //return $this->failed("Currency Convert Rate doesn't exists");
            throw new \InvalidArgumentException("Currency Convert Rate doesn't exists");
        }
    }
}
