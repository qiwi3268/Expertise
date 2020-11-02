<!--todo переделать в VT-->
<?php $_VT = \Lib\Singles\VariableTransfer::getInstance(); ?>
<?php $_TEPsByAuthors = $_VT->getValue('TEPs_by_authors'); ?>
<?php $_comments = $_VT->getValue('comments'); ?>
<?php $_allCommentsDiagram = $_VT->getValue('criticality_all_comments_diagram'); ?>


<div class="view-section">

    <div class="statistic__card full">
        <div class="statistic__panel-header">Замечания по типу</div>
        <div class="panel statistic__panel" data-col_width="50">
            <div class="panel__body">
                <div class="panel__item">
                    <div class="panel__number">7</div>
                    <div class="panel__label">Всего</div>
                </div>
                <div class="panel__item">
                    <div class="panel__number">7</div>
                    <div class="panel__label">Активные</div>
                </div>
                <div class="panel__item">
                    <div class="panel__number">0</div>
                    <div class="panel__label">Снятые</div>
                </div>
                <div class="panel__item">
                    <div class="panel__number">0</div>
                    <div class="panel__label">Рассмотрение экспертом</div>
                </div>
                <div class="panel__item">
                    <div class="panel__number">7</div>
                    <div class="panel__label">Устранение заявителем</div>
                </div>
            </div>
        </div>
    </div>

    <div class="view-section__statistic statistic">
        <div class="statistic__card">
            <div class="statistic__header">Критичность всех замечаний</div>
            <div class="diagram statistic__diagram" data-col_width="30">
                <div class="diagram__body">
                    <div class="diagram__col"
                         data-color="green"
                         style="grid-template-rows: <?= $_allCommentsDiagram['Техническая ошибка']['non_filled'] ?>fr <?= $_allCommentsDiagram['Техническая ошибка']['filled'] ?>fr;">
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col"
                         data-color="blue"
                         style="grid-template-rows: <?= $_allCommentsDiagram['Критическая ошибка']['non_filled'] ?>fr <?= $_allCommentsDiagram['Критическая ошибка']['filled'] ?>fr;">
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col"
                         data-color="red"
                         style="grid-template-rows: <?= $_allCommentsDiagram['Неустранимая ошибка']['non_filled'] ?>fr <?= $_allCommentsDiagram['Неустранимая ошибка']['filled'] ?>fr;">
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                </div>
                <div class="diagram__footer">
                    <div class="diagram__col">
                        <span class="diagram__number"><?= $_allCommentsDiagram['Техническая ошибка']['filled'] ?></span>
                        <span class="diagram__label">Техническая ошибка</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number"><?= $_allCommentsDiagram['Критическая ошибка']['filled'] ?></span>
                        <span class="diagram__label">Критическая ошибка</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number"><?= $_allCommentsDiagram['Неустранимая ошибка']['filled'] ?></span>
                        <span class="diagram__label">Неустранимая ошибка</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="statistic__card">
            <div class="statistic__header">Критичность активных замечаний</div>
            <div class="diagram statistic__diagram" data-col_width="30">
                <div class="diagram__body">
                    <div class="diagram__col"
                         data-color="green"
                         style="grid-template-rows: 5fr 2fr;">
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col"
                         data-color="blue"
                         style="grid-template-rows: 3fr 4fr;">
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col"
                         data-color="red"
                         style="grid-template-rows: 6fr 1fr;">
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
                        <span class="diagram__number">4</span>
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

    <div class="view-section__card card">
        <div class="view-section__header card-expand">
            <div class="view-section__label">
                <i class="view-section__icon-label fas fa-briefcase"></i>
                <div class="view-section__amount"><?= count($_comments) ?></div>
            </div>
            <div class="view-section__title">Замечания на стороне заявителя</div>
        </div>

        <?php if (!empty($_comments)): ?>
            <div class="view-section__body card-body expanded">
                <div class="comments-table">
                    <div class="comments-table__row">
                        <div class="comments-table__column">№</div>
                        <div class="comments-table__column">Автор</div>
                        <div class="comments-table__column">Текст замечания</div>
                        <div class="comments-table__column">Нормативный документ</div>
                        <div class="comments-table__column">Отмеченный файл</div>
                        <div class="comments-table__column">Ответ эксперта</div>
                    </div>
                    <?php foreach ($_comments as $comment): ?>
                        <div class="comments-table__row">
                            <div data-criticality="<?= $comment['comment_criticality']['id'] ?>" class="comments-table__column comments-table__number"><?= $comment['number'] ?></div>
                            <div class="comments-table__column comments-table__author"><?= $comment['author'] ?></div>
                            <div class="comments-table__column"><?= $comment['text'] ?></div>
                            <div class="comments-table__column"><?= $comment['normative_document'] ?></div>
                            <div class="comments-table__column">
                                <?php if (!empty($_file = $comment['file'])): ?>

                                    <div class="comments-table__file files" data-id_file_field data-read_only>
                                        <div class="files__item"
                                             data-id="<?= $_file['id'] ?>"
                                            <?php if (isset($_file['validate_results'])): ?>
                                                data-validate_results='<?= $_file['validate_results'] ?>'
                                            <?php endif; ?>
                                        >
                                            <div class="files__info">
                                                <i class="files__icon fas <?= $_file['file_icon'] ?>"></i>
                                                <div class="files__description">
                                                    <span class="files__name"><?= $_file['file_name'] ?></span>
                                                    <span class="files__size"><?= $_file['human_file_size'] ?></span>
                                                </div>
                                            </div>
                                            <div class="files__state" data-type="short"></div>
                                            <div class="files__actions">
                                                <i class="files__action unload fas fa-angle-double-down" data-file_unload></i>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    Отсутствует
                                <?php endif; ?>
                            </div>
                            <div class="comments-table__column">Ответ</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php foreach ($_VT->getValue('descriptions') as $author => $description): ?>
        <?php if (!empty($description)): ?>
            <div class="view-section__card card">
                <div class="view-section__header card-expand">
                    <div class="view-section__title"><?= $author ?></div>
                </div>
                <div class="view-section__body card-body expanded">
                    <div class="view-section__description"><?= $description ?></div>
                </div>
            </div>
        <?php endif;?>
    <?php endforeach; ?>

    <div class="view-section__card card">
        <div class="view-section__header card-expand">
            <div class="view-section__title">Технико-экономические показатели</div>
        </div>

        <div class="view-section__body card-body expanded">
            <?php if (!empty($_TEPsByAuthors)): ?>
                <div class="tep-table">
                    <div class="tep-table__header">
                        <div class="tep-table__item">Автор</div>
                        <div class="tep-table__item">Показатель</div>
                        <div class="tep-table__item">Значение</div>
                        <div class="tep-table__item">Примечание</div>
                    </div>
                    <div class="tep-table__body">
                        <?php foreach ($_TEPsByAuthors as $author => $TEPs): ?>
                            <div class="tep-table__item tep-table__author"><?= $author ?></div>
                            <div class="tep-table__values">
                                <?php for ($l = 0; $l < count($TEPs); $l++): ?>
                                    <div class="tep-table__item"><?= $TEPs[$l]['indicator'] ?></div>
                                    <div class="tep-table__item"><?= $TEPs[$l]['value'] ?></div>
                                    <div class="tep-table__item"><?= $TEPs[$l]['note'] ?></div>
                                <?php endfor; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>