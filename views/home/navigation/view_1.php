<!-- view отображения заявления в табличной форме -->
<?php $variablesTV = VariableTransfer::getInstance(); ?>



    <div class="navigation__table table-navigation">
        <div class="table-navigation__header">
            <div class="table-navigation__title">id-заявления</div>
            <div class="table-navigation__title">Номер заявления</div>
        </div>
        <?php foreach ($variablesTV->getValue('navigationData') as $app): ?>
            <tr>
                <td>
                    <?= $app['id'] ?>
                </td>
                <td>
                    <a target="_blank" href="/home/application/view?id_application=<?= $app['id'] ?>"><?= $app['numerical_name'] ?></a>

                </td>
            </tr>
        <?php endforeach; ?>
    </div>





</div>