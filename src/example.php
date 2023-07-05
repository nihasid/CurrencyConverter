<?php 
require 'Currency.php';
require 'CurrencyConverter.php';

// Example usage
$baseCurrency = 'USD';
$exchangeRates = [
    'EUR' => 1,
    'USD' => 5,
    'CHF' => 0.97,
    'CNY' => 2.3
];

$currencyConverter = new CurrencyConverter($baseCurrency, $exchangeRates);

$usdCurrency = new Currency('USD', 'US Dollar');
$eurCurrency = new Currency('EUR', 'Euro');
$chfCurrency = new Currency('CHF', 'Swiss Franc');
$cnyCurrency = new Currency('CNY', 'Chinese Yuan');

$currencies = [$usdCurrency, $eurCurrency, $chfCurrency, $cnyCurrency];
$amount = 100;
$outputFormat = 'csv'; // Set to 'csv' for CSV format

$results = $currencyConverter->convert($amount, $currencies, $outputFormat);

echo $results;






