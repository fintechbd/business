<?php

namespace Fintech\Business\Http\Controllers;
use Exception;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Traits\ApiResponseTrait;
use Fintech\Business\Facades\Business;
use Fintech\Business\Http\Resources\CurrencyRateResource;
use Fintech\Business\Http\Resources\CurrencyRateCollection;
use Fintech\Business\Http\Requests\ImportCurrencyRateRequest;
use Fintech\Business\Http\Requests\StoreCurrencyRateRequest;
use Fintech\Business\Http\Requests\UpdateCurrencyRateRequest;
use Fintech\Business\Http\Requests\IndexCurrencyRateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Class CurrencyRateController
 * @package Fintech\Business\Http\Controllers
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to CurrencyRate
 * @lrd:end
 *
 */

class CurrencyRateController extends Controller
{
    use ApiResponseTrait;

    /**
     * @lrd:start
     * Return a listing of the *CurrencyRate* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     * @lrd:end
     *
     * @param IndexCurrencyRateRequest $request
     * @return CurrencyRateCollection|JsonResponse
     */
    public function index(IndexCurrencyRateRequest $request): CurrencyRateCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $currencyRatePaginate = Business::currencyRate()->list($inputs);

            return new CurrencyRateCollection($currencyRatePaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a new *CurrencyRate* resource in storage.
     * @lrd:end
     *
     * @param StoreCurrencyRateRequest $request
     * @return JsonResponse
     * @throws StoreOperationException
     */
    public function store(StoreCurrencyRateRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $currencyRate = Business::currencyRate()->create($inputs);

            if (!$currencyRate) {
                throw (new StoreOperationException)->setModel(config('fintech.business.currency_rate_model'));
            }

            return $this->created([
                'message' => __('core::messages.resource.created', ['model' => 'Currency Rate']),
                'id' => $currencyRate->id
             ]);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Return a specified *CurrencyRate* resource found by id.
     * @lrd:end
     *
     * @param string|int $id
     * @return CurrencyRateResource|JsonResponse
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): CurrencyRateResource|JsonResponse
    {
        try {

            $currencyRate = Business::currencyRate()->find($id);

            if (!$currencyRate) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.currency_rate_model'), $id);
            }

            return new CurrencyRateResource($currencyRate);

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Update a specified *CurrencyRate* resource using id.
     * @lrd:end
     *
     * @param UpdateCurrencyRateRequest $request
     * @param string|int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws UpdateOperationException
     */
    public function update(UpdateCurrencyRateRequest $request, string|int $id): JsonResponse
    {
        try {

            $currencyRate = Business::currencyRate()->find($id);

            if (!$currencyRate) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.currency_rate_model'), $id);
            }

            $inputs = $request->validated();

            if (!Business::currencyRate()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.currency_rate_model'), $id);
            }

            return $this->updated(__('core::messages.resource.updated', ['model' => 'Currency Rate']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *CurrencyRate* resource using id.
     * @lrd:end
     *
     * @param string|int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws DeleteOperationException
     */
    public function destroy(string|int $id)
    {
        try {

            $currencyRate = Business::currencyRate()->find($id);

            if (!$currencyRate) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.currency_rate_model'), $id);
            }

            if (!Business::currencyRate()->destroy($id)) {

                throw (new DeleteOperationException())->setModel(config('fintech.business.currency_rate_model'), $id);
            }

            return $this->deleted(__('core::messages.resource.deleted', ['model' => 'Currency Rate']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Restore the specified *CurrencyRate* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     * @lrd:end
     *
     * @param string|int $id
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $currencyRate = Business::currencyRate()->find($id, true);

            if (!$currencyRate) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.currency_rate_model'), $id);
            }

            if (!Business::currencyRate()->restore($id)) {

                throw (new RestoreOperationException())->setModel(config('fintech.business.currency_rate_model'), $id);
            }

            return $this->restored(__('core::messages.resource.restored', ['model' => 'Currency Rate']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *CurrencyRate* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @param IndexCurrencyRateRequest $request
     * @return JsonResponse
     */
    public function export(IndexCurrencyRateRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $currencyRatePaginate = Business::currencyRate()->export($inputs);

            return $this->exported(__('core::messages.resource.exported', ['model' => 'Currency Rate']));

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *CurrencyRate* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @param ImportCurrencyRateRequest $request
     * @return CurrencyRateCollection|JsonResponse
     */
    public function import(ImportCurrencyRateRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $currencyRatePaginate = Business::currencyRate()->list($inputs);

            return new CurrencyRateCollection($currencyRatePaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }
}
