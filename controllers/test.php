<?php




$Transaction = new \core\Classes\Transaction();

$Transaction->add('\Classes\Tables\FinancingSource\Type1', 'create', [2,
    1,
    0,
    50]);


$results = $Transaction->start()->lastResults;

var_dump($results);


