<?php

namespace Fintech\Business\Services;


use Fintech\Business\Interfaces\ServiceSettingRepository;

/**
 * Class ServiceSettingService
 * @package Fintech\Business\Services
 *
 */
class ServiceSettingService
{
    /**
     * ServiceSettingService constructor.
     * @param ServiceSettingRepository $serviceSettingRepository
     */
    public function __construct(ServiceSettingRepository $serviceSettingRepository) {
        $this->serviceSettingRepository = $serviceSettingRepository;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->serviceSettingRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->serviceSettingRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->serviceSettingRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->serviceSettingRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->serviceSettingRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->serviceSettingRepository->restore($id);
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
