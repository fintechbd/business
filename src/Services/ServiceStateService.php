<?php

namespace Fintech\Business\Services;


use Fintech\Business\Interfaces\ServiceStateRepository;

/**
 * Class ServiceStateService
 * @package Fintech\Business\Services
 *
 */
class ServiceStateService
{
    /**
     * ServiceStateService constructor.
     * @param ServiceStateRepository $serviceStateRepository
     */
    public function __construct(ServiceStateRepository $serviceStateRepository) {
        $this->serviceStateRepository = $serviceStateRepository;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->serviceStateRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->serviceStateRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->serviceStateRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->serviceStateRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->serviceStateRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->serviceStateRepository->restore($id);
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
