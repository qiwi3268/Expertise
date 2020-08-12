<?php

/*
$testDir = '/var/www/internal';
$files = array_diff(scandir($testDir), ['.', '..']);

$Parser = new \csp\MessageParser();
$Shell = new \csp\InternalSignature();
$Validator = new \csp\Validator($Parser, $Shell);

foreach($files as $file){
    
    $path = $testDir.'/'.$file;
    $validateResult = $Validator->validate($path);
    
    try{
        
        $validateResult = $Validator->validate($path);
    }catch(CSPShellException $e){
        
        // Произошла ошибка при исполнении cmd команды
    }catch(CSPMessageParserException $e){
        
        // code:
        //  1 - Во время выполнения функции произошла ошибка или нет вхождений шаблона в строку
        //  2 - В БД не нашлось имени из ФИО
        //  3 - В одном Signer нашлось больше одного ФИО
    }catch(CSPValidatorException $e){
        
        // code:
        //  1 - получен неизвестный результат проверки подписи / сертификата (подписи)
        //  2 - неизвестный формат блока, следующий за Signer
        //  3 - неизвестная часть сообщения
        //  4 - в частях сообщения отсустсвует(ют) Signer
        //  5 - получено некорректное количество блоков ErrorCode
    }
    
    var_dump($validateResult);
    
    
}*/


$testDir = '/var/www/external';
$files = [['1.pdf', '1.pdf.sig'],
          ['2.pdf', '2.pdf.sig'],
          ['3.pdf', '3.pdf.sig'],
          ['4.pdf', '4.pdf.sig'],
          ['5.pdf', '5.pdf.sig'],
];

$Parser = new \csp\MessageParser();
$Shell = new \csp\ExternalSignature();
$Validator = new \csp\Validator($Parser, $Shell);

foreach($files as $file){
    
    $filePath = $testDir.'/'.$file[0];
    $sigPath = $testDir.'/'.$file[1];
    
    $validateResult = $Validator->validate($filePath, $sigPath);
    
    var_dump($validateResult);
    echo '---------------------------------------------------------------------';
}
