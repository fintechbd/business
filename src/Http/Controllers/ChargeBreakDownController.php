<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Http\Requests\ImportChargeBreakDownRequest;
use Fintech\Business\Http\Requests\IndexChargeBreakDownRequest;
use Fintech\Business\Http\Requests\StoreChargeBreakDownRequest;
use Fintech\Business\Http\Requests\UpdateChargeBreakDownRequest;
use Fintech\Business\Http\Resources\ChargeBreakDownCollection;
use Fintech\Business\Http\Resources\ChargeBreakDownResource;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Class ChargeBreakDownController
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to ChargeBreakDown
 *
 * @lrd:end
 */
class ChargeBreakDownController extends Controller
{
    /**
     * @lrd:start
     * Return a listing of the *ChargeBreakDown* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     *
     * @lrd:end
     */
    public function index(IndexChargeBreakDownRequest $request): ChargeBreakDownCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $chargeBreakDownPaginate = business()->chargeBreakDown()->list($inputs);

            return new ChargeBreakDownCollection($chargeBreakDownPaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a new *ChargeBreakDown* resource in storage.
     *
     * @lrd:end
     *
     * @throws StoreOperationException
     */
    public function store(StoreChargeBreakDownRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $chargeBreakDown = business()->chargeBreakDown()->create($inputs);

            if (! $chargeBreakDown) {
                throw (new StoreOperationException)->setModel(config('fintech.business.charge_break_down_model'));
            }

            return response()->created([
                'message' => __('core::messages.resource.created', ['model' => 'Charge Break Down']),
                'id' => $chargeBreakDown->id,
            ]);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Return a specified *ChargeBreakDown* resource found by id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): ChargeBreakDownResource|JsonResponse
    {
        try {

            $chargeBreakDown = business()->chargeBreakDown()->find($id);

            if (! $chargeBreakDown) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.charge_break_down_model'), $id);
            }

            return new ChargeBreakDownResource($chargeBreakDown);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Update a specified *ChargeBreakDown* resource using id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     * @throws UpdateOperationException
     */
    public function update(UpdateChargeBreakDownRequest $request, string|int $id): JsonResponse
    {
        try {

            $chargeBreakDown = business()->chargeBreakDown()->find($id);

            if (! $chargeBreakDown) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.charge_break_down_model'), $id);
            }

            $inputs = $request->validated();

            if (! business()->chargeBreakDown()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.charge_break_down_model'), $id);
            }

            return response()->updated(__('core::messages.resource.updated', ['model' => 'Charge Break Down']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *ChargeBreakDown* resource using id.
     *
     * @lrd:end
     *
     * @return JsonResponse
     *
     * @throws ModelNotFoundException
     * @throws DeleteOperationException
     */
    public function destroy(string|int $id)
    {
        try {

            $chargeBreakDown = business()->chargeBreakDown()->find($id);

            if (! $chargeBreakDown) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.charge_break_down_model'), $id);
            }

            if (! business()->chargeBreakDown()->destroy($id)) {

                throw (new DeleteOperationException)->setModel(config('fintech.business.charge_break_down_model'), $id);
            }

            return response()->deleted(__('core::messages.resource.deleted', ['model' => 'Charge Break Down']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Restore the specified *ChargeBreakDown* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     *
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $chargeBreakDown = business()->chargeBreakDown()->find($id, true);

            if (! $chargeBreakDown) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.charge_break_down_model'), $id);
            }

            if (! business()->chargeBreakDown()->restore($id)) {

                throw (new RestoreOperationException)->setModel(config('fintech.business.charge_break_down_model'), $id);
            }

            return response()->restored(__('core::messages.resource.restored', ['model' => 'Charge Break Down']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *ChargeBreakDown* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexChargeBreakDownRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $chargeBreakDownPaginate = business()->chargeBreakDown()->export($inputs);

            return response()->exported(__('core::messages.resource.exported', ['model' => 'Charge Break Down']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *ChargeBreakDown* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @return ChargeBreakDownCollection|JsonResponse
     */
    public function import(ImportChargeBreakDownRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $chargeBreakDownPaginate = business()->chargeBreakDown()->list($inputs);

            return new ChargeBreakDownCollection($chargeBreakDownPaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }
}
