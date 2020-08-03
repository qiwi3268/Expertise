<!-- view отображения заявления в табличной форме -->
<?php $variablesTV = VariableTransfer::getInstance(); ?>



    <div class="navigation__table table-navigation">
        <div class="table-navigation__header">
            <div class="table-navigation__column">id-заявления</div>
            <div class="table-navigation__column">Номер заявления</div>
        </div>
        <?php foreach ($variablesTV->getValue('navigationData') as $app): ?>
            <div>
                <div>
                    <?= $app['id'] ?>
                </div>
                <div>
                    <a target="_blank" href="/home/application/view?id_application=<?= $app['id'] ?>"><?= $app['numerical_name'] ?></a>

                </div>
            </div>
        <?php endforeach; ?>
    </div>





</div>