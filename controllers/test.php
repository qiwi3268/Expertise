<?php







$validator = new PrimitiveValidator();
$arr = ["1", "2", "5", "3"];

$json = json_encode($arr);


$arr = $validator->getValidatedArrayFromNumericalJson($json, true);
var_dump($arr);

$date = '31.06.2020';
$validator->validateStringDate($date);