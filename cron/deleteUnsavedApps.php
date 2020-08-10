<?php


//require_once './initialization.php';

file_put_contents(_ROOT_.'/logs/cron/deleteUnsavedApps.log', 'я работаю'."\n", FILE_APPEND);

require_once _ROOT_.'Classes/Tables/ApplicationsTable.php';

//-------------------------------------------------------------

$unsavedApps = ApplicationsTable::getAllUnsaved();

if(empty($unsavedApps)){

    $message = ' Отсутствуют несохраненные заявления';

}else{

    $appsIds = [];

    foreach($unsavedApps as $app){
        $appsIds[] = $app['id'];
    }

    ApplicationsTable::deleteFromIdsArray($appsIds);

    $deletedCounts = count($appsIds);
    $strIds = implode(',', $appsIds);
    $message = " Количество удаленных записей: $deletedCounts, id: $strIds";
}

file_put_contents(_ROOT_.'/logs/cron/deleteUnsavedApps.log', date('d.m.Y H:i:s').$message."\n", FILE_APPEND);
