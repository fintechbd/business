<?php

namespace Fintech\Business\Services;


use Fintech\Business\Interfaces\ServiceTypeRepository;

/**
 * Class ServiceTypeService
 * @package Fintech\Business\Services
 *
 */
class ServiceTypeService
{
    /**
     * ServiceTypeService constructor.
     * @param ServiceTypeRepository $serviceTypeRepository
     */
    public function __construct(ServiceTypeRepository $serviceTypeRepository) {
        $this->serviceTypeRepository = $serviceTypeRepository;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
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
        return $this->permissionRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->permissionRepository->create($filters);
    }
}
