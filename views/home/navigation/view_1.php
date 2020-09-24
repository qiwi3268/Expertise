<!-- view отображения заявления в табличной форме -->
<?php $variablesTV = \Lib\Singles\VariableTransfer::getInstance(); ?>

<div class="table-navigation__body">
    <div class="table-navigation__row table-navigation__header">
        <div class="table-navigation__column table-navigation__title">
            <span class="table-navigation__text">id-заявления</span>
        </div>
        <div class="table-navigation__column table-navigation__title">
            <span class="table-navigation__text">Номер заявления</span>
        </div>
    </div>
    <?php if ($variablesTV->getExistenceFlag('navigationData')): ?>
        <?php foreach ($variablesTV->getValue('navigationData') as $app): ?>
            <div class="table-navigation__row">
                <div class="table-navigation__column"><?= $app['id'] ?></div>
                <a class="table-navigation__column" target="_blank" href="/home/expertise_cards/application/view?id_document=<?= $app['id'] ?>"><?= $app['numerical_name'] ?></a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>