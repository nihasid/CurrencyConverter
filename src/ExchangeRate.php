<?php
namespace App;

use Exception;

interface ExchangeRateInterface
{
    public function getExchangeRates(array $currencies): array;
}

class ExchangeRate implements ExchangeRateInterface
{
    private $exchangeRates;

    public function setExchangeRates(array $exchangeRates): void
    {
        $this->exchangeRates = $exchangeRates;
    }

    public function getExchangeRates(array $currencies = []): array
    {
        if($currencies == []) {
            return $this->exchangeRates;
        }
        $exchangeRates = [];
        foreach ($currencies as $currency) {
            if (isset($this->exchangeRates[$currency])) {
                $exchangeRates[$currency] = $this->exchangeRates[$currency];
            } else {
                throw new Exception("Currency not found: $currency");
            }
        }

        return $exchangeRates;
    }
}

