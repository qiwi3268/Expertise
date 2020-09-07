

<?php $variablesTV = \Lib\Singles\VariableTransfer::getInstance(); ?>

<?php var_dump($variablesTV->getValue('documentation_files_in_structure')); ?>

<div class="action">

    <div class="action__info">
        <div class="action__row">
            <span class="header-action__name">ПЕРЕДАТЬ НА РАССМОТРЕНИЕ В ПТО</span>
            <span class="header-action__document">Заявление на экспертизу № 2020-6-1179/2020-8-1025-С от 18.08.2020</span>
            <div class="header-action__buttons">
                <span class="header-action__button">ОК</span>
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
        </div>
        <div class="assignment__sections">
            <div class="assignment__main">
                <div class="assignment__header">Разделы ПД</div>
                
                <div class="assignment__body">
    
    
                    <?php foreach ($variablesTV->getValue('documentation_files_in_structure') as $section): ?>
                        <div class="assignment__section section">
                            <span class="section__title"><?= $section['name'] ?></span>
                            <div class="section__body">
                                <div class="section__experts">
                                    <div class="section__experts-title">Назначенные эксперты:</div>
                                </div>
                                <div class="section__files">
                                    <div class="section__expand-button">
                                        <span class="section__button-name">Показать загруженные файлы</span>
                                        <i class="section__icon-expand fas fa-chevron-down"></i>
                                    </div>
                                    <div class="section__files files filled block">
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
                    
                    <div class="assignment__section">
                        <div class="assignment__section-title">
                            <span class="assignment__name">Ведомости объемов работ по разделу и спецификации, выполненные на основании проектной документации</span>
                            <i class="assignment__icon-expand fas fa-chevron-right"></i>
                        </div>
                    </div>
                    <div class="assignment__section">
                        <div class="assignment__section-title">
                            <span class="assignment__name">Раздел 5. Сведения об инженерном оборудовании, о сетях инженерно-технического обеспечения, перечень инженерно-технических мероприятий, содержание технологических решений</span>
                            <i class="assignment__icon-expand fas fa-chevron-right"></i>
                        </div>
                        <div class="assignment__section-items">
                            <div class="assignment__item">Expert</div>
                        </div>
                    </div>
                    
                    <div class="assignment__section section">
                        <div class="section__title">
                            <span class="section__name">Мероприятия по обеспечению пожарной безопасности</span>
                            <i class="section__icon-expand fas fa-chevron-right"></i>
                        </div>
                    </div>
                    
                    
<!--                    <div class="assignment__section section">-->
<!--                        <span class="section__title">Сметные расчеты на отдельные виды затрат (калькуляции, расчёт земляных масс, расчёт по НЦС, расчёт по мусору, ведомость поставки материалов, утвержденную Заказчиком (для дорожного строительства по области) и т.п.)</span>-->
<!--                        <div class="section__body">-->
<!--                            <div class="section__experts">-->
<!--                                <div class="section__expert">-->
<!--                                    <span class="section__name">Носков И.Н.</span>-->
<!--                                    <i class="section__icon-remove fas fa-minus"></i>-->
<!--                                </div>-->
<!--                                <div class="section__expert">-->
<!--                                    <span class="section__name">Карякин В.А.</span>-->
<!--                                    <i class="section__icon-remove fas fa-minus"></i>-->
<!--                                </div>-->
<!--                                <div class="section__expert">-->
<!--                                    <span class="section__name">Некерова Т.С.</span>-->
<!--                                    <i class="section__icon-remove fas fa-minus"></i>-->
<!--                                </div>-->
<!--                                <div class="section__expert">-->
<!--                                    <span class="section__name">Антошкин В.А.</span>-->
<!--                                    <i class="section__icon-remove fas fa-minus"></i>-->
<!--                                </div>-->
<!--                                <div class="section__expert">-->
<!--                                    <span class="section__name">Белов А.В.</span>-->
<!--                                    <i class="section__icon-remove fas fa-minus"></i>-->
<!--                                </div>-->
<!--                                -->
<!--                            </div>-->
<!--                            <div class="section__files">-->
<!--                                <div class="section__expand-button">-->
<!--                                    <span class="section__button-name">Показать загруженные файлы</span>-->
<!--                                    <i class="section__icon-expand fas fa-chevron-down"></i>-->
<!--                                </div>-->
<!--                                <div class="section__files files filled">-->
<!--                                    <div class="files__item" data-id="775" data-state="not_signed">-->
<!--                                        <div class="files__info">-->
<!--                                            <i class="files__icon fas fa-file-pdf"></i>-->
<!--                                            <div class="files__description">-->
<!--                                                <span class="files__name">Темы участников.pdf</span>-->
<!--                                                <span class="files__size">609,13 Кб</span>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="files__item" data-id="775" data-state="not_signed">-->
<!--                                        <div class="files__info">-->
<!--                                            <i class="files__icon fas fa-file-pdf"></i>-->
<!--                                            <div class="files__description">-->
<!--                                                <span class="files__name">Темы участников.pdf</span>-->
<!--                                                <span class="files__size">609,13 Кб</span>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="files__item" data-id="775" data-state="not_signed">-->
<!--                                        <div class="files__info">-->
<!--                                            <i class="files__icon fas fa-file-pdf"></i>-->
<!--                                            <div class="files__description">-->
<!--                                                <span class="files__name">Темы участников.pdf</span>-->
<!--                                                <span class="files__size">609,13 Кб</span>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        -->
<!--                    </div>-->
                </div>
                
            </div>
            <div class="assignment__additional">
                <div class="assignment__header">Дополнительные разделы</div>
                
            </div>
        </div>
    
    </div>

</div>

<div class="sign-overlay"></div>
<div class="modal sign-modal" data-plugin_loaded="false">
    <div class="sign-modal__file-body">
        <div class="sign-modal__file-info">
            <div class="sign-modal__file"></div>
        </div>
    </div>
    
    <div class="sign-modal__validate" data-active="false"></div>
    
    <div class="sign-modal__empty"></div>
</div>

<div class="error-overlay"></div>
<div class="modal error-modal">
    <div class="error-modal__header">
        <i class="error-modal__icon fas fa-exclamation"></i>
        <span class="error-modal__title"></span>
    </div>
    <span class="error-modal__message"></span>
</div>