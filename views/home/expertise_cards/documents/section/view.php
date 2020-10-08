<!--todo переделать в VT-->
<?php $_VT = \Lib\Singles\VariableTransfer::getInstance(); ?>


<div class="view-section">
    
    <table class="tep-table">
        <thead>
            <tr>
                <th colspan="4">Технико-экономические показатели</th>
            </tr>
            <tr>
                <th style="width: 15%;">Автор</th>
                <th>Показатель</th>
                <th>Значение</th>
                <th>Примечание</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_VT->getValue('TEPs') as $author => $TEPs): ?>
                <tr>
                    <td rowspan="<?= count($TEPs) ?>"><?= $author ?></td>
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
    
    
<!--    <table class="tep-table">-->
<!--        <thead>-->
<!--            <tr>-->
<!--                <th colspan="4">Источники финансирования</th>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <th style="width: 15%;">Автор</th>-->
<!--                <th>Показатель</th>-->
<!--                <th>Значение</th>-->
<!--                <th>Примечание</th>-->
<!--            </tr>-->
<!--        </thead>-->
<!--        <tbody>-->
<!--        -->
<!--            <tr>-->
<!--                <td rowspan="2">Иванов Т.Т.</td>-->
<!--                <td>Высота</td>-->
<!--                <td>30 м</td>-->
<!--                <td>примечание</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>Этажность</td>-->
<!--                <td>10 эт</td>-->
<!--                <td>примечание</td>-->
<!--            </tr>-->
<!---->
<!--            <tr>-->
<!--                <td rowspan="3">Иванов Т.Т.</td>-->
<!--                <td>Высота</td>-->
<!--                <td>30 м</td>-->
<!--                <td>примечание</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>Этажность</td>-->
<!--                <td>10 эт</td>-->
<!--                <td>примечание</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>Этажность</td>-->
<!--                <td>10 эт</td>-->
<!--                <td>примечание</td>-->
<!--            </tr>-->
<!--        </tbody>-->
<!--    </table>-->

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