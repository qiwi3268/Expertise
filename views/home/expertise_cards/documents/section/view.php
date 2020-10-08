<!--todo переделать в VT-->
<?php $_VT = \Lib\Singles\VariableTransfer::getInstance(); ?>
<?php $_TEPsByAuthors = $_VT->getValue('TEPsByAuthors'); ?>


<div class="view-section">
    
    <div class="view-section__description">
        <?php foreach ($_VT->getValue('descriptions') as $author => $description): ?>
            <?php echo $description ?>
        <?php endforeach; ?>
    </div>
    
    <div class="view-section__tep">
        <?php if (!empty($_TEPsByAuthors)): ?>
            <table class="tep-table">
                <thead>
                <tr>
                    <th colspan="4">Технико-экономические показатели</th>
                </tr>
                <tr>
                    <th class="tep-table__author">Автор</th>
                    <th>Показатель</th>
                    <th>Значение</th>
                    <th>Примечание</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($_TEPsByAuthors as $author => $TEPs): ?>
                    <tr>
                        <td class="tep-table__author" rowspan="<?= count($TEPs) ?>"><?= $author ?></td>
                        <td><?= $TEPs[0]['indicator'] ?></td>
                        <td><?= $TEPs[0]['value'] ?></td>
                        <td><?= $TEPs[0]['note'] ?></td>
                    </tr>
                    <?php for ($l = 1; $l < count($TEPs); $l++): ?>
                        <tr>
                            <td><?= $TEPs[$l]['indicator'] ?></td>
                            <td><?= $TEPs[$l]['value'] ?></td>
                            <td><?= $TEPs[$l]['note'] ?></td>
                        </tr>
                    <?php endfor; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    

    
    
</div>