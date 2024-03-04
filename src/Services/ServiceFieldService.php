<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\ServiceFieldRepository;

/**
 * Class ServiceFieldService
 */
class ServiceFieldService
{
    /**
     * ServiceFieldService constructor.
     */
    public function __construct(private readonly ServiceFieldRepository $serviceFieldRepository)
    {
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->serviceFieldRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->serviceFieldRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->serviceFieldRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->serviceFieldRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->serviceFieldRepository->list($filters);
    }

    /**
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->serviceFieldRepository->list($filters);

    }

    public function import(array $filters)
    {
        return $this->serviceFieldRepository->create($filters);
    }

    public function create(array $inputs = [])
    {
        return $this->serviceFieldRepository->create($inputs);
    }
}
