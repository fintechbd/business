<?php

namespace Fintech\Business\Http\Controllers;
use Exception;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Traits\ApiResponseTrait;
use Fintech\Business\Facades\Business;
use Fintech\Business\Http\Resources\VendorResource;
use Fintech\Business\Http\Resources\VendorCollection;
use Fintech\Business\Http\Requests\ImportVendorRequest;
use Fintech\Business\Http\Requests\StoreVendorRequest;
use Fintech\Business\Http\Requests\UpdateVendorRequest;
use Fintech\Business\Http\Requests\IndexVendorRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Class VendorController
 * @package Fintech\Business\Http\Controllers
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to Vendor
 * @lrd:end
 *
 */

class VendorController extends Controller
{
    use ApiResponseTrait;

    /**
     * @lrd:start
     * Return a listing of the *Vendor* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     * @lrd:end
     *
     * @param IndexVendorRequest $request
     * @return VendorCollection|JsonResponse
     */
    public function index(IndexVendorRequest $request): VendorCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $vendorPaginate = Business::vendor()->list($inputs);

            return new VendorCollection($vendorPaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a new *Vendor* resource in storage.
     * @lrd:end
     *
     * @param StoreVendorRequest $request
     * @return JsonResponse
     * @throws StoreOperationException
     */
    public function store(StoreVendorRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $vendor = Business::vendor()->create($inputs);

            if (!$vendor) {
                throw (new StoreOperationException)->setModel(config('fintech.business.vendor_model'));
            }

            return $this->created([
                'message' => __('core::messages.resource.created', ['model' => 'Vendor']),
                'id' => $vendor->id
             ]);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Return a specified *Vendor* resource found by id.
     * @lrd:end
     *
     * @param string|int $id
     * @return VendorResource|JsonResponse
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): VendorResource|JsonResponse
    {
        try {

            $vendor = Business::vendor()->find($id);

            if (!$vendor) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.vendor_model'), $id);
            }

            return new VendorResource($vendor);

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Update a specified *Vendor* resource using id.
     * @lrd:end
     *
     * @param UpdateVendorRequest $request
     * @param string|int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws UpdateOperationException
     */
    public function update(UpdateVendorRequest $request, string|int $id): JsonResponse
    {
        try {

            $vendor = Business::vendor()->find($id);

            if (!$vendor) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.vendor_model'), $id);
            }

            $inputs = $request->validated();

            if (!Business::vendor()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.vendor_model'), $id);
            }

            return $this->updated(__('core::messages.resource.updated', ['model' => 'Vendor']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *Vendor* resource using id.
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

            $vendor = Business::vendor()->find($id);

            if (!$vendor) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.vendor_model'), $id);
            }

            if (!Business::vendor()->destroy($id)) {

                throw (new DeleteOperationException())->setModel(config('fintech.business.vendor_model'), $id);
            }

            return $this->deleted(__('core::messages.resource.deleted', ['model' => 'Vendor']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Restore the specified *Vendor* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     * @lrd:end
     *
     * @param string|int $id
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $vendor = Business::vendor()->find($id, true);

            if (!$vendor) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.vendor_model'), $id);
            }

            if (!Business::vendor()->restore($id)) {

                throw (new RestoreOperationException())->setModel(config('fintech.business.vendor_model'), $id);
            }

            return $this->restored(__('core::messages.resource.restored', ['model' => 'Vendor']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *Vendor* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @param IndexVendorRequest $request
     * @return JsonResponse
     */
    public function export(IndexVendorRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $vendorPaginate = Business::vendor()->export($inputs);

            return $this->exported(__('core::messages.resource.exported', ['model' => 'Vendor']));

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *Vendor* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @param ImportVendorRequest $request
     * @return VendorCollection|JsonResponse
     */
    public function import(ImportVendorRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $vendorPaginate = Business::vendor()->list($inputs);

            return new VendorCollection($vendorPaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }
}
