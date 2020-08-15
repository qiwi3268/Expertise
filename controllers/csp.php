<?php

<<<<<<< HEAD

$testDir = '/var/www/internal';
$files = array_diff(scandir($testDir), ['.', '..']);

$CSP = new CSP();

foreach($files as $file){
    
    $path = $testDir.'/'.$file;
    
    //echo '--------------------------------------------';
    
    //var_dump($path);
    //$CSP->validateInternal($path);
    
}

var_dump(shell_exec('lalal 2>&1'));
=======
/*
$testDir = '/var/www/internal';
$files = array_diff(scandir($testDir), ['.', '..']);

$Parser = new \csp\MessageParser();
$Shell = new \csp\InternalSignature();
$Validator = new \csp\Validator($Parser, $Shell);

foreach($files as $File){
    
    $path = $testDir.'/'.$File;
    $validateResult = $Validator->validate($path);
    
    try{
        
        $validateResult = $Validator->validate($path);
    }catch(ShellException $e){
        
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
    echo '------------------------------------------------------------------------------------------';
    
}*/

/*
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
}*/

// Существующие алгоритмы подписи
define('sign_algorithms', ['1.2.643.2.2.19'    => '1.2.643.2.2.19',     // Алгоритм ГОСТ Р 34.10-2001, используемый при экспорте/импорте ключей
                           '1.2.643.7.1.1.1.1' => '1.2.643.7.1.1.1.1', // Алгоритм ГОСТ Р 34.10-2012 для ключей длины 256 бит, используемый при экспорте/импорте ключей
                           '1.2.643.7.1.1.1.2' => '1.2.643.7.1.1.1.2', // Алгоритм ГОСТ Р 34.10-2012 для ключей длины 512 бит, используемый при экспорте/импорте ключей
]);

// Существующие алгоритмы хэширования
// Соответсвие алгоритмов хэширования к алгоритмам подписи
define('hash_algorithms', [sign_algorithms['1.2.643.2.2.19']    => '1.2.643.2.2.9',     // Функция хэширования ГОСТ Р 34.11-94
                           sign_algorithms['1.2.643.7.1.1.1.1'] => '1.2.643.7.1.1.2.2', // Функция хэширования ГОСТ Р 34.11-2012, длина выхода 256 бит
                           sign_algorithms['1.2.643.7.1.1.1.2'] => '1.2.643.7.1.1.2.3'  // Функция хэширования ГОСТ Р 34.11-2012, длина выхода 512 бит
]);


// todo проверка, что пришедший sign алгоритм существует в sign_algorithms

// Директория хранения временных файлов base64
$base64_dir = '/var/www/hash/tmp_base64';
// Директория хранения полученных хэшей
$hash_dir = '/var/www/hash/tmp_hash';

// Путь к исходному файлу
$filePath = '/var/www/tmp_test/hash.pdf';

//todo удалить лишние заголовки
$data = file_get_contents($filePath);

if($data === false){
    throw new Exception();
}

$base64_data = base64_encode($data);
$base64_fileName = null;
// Формирование имени для временного файла

do{
    
    $hash = bin2hex(random_bytes(10)); // Длина 20 символов
    if(!file_exists("{$base64_dir}/{$hash}")) $base64_fileName = $hash;
    
}while(!$base64_fileName);

$base64_filePath = "{$base64_dir}/{$base64_fileName}";


if(file_put_contents($base64_filePath, $base64_data) === false){
    throw new Exception("Произошла ошибка при создании файла: '{$base64_filePath}'");
}

// Получаем алгоритм хэширования на основе алгоритма подписи
$signAlgorithm = '1.2.643.7.1.1.1.1'; //todo  Заглушка алгоритма подписи
$hashAlgorithm = hash_algorithms[$signAlgorithm];

$cmd = sprintf('/opt/cprocsp/bin/amd64/cryptcp -hash -dir "%s" -provtype 80 -hashAlg "%s" "%s" 2>&1', $hash_dir, $hashAlgorithm, $base64_filePath);

$message = shell_exec($cmd);

var_dump($cmd);
var_dump($message);

//todo проверка результата выполнения

// Путь к созданному файлу хэша
$hash_filePath = "{$hash_dir}/{$base64_fileName}.hsh";


$hash_data = file_get_contents($hash_filePath);

if($hash_data === false){
    throw new Exception("Произошла ошибка при чтании файла: '{$filePath}'");
}


var_dump($hash_data);
>>>>>>> 5b015d9495abdca6a19a460370085b00167fbfbb
