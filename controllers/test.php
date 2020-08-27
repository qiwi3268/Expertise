<?php



/*
 * $id = 10;

$test = new \core\Classes\Transaction();

$test->add('ApplicationsTable', 'createTemporary', [1, '9999-9990']);
$test->add('ApplicationsTable', 'createTemporary', [1, '9999-9992']);
$test->add('ApplicationsTable', 'createTemporary', [1, '9999-9992']);
$test->add('ApplicationsTable', 'createTemporary', [5, '9999-9992']);


$results = $test->start()->lastResults;
*/


$Validator = new PrimitiveValidator();


$array =  ['type'       => null,
           'is_changed' => "1",
           'no_data'    => 0,
           'percent'    => 200];


$settings = ['type'       => ['is_int', 'is_null'],
             'is_changed' => ['is_numeric'],
             'percent'    => [[$Validator, 'validatePercent']]
    ];

$Validator->validateAssociativeArray($array, $settings);



class classA{

}

class classB extends classA{
    
    public function testing():self {
        return $this;
    }
}

