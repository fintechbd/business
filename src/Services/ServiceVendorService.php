<?php

namespace Fintech\Business\Services;


use Fintech\Business\Interfaces\ServiceVendorRepository;

/**
 * Class ServiceVendorService
 * @package Fintech\Business\Services
 *
 */
class ServiceVendorService
{
    /**
     * ServiceVendorService constructor.
     * @param ServiceVendorRepository $serviceVendorRepository
     */
    public function __construct(ServiceVendorRepository $serviceVendorRepository) {
        $this->serviceVendorRepository = $serviceVendorRepository;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->serviceVendorRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->serviceVendorRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->serviceVendorRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->serviceVendorRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->serviceVendorRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->serviceVendorRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->serviceVendorRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->serviceVendorRepository->create($filters);
    }
}
