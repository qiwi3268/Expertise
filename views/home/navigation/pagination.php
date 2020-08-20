
<?php $variablesTV = VariableTransfer::getInstance(); ?>

<?php if ($variablesTV->getExistenceFlag('pagination_PreviousPage')): ?>
    <a href="<?= $variablesTV->getValue('pagination_PreviousPageRef') ?>">Назад</a>
<?php endif; ?>

<?= $variablesTV->getValue('pagination_CurrentPage') ?>

<?php if ($variablesTV->getExistenceFlag('pagination_NextPage')): ?>
    <a href="<?= $variablesTV->getValue('pagination_NextPageRef') ?>">Вперед</a>
<?php endif; ?>

<?php

