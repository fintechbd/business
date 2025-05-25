<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Http\Requests\ServiceRateRequest;
use Fintech\Business\Http\Resources\ServiceCostResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class CalculateCostController extends Controller
{
    /**
     * @lrd:start
     */
    public function __invoke(ServiceRateRequest $request): JsonResponse|ServiceCostResource
    {
        $inputs = $request->all();

        try {
            $roles = config('fintech.auth.customer_roles', [7]);

            $inputs['role_id'] = array_shift($roles);

            $exchangeRate = business()->serviceStat()->cost($inputs);

            return new ServiceCostResource($exchangeRate);

        } catch (Exception $exception) {
            return response()->failed($exception);
        }
    }
}
