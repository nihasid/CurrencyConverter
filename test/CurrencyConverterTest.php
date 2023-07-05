<?php

use PHPUnit\Framework\TestCase;

require_once 'src/CurrencyConverter.php';
require_once 'src/Currency.php';

class CurrencyConverterTest extends TestCase
{
    private CurrencyConverter $converter;
    private string $outputFormat;

    protected function setUp(): void
    {
        $baseCurrency = 'EUR';
        $exchangeRates = [
            'EUR' => 1,
            'USD' => 5,
            'CHF' => 0.97,
            'CNY' => 2.3
        ];
        $this->outputFormat = 'csv'; // Set to 'csv' for CSV format
        $this->converter = new CurrencyConverter($baseCurrency, $exchangeRates);
    }

    public function testConvertSingleCurrency()
    {
        $amount = 100;
        $targetCurrency = [new Currency('USD', 'US Dollar')];
        
        $result = $this->converter->convert($amount, $targetCurrency, $this->outputFormat);
        $expectedResult =[
            'json' => '[{"currency":"USD","amount":"500.00 USD"}]',
            'csv' => "USD,500.00 USD"
        ];

        $this->assertEquals($expectedResult[$this->outputFormat], $result);
    }

    public function testConvertMultipleCurrencies()
    {
        $amount = 100;
        $targetCurrencies = [
            new Currency('USD', 'US Dollar'),
            new Currency('CHF', 'Swiss Franc'),
            new Currency('CNY', 'Chinese Yuan')
        ];
        
        $result = $this->converter->convert($amount, $targetCurrencies, $this->outputFormat);
        
        $expectedResult = [
            'json'  => '[{"currency":"USD","amount":"500.00 USD"},{"currency":"CHF","amount":"97.00 CHF"},{"currency":"CNY","amount":"230.00 CNY"}]',
            'csv'   => "USD,500.00 USD" . PHP_EOL . "CHF,97.00 CHF" . PHP_EOL . "CNY,230.00 CNY"
        ];

        $this->assertEquals($expectedResult[$this->outputFormat], $result);
    }

    public function testConvertWithNegativeValue()
    {
        $amount = -100;
        $targetCurrency = [new Currency('USD', 'US Dollar')];

        $result = $this->converter->convert($amount, $targetCurrency, $this->outputFormat);
        $expectedResult = [
            'json'  => '[{"currency":"USD","amount":"-500.00 USD"}]',
            'csv'   => "USD,-500.00 USD"
        ];

        $this->assertEquals($expectedResult[$this->outputFormat], $result);
    }

    public function testConvertWithLongNumbers()
    {
        $amount = 1234567890.12345;
        $targetCurrency = [new Currency('CHF', 'Swiss Franc')];

        $result = $this->converter->convert($amount, $targetCurrency, $this->outputFormat);
        $expectedResult = [
            'json'  => '[{"currency":"CHF","amount":"1197530853.42 CHF"}]',
            'csv'   => "CHF,1197530853.42 CHF"
        ];

        $this->assertEquals($expectedResult[$this->outputFormat], $result);
    }

    public function testConvertWithLargeLoop()
    {
        $amount = 100;
        $targetCurrency = [new Currency('USD', 'US Dollar')];

        $startTime = microtime(true);
        
        for ($i = 0; $i < 10000; $i++) {
            $result = $this->converter->convert($amount, $targetCurrency, $this->outputFormat);
        }
        $endTime = microtime(true);
        
        $executionTime = $endTime - $startTime;
        
        $this->assertLessThan(1.0, $executionTime); // Ensure the loop executes within 1 second
    }
    
    
}
