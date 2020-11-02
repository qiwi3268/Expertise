<?php $VT = \Lib\Singles\VariableTransfer::getInstance(); ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?= $VT->getValue('page_name') ?></title>
    <meta charset="utf-8">
    <?php foreach($VT->getValue('sources_files') as $file): ?>
        <?= $file ?>
    <?php endforeach; ?>
</head>
<body>