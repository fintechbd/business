<?php

namespace Fintech\Business\Services;


use Fintech\Business\Interfaces\VendorRepository;

/**
 * Class VendorService
 * @package Fintech\Business\Services
 *
 */
class VendorService
{
    /**
     * VendorService constructor.
     * @param VendorRepository $vendorRepository
     */
    public function __construct(VendorRepository $vendorRepository) {
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->vendorRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->vendorRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->vendorRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->vendorRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->vendorRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->vendorRepository->restore($id);
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
