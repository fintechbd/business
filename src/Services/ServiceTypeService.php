<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\ServiceTypeRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use MongoDB\Laravel\Eloquent\Model;

/**
 * Class ServiceTypeService
 */
class ServiceTypeService
{
    /**
     * ServiceTypeService constructor.
     */
    public function __construct(private readonly ServiceTypeRepository $serviceTypeRepository)
    {

    }

    public function find($id, bool $onlyTrashed = false): Model|Model|null
    {
        return $this->serviceTypeRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = []): Model|Model|null
    {
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

    public function import(array $filters): Model|Model|null
    {
        return $this->serviceTypeRepository->create($filters);
    }

    public function create(array $inputs = []): Model|Model|null
    {
        return $this->serviceTypeRepository->create($inputs);
    }
}
