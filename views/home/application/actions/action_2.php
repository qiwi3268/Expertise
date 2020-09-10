

<?php $variablesTV = \Lib\Singles\VariableTransfer::getInstance(); ?>

<?php //var_dump($variablesTV->getValue('documentation_files_in_structure')); ?>
<?php //var_dump($variablesTV->getValue('experts')); ?>

<div class="action">

    <div class="action__info">
        <div class="action__row">
            <span class="header-action__name">ПЕРЕДАТЬ НА РАССМОТРЕНИЕ В ПТО</span>
            <span class="header-action__document">Заявление на экспертизу № 2020-6-1179/2020-8-1025-С от 18.08.2020</span>
            <div class="header-action__buttons">
                <span class="header-action__button" data-action_submit>ОК</span>
                <span class="header-action__button">Отмена</span>
            </div>
        </div>
        <div class="action__row">
            <span class="header-action__description">Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)</span>
        </div>
    </div>


    <div class="assignment">
    
        <div class="assignment__experts">
            <div class="assignment__header">Эксперты</div>
            <div class="assignment__body-experts">
                <div class="assignment__expert-section">
                    <div class="assignment__type">Смета</div>
                    <div class="assignment__expert-list"
                         data-drag_container
                         data-drag_multiple="true"
                         data-transform_callback="expert"
                    >
                        <?php foreach ($variablesTV->getValue('experts') as $index => $expert): ?>
                            <div class="assignment__expert"
                                 data-drag_element
                                 data-id="<?= $expert['id'] ?>"
                                 data-drag_callback="expert"
                                 style="order: <?= -$index ?>"
                            ><?= $expert['fio'] ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="assignment__expert-section">
                    <div class="assignment__type">Не смета</div>
                    <div class="assignment__expert-list" data-drag_container data-drag_multiple="false" data-transform_callback="expert">
                        <?php foreach ($variablesTV->getValue('experts') as $index => $expert): ?>
                            <div class="assignment__expert"
                                 data-drag_element
                                 data-id="<?= $expert['id'] ?>"
                                 data-drag_callback="expert"
                                 style="order: <?= $index ?>"
                            ><?= $expert['fio'] ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="assignment__sections">
            <div class="assignment__main">
                <div class="assignment__header">Разделы ПД</div>
                <div class="assignment__body-sections">
                    <?php foreach ($variablesTV->getValue('documentation_files_in_structure') as $section): ?>
                        <div class="assignment__section section"
                             data-drop_area
                             data-id="<?= $section['id'] ?>"
                             data-result_callback="experts_json"
                        >
                            <span class="section__title"><?= $section['name'] ?></span>
                            <div class="section__body">
                                <div class="section__experts">
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
                                        <span class="section__button-name card-expand">Показать загруженные файлы</span>
                                        <i class="section__icon-expand fas fa-chevron-down arrow-down card-icon"></i>
                                    </div>
                                    <div class="section__files files filled block card-body">
                                        <?php foreach ($section['files'] as $file): ?>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="assignment__additional">
                <div class="assignment__header">Дополнительные разделы</div>
                
            </div>
        </div>
    
    </div>

</div>

<div class="error-overlay"></div>
<div class="modal error-modal">
    <div class="error-modal__header">
        <i class="error-modal__icon fas fa-exclamation"></i>
        <span class="error-modal__title"></span>
    </div>
    <span class="error-modal__message"></span>
</div>