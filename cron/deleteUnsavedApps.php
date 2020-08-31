<?php


//require_once './initialization.php';

file_put_contents(ROOT.'/logs/cron/deleteUnsavedApps.log', 'я работаю'."\n", FILE_APPEND);


//-------------------------------------------------------------

$unsavedApps = \Tables\applications::getAllUnsaved();

if(empty($unsavedApps)){

    $message = ' Отсутствуют несохраненные заявления';

}else{

    $appsIds = [];

    foreach($unsavedApps as $app){
        $appsIds[] = $app['id'];
    }

    \Tables\applications::deleteFromIdsArray($appsIds);

    $deletedCounts = count($appsIds);
    $strIds = implode(',', $appsIds);
    $message = " Количество удаленных записей: $deletedCounts, id: $strIds";
}

file_put_contents(ROOT.'/logs/cron/deleteUnsavedApps.log', date('d.m.Y H:i:s').$message."\n", FILE_APPEND);
