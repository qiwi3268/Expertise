

<div class="view__body">
    <div class="application-form__cards">
    
        <div class="application-form__card card-form card" data-type="purpose" data-completed="<?= $variablesTV->getValue('block1_completed') ? 'true' : 'false' ?>">
            <div class="card-form__header card-expand">
                <?php if ($variablesTV->getValue('block1_completed')): ?>
                    <i class="card-form__icon-state fas fa-check-circle valid"></i>
                <?php else: ?>
                    <i class="card-form__icon-state fas fa-exclamation-circle"></i>
                <?php endif; ?>
                <span class="card-form__title">СВЕДЕНИЯ О ЦЕЛИ ОБРАЩЕНИЯ</span>
                <i class="card-form__icon-expand fas fa-chevron-down arrow-up card-icon"></i>
            </div>
    
            <div class="card-form__body card-body expanded">
                
                <!--Цель обращения-->
                <div class="form-field">
                    <div class="form-field__title">
                        <span class="form-field__title-text">Цель обращения</span>
                    </div>
                    <span class="form-field__item">
                        <?php if ($variablesTV->getExistenceFlag('expertise_purpose')): ?>
                            <?= $variablesTV->getValue('expertise_purpose')['name'] ?>
                        <?php else: ?>
                            <i class="form-field__icon-misc fas fa-exclamation-circle"></i>
                            <span class="form-field__input">Не выбрана</span>
                        <?php endif; ?>
                    </span>
                </div>
                <!--//Цель обращения//-->

                <!--Предмет экспертизы-->
                <div class="form-field">
                    <span class="form-field__title">Предмет экспертизы</span>
                    <?php if ($variablesTV->getExistenceFlag('expertise_subjects')): ?>
                        <div class="form-field__value radio">
                            <div class="radio__body">
                                <?php foreach ($variablesTV->getValue('expertise_subjects') as $subject): ?>
                                    <div class="radio__item">
                                        <i class="radio__icon fas fa-check"></i>
                                        <span class="radio__value"><?= $subject['name'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="form-field__item">
                            <i class="form-field__icon-misc fas fa-exclamation-circle"></i>
                            <span class="form-field__input">Не выбран</span>
                        </div>
                    <?php endif; ?>
                </div>
                <!--//Предмет экспертизы//-->

                <!--Дополнительная информация-->
                <?php if ($variablesTV->getExistenceFlag('additional_information')): ?>
                    <div class="form-field">
                        <span class="form-field__title">Дополнительная информация</span>
                        <span class="form-field__item"><?= $variablesTV->getValue('additional_information') ?></span>
                    </div>
                <?php endif; ?>
                <!--//Дополнительная информация//-->
            </div>
        </div>

        <div class="application-form__card card-form card" data-type="object" data-completed="<?= $variablesTV->getValue('block2_completed') ? 'true' : 'false' ?>">
            <div class="card-form__header card-expand">
                <?php if ($variablesTV->getValue('block2_completed')): ?>
                    <i class="card-form__icon-state fas fa-check-circle valid"></i>
                <?php else: ?>
                    <i class="card-form__icon-state fas fa-exclamation-circle"></i>
                <?php endif; ?>
                <span class="card-form__title">СВЕДЕНИЯ ОБ ОБЪЕКТЕ</span>
                <i class="card-form__icon-expand fas fa-chevron-down arrow-up card-icon"></i>
            </div>
            <div class="card-form__body card-body expanded">

                <!--Наименование объекта-->
                <div class="form-field">
                    <span class="form-field__title">Наименование объекта</span>
                    <span class="form-field__item">
                        <?php if ($variablesTV->getExistenceFlag('object_name')): ?>
                            <?= $variablesTV->getValue('object_name') ?>
                        <?php else: ?>
                        <i class="form-field__icon-misc fas fa-exclamation-circle"></i>
                        <span class="form-field__input">Не указано</span>
                        <?php endif; ?>
                    </span>
                </div>
                <!--//Наименование объекта//-->

                <!--Вид объекта-->
                <div class="form-field">
                    <div class="form-field__title">
                        <span class="form-field__title-text">Вид объекта</span>
                    </div>
                    <span class="form-field__item">
                        <?php if ($variablesTV->getExistenceFlag('type_of_object')): ?>
                            <?= $variablesTV->getValue('type_of_object')['name'] ?>
                        <?php else: ?>
                            <i class="form-field__icon-misc fas fa-exclamation-circle"></i>
                            <span class="form-field__input">Не выбран</span>
                        <?php endif; ?>
                    </span>
                </div>
                <!--//Вид объекта//-->

                <!--Функциональное назначение-->
                <div class="form-field">
                    <span class="form-field__title">Функциональное назначение</span>
                    <span class="form-field__item">
                        <?php if ($variablesTV->getExistenceFlag('functional_purpose')): ?>
                            <?= $variablesTV->getValue('functional_purpose')['name'] ?>
                        <?php else: ?>
                            <i class="form-field__icon-misc fas fa-exclamation-circle"></i>
                            <span class="form-field__input">Не выбрано</span>
                        <?php endif; ?>
                    </span>
                </div>
                <!--//Функциональное назначение//-->

                <!--Функциональное назначение. Подотрасль-->
                <div class="form-field">
                    <span class="form-field__title">Функциональное назначение. Подотрасль</span>
                    <span class="form-field__item">
                        <?php if ($variablesTV->getExistenceFlag('functional_purpose_subsector')): ?>
                            <?= $variablesTV->getValue('functional_purpose_subsector')['name'] ?>
                        <?php else: ?>
                            <i class="form-field__icon-misc fas fa-exclamation-circle"></i>
                            <span class="form-field__input">Не выбрано</span>
                        <?php endif; ?>
                    </span>
                </div>
                <!--//Функциональное назначение. Подотрасль//-->

                <!--Функциональное назначение. Группа-->
                <div class="form-field">
                    <span class="form-field__title">Функциональное назначение. Группа</span>
                    <span class="form-field__item">
                        <?php if ($variablesTV->getExistenceFlag('functional_purpose_group')): ?>
                            <?= $variablesTV->getValue('functional_purpose_group')['name'] ?>
                        <?php else: ?>
                            <i class="form-field__icon-misc fas fa-exclamation-circle"></i>
                            <span class="form-field__input">Не выбрано</span>
                        <?php endif; ?>
                    </span>
                </div>
                <!--//Функциональное назначение. Группа//-->

                <!--Блок производственные / непроизводственные объекты капитального строительства-->

                <!--Номер утверждения документации по планировке территории-->
                <?php if ($variablesTV->getExistenceFlag('number_planning_documentation_approval')): ?>
                    <div class="form-field">
                        <span class="form-field__title">Номер утверждения документации по планировке территории</span>
                        <span class="form-field__item"><?= $variablesTV->getValue('number_planning_documentation_approval') ?></span>
                    </div>
                <?php endif; ?>
                <!--//Номер утверждения документации по планировке территории//-->

                <!--Дата утверждения документации по планировке территории-->
                <?php if ($variablesTV->getExistenceFlag('date_planning_documentation_approval')): ?>
                    <div class="form-field">
                        <span class="form-field__title">Дата утверждения документации по планировке территории</span>
                        <span class="form-field__item"><?= $variablesTV->getValue('date_planning_documentation_approval') ?></span>
                    </div>
                <?php endif; ?>
                <!--//Дата утверждения документации по планировке территории//-->
                <!--//Блок производственные / непроизводственные объекты капитального строительства//-->

                <!--Блок линейные объекты капитального строительства-->
                <!--Номер ГПЗУ-->
                <?php if ($variablesTV->getExistenceFlag('number_GPZU')): ?>
                    <div class="form-field">
                        <span class="form-field__title">Номер ГПЗУ</span>
                        <span class="form-field__item"><?= $variablesTV->getValue('number_GPZU') ?></span>
                    </div>
                <?php endif; ?>
                <!--//Номер ГПЗУ//-->

                <!--Дата ГПЗУ-->
                <?php if ($variablesTV->getExistenceFlag('date_GPZU')): ?>
                    <div class="form-field">
                        <span class="form-field__title">Дата ГПЗУ</span>
                        <span class="form-field__item"><?= $variablesTV->getValue('date_GPZU') ?></span>
                    </div>
                <?php endif; ?>
                <!--//Дата ГПЗУ//-->
                <!--//Блок линейные объекты капитального строительства//-->

                <!--Вид работ-->
                <div class="form-field">
                    <span class="form-field__title">Вид работ</span>
                    <span class="form-field__item">
                        <?php if ($variablesTV->getExistenceFlag('type_of_work')): ?>
                            <?= $variablesTV->getValue('type_of_work')['name'] ?>
                        <?php else: ?>
                            <i class="form-field__icon-misc fas fa-exclamation-circle"></i>
                            <span class="form-field__input">Не выбран</span>
                        <?php endif; ?>
                    </span>
                </div>
                <!--//Вид работ//-->

                <!--ГРБС-->
                <?php if (!is_null($variablesTV->getValue('form_files')[1][1])): ?>
                    <div class="form-field" data-id_file_field data-mapping_level_1="1" data-mapping_level_2="1">
                        <span class="form-field__title">Файл ГРБС</span>
                        <div class="form-field__files files">
                            <?php foreach ($variablesTV->getValue('form_files')[1][1] as $file): ?>
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
                <?php endif; ?>
                <!--//ГРБС//-->

                <!--Кадастровый номер земельного участка-->
                <?php if ($variablesTV->getExistenceFlag('cadastral_number')): ?>
                    <div class="form-field">
                        <span class="form-field__title">Кадастровый номер земельного участка</span>
                        <span class="form-field__item"><?= $variablesTV->getValue('cadastral_number') ?></span>
                    </div>
                <?php endif; ?>
                <!--//Кадастровый номер земельного участка//-->

                <!--Тип объекта культурного наследия-->
                <?php if ($variablesTV->getExistenceFlag('cultural_object_type')): ?>
                    <div class="form-field">
                        <span class="form-field__title">Тип объекта культурного наследия</span>
                        <span class="form-field__item"><?= $variablesTV->getValue('cultural_object_type')['name'] ?></span>
                    </div>
                <?php endif; ?>
                <!--//Тип объекта культурного наследия//-->

                <!--Название национального проекта-->
                <?php if ($variablesTV->getExistenceFlag('national_project')): ?>
                    <div class="form-field">
                        <span class="form-field__title">Название национального проекта</span>
                        <span class="form-field__item"><?= $variablesTV->getValue('national_project')['name'] ?></span>
                    </div>
                <?php endif; ?>
                <!--//Название национального проекта//-->

                <!--Название федерального проекта-->
                <?php if ($variablesTV->getExistenceFlag('federal_project')): ?>
                    <div class="form-field">
                        <span class="form-field__title">Название федерального проекта</span>
                        <span class="form-field__item"><?= $variablesTV->getValue('federal_project')['name'] ?></span>
                    </div>
                <?php endif; ?>
                <!--//Название федерального проекта//-->

                <!--Дата окончания строительства-->
                <?php if ($variablesTV->getExistenceFlag('date_finish_building')): ?>
                    <div class="form-field">
                        <span class="form-field__title">Дата окончания строительства</span>
                        <span class="form-field__item"><?= $variablesTV->getValue('date_finish_building') ?></span>
                    </div>
                <?php endif; ?>
                <!--//Дата окончания строительства//-->

                <!--Куратор-->
                <?php if ($variablesTV->getExistenceFlag('curator')): ?>
                    <div class="form-field">
                        <span class="form-field__title">Куратор</span>
                        <span class="form-field__item"><?= $variablesTV->getValue('curator')['name'] ?></span>
                    </div>
                <?php endif; ?>
                <!--//Куратор//-->
            </div>
        </div>

        <!-- todo data-type-->
        <div class="application-form__card card-form card" data-type="ещвщ" data-completed="<?= $variablesTV->getValue('block77_completed') ? 'true' : 'false' ?>">
            <div class="card-form__header card-expand">
                <?php if ($variablesTV->getValue('block77_completed')): ?>
                    <i class="card-form__icon-state fas fa-check-circle valid"></i>
                <?php else: ?>
                    <i class="card-form__icon-state fas fa-exclamation-circle"></i>
                <?php endif; ?>
                <span class="card-form__title">ДОКУМЕНТАЦИЯ</span>
                <i class="card-form__icon-expand fas fa-chevron-up arrow-up card-icon"></i>
            </div>
            <div class="card-form__body card-body expanded">
                <?php if (!$variablesTV->getExistenceFlag('type_of_object')): ?>
                    <div class="card-form__message">
                        <i class="card-form__icon-message fas fa-exclamation"></i>
                        <span class="card-form__message-text">Для отображения структуры разделов документации выберите вид объекта</span>
                    </div>
                <?php else: ?>
                    <div class="documentation" data-id_file_field data-mapping_level_1="<?= $variablesTV->getValue('documentation_mapping_level_1') ?>" data-mapping_level_2="<?= $variablesTV->getValue('documentation_mapping_level_2') ?>">
                        <?php foreach ($variablesTV->getValue('documentation_files_in_structure') as $node): ?>
                            <div class="documentation__node">
                                <span class="documentation__name" style="padding-left: <?= $node['depth']*25 + 15 ?>px"><?= $node['name'] ?></span>
                                <?php if (isset($node['files'])): ?>
                                    <div class="documentation__files files" >
                                        <?php foreach ($node['files'] as $file): ?>
                                            <div class="files__item" data-read_only="true" data-id="<?= $file['id'] ?>" style="padding-left: <?= $node['depth']*25 + 7 ?>px" data-validate_results='<?= $file['validate_results'] ?>'>
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
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
