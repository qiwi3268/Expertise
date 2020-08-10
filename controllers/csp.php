<?php


$testDir = '/var/www/internal';
$files = array_diff(scandir($testDir), ['.', '..']);

$CSP = new CSP();

foreach($files as $file){
    
    $path = $testDir.'/'.$file;
    
    //echo '--------------------------------------------';
    
    //var_dump($path);
    $CSP->validateInternal($path);
    
}



