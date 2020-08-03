<!-- view отображения заявления в табличной форме -->
<?php $variablesTV = VariableTransfer::getInstance(); ?>

<table border="1">
    <tr>
        <th>id-заявления</th>
        <th>Номер заявления</th>
    </tr>
    <?php foreach ($variablesTV->getValue('navigationData') as $app): ?>
        <tr>
            <td>
                <?= $app['id'] ?>
            </td>
            <td>
                <a  target="_blank" href="/home/application/view?id_application=<?= $app['id'] ?>"><?= $app['numerical_name'] ?></a>
                
            </td>
        </tr>
    <?php endforeach; ?>
</table>