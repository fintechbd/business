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

    /**
     * Check if this slot is available
     *
     * @param string|float|int $lower
     * @param string|float|int $higher
     * @return bool
     */
    public function available(string|float|int $lower, string|float|int $higher) : bool
    {
        return $this->chargeBreakDownRepository->available($lower, $higher);
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
