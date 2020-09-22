<?php


// Единая точка входа для cron


// Включения вывода ошибок и предупреждений
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '/var/www/html/core/defined_variables.php';
require_once ROOT . '/functions/functions.php';

enableAutoloadRegister();

use Lib\Singles\Logger;
use Lib\Singles\VariableTransfer;
use Lib\DataBase\DataBase;


// Директория логов действий и логов ошибок
$logsDir = LOGS . '/cron';
$logsErrorsDir = LOGS . '/cron/errors';
$logFileName = 'index.log';

$logger = new Logger($logsDir, $logFileName);
$errorLogger = new Logger($logsErrorsDir, $logFileName);

function FlushLogger(Logger $logger): callable
{
    return function ($buffer) use ($logger) {
        if (!empty($buffer)) $logger->write('СООБЩЕНИЕ ИЗ БУФЕРА ВЫВОДА:' . PHP_EOL . $buffer);
    };
}

ob_start(FlushLogger($errorLogger));


if ($argc > 2) {
    $tmp = $argv;
    unset($tmp[0]);
    $tmp = implode(', ', $tmp);
    $errorLogger->write("ОШИБКА. Передано более 1 параметра: '{$tmp}'");
    exit;
}

$cron_fileName = $argv[1];
$cron_path = __DIR__ . "/{$cron_fileName}";

if (!file_exists($cron_path)) {
    $errorLogger->write("ОШИБКА. Указанный cron: '{$argv[1]}', по пути: '{$cron_path}' не существует");
    exit;
}

$cron_logFileName = str_replace('.php', '.log', $cron_fileName);

// Абсолютные пути в фс сервера к файлам логов действий и логов ошибок cron'а
$cron_logsPath = $logsDir . "/{$cron_logFileName}";
$cron_logsErrorsPath = $logsErrorsDir . "/{$cron_logFileName}";


if (!file_exists($cron_logsPath)) {
    $errorLogger->write("ОШИБКА. Не существует лог-файл действий по пути: '{$cron_logsPath}'");
    exit;
} elseif (!is_writable($cron_logsPath)) {
    $errorLogger->write("ОШИБКА. Лог-файл действий по пути: '{$cron_logsPath}', недоступен для записи");
    exit;
}

if (!file_exists($cron_logsErrorsPath)) {
    $errorLogger->write("ОШИБКА. Не существует лог-файл ошибок по пути: '{$cron_logsErrorsPath}'");
    exit;
} elseif (!is_writable($cron_logsErrorsPath)) {
    $errorLogger->write("ОШИБКА. Лог-файл ошибок действий по пути: '{$cron_logsErrorsPath}', недоступен для записи");
    exit;
}


// Logger и ErrorLogger для подключаемого cron'a
$cron_Logger = new Logger($logsDir, $cron_logFileName);
$cron_ErrorLogger = new Logger($logsErrorsDir, $cron_logFileName);

$VT = VariableTransfer::getInstance();
$VT->setValue('Logger', $cron_Logger);
$VT->setValue('ErrorLogger', $cron_ErrorLogger);

DataBase::constructDB('ge');

// Переключаем логирование буферизации на лог-файл ошибок cron'а только после того,
// как были выполнены все основные действия в точке входа
ob_end_clean();
ob_start(FlushLogger($cron_ErrorLogger));

$logger->write("Запускается: $cron_fileName");

// Удаляем объекты, связанные с единой точкой входа
unset($logger);
unset($errorLogger);

// Подключаем cron
require_once $cron_path;

DataBase::closeDB();