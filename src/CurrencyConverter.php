<?php
namespace App;

use Exception;

interface CurrencyConverterInterface
{
    public function convert(float $amount, string $fromCurrency, array $toCurrencies, string $outputFormat): string;
}

class CurrencyConverter implements CurrencyConverterInterface
{
    private ExchangeRate $exchangeRate;
    private ExchangeRateCalculator $exchangeRateCalculator;
    private OutputFormatter $outputFormatter;

    public function __construct( ExchangeRate $exchangeRate, ExchangeRateCalculator $exchangeRateCalculator, OutputFormatter $outputFormatter)
    {
    
        $this->exchangeRateCalculator = $exchangeRateCalculator;
        $this->exchangeRate = $exchangeRate;
        $this->outputFormatter = $outputFormatter;
    }

    public function convert(float $amount, string $fromCurrency, array $toCurrencies, string $outputFormat): string
    {
        $allExchangeRates = $this->exchangeRate->getExchangeRates();
        $results = $this->calculateConversion($amount, $fromCurrency, $toCurrencies, $allExchangeRates);
       
        return $this->formatResults($results, $outputFormat);

    }

    private function calculateConversion(float $amount, string $fromCurrency, array $toCurrencies, array $exchangeRates): array
    {
        $results = [];
        if(count($toCurrencies) > 0) {
            foreach ($toCurrencies as $toCurrency) {
                $convertedAmount = $this->exchangeRateCalculator->calculateConvertedAmount($amount, $fromCurrency, $toCurrency, $exchangeRates);
                $results[] = [
                    $toCurrency => $convertedAmount
                ];
            }
        }
        
        return $results;
    }

    public function formatResults(array $results, string $outputFormat): string
    {
        return $this->outputFormatter->format($results, $outputFormat);
    }
    
}
