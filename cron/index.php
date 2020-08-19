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
$Logger = new Logger(_LOGS_.'/cron/errors', 'index.log');

function FlushLogger(Logger $Logger){
    return function($buffer) use ($Logger){
        if(!empty($buffer)) $Logger->write('СООБЩЕНИЕ ИЗ БУФЕРА ВЫВОДА:'.PHP_EOL.$buffer);
    };
}
$test = ob_start(FlushLogger($Logger));
var_dump($test);


require_once _ROOT_.'/functions/functions.php';
require_once _ROOT_.'/Classes/VariableTransfer.php';




DataBase::constructDB('ge');

//todo подключение всего



DataBase::closeDB();
