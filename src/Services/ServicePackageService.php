<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\ServicePackageRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * Class ServicePackageService
 */
class ServicePackageService
{
    use \Fintech\Core\Traits\HasFindWhereSearch;

    /**
     * ServicePackageService constructor.
     */
    public function __construct(private readonly ServicePackageRepository $servicePackageRepository) {}

    public function find($id, $onlyTrashed = false)
    {
        return $this->servicePackageRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->servicePackageRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->servicePackageRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->servicePackageRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->servicePackageRepository->list($filters);
    }

    /**
     * @return Paginator|Collection
     */
    public function list(array $filters = [])
    {
        return $this->servicePackageRepository->list($filters);

    }

    public function import(array $filters)
    {
        return $this->servicePackageRepository->create($filters);
    }

    public function create(array $inputs = [])
    {
        return $this->servicePackageRepository->create($inputs);
    }
}
