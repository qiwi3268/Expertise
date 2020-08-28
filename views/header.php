<?php $variablesTV = \Classes\VariableTransfer::getInstance(); ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?= $variablesTV->getValue('pageName') ?></title>
    <meta charset="utf-8">
    <?php foreach($variablesTV->getValue('sourcesFiles') as $file): ?>
        <?= $file ?>
    <?php endforeach; ?>
</head>
<body>