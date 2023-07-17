<?php
namespace App;

use Exception;

interface ExchangeRateCalculatorInterface
{
    public function calculateConvertedAmount(float $amount, string $fromCurrency, string $toCurrency,  array $exchangeRates): float;
}

class ExchangeRateCalculator implements ExchangeRateCalculatorInterface
{

    public function calculateConvertedAmount(float $amount, string $fromCurrency, string $toCurrency,  array $exchangeRates): float
    {   
        if (!isset($exchangeRates[$fromCurrency])) {
            throw new Exception("Currency not found: {$fromCurrency}");
        }

        if (!isset($exchangeRates[$toCurrency])) {
            throw new Exception("Currency not found: {$toCurrency}");
        }

        if ($fromCurrency === $toCurrency) {
            $result = round($amount , 2);
            return $result;
        } else {
            $fromRate = $exchangeRates[$fromCurrency];
            $toRate = $exchangeRates[$toCurrency];
            $result = round($amount * ($toRate / $fromRate), 2);
    
            return $result;
        }
    }

}

