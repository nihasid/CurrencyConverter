<?php

use PHPUnit\Framework\TestCase;
use App\CurrencyConverter;
use App\ExchangeRate;
use App\ExchangeRateCalculator;
use App\OutputFormatter;

class CurrencyConverterTest extends TestCase
{
    private CurrencyConverter $converter;
    private string $outputFormat;
    private $formatType;
    private $fromCurrency;

    protected function setUp(): void
    {
        $exchangeRate = new ExchangeRate();
        $exchangeRate->setExchangeRates([
            'EUR' => 1,
            'USD' => 5,
            'CHF' => 0.97,
            'CNY' => 2.3
        ]);

        $exchangeRateCalculator = new ExchangeRateCalculator();
        $outputFormatter = new OutputFormatter();
        $this->formatType = 'json';
        $this->fromCurrency = 'EUR';
        $this->converter = new CurrencyConverter($exchangeRate, $exchangeRateCalculator, $outputFormatter);
    }

    // a failing test for the convert method
    public function testConvertShouldReturnWithConvertedValues() 
    {   
        $result = $this->converter->convert(10, $this->fromCurrency, ['USD', 'CHF', 'CNY'], $this->formatType);
        $result = (array)json_decode($result, true);
       
        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        $this->assertArrayHasKey('USD', $result[0]);
        $this->assertArrayHasKey('CHF', $result[1]);
        $this->assertArrayHasKey('CNY', $result[2]);
    }
  
    public function testConvertSingleCurrency()
    {
        $amount = 100;
        $targetCurrency = ['USD'];
    
        $result = $this->converter->convert($amount, $this->fromCurrency, $targetCurrency, $this->formatType);
        
        $expectedResult =[
            'json' => '[{"USD":500}]',
            'csv' => "USD,500"
        ];

        $this->assertEquals($expectedResult[$this->formatType], $result);
    }

    public function testConvertMultipleCurrencies()
    {
        $amount = 100;
        $targetCurrencies = [
            'USD',
            'CHF',
            'CNY'
        ];
        
        $result = $this->converter->convert($amount, $this->fromCurrency = 'USD', $targetCurrencies, $this->formatType);
        
        $expectedResult = [
            'json'  => '[{"USD":100},{"CHF":19.4},{"CNY":46}]',
            'csv'   => "USD,100" . PHP_EOL . "CHF,19.4" . PHP_EOL . "CNY,46"
        ];

        $this->assertEquals($expectedResult[$this->formatType], $result);
    }

    public function testConvertWithNegativeValue()
    {
        $amount = -100;
        $targetCurrency = ['USD'];

        $result = $this->converter->convert($amount, $this->fromCurrency, $targetCurrency, $this->formatType);
        $expectedResult = [
            'json'  => '[{"USD":-500}]',
            'csv'   => "USD,-500"
        ];

        $this->assertSame($expectedResult[$this->formatType], $result);
    }

    public function testConvertWithLongNumbers()
    {
        $amount = 1234567890.12345;
        $targetCurrency = ['CHF'];

        $result = $this->converter->convert($amount, $this->fromCurrency, $targetCurrency, $this->formatType='csv');
        $expectedResult = [
            'json'  => '[{"CHF":1197530853.42}]',
            'csv'   => "CHF,1197530853.42"
        ];

        $this->assertEquals($expectedResult[$this->formatType='csv'], $result);
    }

    public function testConvertWithLargeLoop()
    {
        $amount = 100;
        $targetCurrency = ['USD'];

        $startTime = microtime(true);
        
        for ($i = 0; $i < 10000; $i++) {
            $result = $this->converter->convert($amount, $this->fromCurrency, $targetCurrency, $this->formatType);
        }
        $endTime = microtime(true);
        
        $executionTime = $endTime - $startTime;
        
        $this->assertLessThan(1.0, $executionTime); // Ensure the loop executes within 1 second
    }
   
    
}
