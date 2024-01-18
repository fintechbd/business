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

    public function list(array $filters = []): Paginator|Collection
    {
        return $this->serviceRepository->list($filters);

    }

    public function create(array $inputs = []): Model|\MongoDB\Laravel\Eloquent\Model|null
    {
        return $this->serviceRepository->create($inputs);
    }

    public function find($id, bool $onlyTrashed = false): Model|\MongoDB\Laravel\Eloquent\Model|null
    {
        return $this->serviceRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = []): Model|\MongoDB\Laravel\Eloquent\Model|null
    {
        return $this->serviceRepository->update($id, $inputs);
    }

    public function destroy($id): mixed
    {
        return $this->serviceRepository->delete($id);
    }

    public function restore($id): mixed
    {
        return $this->serviceRepository->restore($id);
    }

    public function export(array $filters): Paginator|Collection
    {
        return $this->serviceRepository->list($filters);
    }

    public function import(array $filters): Model|\MongoDB\Laravel\Eloquent\Model|null
    {
        return $this->serviceRepository->create($filters);
    }
}
