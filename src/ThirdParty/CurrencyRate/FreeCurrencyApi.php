<?php

namespace Fintech\Business\ThirdParty\CurrencyRate;

use Exception;
use Fintech\Business\Exceptions\FreeCurrencyApiException;
use Illuminate\Support\Facades\Http;

class FreeCurrencyApi
{
    private mixed $config;

    public function __construct()
    {
        $this->config = config('fintech.business.currency_rate_vendor.free_currency_api', []);
    }

    /**
     * @throws FreeCurrencyApiException
     */
    public function status()
    {
        return $this->call('status');
    }

    /**
     * @return array|mixed
     *
     * @throws FreeCurrencyApiException
     */
    private function call(string $endpoint, ?array $query = []): mixed
    {
        try {
            $response = Http::baseUrl($this->config['base_url'])
                ->withHeaders([
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    'apikey' => $this->config['api_key'],
                ])
                ->get($endpoint, $query);

            return json_decode($response->body(), true);

        } catch (Exception $e) {

            throw new FreeCurrencyApiException($e->getMessage());

            return null;
        }

        return null;
    }

    /**
     * @throws FreeCurrencyApiException
     */
    public function currencies(?array $query = [])
    {
        return $this->call('currencies', $query);
    }

    /**
     * @throws FreeCurrencyApiException
     */
    public function rate(?array $query = [])
    {
        return $this->call('latest', $query);
    }

    /**
     * @throws FreeCurrencyApiException
     */
    public function history($query)
    {
        return $this->call('historical', $query);
    }
}
