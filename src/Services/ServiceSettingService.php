<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\ServiceSettingRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class ServiceSettingService
 */
class ServiceSettingService
{
    /**
     * ServiceSettingService constructor.
     */
    public function __construct(private readonly ServiceSettingRepository $serviceSettingRepository)
    {

    }

    /**
     * @param array $filters
     * @return Paginator|Collection
     */
    public function list(array $filters = []): Paginator|Collection
    {
        return $this->serviceSettingRepository->list($filters);

    }

    /**
     * @param array $inputs
     * @return Model|\MongoDB\Laravel\Eloquent\Model|null
     */
    public function create(array $inputs = []): Model|\MongoDB\Laravel\Eloquent\Model|null
    {
        return $this->serviceSettingRepository->create($inputs);
    }

    /**
     * @param $id
     * @param bool $onlyTrashed
     * @return Model|\MongoDB\Laravel\Eloquent\Model|null
     */
    public function find($id, bool $onlyTrashed = false): Model|\MongoDB\Laravel\Eloquent\Model|null
    {
        return $this->serviceSettingRepository->find($id, $onlyTrashed);
    }

    /**
     * @param $id
     * @param array $inputs
     * @return Model|\MongoDB\Laravel\Eloquent\Model|null
     */
    public function update($id, array $inputs = []): Model|\MongoDB\Laravel\Eloquent\Model|null
    {
        return $this->serviceSettingRepository->update($id, $inputs);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id): mixed
    {
        return $this->serviceSettingRepository->delete($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id): mixed
    {
        return $this->serviceSettingRepository->restore($id);
    }

    /**
     * @param array $filters
     * @return Paginator|Collection
     */
    public function export(array $filters): Paginator|Collection
    {
        return $this->serviceSettingRepository->list($filters);
    }

    /**
     * @param array $filters
     * @return Model|\MongoDB\Laravel\Eloquent\Model|null
     */
    public function import(array $filters): Model|\MongoDB\Laravel\Eloquent\Model|null
    {
        return $this->serviceSettingRepository->create($filters);
    }
}
