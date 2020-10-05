

<?php $variablesTV = \Lib\Singles\VariableTransfer::getInstance(); ?>

<?php //var_dump($variablesTV->getValue('documentation_files_in_structure')); ?>
<?php //var_dump($variablesTV->getValue('experts')); ?>
<?php //var_dump($variablesTV->getValue('main_block_341')); ?>

<div class="action-info">Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)</div>

<div class="action-header">
    <div class="action-header__title">
        <span class="action-header__name">НАЗНАЧЕНИЕ ЭКСПЕРТОВ</span>
        <span class="action-header__document">Заявление на экспертизу № 2020-6-1179/2020-8-1025-С от 18.08.2020</span>
    </div>
    <div class="action-header__buttons">
        <span class="action-header__button" data-action_submit>ОК</span>
        <span class="action-header__button">Отмена</span>
    </div>
</div>


<div class="assignment">
    <div class="assignment__experts">
        <div class="assignment__experts-header">Эксперты</div>
        <div class="assignment__body-experts">
            <div class="assignment__expert-section">
                <div class="assignment__type">Сметный отдел</div>
                <div class="assignment__expert-list"
                     data-drag_container
                     data-drag_multiple="true"
                     data-transform_callback="expert"
                >
                    <?php foreach ($variablesTV->getValue('experts') as $index => $expert): ?>
                        <span class="assignment__expert"
                              data-drag_element
                              data-id="<?= $expert['id'] ?>"
                              data-drag_callback="expert"
                              style="order: <?= -$index ?>"
                        ><?= $expert['fio'] ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="assignment__expert-section">
                <div class="assignment__type">Экспертный отдел</div>
                <div class="assignment__expert-list" data-drag_container data-drag_multiple="false" data-transform_callback="expert">
                    <?php foreach ($variablesTV->getValue('experts') as $index => $expert): ?>
                        <div class="assignment__expert"
                             data-drag_element
                             data-id="<?= $expert['id'] ?>"
                             data-drag_callback="expert"
                             style="order: <?= $index ?>">
                            <?= $expert['fio'] ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="assignment__sections">
        <div class="assignment__main">
            <div class="assignment__header">
                <span class="assignment__title">Представленные на экспертизу разделы</span>
            </div>
            <div class="assignment__body-sections">
                <?php foreach ($variablesTV->getValue('documentation_files_in_structure') as $section): ?>
                    <div class="assignment__section section"
                         data-drop_area
                         data-id="<?= $section['id_main_block_341'] ?>"
                         data-result_callback="experts_json"
                         data-add_element_callback="add_expert"
                    >
                        <div class="section__header">
                            <i class="section__folder fas fa-book"></i>
                            <span class="section__title"><?= $section['name'] ?></span>
                        </div>
                        <div class="section__body">
                            <div class="section__experts" data-active="false">
                                <div class="section__experts-title">Назначенные эксперты:</div>
                                <div class="section__expert-list"
                                     data-drop_container
                                     data-drop_multiple="false"
                                     data-drag_container
                                     data-drag_multiple="false"
                                ></div>
                            </div>
                            <div class="section__uploaded-files card">
                                <div class="section__expand-button">
                                    <span class="section__button-name card-expand">Загруженные файлы</span>
                                    <i class="section__icon-expand fas fa-chevron-down arrow-down card-icon"></i>
                                </div>
                                <div class="section__files files filled block card-body" data-id_file_field>
                                    <?php if (isset($section['self_files'])): ?>
                                        <div class="files__title">Файлы по разделу</div>
                                        <?php foreach ($section['self_files'] as $file): ?>
                                            <div class="files__item" data-read_only="true" data-id="<?= $file['id'] ?>" data-validate_results='<?= $file['validate_results'] ?>'>
                                                <div class="files__info">
                                                    <i class="files__icon fas <?= $file['file_icon'] ?>"></i>
                                                    <div class="files__description">
                                                        <span class="files__name"><?= $file['file_name'] ?></span>
                                                        <span class="files__size"><?= $file['human_file_size'] ?></span>
                                                    </div>
                                                </div>
                                                <div class="files__state"></div>
                                                <div class="files__actions">
                                                    <i class="files__unload fas fa-file-download"></i>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <?php if (isset($section['vor_files'])): ?>
                                        <div class="files__title">Файлы ведомостей объемов работ</div>
                                        <?php foreach ($section['vor_files'] as $file): ?>
                                            <div class="files__item" data-read_only="true" data-id="<?= $file['id'] ?>" data-validate_results='<?= $file['validate_results'] ?>'>
                                                <div class="files__info">
                                                    <i class="files__icon fas <?= $file['file_icon'] ?>"></i>
                                                    <div class="files__description">
                                                        <span class="files__name"><?= $file['file_name'] ?></span>
                                                        <span class="files__size"><?= $file['human_file_size'] ?></span>
                                                    </div>
                                                </div>
                                                <div class="files__state"></div>
                                                <div class="files__actions">
                                                    <i class="files__unload fas fa-file-download"></i>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="additional_sections" class="assignment__additional" data-active="false">
            <div class="assignment__header">
                <div class="assignment__title">Дополнительные разделы</div>
            </div>
            <div class="assignment__body-sections"></div>
            <div id="section_template" class="assignment__section section field"
                 data-misc_field
                 data-active="false"
                 data-id
                 data-result_callback="experts_json"
                 data-add_element_callback="add_expert"
            >
                <div class="section__header empty" data-misc_select>
                    <i class="section__folder fas fa-book"></i>
                    <span class="section__title" data-misc_value>Выберите раздел</span>
                    <i class="section__remove fas fa-minus"></i>
                </div>
                <div class="section__body">
                    <div class="section__experts" data-active="false">
                        <div class="section__experts-title">Назначенные эксперты:</div>
                        <div class="section__expert-list"
                             data-drop_container
                             data-drop_multiple="false"
                             data-drag_container
                             data-drag_multiple="false"
                        ></div>
                    </div>
                </div>
                <div class="modal" data-misc_modal data-result_callback="additional_section">
                    <i class="modal__close fas fa-times" data-misc_close></i>
                    <div class="modal__items" data-misc_body>
                        <?php foreach ($variablesTV->getValue('main_block_341') as $pageNumber => $page): ?>
                            <div class="modal__page" data-misc_page="<?= $pageNumber ?>">
                                <?php foreach ($page as $item): ?>
                                    <div class="modal__item" data-misc_item data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="add_section" class="assignment__add">
            <span class="assignment__add-text">Добавить раздел из 341 приказа</span>
            <i class="assignment__add-icon fas fa-plus"></i>
        </div>
    </div>

</div>

<div id="misc_overlay" class="modal-overlay"></div>

