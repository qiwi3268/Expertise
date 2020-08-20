<?php


// Единая точка входа для cron


// Включения вывода ошибок и предупреждений
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '/var/www/html/core/Classes/StartingInitialization.php';
$ini = new StartingInitialization('/var/www/html');
$ini->requireDefinedVariables();
$ini->enableClassAutoloading();
$ini->requireDataBasePack();

require_once _ROOT_.'/Classes/Logger.php';

// Директория логов действий и логов ошибок
$logsDir = _LOGS_.'/cron';
$logsErrorsDir = _LOGS_.'/cron/errors';
$logFileName = 'index.log';

$Logger = new Logger($logsDir, $logFileName);
$ErrorLogger = new Logger($logsErrorsDir, $logFileName);

function FlushLogger(Logger $Logger){
    return function($buffer) use ($Logger){
        if(!empty($buffer)) $Logger->write('СООБЩЕНИЕ ИЗ БУФЕРА ВЫВОДА:'.PHP_EOL.$buffer);
    };
}
ob_start(FlushLogger($ErrorLogger));


if($argc > 2){
    $tmp = $argv;
    unset($tmp[0]);
    $tmp = implode(', ', $tmp);
    $ErrorLogger->write("ОШИБКА. Передано более 1 параметра: '{$tmp}'");
    exit;
}

$cron_fileName = $argv[1];
$cron_path = __DIR__."/{$cron_fileName}";

if(!file_exists($cron_path)){
    $ErrorLogger->write("ОШИБКА. Указанный cron: '{$argv[1]}', по пути: '{$cron_path}' не существует");
    exit;
}

$cron_logFileName = str_replace('.php', '.log', $cron_fileName);

// Абсолютные пути в фс сервера к файлам логов действий и логов ошибок cron'а
$cron_logsPath = $logsDir."/{$cron_logFileName}";
$cron_logsErrorsPath = $logsErrorsDir."/{$cron_logFileName}";


if(!file_exists($cron_logsPath)){
    $ErrorLogger->write("ОШИБКА. Не существует лог-файл действий по пути: '{$cron_logsPath}'");
    exit;
}elseif(!is_writable($cron_logsPath)){
    $ErrorLogger->write("ОШИБКА. Лог-файл действий по пути: '{$cron_logsPath}', недоступен для записи");
    exit;
}

if(!file_exists($cron_logsErrorsPath)){
    $ErrorLogger->write("ОШИБКА. Не существует лог-файл ошибок по пути: '{$cron_logsErrorsPath}'");
    exit;
}elseif(!is_writable($cron_logsErrorsPath)){
    $ErrorLogger->write("ОШИБКА. Лог-файл ошибок действий по пути: '{$cron_logsErrorsPath}', недоступен для записи");
    exit;
}

require_once _ROOT_.'/functions/functions.php';
require_once _ROOT_.'/Classes/VariableTransfer.php';



// Logger и ErrorLogger для подключаемого cron'a
$cron_Logger = new Logger($logsDir, $cron_logFileName);
$cron_ErrorLogger = new Logger($logsErrorsDir, $cron_logFileName);

$variablesTV = VariableTransfer::getInstance();
$variablesTV->setValue('Logger', $cron_Logger);
$variablesTV->setValue('ErrorLogger', $cron_ErrorLogger);

DataBase::constructDB('ge');

// Переключаем логирование буферизации на лог-файл ошибок cron'а только после того,
// как были выполнены все основные действия в точке входа
ob_end_clean();
ob_start(FlushLogger($cron_ErrorLogger));

$Logger->write("Запускается: $cron_fileName");

// Удаляем объекты, связанные с единой точкой входа
unset($Logger);
unset($ErrorLogger);

// Подключаем cron
require_once $cron_path;

DataBase::closeDB();