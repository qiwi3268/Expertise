<?php

use Lib\Files\Unloader;

// Ошибки в этом скрипте в максимальной степени нежелательны,
// т.к. человек со своей рабочей страницы будет перенаправлен на эту с ошибкой и все
// несохраненные данные пропадут. Поэтому выводим полный дамп ошибок
//
$administrationMessage = '<h4>Пожалуйста, обратитесь в администрацию системы с текстом или скришотом данной ошибки.</h4>';
$URIMessage = "Ваш запрос: {$_SERVER['REQUEST_URI']}</br>";


if(!checkParamsGET('fs_name', 'file_name')){

    $message = '<h1>ОШИБКА. Нет обязательных параметров GET запроса</h1><br/>';
    $message .= $URIMessage;
    $message .= '<h3>Результаты проверок параметров на существование:</h3><br/>';

    
    $isset = isset($_GET['fs_name']);
    $isset_string = $isset ? 'true' : '<strong>false</strong>';
    $message .= "Существование fs_name = {$isset_string}<br/>";
    if($isset) $message .= "Значение fs_name = {$_GET['fs_name']}<br/>";
    $message .= '<hr/>';

    $isset = isset($_GET['file_name']);
    $isset_string = $isset ? 'true' : '<strong>false</strong>';
    $message .= "Существование file_name = {$isset_string}<br/>";
    if($isset) $message .= "Значение file_name = {$_GET['file_name']}<br/>";
    $message .= '<hr/>';

    $message .= $administrationMessage;
    exit($message);
}

/** @var string $G_fs_name        */
/** @var string $G_file_name      */
extract(clearHtmlArr($_GET), EXTR_PREFIX_ALL, 'G');

if(!file_exists($G_fs_name) || empty($G_file_name)){

    $message = '<h1>ОШИБКА. Файл не существует или передано пустое имя для выгрузки</h1><br/>';
    $message .= $URIMessage;
    $message .= '<h3>Результаты проверок параметров:</h3><br/>';

    $result_string = file_exists($G_fs_name) ? 'true' : '<strong>false</strong>';
    $message .= "Существования файла: {$result_string}<br/>";
    $message .= "Значение G_fs_name = {$G_fs_name}<br/>";
    $message .= '<hr/>';

    $result_string = empty($G_file_name) ? '<strong>true</strong>' : 'false';
    $message .= "Проверка имени файла на пустоту: {$result_string}<br/>";
    $message .= "Значение G_file_name = {$G_file_name}<br/>";
    $message .= '<hr/>';

    $message .= $administrationMessage;
    exit($message);
}

// Выгрузка файла
Unloader::unload($G_fs_name, $G_file_name);