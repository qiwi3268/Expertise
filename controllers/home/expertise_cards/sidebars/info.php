<?php


use Lib\Responsible\Responsible;
use Lib\Singles\VariableTransfer;


$VT = VariableTransfer::getInstance();

$responsible = new Responsible(CURRENT_DOCUMENT_ID, CURRENT_DOCUMENT_TYPE);

// cr - current responsible
$cr = $responsible->getCurrentResponsible();

if ($cr['type'] != 'type_1') {

    list('type'  => $cr_type, 'users' => $cr_users) = $cr;

    if ($cr_type == 'type_3') {
        $cr_labelTV = 'Сторона заявителя';
    } else {
        $cr_labelTV = 'ОГАУ "Госэкспертиза Челябинской области"';
    }

    $cr_usersTV = [];

    foreach ($cr_users as $user) {
        $cr_usersTV[] = getFIO($user, false);
    }

    $VT->setExistenceFlag('responsible', true);
    $VT->setValue('responsibleLabel', $cr_labelTV);
    $VT->setValue('responsibleUsers', $cr_usersTV);
} else {

    $VT->setExistenceFlag('responsible', false);
}





