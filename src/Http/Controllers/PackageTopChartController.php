<?php

namespace Fintech\Business\Http\Controllers;
use Exception;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Traits\ApiResponseTrait;
use Fintech\Business\Facades\Business;
use Fintech\Business\Http\Resources\PackageTopChartResource;
use Fintech\Business\Http\Resources\PackageTopChartCollection;
use Fintech\Business\Http\Requests\ImportPackageTopChartRequest;
use Fintech\Business\Http\Requests\StorePackageTopChartRequest;
use Fintech\Business\Http\Requests\UpdatePackageTopChartRequest;
use Fintech\Business\Http\Requests\IndexPackageTopChartRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Class PackageTopChartController
 * @package Fintech\Business\Http\Controllers
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to PackageTopChart
 * @lrd:end
 *
 */

class PackageTopChartController extends Controller
{
    use ApiResponseTrait;

    /**
     * @lrd:start
     * Return a listing of the *PackageTopChart* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     * @lrd:end
     *
     * @param IndexPackageTopChartRequest $request
     * @return PackageTopChartCollection|JsonResponse
     */
    public function index(IndexPackageTopChartRequest $request): PackageTopChartCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $packageTopChartPaginate = Business::packageTopChart()->list($inputs);

            return new PackageTopChartCollection($packageTopChartPaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a new *PackageTopChart* resource in storage.
     * @lrd:end
     *
     * @param StorePackageTopChartRequest $request
     * @return JsonResponse
     * @throws StoreOperationException
     */
    public function store(StorePackageTopChartRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $packageTopChart = Business::packageTopChart()->create($inputs);

            if (!$packageTopChart) {
                throw (new StoreOperationException)->setModel(config('fintech.business.package_top_chart_model'));
            }

            return $this->created([
                'message' => __('core::messages.resource.created', ['model' => 'Package Top Chart']),
                'id' => $packageTopChart->id
             ]);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Return a specified *PackageTopChart* resource found by id.
     * @lrd:end
     *
     * @param string|int $id
     * @return PackageTopChartResource|JsonResponse
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): PackageTopChartResource|JsonResponse
    {
        try {

            $packageTopChart = Business::packageTopChart()->find($id);

            if (!$packageTopChart) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.package_top_chart_model'), $id);
            }

            return new PackageTopChartResource($packageTopChart);

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Update a specified *PackageTopChart* resource using id.
     * @lrd:end
     *
     * @param UpdatePackageTopChartRequest $request
     * @param string|int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws UpdateOperationException
     */
    public function update(UpdatePackageTopChartRequest $request, string|int $id): JsonResponse
    {
        try {

            $packageTopChart = Business::packageTopChart()->find($id);

            if (!$packageTopChart) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.package_top_chart_model'), $id);
            }

            $inputs = $request->validated();

            if (!Business::packageTopChart()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.package_top_chart_model'), $id);
            }

            return $this->updated(__('core::messages.resource.updated', ['model' => 'Package Top Chart']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *PackageTopChart* resource using id.
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

            $packageTopChart = Business::packageTopChart()->find($id);

            if (!$packageTopChart) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.package_top_chart_model'), $id);
            }

            if (!Business::packageTopChart()->destroy($id)) {

                throw (new DeleteOperationException())->setModel(config('fintech.business.package_top_chart_model'), $id);
            }

            return $this->deleted(__('core::messages.resource.deleted', ['model' => 'Package Top Chart']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Restore the specified *PackageTopChart* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     * @lrd:end
     *
     * @param string|int $id
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $packageTopChart = Business::packageTopChart()->find($id, true);

            if (!$packageTopChart) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.package_top_chart_model'), $id);
            }

            if (!Business::packageTopChart()->restore($id)) {

                throw (new RestoreOperationException())->setModel(config('fintech.business.package_top_chart_model'), $id);
            }

            return $this->restored(__('core::messages.resource.restored', ['model' => 'Package Top Chart']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *PackageTopChart* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @param IndexPackageTopChartRequest $request
     * @return JsonResponse
     */
    public function export(IndexPackageTopChartRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $packageTopChartPaginate = Business::packageTopChart()->export($inputs);

            return $this->exported(__('core::messages.resource.exported', ['model' => 'Package Top Chart']));

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *PackageTopChart* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @param ImportPackageTopChartRequest $request
     * @return PackageTopChartCollection|JsonResponse
     */
    public function import(ImportPackageTopChartRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $packageTopChartPaginate = Business::packageTopChart()->list($inputs);

            return new PackageTopChartCollection($packageTopChartPaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }
}
