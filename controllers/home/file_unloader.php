<?php


// Ошибки в этом скрипте в максимальной степени нежелательны,
// т.к. человек со своей рабочей страницы будет перенаправлен на эту с ошибкой и все
// несохраненные данные пропадут.Поэтому выводим полный дамп ошибок
//
$administrationMessage = '<h4>Пожалуйста, обратитесь в администрацию системы с текстом или скришотом данной ошибки.</h4>';
$URIMessage = 'Ваш запрос: '.$_SERVER['REQUEST_URI'].'</br>';



if(!checkParamsGET(_PROPERTY_IN_APPLICATION['id_application'], 'fs_name', 'file_name')){

    $message = '<h1>ОШИБКА. Нет обязательных параметров GET запроса</h1><br/>';
    $message .= $URIMessage;
    $message .= '<h3>Результаты проверок параметров на существование:</h3><br/>';

    $isset = isset($_GET[_PROPERTY_IN_APPLICATION['id_application']]);
    $isset_string = $isset ? 'true' : '<strong>false</strong>';
    $message .= 'Существование id_application = '.$isset_string.'<br/>';
    if($isset) $message .= 'Значение id_application = '.$_GET['id_application'].'<br/>';
    $message .= '<hr/>';

    $isset = isset($_GET['fs_name']);
    $isset_string = $isset ? 'true' : '<strong>false</strong>';
    $message .= 'Существование fs_name = '.$isset_string.'<br/>';
    if($isset) $message .= 'Значение fs_name = '.$_GET['fs_name'].'<br/>';
    $message .= '<hr/>';

    $isset = isset($_GET['file_name']);
    $isset_string = $isset ? 'true' : '<strong>false</strong>';
    $message .= 'Существование file_name = '.$isset_string.'<br/>';
    if($isset) $message .= 'Значение file_name = '.$_GET['file_name'].'<br/>';
    $message .= '<hr/>';

    $message .= $administrationMessage;
    exit($message);
}

/** @var string $G_id_application */
/** @var string $G_fs_name        */
/** @var string $G_file_name      */
extract(clearHtmlArr($_GET), EXTR_PREFIX_ALL, 'G');

$fsName = _APPLICATIONS_FILES_.'/'.$G_id_application.'/'.$G_fs_name;

if(!file_exists($fsName) || empty($G_file_name)){

    $message = '<h1>ОШИБКА. Файл не существует или передано пустое имя для выгрузки</h1><br/>';
    $message .= $URIMessage;
    $message .= '<h3>Результаты проверок параметров:</h3><br/>';

    $result_string = file_exists($fsName) ? 'true' : '<strong>false</strong>';
    $message .= 'Существования файла: '.$result_string.'<br/>';
    $message .= 'Значение fsName = '.$fsName.'<br/>';
    $message .= '<hr/>';

    $result_string = empty($G_file_name) ? '<strong>true</strong>' : 'false';
    $message .= 'Проверка имени файла на пустоту: '.$result_string.'<br/>';
    $message .= 'Значение G_file_name = '.$G_file_name.'<br/>';
    $message .= '<hr/>';

    $message .= $administrationMessage;
    exit($message);
}

// Выгрузка файла
FilesUnload::unload($fsName, $G_file_name);




