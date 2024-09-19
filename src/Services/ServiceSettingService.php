<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\ServiceSettingRepository;
use Fintech\Core\Abstracts\BaseModel;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * Class ServiceSettingService
 */
class ServiceSettingService extends \Fintech\Core\Abstracts\Service
{
    /**
     * ServiceSettingService constructor.
     */
    public function __construct(private readonly ServiceSettingRepository $serviceSettingRepository) {}

    public function find($id, bool $onlyTrashed = false): ?BaseModel
    {
        return $this->serviceSettingRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = []): ?BaseModel
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

    public function list(array $filters = []): Paginator|Collection
    {
        return $this->serviceSettingRepository->list($filters);

    }

    public function import(array $filters): ?BaseModel
    {
        return $this->serviceSettingRepository->create($filters);
    }

    public function create(array $inputs = []): ?BaseModel
    {
        return $this->serviceSettingRepository->create($inputs);
    }
}
