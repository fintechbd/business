<?php

namespace Fintech\Business\Services;


use Fintech\Business\Interfaces\ServiceRepository;

/**
 * Class ServiceService
 * @package Fintech\Business\Services
 *
 */
class ServiceService
{
    /**
     * ServiceService constructor.
     * @param ServiceRepository $serviceRepository
     */
    public function __construct(ServiceRepository $serviceRepository) {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->serviceRepository->list($filters);

    }

    public function create(array $inputs = [])
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
        return $this->permissionRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->permissionRepository->create($filters);
    }
}
