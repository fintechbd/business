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

    public function list(array $filters = []): Paginator|Collection
    {
        return $this->serviceSettingRepository->list($filters);

    }

    public function create(array $inputs = []): Model|\MongoDB\Laravel\Eloquent\Model|null
    {
        return $this->serviceSettingRepository->create($inputs);
    }

    public function find($id, bool $onlyTrashed = false): Model|\MongoDB\Laravel\Eloquent\Model|null
    {
        return $this->serviceSettingRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = []): Model|\MongoDB\Laravel\Eloquent\Model|null
    {
        return $this->serviceSettingRepository->update($id, $inputs);
    }

    public function destroy($id): mixed
    {
        return $this->serviceSettingRepository->delete($id);
    }

    public function restore($id): mixed
    {
        return $this->serviceSettingRepository->restore($id);
    }

    public function export(array $filters): Paginator|Collection
    {
        return $this->serviceSettingRepository->list($filters);
    }

    public function import(array $filters): Model|\MongoDB\Laravel\Eloquent\Model|null
    {
        return $this->serviceSettingRepository->create($filters);
    }
}
