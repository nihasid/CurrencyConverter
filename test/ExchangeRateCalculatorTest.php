<?php

use PHPUnit\Framework\TestCase;
use App\ExchangeRateCalculator;

class ExchangeRateCalculatorTest extends TestCase
{
    public function testCalculateConvertedAmountThrowsCurrencyNotFoundExceptionIfFromCurrencyIsNotFound()
    {
        $exchangeRateCalculator = new ExchangeRateCalculator();

        $this->expectException(Exception::class);
        $exchangeRateCalculator->calculateConvertedAmount(100, 'USD', 'EUR', ['CNY' => 2.3, 'EUR' => 1.0]);
    }

    public function testCalculateConvertedAmountThrowsCurrencyNotFoundExceptionIfToCurrencyIsNotFound()
    {
        $exchangeRateCalculator = new ExchangeRateCalculator();

        $this->expectException(Exception::class);
        $exchangeRateCalculator->calculateConvertedAmount(100, 'CHF', 'EUR', ['CHF' => 0.97, 'USD' => 5]);
    }

    public function testCalculateConvertedAmountReturnsAmountForBaseCurrency()
    {
        $exchangeRateCalculator = new ExchangeRateCalculator();

        $this->assertSame(100.0, $exchangeRateCalculator->calculateConvertedAmount(100, 'USD', 'USD', ['USD' => 5, 'EUR' => 1]));
    }

    public function testCalculateConvertedAmountCalculatesCorrectlyForNonBaseCurrency()
    {
        $exchangeRateCalculator = new ExchangeRateCalculator();

        $this->assertEquals(500, $exchangeRateCalculator->calculateConvertedAmount(100, 'EUR', 'USD', ['USD' => 5.0, 'EUR' => 1.0]));
        $this->assertEquals(230.58, $exchangeRateCalculator->calculateConvertedAmount(100.25, 'EUR', 'CNY', ['CNY' => 2.3, 'USD' => 5.0, 'EUR' => 1.0]));
    }
    
}