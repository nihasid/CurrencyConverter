<?php
namespace App;

use Exception;

interface OutputFormatterInterface
{
    public function format(array $results, string $outputFormat): string;
}

class OutputFormatter implements OutputFormatterInterface
{
    public function format(array $results, string $outputFormat): string
    {
        if ($outputFormat === 'json') {
            return json_encode($results);
        } elseif ($outputFormat === 'csv') {
            $csv = [];
            
            foreach ($results as $result) {
                foreach ($result as $currency => $amount) {
                    $csv[] = $currency . ',' . $amount;
                }
            }
          
            return implode(PHP_EOL, $csv);
        } else {
            throw new Exception("Invalid output format specified: $outputFormat");
        }
    }
}
