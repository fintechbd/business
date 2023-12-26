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

    public function update($id, array $inputs = [])
    {
        return $this->serviceSettingRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->serviceSettingRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->serviceSettingRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->serviceSettingRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->serviceSettingRepository->create($filters);
    }
}
