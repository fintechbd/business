<?php

namespace Fintech\Business\Services;

use Fintech\Business\Facades\Business;
use Fintech\Business\Interfaces\CurrencyRateRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function convert(array $data): float|array
    {
        $inputCountryId = $data['source_country_id'] ?? null;

        $outputCountryId = $data['destination_country_id'] ?? null;

        $serviceId = $data['service_id'] ?? null;

        $onlyRate = $data['get_only_rate'] ?? false;

        if (! $inputCountryId || ! $outputCountryId || ! $serviceId) {
            throw new \InvalidArgumentException('Source, destination country or service id value missing or empty');
        }

        $inputCountry = \Fintech\MetaData\Facades\MetaData::country()->find($inputCountryId);

        $outputCountry = \Fintech\MetaData\Facades\MetaData::country()->find($outputCountryId);

        $service = Business::service()->find($serviceId);

        if (! $inputCountry || ! $outputCountry || ! $service) {
            throw new \InvalidArgumentException("source, destination country or service doesn't exists");
        }

        $amount = $data['amount'] ?? 1;

        $isReverse = $data['reverse'] ?? false;

        $currencyRate = $this->currencyRateRepository->list([
            'source_country_id' => $inputCountryId,
            'destination_country_id' => $outputCountryId,
            'service_id' => $serviceId,
        ])->first();

        if (! $currencyRate) {
            throw (new ModelNotFoundException())->setModel(config('fintech.business.currency_rate_model', \Fintech\Business\Models\CurrencyRate::class), $data);
        }

        if ($isReverse) {
            $convertedAmount = (float) $amount / (float) $currencyRate->rate;
        } else {
            $convertedAmount = (float) $amount * (float) $currencyRate->rate;
        }

        $exchangeData['input'] = ($isReverse) ? $outputCountry->currency : $inputCountry->currency;
        $exchangeData['output'] = ($isReverse) ? $inputCountry->currency : $outputCountry->currency;
        $exchangeData['rate'] = round($currencyRate->rate, 6);
        $exchangeData['amount'] = $amount;
        $exchangeData['amount_formatted'] = currency()->parse($amount, $inputCountry->currency)->format();
        $exchangeData['converted'] = $convertedAmount;
        $exchangeData['converted_formatted'] = currency()->parse($convertedAmount, $outputCountry->currency)->format();

        return ($onlyRate) ? $exchangeData['rate'] : $exchangeData;
    }
}
