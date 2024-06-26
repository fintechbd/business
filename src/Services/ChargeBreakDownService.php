<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\ChargeBreakDownRepository;

/**
 * Class ChargeBreakDownService
 */
class ChargeBreakDownService
{
    /**
     * ChargeBreakDownService constructor.
     */
    public function __construct(private readonly ChargeBreakDownRepository $chargeBreakDownRepository) {}

    public function find($id, $onlyTrashed = false)
    {
        return $this->chargeBreakDownRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->chargeBreakDownRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->chargeBreakDownRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->chargeBreakDownRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->chargeBreakDownRepository->list($filters);
    }

    /**
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->chargeBreakDownRepository->list($filters);

    }

    public function import(array $filters)
    {
        return $this->chargeBreakDownRepository->create($filters);
    }

    public function create(array $inputs = [])
    {
        return $this->chargeBreakDownRepository->create($inputs);
    }
}
