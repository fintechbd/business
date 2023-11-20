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

    public function convert(array $input)
    {
        // Get currencies involved
        $from = $input['from'] ?: session('default_currency');  //SGD
        $to = $input['to'] ?: session('default_currency'); //BDT

        // Get exchange rates
        $fromData['currency_currency'] = $from; //SGD
        $fromData['service_id'] = $input['service_id']; //MOBILE 1
        $fromData['default_country_id'] = isset($input['default_country_id'])?$input['default_country_id']:session('default_country_id'); // SGP 199

//        'currency' => sgd, service = 1, source_id = 199, dest = 19
        $from_rate = $this->currencyRateService->getCurrencyRate($fromData);

        //dump("From Rate", $from_rate);

        //'currency' => bdt, service = 1, source_id = 199
        $toData['currency_currency'] = $to;
        $toData['service_id'] = $input['service_id'];
        $toData['default_country_id'] = isset($input['default_country_id'])?$input['default_country_id']:session('default_country_id');
        $to_rate = $this->currencyRateService->getCurrencyRate($toData);

        //dump("To Rate", $to_rate);
        // Skip invalid to currency rates
        if ($to_rate === null) {
            //return null;
            $to_rate = 1;
        }

        try {
            // Convert amount
            if ($from === $to) {
                $value = $input['amount'];
            } else {
                $value = ($input['amount'] * $to_rate) / $from_rate;
            }
            //\Log::info("Input: " .$input['amount'] . ", To Rate: " . $to_rate . ", From Rate: "  . $from_rate . " Output: " . $value);
        } catch (\Exception $e) {
            \Log::error('Currency Rate Exception');
            \Log::error($e->getMessage());
            // Prevent invalid conversion or division by zero errors
            return null;
        }

        // Return value
        return $value;
    }
}
