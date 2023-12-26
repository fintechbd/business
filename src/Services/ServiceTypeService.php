<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\ServiceTypeRepository;
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
    public function __construct(private readonly ServiceTypeRepository $serviceTypeRepository)
    {

    }

    /**
     * @param array $filters
     * @return Collection|Paginator
     */
    public function list(array $filters = []): Collection|Paginator
    {
        return $this->serviceTypeRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->serviceTypeRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->serviceTypeRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->serviceTypeRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->serviceTypeRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->serviceTypeRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->serviceTypeRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->serviceTypeRepository->create($filters);
    }
}
