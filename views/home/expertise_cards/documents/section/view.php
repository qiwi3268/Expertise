<!--todo переделать в VT-->
<?php $_VT = \Lib\Singles\VariableTransfer::getInstance(); ?>
<?php $_TEPsByAuthors = $_VT->getValue('TEPsByAuthors'); ?>


<div class="view-section">
    
    <div class="view-section__statistics statistics">
        
        <div class="statistics__card">
            <div class="statistics__header">Критичность всех замечаний</div>
            <div class="diagram statistics__diagram" data-col_width="50">
                <div class="diagram__body">
                    <div class="diagram__col" data-color="green">
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col" data-color="blue">
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col" data-color="red">
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                </div>
                <div class="diagram__footer">
                    <div class="diagram__col">
                        <span class="diagram__number">4</span>
                        <span class="diagram__label">Техническая ошибка</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number">6</span>
                        <span class="diagram__label">Критическая ошибка</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number">1</span>
                        <span class="diagram__label">Неустранимая ошибка</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="statistics__card">
            <div class="statistics__header">Критичность активных замечаний</div>
            <div class="diagram statistics__diagram" data-col_width="50">
                <div class="diagram__body">
                    <div class="diagram__col" data-color="green">
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col" data-color="blue">
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col" data-color="red">
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                </div>
                <div class="diagram__footer">
                    <div class="diagram__col">
                        <span class="diagram__number">2</span>
                        <span class="diagram__label">Техническая ошибка</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number">5</span>
                        <span class="diagram__label">Критическая ошибка</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number">1</span>
                        <span class="diagram__label">Неустранимая ошибка</span>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="view-section__card">
        <div class="view-section__body">
        
        </div>
    </div>
    
    <?php foreach ($_VT->getValue('descriptions') as $author => $description): ?>
        <?php if (!empty($description)): ?>
            <div class="view-section__card">
                <div class="view-section__header"><?= $author ?></div>
                <div class="view-section__body">
                    <div class="view-section__description"><?= $description ?></div>
                </div>
            </div>
        <?php endif;?>
    <?php endforeach; ?>
    <div class="view-section__card">
        <div class="view-section__header">Технико-экономические показатели</div>
        <div class="view-section__body">
            <?php if (!empty($_TEPsByAuthors)): ?>
                <table class="tep-table">
                    <thead>
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
    
    

    
    
</div>