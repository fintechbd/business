<?php

namespace Fintech\Business\Services;

use Fintech\Business\Facades\Business;
use Fintech\Business\Interfaces\CurrencyRateRepository;
use Fintech\Core\Supports\Currency;
use Fintech\MetaData\Facades\MetaData;
use InvalidArgumentException;

/**
 * Class CurrencyRateService
 */
class CurrencyRateService
{
    /**
     * CurrencyRateService constructor.
     */
    public function __construct(private readonly CurrencyRateRepository $currencyRateRepository) {}

    public function update($id, array $inputs = [])
    {
        if (isset($inputs['rate'])) {
            <<<JSON
{"rate": 1, "input": "AFN", "markup": "5%", "output": "AFN", "usd_to_input": 71.01, "customer_rate": 0.95, "input_partner": null, "markup_amount": 0.05, "usd_to_output": 71.01, "output_partner": null}
JSON;

        }

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

    /**
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->currencyRateRepository->list($filters);

    }

    public function import(array $filters)
    {
        return $this->currencyRateRepository->create($filters);
    }

    public function create(array $inputs = [])
    {
        return $this->currencyRateRepository->create($inputs);
    }

    public function convert(array $data): float|array
    {
        $inputCountryId = $data['source_country_id'] ?? null;

        $outputCountryId = $data['destination_country_id'] ?? null;

        $serviceId = $data['service_id'] ?? null;

        $onlyRate = $data['get_only_rate'] ?? false;

        if (! $inputCountryId || ! $outputCountryId || ! $serviceId) {
            throw new InvalidArgumentException('Source, destination country or service id value missing or empty');
        }

        $inputCountry = MetaData::country()->find($inputCountryId);

        $outputCountry = MetaData::country()->find($outputCountryId);

        $service = Business::service()->find($serviceId);

        if (! $inputCountry || ! $outputCountry || ! $service) {
            throw new InvalidArgumentException("source, destination country or service doesn't exists");
        }

        if ($service->enabled == false) {
            throw new InvalidArgumentException('This service is disabled');
        }

        $amount = $data['amount'] ?? 1;

        $isReverse = $data['reverse'] ?? false;

        $currencyRate = $this->currencyRateRepository->list([
            'source_country_id' => $inputCountryId,
            'destination_country_id' => $outputCountryId,
            'service_id' => $serviceId,
        ])->first();

        if (! $currencyRate) {
            //throw (new ModelNotFoundException())->setModel(config('fintech.business.currency_rate_model', \Fintech\Business\Models\CurrencyRate::class), []);
            throw new InvalidArgumentException("currency rate doesn't exists");
        }

        $exchangeData['rate'] = round($currencyRate->rate, 5);

        if ($isReverse) {
            $convertedAmount = (float) $amount / (float) $currencyRate->rate;
            $exchangeData['input'] = $outputCountry->currency;
            $exchangeData['output'] = $inputCountry->currency;
            $exchangeData['input_unit'] = currency(1, $exchangeData['output'])->format();
            $exchangeData['output_unit'] = currency($exchangeData['rate'], $exchangeData['input'])->format();
        } else {
            $convertedAmount = (float) $amount * (float) $currencyRate->rate;
            $exchangeData['input'] = $inputCountry->currency;
            $exchangeData['output'] = $outputCountry->currency;
            $exchangeData['input_unit'] = currency(1, $exchangeData['input'])->format();
            $exchangeData['output_unit'] = currency($exchangeData['rate'], $exchangeData['output'])->format();
        }

        $exchangeData['input_symbol'] = Currency::config($exchangeData['input'])['symbol'];
        $exchangeData['output_symbol'] = Currency::config($exchangeData['output'])['symbol'];
        $exchangeData['amount'] = (string) round($amount, Currency::config($exchangeData['input'])['precision']);
        $exchangeData['amount_formatted'] = currency($amount, $exchangeData['input'])->format();
        $exchangeData['converted'] = (string) round($convertedAmount, Currency::config($exchangeData['output'])['precision']);
        $exchangeData['converted_formatted'] = currency($convertedAmount, $exchangeData['output'])->format();

        return ($onlyRate) ? $exchangeData['rate'] : $exchangeData;
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->currencyRateRepository->find($id, $onlyTrashed);
    }
}
