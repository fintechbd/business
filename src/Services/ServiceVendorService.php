<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\ServiceVendorRepository;

/**
 * Class ServiceVendorService
 */
class ServiceVendorService
{
    use \Fintech\Core\Traits\HasFindWhereSearch;

    /**
     * ServiceVendorService constructor.
     */
    public function __construct(private readonly ServiceVendorRepository $serviceVendorRepository) {}

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

    /**
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->serviceVendorRepository->list($filters);

    }

    public function import(array $filters)
    {
        return $this->serviceVendorRepository->create($filters);
    }

    public function create(array $inputs = [])
    {
        return $this->serviceVendorRepository->create($inputs);
    }
}
