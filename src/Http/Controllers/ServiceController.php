<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Auth\Facades\Auth;
use Fintech\Business\Http\Requests\ImportServiceRequest;
use Fintech\Business\Http\Requests\IndexServiceRequest;
use Fintech\Business\Http\Requests\ServiceRateRequest;
use Fintech\Business\Http\Requests\StoreServiceRequest;
use Fintech\Business\Http\Requests\UpdateServiceRequest;
use Fintech\Business\Http\Resources\ServiceCollection;
use Fintech\Business\Http\Resources\ServiceCostResource;
use Fintech\Business\Http\Resources\ServiceResource;
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
 * Class ServiceController
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to Service
 *
 * @lrd:end
 */
class ServiceController extends Controller
{
    /**
     * @lrd:start
     * Return a listing of the *Service* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     *
     * @lrd:end
     */
    public function index(IndexServiceRequest $request): ServiceCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $servicePaginate = business()->service()->list($inputs);

            return new ServiceCollection($servicePaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a new *Service* resource in storage.
     *
     * @lrd:end
     */
    public function store(StoreServiceRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $service = business()->service()->create($inputs);

            if (! $service) {
                throw (new StoreOperationException)->setModel(config('fintech.business.service_model'));
            }

            return response()->created([
                'message' => __('core::messages.resource.created', ['model' => 'Service']),
                'id' => $service->getKey(),
            ]);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Return a specified *Service* resource found by id.
     *
     * @lrd:end
     */
    public function show(string|int $id): ServiceResource|JsonResponse
    {
        try {

            $service = business()->service()->find($id);

            if (! $service) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_model'), $id);
            }

            return new ServiceResource($service);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Update a specified *Service* resource using id.
     *
     * @lrd:end
     */
    public function update(UpdateServiceRequest $request, string|int $id): JsonResponse
    {
        try {

            $service = business()->service()->find($id);

            if (! $service) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_model'), $id);
            }

            $inputs = $request->validated();

            if (! business()->service()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.service_model'), $id);
            }

            return response()->updated(__('core::messages.resource.updated', ['model' => 'Service']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *Service* resource using id.
     *
     * @lrd:end
     */
    public function destroy(string|int $id): JsonResponse
    {
        try {

            $service = business()->service()->find($id);

            if (! $service) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_model'), $id);
            }

            if (! business()->service()->destroy($id)) {

                throw (new DeleteOperationException)->setModel(config('fintech.business.service_model'), $id);
            }

            return response()->deleted(__('core::messages.resource.deleted', ['model' => 'Service']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Restore the specified *Service* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     */
    public function restore(string|int $id): JsonResponse
    {
        try {

            $service = business()->service()->find($id, true);

            if (! $service) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_model'), $id);
            }

            if (! business()->service()->restore($id)) {

                throw (new RestoreOperationException)->setModel(config('fintech.business.service_model'), $id);
            }

            return response()->restored(__('core::messages.resource.restored', ['model' => 'Service']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create an exportable list of the *Service* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexServiceRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            // $servicePaginate = business()->service()->export($inputs);
            business()->service()->export($inputs);

            return response()->exported(__('core::messages.resource.exported', ['model' => 'Service']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create an exportable list of the *Service* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function import(ImportServiceRequest $request): ServiceCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $servicePaginate = business()->service()->list($inputs);

            return new ServiceCollection($servicePaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    public function cost(ServiceRateRequest $request): JsonResponse|ServiceCostResource
    {
        $inputs = $request->validated();

        try {

            if (! $request->filled('user_id')) {
                $inputs['user_id'] = auth()->id();
            }

            if ($user = Auth::user()->find($inputs['user_id'])) {
                $inputs['role_id'] = $user->roles->first()?->getKey() ?? null;
            }

            $exchangeRate = business()->serviceStat()->cost($inputs);

            return new ServiceCostResource($exchangeRate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @LRDparam service_type_id nullable|integer|min:1
     * @LRDparam service_vendor_id nullable|integer|min:1
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

            $entries = business()->service()->list($filters)->map(function ($entry) use ($label, $attribute) {
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
