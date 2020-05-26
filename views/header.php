<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?= $pageName ?></title>
    <meta charset="utf-8">
    <?php foreach($sourcesFiles as $file): ?>
        <?= $file ?>
    <?php endforeach; ?>
</head>
<body>