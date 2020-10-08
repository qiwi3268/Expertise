<!--todo переделать в VT-->
<?php $_VT = \Lib\Singles\VariableTransfer::getInstance(); ?>
<?php $_TEPsByAuthors = $_VT->getValue('TEPsByAuthors'); ?>


<div class="view-section">
    
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
    
    
    
    <!--    <div class="view-table">-->
<!--        -->
<!--        <div class="view-table__header">-->
<!--            <div class="view-table__title">Технико-экономические показатели</div>-->
<!--        </div>-->
<!--        <div class="view-table__body">-->
<!--            <div class="view-table__row">-->
<!--                <div class="view-table__column" style="width: 20%">-->
<!--                    <div class="view-table__item">Тестов Т.Т.</div>-->
<!--                </div>-->
<!--                <div class="view-table__column" style="width: 80%">-->
<!--                    <div class="view-table__row">-->
<!--                        <div class="view-table__column" style="width: 30%">-->
<!--                            <div class="view-table__item">Длина</div>-->
<!--                        </div>-->
<!--                        <div class="view-table__column" style="width: 30%">-->
<!--                            <div class="view-table__item">30 м</div>-->
<!--                        </div>-->
<!--                        <div class="view-table__column" style="width: 40%">-->
<!--                            <div class="view-table__item">Приметил примечание</div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="view-table__row">-->
<!--                        <div class="view-table__column" style="width: 30%">-->
<!--                            <div class="view-table__item">Этажность</div>-->
<!--                        </div>-->
<!--                        <div class="view-table__column" style="width: 30%">-->
<!--                            <div class="view-table__item">10 эт</div>-->
<!--                        </div>-->
<!--                        <div class="view-table__column" style="width: 40%">-->
<!--                            <div class="view-table__item">Приметил примечание_1</div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="view-table__row" >-->
<!--                <div class="view-table__column" style="width: 20%">-->
<!--                    <div class="view-table__item">Иванов Т.Т.</div>-->
<!--                </div>-->
<!--                <div class="view-table__column" style="width: 80%">-->
<!--                    <div class="view-table__row">-->
<!--                        <div class="view-table__column" style="width: 30%">-->
<!--                            <div class="view-table__item">Высота</div>-->
<!--                        </div>-->
<!--                        <div class="view-table__column" style="width: 30%">-->
<!--                            <div class="view-table__item">30 м</div>-->
<!--                        </div>-->
<!--                        <div class="view-table__column" style="width: 40%">-->
<!--                            <div class="view-table__item">примечание</div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    
</div>