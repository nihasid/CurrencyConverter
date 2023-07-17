<?php

use PHPUnit\Framework\TestCase;
use App\ExchangeRate;

class ExchangeRateTest extends TestCase
{
    private $exchangeRate;

    protected function setUp(): void
    {
        $this->exchangeRate = new ExchangeRate();
        $this->exchangeRate->setExchangeRates([
            'EUR' => 1.0,
            'USD' => 5.0,
            'CHF' => 0.97,
            'CNY' => 2.3
        ]);
    }

    public function testGetExchangeRates(): void
    {
        $currencies = ['USD', 'EUR', 'CHF', 'CNY'];
        $expected = [
            'EUR' => 1.0,
            'USD' => 5.0,
            'CHF' => 0.97,
            'CNY' => 2.3
        ];
        $this->assertEquals($expected, $this->exchangeRate->getExchangeRates($currencies));
    }

    public function testGetExchangeRatesWithUnknownCurrency(): void
    {
        $currencies = ['USD', 'JPY'];
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Currency not found: JPY');
        $this->exchangeRate->getExchangeRates($currencies);
    }
}