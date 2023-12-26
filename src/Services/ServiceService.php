<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\ServiceRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class ServiceService
 */
class ServiceService
{
    /**
     * ServiceService constructor.
     */
    public function __construct(private readonly ServiceRepository $serviceRepository)
    {
    }

    /**
     * @param array $filters
     * @return Paginator|Collection
     */
    public function list(array $filters = []): Paginator|Collection
    {
        return $this->serviceRepository->list($filters);

    }

    /**
     * @param array $inputs
     * @return Model|\MongoDB\Laravel\Eloquent\Model|null
     */
    public function create(array $inputs = []): Model|\MongoDB\Laravel\Eloquent\Model|null
    {
        return $this->serviceRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->serviceRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->serviceRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->serviceRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->serviceRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->serviceRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->serviceRepository->create($filters);
    }
}
