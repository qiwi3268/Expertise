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
                    <div class="panel__number">120</div>
                    <div class="panel__label">Всего</div>
                </div>
                <div class="panel__item">
                    <div class="panel__number">40</div>
                    <div class="panel__label">Активные</div>
                </div>
                <div class="panel__item">
                    <div class="panel__number">80</div>
                    <div class="panel__label">Снятые</div>
                </div>
                <div class="panel__item">
                    <div class="panel__number">15</div>
                    <div class="panel__label">Рассмотрение эксперта</div>
                </div>
                <div class="panel__item">
                    <div class="panel__number">25</div>
                    <div class="panel__label">Сторона заявителя</div>
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
                         style="grid-template-rows: 30fr 70fr;">
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col"
                         data-color="blue"
                         style="grid-template-rows: 80fr 20fr;">
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col"
                         data-color="red"
                         style="grid-template-rows: 90fr 10fr;">
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                </div>
                <div class="diagram__footer">
                    <div class="diagram__col">
                        <span class="diagram__number">70</span>
                        <span class="diagram__label">Техническая ошибка</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number">20</span>
                        <span class="diagram__label">Критическая ошибка</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number">10</span>
                        <span class="diagram__label">Неустранимая ошибка</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!--
    <div class="view-section__card card">
        <div class="view-section__header card-expand">
            <div class="view-section__label">
                <i class="view-section__icon-label fab fa-sistrix"></i>
                <div class="view-section__amount">10</div>
            </div>
            <div class="view-section__title">Замечания на рассмотрении эксперта</div>
        </div>
        <div class="view-section__body card-body expanded">
            <table id="comments_table" class="comments-table">
                <thead class="comments-table__header">
                <tr>
                    <th style="width: 1%">№</th>
                    <th style="width: 7%">Автор</th>
                    <th style="width: 25%">Текст замечания</th>
                    <th style="width: 10%">Нормативный документ</th>
                    <th style="width: 22%">Отмеченные файлы</th>
                    <th style="width: 25%">Ответ заявителя</th>
                </tr>
                </thead>
                <tbody id="comments_table_body" class="comments-table__body">
                    <tr class="comments-table__row" data-comment_hash="1602587915838">
                        <td class="comments-table__criticality-border red">123</td>
                        <td>
                            <div  class="comments-table__text">Автор</div>
                        </td>
                        <td>1. Замечания по ИОС1. ВР. ЛС02-1-17, ИОС2. ВР. ЛС02-1-29, ИОС3. ВР. ЛС02-1-28, ИОС4. ВР. ЛС02-1-31, ИОС5. ВР. ЛС02-1-33, ИОС6. ВР. ЛС02-1-32, ИОС7. ВР. ЛС02-1-30, ИОС1. ВР. ЛС02-2-12 (Системы электроснабжения)</td>
                        <td>dsadsa</td>
                        <td class="comments-table__files">
                            <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                                 data-mapping_level_1="2" data-mapping_level_2="1">
                                <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                     data-state="not_signed">
                                    <div class="files__info">

                                        <i class="files__icon fas fa-file-excel"></i>
                                        <div class="files__description">
                                            <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                            <span class="files__size">168,75 Кб</span>
                                        </div>
                                    </div>
                                    <div class="files__state short">
                                        <i class="files__state-icon fas fa-pen-alt"></i>
                                    </div>
                                    <div class="files__actions">
                                        <i class="files__unload fas fa-angle-double-down"></i>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>Исправлено что-то</td>
                    </tr>
                    <tr class="comments-table__row" data-comment_hash="1602587915838">
                        <td class="comments-table__criticality-border">123</td>
                        <td>
                            <div  class="comments-table__text">Автор</div>
                        </td>
                        <td>asdsa</td>
                        <td>dsadsa</td>
                        <td class="comments-table__files">
                            <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                                 data-mapping_level_1="2" data-mapping_level_2="1">
                                <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                     data-state="not_signed">
                                    <div class="files__info">

                                        <i class="files__icon fas fa-file-excel"></i>
                                        <div class="files__description">
                                            <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                            <span class="files__size">168,75 Кб</span>
                                        </div>
                                    </div>
                                    <div class="files__state short">
                                        <i class="files__state-icon fas fa-pen-alt"></i>
                                    </div>
                                    <div class="files__actions">
                                        <i class="files__unload fas fa-angle-double-down"></i>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>Исправлено что-то</td>
                    </tr>
                    <tr class="comments-table__row" data-comment_hash="1602587915838">
                        <td class="comments-table__criticality-border green">123</td>
                        <td>
                            <div  class="comments-table__text">Автор</div>
                        </td>
                        <td>asdsa</td>
                        <td>dsadsa</td>
                        <td class="comments-table__files">
                            <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                                 data-mapping_level_1="2" data-mapping_level_2="1">
                                <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                     data-state="not_signed">
                                    <div class="files__info">

                                        <i class="files__icon fas fa-file-excel"></i>
                                        <div class="files__description">
                                            <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                            <span class="files__size">168,75 Кб</span>
                                        </div>
                                    </div>
                                    <div class="files__state short">
                                        <i class="files__state-icon fas fa-pen-alt"></i>
                                    </div>
                                    <div class="files__actions">
                                        <i class="files__unload fas fa-angle-double-down"></i>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>Исправлено что-то</td>
                    </tr>
                    <tr class="comments-table__row" data-comment_hash="1602587915838">
                        <td class="comments-table__criticality-border">123</td>
                        <td>
                            <div  class="comments-table__text">Автор</div>
                        </td>
                        <td>asdsa</td>
                        <td>dsadsa</td>
                        <td class="comments-table__files">
                            <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                                 data-mapping_level_1="2" data-mapping_level_2="1">
                                <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                     data-state="not_signed">
                                    <div class="files__info">

                                        <i class="files__icon fas fa-file-excel"></i>
                                        <div class="files__description">
                                            <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                            <span class="files__size">168,75 Кб</span>
                                        </div>
                                    </div>
                                    <div class="files__state short">
                                        <i class="files__state-icon fas fa-pen-alt"></i>
                                    </div>
                                    <div class="files__actions">
                                        <i class="files__unload fas fa-angle-double-down"></i>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>Исправлено что-то</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    -->

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
                <table id="comments_table" class="comments-table">
                    <thead class="comments-table__header">
                    <tr>
                        <th style="width: 1%">№</th>
                        <th style="width: 7%">Автор</th>
                        <th style="width: 25%">Текст замечания</th>
                        <th style="width: 10%">Нормативный документ</th>
                        <th style="width: 22%">Отмеченные файлы</th>
                        <th style="width: 25%">Ответ эксперта</th>
                    </tr>
                    </thead>
                    <tbody id="comments_table_body" class="comments-table__body">
                        <?php foreach ($_comments as $number => $comment): ?>
                            <tr class="comments-table__row" data-criticality="<?= $comment['comment_criticality']['id'] ?>">
                                <td class="comments-table__number"><?= $number ?></td>
                                <td class="comments-table__author"><?= $comment['author'] ?></td>
                                <td><?= $comment['text'] ?></td>
                                <td><?= $comment['normative_document'] ?></td>
                                    <td>
                                        <?php if (!empty($comment['files'])): ?>
                                            <div class="documentation__files files filled" data-id_file_field>
                                                <?php foreach ($comment['files'] as $file): ?>
                                                    <div class="files__item"
                                                         data-read_only="true"
                                                         data-id="<?= $file['id'] ?>"
                                                        <?php if (isset($file['validate_results'])): ?>
                                                            data-validate_results='<?= $file['validate_results'] ?>'
                                                        <?php endif; ?>
                                                    >
                                                        <div class="files__info">
                                                            <i class="files__icon fas <?= $file['file_icon'] ?>"></i>
                                                            <div class="files__description">
                                                                <span class="files__name"><?= $file['file_name'] ?></span>
                                                                <span class="files__size"><?= $file['human_file_size'] ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="files__state" data-type="short"></div>
                                                        <div class="files__actions">
                                                            <i class="files__unload fas fa-angle-double-down"></i>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            Отсутствуют
                                        <?php endif; ?>
                                    </td>
                                <!--todo чето придумать-->
                                <td>Ответ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!--
    <div class="view-section__card card">
        <div class="view-section__header card-expand">
            <div class="view-section__label">
                <i class="view-section__icon-label fas fa-check"></i>
                <div class="view-section__amount">4</div>
            </div>
            <div class="view-section__title">Снятые замечания</div>

        </div>
        <div class="view-section__body card-body expanded">
            <table id="comments_table" class="comments-table">
                <thead class="comments-table__header">
                <tr>
                    <th style="width: 1%">№</th>
                    <th style="width: 7%">Автор</th>
                    <th style="width: 25%">Текст замечания</th>
                    <th style="width: 10%">Нормативный документ</th>
                    <th style="width: 22%">Отмеченные файлы</th>
                    <th style="width: 25%">Вывод</th>
                </tr>
                </thead>
                <tbody id="comments_table_body" class="comments-table__body">
                <tr class="comments-table__row" data-comment_hash="1602587915838">
                    <td class="comments-table__criticality-border red">123</td>
                    <td>
                        <div  class="comments-table__text">Автор</div>
                    </td>
                    <td>1. Замечания по ИОС1. ВР. ЛС02-1-17, ИОС2. ВР. ЛС02-1-29, ИОС3. ВР. ЛС02-1-28, ИОС4. ВР. ЛС02-1-31, ИОС5. ВР. ЛС02-1-33, ИОС6. ВР. ЛС02-1-32, ИОС7. ВР. ЛС02-1-30, ИОС1. ВР. ЛС02-2-12 (Системы электроснабжения)</td>
                    <td>dsadsa</td>
                    <td class="comments-table__files">
                        <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                             data-mapping_level_1="2" data-mapping_level_2="1">
                            <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                 data-state="not_signed">
                                <div class="files__info">

                                    <i class="files__icon fas fa-file-excel"></i>
                                    <div class="files__description">
                                        <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                        <span class="files__size">168,75 Кб</span>
                                    </div>
                                </div>
                                <div class="files__state short">
                                    <i class="files__state-icon fas fa-pen-alt"></i>
                                </div>
                                <div class="files__actions">
                                    <i class="files__unload fas fa-angle-double-down"></i>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>Исправлено что-то</td>
                </tr>
                <tr class="comments-table__row" data-comment_hash="1602587915838">
                    <td class="comments-table__criticality-border">123</td>
                    <td>
                        <div  class="comments-table__text">Автор</div>
                    </td>
                    <td>asdsa</td>
                    <td>dsadsa</td>
                    <td class="comments-table__files">
                        <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                             data-mapping_level_1="2" data-mapping_level_2="1">
                            <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                 data-state="not_signed">
                                <div class="files__info">

                                    <i class="files__icon fas fa-file-excel"></i>
                                    <div class="files__description">
                                        <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                        <span class="files__size">168,75 Кб</span>
                                    </div>
                                </div>
                                <div class="files__state short">
                                    <i class="files__state-icon fas fa-pen-alt"></i>
                                </div>
                                <div class="files__actions">
                                    <i class="files__unload fas fa-angle-double-down"></i>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>Исправлено что-то</td>
                </tr>
                <tr class="comments-table__row" data-comment_hash="1602587915838">
                    <td class="comments-table__criticality-border green">123</td>
                    <td>
                        <div  class="comments-table__text">Автор</div>
                    </td>
                    <td>asdsa</td>
                    <td>dsadsa</td>
                    <td class="comments-table__files">
                        <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                             data-mapping_level_1="2" data-mapping_level_2="1">
                            <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                 data-state="not_signed">
                                <div class="files__info">

                                    <i class="files__icon fas fa-file-excel"></i>
                                    <div class="files__description">
                                        <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                        <span class="files__size">168,75 Кб</span>
                                    </div>
                                </div>
                                <div class="files__state short">
                                    <i class="files__state-icon fas fa-pen-alt"></i>
                                </div>
                                <div class="files__actions">
                                    <i class="files__unload fas fa-angle-double-down"></i>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>Исправлено что-то</td>
                </tr>
                <tr class="comments-table__row" data-comment_hash="1602587915838">
                    <td class="comments-table__criticality-border">123</td>
                    <td>
                        <div  class="comments-table__text">Автор</div>
                    </td>
                    <td>asdsa</td>
                    <td>dsadsa</td>
                    <td class="comments-table__files">
                        <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                             data-mapping_level_1="2" data-mapping_level_2="1">
                            <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                 data-state="not_signed">
                                <div class="files__info">

                                    <i class="files__icon fas fa-file-excel"></i>
                                    <div class="files__description">
                                        <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                        <span class="files__size">168,75 Кб</span>
                                    </div>
                                </div>
                                <div class="files__state short">
                                    <i class="files__state-icon fas fa-pen-alt"></i>
                                </div>
                                <div class="files__actions">
                                    <i class="files__unload fas fa-angle-double-down"></i>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>Исправлено что-то</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    -->

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
                <div class="tep-grid">
                    <div class="tep-grid__header">
                        <div class="tep-grid__item">Автор</div>
                        <div class="tep-grid__item">Показатель</div>
                        <div class="tep-grid__item">Значение</div>
                        <div class="tep-grid__item">Примечание</div>
                    </div>
                    <div class="tep-grid__body">
                        <?php foreach ($_TEPsByAuthors as $author => $TEPs): ?>
                            <div class="tep-grid__item tep-grid__author"><?= $author ?></div>
                            <div class="tep-grid__values">
                                <?php for ($l = 0; $l < count($TEPs); $l++): ?>
                                    <div class="tep-grid__item"><?= $TEPs[$l]['indicator'] ?></div>
                                    <div class="tep-grid__item"><?= $TEPs[$l]['value'] ?></div>
                                    <div class="tep-grid__item"><?= $TEPs[$l]['note'] ?></div>
                                <?php endfor; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>