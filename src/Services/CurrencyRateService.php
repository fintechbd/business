<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\CurrencyRateRepository;

/**
 * Class CurrencyRateService
 */
class CurrencyRateService
{
    /**
     * CurrencyRateService constructor.
     */
    public function __construct(private readonly CurrencyRateRepository $currencyRateRepository)
    {
    }

    /**
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->currencyRateRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->currencyRateRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->currencyRateRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->currencyRateRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->currencyRateRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->currencyRateRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->currencyRateRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->currencyRateRepository->create($filters);
    }

    public function convert(array $inputs)
    {

    }
}
