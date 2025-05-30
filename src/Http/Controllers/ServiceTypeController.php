<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Http\Requests\ImportServiceTypeRequest;
use Fintech\Business\Http\Requests\IndexServiceTypeRequest;
use Fintech\Business\Http\Requests\StoreServiceTypeRequest;
use Fintech\Business\Http\Requests\UpdateServiceTypeRequest;
use Fintech\Business\Http\Resources\ServiceTypeCollection;
use Fintech\Business\Http\Resources\ServiceTypeResource;
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
 * Class ServiceTypeController
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to ServiceType
 *
 * @lrd:end
 */
class ServiceTypeController extends Controller
{
    /**
     * @lrd:start
     * Return a listing of the *ServiceType* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     *
     * @lrd:end
     */
    public function index(IndexServiceTypeRequest $request): ServiceTypeCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceTypePaginate = business()->serviceType()->list($inputs);

            return new ServiceTypeCollection($serviceTypePaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a new *ServiceType* resource in storage.
     *
     * @lrd:end
     */
    public function store(StoreServiceTypeRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceType = business()->serviceType()->create($inputs);

            if (! $serviceType) {
                throw (new StoreOperationException)->setModel(config('fintech.business.service_type_model'));
            }

            return response()->created([
                'message' => __('core::messages.resource.created', ['model' => 'Service Type']),
                'id' => $serviceType->getKey(),
            ]);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Return a specified *ServiceType* resource found by id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): ServiceTypeResource|JsonResponse
    {
        try {

            $serviceType = business()->serviceType()->find($id);

            if (! $serviceType) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_type_model'), $id);
            }

            return new ServiceTypeResource($serviceType);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Update a specified *ServiceType* resource using id.
     *
     * @lrd:end
     */
    public function update(UpdateServiceTypeRequest $request, string|int $id): JsonResponse
    {
        try {

            $serviceType = business()->serviceType()->find($id);

            if (! $serviceType) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_type_model'), $id);
            }

            $inputs = $request->validated();

            if (! business()->serviceType()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.service_type_model'), $id);
            }

            return response()->updated(__('core::messages.resource.updated', ['model' => 'Service Type']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *ServiceType* resource using id.
     *
     * @lrd:end
     */
    public function destroy(string|int $id): JsonResponse
    {
        try {

            $serviceType = business()->serviceType()->find($id);

            if (! $serviceType) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_type_model'), $id);
            }

            if (! business()->serviceType()->destroy($id)) {

                throw (new DeleteOperationException)->setModel(config('fintech.business.service_type_model'), $id);
            }

            return response()->deleted(__('core::messages.resource.deleted', ['model' => 'Service Type']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Restore the specified *ServiceType* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     */
    public function restore(string|int $id): JsonResponse
    {
        try {

            $serviceType = business()->serviceType()->find($id, true);

            if (! $serviceType) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_type_model'), $id);
            }

            if (! business()->serviceType()->restore($id)) {

                throw (new RestoreOperationException)->setModel(config('fintech.business.service_type_model'), $id);
            }

            return response()->restored(__('core::messages.resource.restored', ['model' => 'Service Type']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create an exportable list of the *ServiceType* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexServiceTypeRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            // $serviceTypePaginate = business()->serviceType()->export($inputs);
            business()->serviceType()->export($inputs);

            return response()->exported(__('core::messages.resource.exported', ['model' => 'Service Type']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create an exportable list of the *ServiceType* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function import(ImportServiceTypeRequest $request): ServiceTypeCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceTypePaginate = business()->serviceType()->list($inputs);

            return new ServiceTypeCollection($serviceTypePaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @LRDparam search nullable|string
     * @LRDparam service_type_parent_id nullable|integer
     * @LRDparam service_type_is_parent nullable|string|in:yes,no
     * @LRDparam service_type_name nullable|string
     * @LRDparam service_type_slug nullable|string
     * @LRDparam enabled nullable|boolean
     */
    public function dropdown(DropDownRequest $request): DropDownCollection|JsonResponse
    {
        try {
            $filters = $request->all();

            $filters['enabled'] = $filters['enabled'] ?? true;

            $label = 'service_type_name';

            $attribute = 'id';

            if (! empty($filters['label'])) {
                $label = $filters['label'];
                unset($filters['label']);
            }

            if (! empty($filters['attribute'])) {
                $attribute = $filters['attribute'];
                unset($filters['attribute']);
            }

            $entries = business()->serviceType()->list($filters)->map(function ($entry) use ($label, $attribute) {
                return [
                    'attribute' => $entry->{$attribute} ?? 'id',
                    'label' => $entry->{$label} ?? 'service_type_name',
                    'parents' => $entry->all_parent_list ?? [],
                ];
            })->toArray();

            return new DropDownCollection($entries);

        } catch (Exception $exception) {
            return response()->failed($exception);
        }
    }
}
