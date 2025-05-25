<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Http\Requests\ImportPackageTopChartRequest;
use Fintech\Business\Http\Requests\IndexPackageTopChartRequest;
use Fintech\Business\Http\Requests\StorePackageTopChartRequest;
use Fintech\Business\Http\Requests\UpdatePackageTopChartRequest;
use Fintech\Business\Http\Resources\PackageTopChartCollection;
use Fintech\Business\Http\Resources\PackageTopChartResource;
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
 * Class PackageTopChartController
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to PackageTopChart
 *
 * @lrd:end
 */
class PackageTopChartController extends Controller
{
    /**
     * @lrd:start
     * Return a listing of the *PackageTopChart* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     *
     * @lrd:end
     */
    public function index(IndexPackageTopChartRequest $request): PackageTopChartCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $packageTopChartPaginate = business()->packageTopChart()->list($inputs);

            return new PackageTopChartCollection($packageTopChartPaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a new *PackageTopChart* resource in storage.
     *
     * @lrd:end
     *
     * @throws StoreOperationException
     */
    public function store(StorePackageTopChartRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $packageTopChart = business()->packageTopChart()->create($inputs);

            if (! $packageTopChart) {
                throw (new StoreOperationException)->setModel(config('fintech.business.package_top_chart_model'));
            }

            return response()->created([
                'message' => __('core::messages.resource.created', ['model' => 'Package Top Chart']),
                'id' => $packageTopChart->id,
            ]);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Return a specified *PackageTopChart* resource found by id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): PackageTopChartResource|JsonResponse
    {
        try {

            $packageTopChart = business()->packageTopChart()->find($id);

            if (! $packageTopChart) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.package_top_chart_model'), $id);
            }

            return new PackageTopChartResource($packageTopChart);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Update a specified *PackageTopChart* resource using id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     * @throws UpdateOperationException
     */
    public function update(UpdatePackageTopChartRequest $request, string|int $id): JsonResponse
    {
        try {

            $packageTopChart = business()->packageTopChart()->find($id);

            if (! $packageTopChart) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.package_top_chart_model'), $id);
            }

            $inputs = $request->validated();

            if (! business()->packageTopChart()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.package_top_chart_model'), $id);
            }

            return response()->updated(__('core::messages.resource.updated', ['model' => 'Package Top Chart']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *PackageTopChart* resource using id.
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

            $packageTopChart = business()->packageTopChart()->find($id);

            if (! $packageTopChart) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.package_top_chart_model'), $id);
            }

            if (! business()->packageTopChart()->destroy($id)) {

                throw (new DeleteOperationException)->setModel(config('fintech.business.package_top_chart_model'), $id);
            }

            return response()->deleted(__('core::messages.resource.deleted', ['model' => 'Package Top Chart']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Restore the specified *PackageTopChart* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     *
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $packageTopChart = business()->packageTopChart()->find($id, true);

            if (! $packageTopChart) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.package_top_chart_model'), $id);
            }

            if (! business()->packageTopChart()->restore($id)) {

                throw (new RestoreOperationException)->setModel(config('fintech.business.package_top_chart_model'), $id);
            }

            return response()->restored(__('core::messages.resource.restored', ['model' => 'Package Top Chart']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *PackageTopChart* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexPackageTopChartRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $packageTopChartPaginate = business()->packageTopChart()->export($inputs);

            return response()->exported(__('core::messages.resource.exported', ['model' => 'Package Top Chart']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *PackageTopChart* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @return PackageTopChartCollection|JsonResponse
     */
    public function import(ImportPackageTopChartRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $packageTopChartPaginate = business()->packageTopChart()->list($inputs);

            return new PackageTopChartCollection($packageTopChartPaginate);

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

            $entries = business()->packageTopChart()->list($filters)->map(function ($entry) use ($label, $attribute) {
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
