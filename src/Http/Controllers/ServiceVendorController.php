<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Http\Requests\ImportServiceVendorRequest;
use Fintech\Business\Http\Requests\IndexServiceVendorRequest;
use Fintech\Business\Http\Requests\StoreServiceVendorRequest;
use Fintech\Business\Http\Requests\UpdateServiceVendorRequest;
use Fintech\Business\Http\Resources\ServiceVendorCollection;
use Fintech\Business\Http\Resources\ServiceVendorResource;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\Core\Http\Requests\DropDownRequest;
use Fintech\Core\Http\Resources\DropDownCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Class ServiceVendorController
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to ServiceVendor
 *
 * @lrd:end
 */
class ServiceVendorController extends Controller
{
    /**
     * @lrd:start
     * Return a listing of the *ServiceVendor* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     *
     * @lrd:end
     */
    public function index(IndexServiceVendorRequest $request): ServiceVendorCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceVendorPaginate = business()->serviceVendor()->list($inputs);

            return new ServiceVendorCollection($serviceVendorPaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a new *ServiceVendor* resource in storage.
     *
     * @lrd:end
     *
     * @throws StoreOperationException
     */
    public function store(StoreServiceVendorRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceVendor = business()->serviceVendor()->create($inputs);

            if (! $serviceVendor) {
                throw (new StoreOperationException)->setModel(config('fintech.business.service_vendor_model'));
            }

            return response()->created([
                'message' => __('core::messages.resource.created', ['model' => 'Service Vendor']),
                'id' => $serviceVendor->id,
            ]);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Return a specified *ServiceVendor* resource found by id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): ServiceVendorResource|JsonResponse
    {
        try {

            $serviceVendor = business()->serviceVendor()->find($id);

            if (! $serviceVendor) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_vendor_model'), $id);
            }

            return new ServiceVendorResource($serviceVendor);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Update a specified *ServiceVendor* resource using id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     * @throws UpdateOperationException
     */
    public function update(UpdateServiceVendorRequest $request, string|int $id): JsonResponse
    {
        try {

            $serviceVendor = business()->serviceVendor()->find($id);

            if (! $serviceVendor) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_vendor_model'), $id);
            }

            $inputs = $request->validated();

            if (! business()->serviceVendor()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.service_vendor_model'), $id);
            }

            return response()->updated(__('core::messages.resource.updated', ['model' => 'Service Vendor']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *ServiceVendor* resource using id.
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

            $serviceVendor = business()->serviceVendor()->find($id);

            if (! $serviceVendor) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_vendor_model'), $id);
            }

            if (! business()->serviceVendor()->destroy($id)) {

                throw (new DeleteOperationException)->setModel(config('fintech.business.service_vendor_model'), $id);
            }

            return response()->deleted(__('core::messages.resource.deleted', ['model' => 'Service Vendor']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Restore the specified *ServiceVendor* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     *
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $serviceVendor = business()->serviceVendor()->find($id, true);

            if (! $serviceVendor) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_vendor_model'), $id);
            }

            if (! business()->serviceVendor()->restore($id)) {

                throw (new RestoreOperationException)->setModel(config('fintech.business.service_vendor_model'), $id);
            }

            return response()->restored(__('core::messages.resource.restored', ['model' => 'Service Vendor']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *ServiceVendor* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexServiceVendorRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceVendorPaginate = business()->serviceVendor()->export($inputs);

            return response()->exported(__('core::messages.resource.exported', ['model' => 'Service Vendor']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *ServiceVendor* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @return ServiceVendorCollection|JsonResponse
     */
    public function import(ImportServiceVendorRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceVendorPaginate = business()->serviceVendor()->list($inputs);

            return new ServiceVendorCollection($serviceVendorPaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @LRDparam country_id required|integer|min:1
     * @LRDparam state_id required|integer|min:1
     */
    public function dropdown(DropDownRequest $request): DropDownCollection|JsonResponse
    {
        try {
            $filters = $request->all();

            $filters['enabled'] = $filters['enabled'] ?? true;

            $label = 'name';

            $attribute = 'id';

            if (! empty($filters['label'])) {
                $label = $filters['label'];
                unset($filters['label']);
            }

            if (! empty($filters['attribute'])) {
                $attribute = $filters['attribute'];
                unset($filters['attribute']);
            }

            $entries = business()->serviceVendor()->list($filters)->map(function ($entry) use ($label, $attribute) {
                return [
                    'attribute' => $entry->{$attribute} ?? 'id',
                    'label' => $entry->{$label} ?? 'name',
                ];
            })->toArray();

            return new DropDownCollection($entries);

        } catch (Exception $exception) {
            return response()->failed($exception);
        }
    }
}
