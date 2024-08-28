<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\ServiceTypeRepository;
use Fintech\Core\Abstracts\BaseModel;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * Class ServiceTypeService
 */
class ServiceTypeService
{
    /**
     * ServiceTypeService constructor.
     */
    public function __construct(private readonly ServiceTypeRepository $serviceTypeRepository) {}

    public function find($id, bool $onlyTrashed = false): ?BaseModel
    {
        return $this->serviceTypeRepository->find($id, $onlyTrashed);
    }

    public function create(array $inputs = []): ?BaseModel
    {
        if (isset($inputs['service_type_parent_id'])) {
            $this->syncServiceTypeParent($inputs['service_type_parent_id']);
        }

        return $this->serviceTypeRepository->create($inputs);
    }

    private function syncServiceTypeParent($parent_id = null): void
    {
        if ($parent_id != null) {
            $this->serviceTypeRepository->update($parent_id, ['service_type_is_parent' => 'yes']);
        }
    }

    public function update($id, array $inputs = []): ?BaseModel
    {
        if (isset($inputs['service_type_parent_id'])) {
            $this->syncServiceTypeParent($inputs['service_type_parent_id']);
        }

        return $this->serviceTypeRepository->update($id, $inputs);
    }

    public function destroy($id): mixed
    {
        return $this->serviceTypeRepository->delete($id);
    }

    public function restore($id): mixed
    {
        return $this->serviceTypeRepository->restore($id);
    }

    public function export(array $filters): Paginator|Collection
    {
        return $this->serviceTypeRepository->list($filters);
    }

    public function list(array $filters = []): Collection|Paginator
    {
        return $this->serviceTypeRepository->list($filters);

    }

    public function import(array $filters): ?BaseModel
    {
        return $this->serviceTypeRepository->create($filters);
    }
}
