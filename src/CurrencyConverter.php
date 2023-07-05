<?php
interface CurrencyConverterInterface
{
    public function convert(float $amount, array $currencies, string $outputFormat): string;
}

class CurrencyConverter implements CurrencyConverterInterface
{
    private string $baseCurrency;
    private array $exchangeRates;

    public function __construct(string $baseCurrency, array $exchangeRates)
    {
        $this->baseCurrency = $baseCurrency;
        $this->exchangeRates = $exchangeRates;
    }


    public function convert(float $amount, array $currencies, string $outputFormat = 'json'): string
    {

        $results = [];
        
        foreach ($currencies as $currency) {
            
            if (!isset($this->exchangeRates[$currency->getCode()])) {
                throw new Exception("Currency not found: {$currency->getCode()}");
            }

            if ($currency->getCode() === $this->baseCurrency) {
                $convertedAmount = $amount;
            } else {
                $rate = $this->exchangeRates[$currency->getCode()];
                $convertedAmount = $amount * $rate;
            }
    
            $formattedAmount = $this->formatCurrency($convertedAmount, $currency->getCode());
            // $results[$currency->getCode()] = $formattedAmount;
            $results[] = [
                'currency' => $currency->getCode(),
                'amount' => $formattedAmount,
            ];
        }

        $dataSourcesArray = ['json', 'csv']; // Set to 'csv' for CSV format

         if (in_array($outputFormat, $dataSourcesArray)) {
        if ($outputFormat === 'json') {
            return json_encode($results);
        } elseif ($outputFormat === 'csv') {
            $csv = [];
            foreach ($results as $result) {
                $csv[] = implode(',', $result);
            }
            return implode(PHP_EOL, $csv);
        }
    }
        

        throw new Exception("Invalid output format specified.");
    

    }

    public function getExchangeRate(array $currencies): array
    {
        
        foreach ($currencies as $currency) {
            
            if (isset($this->exchangeRates[$currency])) {
                $exchangeRates[$currency] = $this->exchangeRates[$currency];
            } else {
                // $exchangeRates[$currency] = 'N/A'; // or handle as per your requirement
                throw new Exception("Currency not found: $currency");
            }
        }

        return $exchangeRates;
    }

    private function formatCurrency(float $amount, string $currency): string
    {
        return number_format($amount, 2, '.', '') . ' ' . $currency;
    }
}
