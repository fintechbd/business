<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\ServiceRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use MongoDB\Laravel\Eloquent\Model;

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

    public function find($id, bool $onlyTrashed = false): ?Model
    {
        return $this->serviceRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = []): ?Model
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

    public function list(array $filters = []): Paginator|Collection
    {
        return $this->serviceRepository->list($filters);

    }

    public function import(array $filters): ?Model
    {
        return $this->serviceRepository->create($filters);
    }

    public function create(array $inputs = []): ?Model
    {
        return $this->serviceRepository->create($inputs);
    }
}
