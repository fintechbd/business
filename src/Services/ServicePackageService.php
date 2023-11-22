<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\ServicePackageRepository;

/**
 * Class ServicePackageService
 */
class ServicePackageService
{
    /**
     * ServicePackageService constructor.
     */
    public function __construct(private readonly ServicePackageRepository $servicePackageRepository)
    {
    }

    /**
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->servicePackageRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->servicePackageRepository->create($inputs);
    }

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

    public function import(array $filters)
    {
        return $this->servicePackageRepository->create($filters);
    }
}
