<?php $_VT = \Lib\Singles\VariableTransfer::getInstance(); ?>

<?php //var_dump($_VT->getValue('structureDocumentation1')); ?>

<div class="application-header">
    <div class="application-header__title">Заявление на экспертизу <?= $_VT->getValue('numerical_name') ?></div>
    <div class="application-header__actions">
        <div id="application_save" class="application-header__button">
            <span class="application-header__text">Сохранить</span>
            <i class="application-header__icon-save fas fa-save"></i>
        </div>
        <div class="application-header__button">
            <span class="application-header__text">Удалить</span>
            <i class="application-header__icon-delete fas fa-trash"></i>
        </div>
    </div>
</div>

<div class="application-form">
    <div class="sidebar-form application-form__sidebar">
        <div class="sidebar-form__row">
            <span class="sidebar-form__text">Сведения о проекте и цели заявления</span>
            <i class="sidebar-form__icon fas"></i>
        </div>
        <div class="sidebar-form__row" data-card="purpose">
            <span class="sidebar-form__text">Сведения о цели обращения</span>
            <i class="sidebar-form__icon fas"></i>
        </div>
        <div class="sidebar-form__row" data-card="object">
            <span class="sidebar-form__text">Сведения об объекте</span>
            <i class="sidebar-form__icon fas"></i>
        </div>
        <div class="sidebar-form__row" data-card="applicant">
            <span class="sidebar-form__text">Сведения о заявителе</span>
            <i class="sidebar-form__icon fas"></i>
        </div>
        <div class="sidebar-form__row">
            <span class="sidebar-form__text">Застройщик(заказчик по договору)</span>
            <i class="sidebar-form__icon fas"></i>
        </div>
        <div class="sidebar-form__row">
            <span class="sidebar-form__text">Сведения об исполнителях работ</span>
            <i class="sidebar-form__icon fas"></i>
        </div>
        <div class="sidebar-form__row">
            <span class="sidebar-form__text">Условия предоставления услуги</span>
            <i class="sidebar-form__icon fas"></i>
        </div>
        <div class="sidebar-form__row">
            <span class="sidebar-form__text">Сведения об исполнителях работ</span>
            <i class="sidebar-form__icon fas"></i>
        </div>
    </div>

    <div class="application-form__cards">
        <input type="hidden" name="id_application" value="<?= $_VT->getValue('id_application') ?>">

        <div class="application-form__block" data-block data-dependency_scope>

            <div class="card-form application-form__card card" data-type="purpose" data-card>
                <div class="card-form__header card-expand">
                    <span class="card-form__title">СВЕДЕНИЯ О ЦЕЛИ ОБРАЩЕНИЯ</span>
                    <i class="card-form__icon-expand fas fa-chevron-down arrow-down card-icon"></i>
                </div>
                <div class="card-form__body card-body">
                    <!--Цель обращения-->
                    <div class="form-field field" data-misc_field data-name="expertise_purpose" data-required="true">
                        <span class="form-field__title field-title">Цель обращения</span>
                        <div class="form-field__item">
                            <div class="form-field__body">
                                <div class="form-field__select field-select" data-misc_select>
                                    <span class="form-field__value field-value" data-misc_value>Выберите значение</span>
                                    <i class="form-field__icon-misc fas fa-bars"></i>
                                    <i class="form-field__icon-filled fas fa-check"></i>
                                </div>
                            </div>
                            <span class="form-field__error field-error">Поле обязательно для заполнения</span>
                        </div>

                        <div class="modal" data-misc_modal data-result_callback="document_field">
                            <i class="modal__close fas fa-times" data-misc_close></i>
                            <div class="modal__items" data-misc_body>
                                <?php foreach ($_VT->getValue('expertise_purpose') as $pageNumber => $page): ?>
                                    <div class="modal__page" data-misc_page="<?= $pageNumber ?>">
                                        <?php foreach ($page as $item): ?>
                                            <div class="modal__item" data-misc_item data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <input class="form-field__result field-result" type="hidden" data-misc_result name="expertise_purpose">
                    </div>
                    <!--//Цель обращения//-->

                    <!--Предмет экспертизы-->
                    <div class="form-field field" data-name="expertise_subjects" data-required="true">
                        <span class="form-field__title">Предмет экспертизы</span>
                        <div class="form-field__item">
                            <div class="radio form-field__body" data-multiple="true" data-required="true">
                                <div class="radio__body">
                                    <span class="radio__title">Выберите цель обращения</span>
                                </div>
                            </div>
                        </div>
                        <input class="form-field__result field-result" type="hidden" data-misc_result name="expertise_subjects" value="">
                    </div>
                    <!--//Предмет экспертизы//-->

                    <!--Дополнительная информация-->
                    <div class="form-field field" data-name="additional_information" data-pattern="text">
                        <span class="form-field__title">Дополнительная информация</span>
                        <div class="form-field__item">
                            <div class="form-field__body">
                                <textarea class="form-field__input field-result" name="additional_information"></textarea>
                            </div>
                            <span class="form-field__error field-error"></span>
                        </div>
                    </div>
                    <!--//Дополнительная информация//-->
                </div>
            </div>

            <div class="card-form application-form__card card" data-type="object">
                <div class="card-form__header card-expand">
                    <span class="card-form__title">СВЕДЕНИЯ ОБ ОБЪЕКТЕ</span>
                    <i class="card-form__icon-expand fas fa-chevron-down arrow-down card-icon"></i>
                </div>
                <div class="card-form__body card-body">
                    <!--Наименование объекта-->
                    <div class="form-field field" data-name="object_name" data-required="true" data-pattern="text">
                        <span class="form-field__title">Наименование объекта</span>
                        <div class="form-field__item">
                            <div class="form-field__body">
                                <textarea class="form-field__input field-result" name="object_name"></textarea>
                            </div>
                            <span class="form-field__error field-error"></span>
                        </div>
                    </div>
                    <!--//Наименование объекта//-->

                    <!--Вид объекта-->
                    <div class="form-field field" data-misc_field data-name="type_of_object" data-required="true">
                        <span class="form-field__title field-title">Вид объекта</span>
                        <div class="form-field__item">
                            <div class="form-field__body">
                                <div class="form-field__select field-select" data-misc_select>
                                    <span class="form-field__value field-value" data-misc_value>Выберите значение</span>
                                    <i class="form-field__icon-misc fas fa-bars"></i>
                                    <i class="form-field__icon-filled fas fa-check"></i>
                                </div>
                            </div>
                            <span class="form-field__error field-error">Поле обязательно для заполнения</span>
                        </div>
                        <div class="modal" data-misc_modal data-result_callback="document_field">
                            <i class="modal__close fas fa-times" data-misc_close></i>
                            <div class="modal__items" data-misc_body>
                                <?php foreach ($_VT->getValue('type_of_object') as $pageNumber => $page): ?>
                                    <div class="modal__page" data-misc_page="<?= $pageNumber ?>">
                                        <?php foreach ($page as $item): ?>
                                            <div class="modal__item" data-misc_item data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <input class="form-field__result field-result" type="hidden" data-misc_result name="type_of_object">
                    </div>
                    <!--//Вид объекта//-->

                    <!--Функциональное назначение-->
                    <div class="form-field field" data-misc_field data-name="functional_purpose" data-required="true">
                        <span class="form-field__title field-title">Функциональное назначение</span>
                        <div class="form-field__item">
                            <div class="form-field__body">
                                <div class="form-field__select field-select" data-misc_select>
                                    <span class="form-field__value field-value" data-misc_value>Выберите значение</span>
                                    <i class="form-field__icon-misc fas fa-bars"></i>
                                    <i class="form-field__icon-filled fas fa-check"></i>
                                </div>
                            </div>
                            <span class="form-field__error field-error">Поле обязательно для заполнения</span>
                        </div>
                        <div class="modal" data-misc_modal data-result_callback="document_field">
                            <i class="modal__close fas fa-times" data-misc_close></i>
                            <div class="modal__items" data-misc_body>
                                <?php foreach ($_VT->getValue('functional_purpose') as $pageNumber => $page): ?>
                                    <div class="modal__page" data-misc_page="<?= $pageNumber ?>">
                                        <?php foreach ($page as $item): ?>
                                            <div class="modal__item" data-misc_item data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <input class="form-field__result field-result" type="hidden" data-misc_result name="functional_purpose">
                    </div>
                    <!--//Функциональное назначение//-->

                    <!--Функциональное назначение. Подотрасль-->
                    <div class="form-field field" data-misc_field data-name="functional_purpose_subsector" data-required="true">
                        <span class="form-field__title field-title">Функциональное назначение. Подотрасль</span>
                        <div class="form-field__item">
                            <div class="form-field__body">
                                <div class="form-field__select field-select" data-misc_select>
                                    <span class="form-field__value field-value" data-misc_value>Выберите значение</span>
                                    <i class="form-field__icon-misc fas fa-bars"></i>
                                    <i class="form-field__icon-filled fas fa-check"></i>
                                </div>
                                <i class="form-field__icon-clear fas fa-times"></i>
                            </div>
                            <span class="form-field__error field-error">Поле обязательно для заполнения</span>
                        </div>
                        <div class="modal" data-misc_modal data-result_callback="document_field">
                            <i class="modal__close fas fa-times" data-misc_close></i>
                            <div class="modal__items" data-misc_body></div>
                        </div>
                        <input class="form-field__result field-result" type="hidden" data-misc_result name="functional_purpose_subsector">
                    </div>
                    <!--//Функциональное назначение. Подотрасль//-->

                    <!--Функциональное назначение. Группа-->
                    <div class="form-field field" data-misc_field data-name="functional_purpose_group" data-required="true">
                        <span class="form-field__title field-title">Функциональное назначение. Группа</span>
                        <div class="form-field__item">
                            <div class="form-field__body">
                                <div class="form-field__select field-select" data-misc_select>
                                    <span class="form-field__value field-value" data-misc_value>Выберите значение</span>
                                    <i class="form-field__icon-misc fas fa-bars"></i>
                                    <i class="form-field__icon-filled fas fa-check"></i>
                                </div>
                                <i class="form-field__icon-clear fas fa-times"></i>
                            </div>
                            <span class="form-field__error field-error">Поле обязательно для заполнения</span>
                        </div>
                        <div class="modal" data-misc_modal data-result_callback="document_field">
                            <i class="modal__close fas fa-times" data-misc_close></i>
                            <div class="modal__items" data-misc_body></div>
                        </div>
                        <input class="form-field__result field-result" type="hidden" data-misc_result name="functional_purpose_group">
                    </div>
                    <!--//Функциональное назначение. Группа//-->

                    <!--Блок производственные/непроизводственные объекты капитального строительства-->
                    <div class="card-form__block" data-block data-name="planning_documentation_approval" data-active="false">
                        <!--Номер утверждения документации по планировке территории-->
                        <div class="form-field field" data-name="number_planning_documentation_approval" data-pattern="number">
                            <span class="form-field__title">Номер утверждения документации по планировке территории</span>
                            <div class="form-field__item">
                                <div class="form-field__body">
                                    <input class="form-field__input field-result" type="text" name="number_planning_documentation_approval" placeholder="Введите значение">
                                </div>
                                <span class="form-field__error field-error"></span>
                            </div>
                        </div>
                        <!--//Номер утверждения документации по планировке территории//-->
                        <!--Дата утверждения документации по планировке территории-->
                        <div class="form-field field" data-name="date_planning_documentation_approval" data-pattern="date">
                            <span class="form-field__title">Дата утверждения документации по планировке территории</span>
                            <div class="form-field__item">
                                <div class="form-field__body">
                                    <div class="form-field__select field-select modal-calendar">
                                        <span class="form-field__value field-value">Выберите дату</span>
                                        <i class="form-field__icon-misc fas fa-calendar-alt"></i>
                                        <i class="form-field__icon-filled fas fa-check"></i>
                                    </div>
                                    <i class="form-field__icon-clear fas fa-times"></i>
                                </div>
                            </div>
                            <input class="form-field__result field-result" type="hidden" name="date_planning_documentation_approval">
                        </div>
                        <!--//Дата утверждения документации по планировке территории//-->
                    </div>
                    <!--//Блок производственные/непроизводственные объекты капитального строительства//-->

                    <!--Блок линейные объекты капитального строительства-->
                    <div class="card-form__block" data-block data-name="GPZU" data-active="false">
                        <!--Номер ГПЗУ-->
                        <div class="form-field field" data-name="number_GPZU" data-pattern="">
                            <span class="form-field__title">Номер ГПЗУ</span>
                            <div class="form-field__item">
                                <div class="form-field__body">
                                    <input class="form-field__input field-result" type="text" name="number_GPZU" placeholder="Введите значение">
                                    <span class="form-field__error field-error"></span>
                                </div>
                            </div>
                        </div>
                        <!--//Номер ГПЗУ//-->
                        <!--Дата ГПЗУ-->
                        <div class="form-field field" data-name="date_GPZU" data-pattern="date">
                            <span class="form-field__title">Дата ГПЗУ</span>
                            <div class="form-field__item">
                                <div class="form-field__body">
                                    <div class="form-field__select field-select modal-calendar">
                                        <span class="form-field__value field-value">Выберите дату</span>
                                        <i class="form-field__icon-misc fas fa-calendar-alt"></i>
                                        <i class="form-field__icon-filled fas fa-check"></i>
                                    </div>
                                    <i class="form-field__icon-clear fas fa-times"></i>
                                </div>
                            </div>
                            <input class="form-field__result field-result" type="hidden" name="date_GPZU">
                        </div>
                        <!--//Дата ГПЗУ//-->
                    </div>
                    <!--//Блок линейные объекты капитального строительства//-->

                    <!--Вид работ-->
                    <div class="form-field field" data-misc_field data-name="type_of_work" data-required="true">
                        <span class="form-field__title field-title">Вид работ</span>
                        <div class="form-field__item">
                            <div class="form-field__body">
                                <div class="form-field__select field-select" data-misc_select>
                                    <span class="form-field__value field-value" data-misc_value>Выберите значение</span>
                                    <i class="form-field__icon-misc fas fa-bars"></i>
                                    <i class="form-field__icon-filled fas fa-check"></i>
                                </div>
                                <i class="form-field__icon-clear fas fa-times"></i>
                            </div>
                            <span class="form-field__error field-error">Поле обязательно для заполнения</span>
                        </div>
                        <div class="modal" data-misc_modal data-result_callback="document_field">
                            <i class="modal__close fas fa-times" data-misc_close></i>
                            <div class="modal__items" data-misc_body></div>
                        </div>
                        <input class="form-field__result field-result" type="hidden" data-misc_result name="type_of_work">
                    </div>
                    <!--//Вид работ//-->

                    <!--Блок смета-->
                    <div class="card-form__block" data-block data-name="estimate" data-active="true">
                        <!--Сметная стоимость-->
                        <div class="form-field field" data-name="estimate_cost" data-pattern="number">
                            <span class="form-field__title">Сведения о сметной или предполагаемой (предельной) стоимости объекта капитального строительства, содержащиеся в решении по объекту или письме. тыс. руб.</span>
                            <div class="form-field__item">
                                <div class="form-field__body">
                                    <input class="form-field__input field-result" type="text" name="estimate_cost" placeholder="Введите значение">
                                </div>
                                <span class="form-field__error field-error"></span>
                            </div>
                        </div>
                        <!--//Сметная стоимость//-->
                        <!--Файл ГРБС-->
                        <!--TODO проверка на обязательность-->
                        <div class="form-field field" data-id_file_field data-name="file_grbs" data-required="false" data-multiple="true" data-mapping_level_1="1" data-mapping_level_2="1">
                            <span class="form-field__title">Файл ГРБС</span>
                            <div class="form-field__item">
                                <div class="form-field__file-block">
                                    <div class="form-field__select field-select modal-file">
                                        <span class="form-field__value">Загрузите файлы</span>
                                        <i class="form-field__icon-misc fas fa-file"></i>
                                        <i class="form-field__icon-filled fas fa-check"></i>
                                    </div>
                                    <div class="files form-field__files"></div>
                                </div>
                                <span class="form-field__error field-error"></span>
                            </div>
                            <input class="form-field__result field-result" type="hidden" name="file_grbs">
                        </div>
                        <!--Файл ГРБС-->
                    </div>
                    <!--//Блок смета//-->

                    <!--Кадастровый номер земельного участка-->
                    <div class="form-field field" data-name="cadastral_number" data-pattern="number">
                        <span class="form-field__title">Кадастровый номер земельного участка</span>
                        <div class="form-field__item">
                            <div class="form-field__body">
                                <input class="form-field__input field-result" type="text" name="cadastral_number" placeholder="Введите значение">
                            </div>
                            <span class="form-field__error field-error"></span>
                        </div>
                    </div>
                    <!--//Кадастровый номер земельного участка//-->

                    <!--CHECKBOX Объект культурного наследия-->
                    <div class="form-field field" data-name="cultural_object_type_checkbox" data-multiple="false">
                        <span class="form-field__title">Объект культурного наследия</span>
                        <div class="form-field__item">
                            <div class="radio form-field__body" data-required="true">
                                <div class="radio__body inline">
                                    <div class="radio__item" data-id="1">
                                        <i class="radio__icon far fa-square"></i>
                                        <span class="radio__text">Да</span>
                                    </div>
                                    <div class="radio__item" data-id="0" data-selected="true">
                                        <i class="radio__icon far fa-check-square"></i>
                                        <span class="radio__text">Нет</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input class="form-field__result field-result" type="hidden" name="cultural_object_type_checkbox">
                    </div>
                    <!--//CHECKBOX Объект культурного наследия//-->

                    <!--Блок культурное наследие-->
                    <div class="card-form__block" data-block data-name="cultural_object_type" data-active="false">
                        <!--Тип объекта культурного наследия-->
                        <div class="form-field field" data-misc_field data-name="cultural_object_type" data-required="true">
                            <span class="form-field__title field-title">Тип объекта культурного наследия</span>
                            <div class="form-field__item">
                                <div class="form-field__body">
                                    <div class="form-field__select field-select" data-misc_select>
                                        <span class="form-field__value field-value" data-misc_value>Выберите значение</span>
                                        <i class="form-field__icon-misc fas fa-bars"></i>
                                        <i class="form-field__icon-filled fas fa-check"></i>
                                    </div>
                                    <i class="form-field__icon-clear fas fa-times"></i>
                                </div>
                                <span class="form-field__error field-error">Поле обязательно для заполнения</span>
                            </div>
                            <div class="modal" data-misc_modal data-result_callback="document_field">
                                <i class="modal__close fas fa-times" data-misc_close></i>
                                <div class="modal__items" data-misc_body>
                                    <?php foreach ($_VT->getValue('cultural_object_type') as $pageNumber => $page): ?>
                                        <div class="modal__page" data-misc_page="<?= $pageNumber ?>">
                                            <?php foreach ($page as $item): ?>
                                                <div class="modal__item" data-misc_item data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <input class="form-field__result field-result" type="hidden" data-misc_result name="cultural_object_type">
                        </div>
                        <!--//Тип объекта культурного наследия//-->
                    </div>
                    <!--//Блок культурное наследие//-->

                    <!--CHECKBOX Национальный проект-->
                    <div class="form-field field" data-name="national_project_checkbox">
                        <span class="form-field__title">Национальный проект</span>
                        <div class="form-field__item">
                            <div class="radio form-field__body" data-required="true">
                                <div class="radio__body inline">
                                    <div class="radio__item" data-id="1">
                                        <i class="radio__icon far fa-square"></i>
                                        <span class="radio__text">Да</span>
                                    </div>
                                    <div class="radio__item" data-id="0" data-selected="true">
                                        <i class="radio__icon far fa-check-square"></i>
                                        <span class="radio__text">Нет</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input class="form-field__result field-result" type="hidden" name="national_project_checkbox">
                    </div>
                    <!--//CHECKBOX Национальный проект//-->

                    <!--Блок национальный проект-->
                    <div class="card-form__block" data-block data-name="national_project" data-active="false">
                        <!--Название национального проекта-->
                        <div class="form-field field" data-misc_field data-name="national_project" data-required="true">
                            <span class="form-field__title field-title">Название национального проекта</span>
                            <div class="form-field__item">
                                <div class="form-field__body">
                                    <div class="form-field__select field-select" data-misc_select>
                                        <span class="form-field__value field-value" data-misc_value>Выберите значение</span>
                                        <i class="form-field__icon-misc fas fa-bars"></i>
                                        <i class="form-field__icon-filled fas fa-check"></i>
                                    </div>
                                    <i class="form-field__icon-clear fas fa-times"></i>
                                </div>
                                <span class="form-field__error field-error">Поле обязательно для заполнения</span>
                            </div>
                            <div class="modal" data-misc_modal data-result_callback="document_field">
                                <i class="modal__close fas fa-times" data-misc_close></i>
                                <div class="modal__items" data-misc_body>
                                    <?php foreach ($_VT->getValue('national_project') as $pageNumber => $page): ?>
                                        <div class="modal__page" data-misc_page="<?= $pageNumber ?>">
                                            <?php foreach ($page as $item): ?>
                                                <div class="modal__item" data-misc_item data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <input class="form-field__result field-result" data-misc_result type="hidden" name="national_project">
                        </div>
                        <!--//Название национального проекта//-->

                        <!--Название федерального проекта-->
                        <div class="form-field field" data-misc_field data-name="federal_project" data-required="true">
                            <span class="form-field__title field-title">Название федерального проекта</span>
                            <div class="form-field__item">
                                <div class="form-field__body">
                                    <div class="form-field__select field-select" data-misc_select>
                                        <span class="form-field__value field-value" data-misc_value>Выберите значение</span>
                                        <i class="form-field__icon-misc fas fa-bars"></i>
                                        <i class="form-field__icon-filled fas fa-check"></i>
                                    </div>
                                    <i class="form-field__icon-clear fas fa-times"></i>
                                </div>
                                <span class="form-field__error field-error">Поле обязательно для заполнения</span>
                            </div>
                            <div class="modal" data-misc_modal data-result_callback="document_field">
                                <i class="modal__close fas fa-times" data-misc_close></i>
                                <div class="modal__items" data-misc_body></div>
                            </div>
                            <input class="form-field__result field-result" data-misc_result type="hidden" name="federal_project">
                        </div>
                        <!--//Название федерального проекта//-->

                        <!--Дата окончания строительства-->
                        <div class="form-field field" data-name="date_finish_building" data-pattern="date">
                            <span class="form-field__title">Дата окончания строительства</span>
                            <div class="form-field__item">
                                <div class="form-field__body">
                                    <div class="form-field__select field-select modal-calendar">
                                        <span class="form-field__value field-value">Выберите дату</span>
                                        <i class="form-field__icon-misc fas fa-calendar-alt"></i>
                                        <i class="form-field__icon-filled fas fa-check"></i>
                                    </div>
                                    <i class="form-field__icon-clear fas fa-times"></i>
                                </div>
                            </div>
                            <input class="form-field__result field-result" type="hidden" name="date_finish_building">
                        </div>
                        <!--//Дата окончания строительства//-->
                    </div>
                    <!--//Блок национальный проект//-->

                    <!--Куратор-->
                    <div class="form-field field" data-misc_field data-name="curator" data-required="true">
                        <span class="form-field__title field-title">Куратор</span>
                        <div class="form-field__item">
                            <div class="form-field__body">
                                <div class="form-field__select field-select" data-misc_select>
                                    <span class="form-field__value field-value" data-misc_value>Выберите значение</span>
                                    <i class="form-field__icon-misc fas fa-bars"></i>
                                    <i class="form-field__icon-filled fas fa-check"></i>
                                </div>
                            </div>
                            <span class="form-field__error field-error">Поле обязательно для заполнения</span>
                        </div>
                        <div class="modal" data-misc_modal data-result_callback="document_field">
                            <i class="modal__close fas fa-times" data-misc_close></i>
                            <div class="modal__items" data-misc_body>
                                <?php foreach ($_VT->getValue('curator') as $pageNumber => $page): ?>
                                    <div class="modal__page" data-misc_page="<?= $pageNumber ?>">
                                        <?php foreach ($page as $item): ?>
                                            <div class="modal__item" data-misc_item data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <input class="form-field__result field-result" type="hidden" data-misc_result name="curator">
                    </div>
                    <!--//Куратор//-->
                </div>
            </div>

            <div class="card-form application-form__card card" data-type="financing_sources">
                <div class="card-form__header card-expand">
                    <span class="card-form__title">СВЕДЕНИЯ ОБ ИСТОЧНИКАХ ФИНАНСИРОВАНИЯ</span>
                    <i class="card-form__icon-expand fas fa-chevron-down arrow-down card-icon"></i>
                </div>
                <div class="card-form__body card-body">

                    <!--Источники финансирования-->
                    <div class="multiple-block card-form__block" data-block data-type="multiple" data-name="financing_sources" data-saved="true">

                        <div class="multiple-block__add" data-multiple_add="add_financing_source">
                            <span class="multiple-block__text">Добавить источник финансирования</span>
                            <i class="multiple-block__icon fas fa-plus"></i>
                        </div>

                        <!--Шаблоны источников финансирования-->
                        <div class="multiple-block__item" data-block data-name="templates_container">
                            <!--Шаблон элемента множественного блока-->
                            <div class="multiple-block__part" data-block data-name="multiple_block_part" data-active="false" data-dependency_scope>
                                <div class="multiple-block__title" data-multiple_title>...</div>
                            </div>
                            <!--Шаблон элемента множественного блока-->
                            <!--Шаблон "Вид финансирования"-->
                            <div class="multiple-block__item" data-block data-type="template" data-name="type" data-active="false">
                                <div class="form-field field" data-name="financing_type" data-required="true">
                                    <span class="form-field__title field-title">Вид финансирования</span>
                                    <div class="form-field__item">
                                        <div class="radio form-field__body" data-required="true" data-result_callback="financing_type">
                                            <div class="radio__body">
                                                <div class="radio__item" data-id="1">
                                                    <i class="radio__icon far fa-square"></i>
                                                    <span class="radio__text" data-part_title="1">Бюджетные средства</span>
                                                </div>
                                                <div class="radio__item" data-id="2">
                                                    <i class="radio__icon far fa-square"></i>
                                                    <span class="radio__text" data-part_title="2">Средства юридических лиц, указанных в ч. 2 статьи 48.2 ГрК</span>
                                                </div>
                                                <div class="radio__item" data-id="3">
                                                    <i class="radio__icon far fa-square"></i>
                                                    <span class="radio__text" data-part_title="3">Собственные средства застройщика</span>
                                                </div>
                                                <div class="radio__item" data-id="4">
                                                    <i class="radio__icon far fa-square"></i>
                                                    <span class="radio__text" data-part_title="4">Средства инвестора</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input class="form-field__result field-result" type="hidden" data-multiple_block_field="type" name="financing_type">
                                </div>
                            </div>
                            <!--//Шаблон "Вид финансирования"//-->
                            <!--Шаблон "Бюджетные средства"-->
                            <div class="multiple-block__item" data-block data-type="template" data-name="budget" data-active="false">
                                <div class="form-field field" data-misc_field data-name="budget_level" data-required="true">
                                    <span class="form-field__title field-title">Уровень бюджета</span>
                                    <div class="form-field__item">
                                        <div class="form-field__body">
                                            <div class="form-field__select field-select" data-misc_select>
                                                <span class="form-field__value field-value" data-misc_value>Выберите значение</span>
                                                <i class="form-field__icon-misc fas fa-bars"></i>
                                                <i class="form-field__icon-filled fas fa-check"></i>
                                            </div>
                                        </div>
                                        <span class="form-field__error field-error">Поле обязательно для заполнения</span>
                                    </div>
                                    <div class="modal" data-misc_modal data-result_callback="document_field">
                                        <i class="modal__close fas fa-times" data-misc_close></i>
                                        <div class="modal__items" data-misc_body>
                                            <?php foreach ($_VT->getValue('budget_level') as $pageNumber => $page): ?>
                                                <div class="modal__page" data-misc_page="<?= $pageNumber ?>">
                                                    <?php foreach ($page as $item): ?>
                                                        <div class="modal__item" data-misc_item data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <input class="form-field__result field-result" data-misc_result type="hidden" data-multiple_block_field="budget_level" name="budget_level">
                                </div>
                                <div class="multiple-block__item" data-block data-type="template" data-name="financing_source_no_data" data-active="false">
                                    <div class="form-field field" data-name="financing_source_no_data">
                                        <span class="form-field__title">Размер финансирования</span>
                                        <div class="form-field__item">
                                            <div class="radio form-field__body">
                                                <div class="radio__body">
                                                    <div class="radio__item" data-id="1">
                                                        <i class="radio__icon far fa-square"></i>
                                                        <span class="radio__text">Нет данных</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input class="form-field__result field-result" type="hidden" data-multiple_block_field="no_data" name="financing_source_no_data">
                                    </div>
                                    <div class="multiple-block__item" data-block data-type="template" data-name="percent" data-active="true">
                                        <div class="form-field field" data-required="true" data-name="percent" data-pattern="number">
                                            <span class="form-field__title">Процент финансирования</span>
                                            <div class="form-field__item">
                                                <div class="form-field__body">
                                                    <input class="form-field__input field-result" type="text" data-multiple_block_field="percent" name="percent" placeholder="Введите процент">
                                                </div>
                                                <span class="form-field__error field-error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--//Шаблон "Бюджетные средства"//-->
                            <!--Шаблон "Средства юридических лиц"-->
                            <div class="multiple-block__item" data-block data-type="template" data-name="organization" data-active="false">
                                <div class="form-field field" data-required="true" data-name="full_name">
                                    <span class="form-field__title">Полное наименование</span>
                                    <div class="form-field__item">
                                        <div class="form-field__body">
                                            <input class="form-field__input field-result" type="text" data-multiple_block_field="full_name" name="full_name" placeholder="Введите значение">
                                        </div>
                                        <span class="form-field__error field-error"></span>
                                    </div>
                                </div>
                                <div class="form-field field" data-required="true" data-name="INN" data-pattern="inn">
                                    <span class="form-field__title">ИНН</span>
                                    <div class="form-field__item">
                                        <div class="form-field__body">
                                            <input class="form-field__input field-result" type="text" data-multiple_block_field="INN" name="INN" placeholder="Введите значение">
                                        </div>
                                        <span class="form-field__error field-error"></span>
                                    </div>
                                </div>
                                <div class="form-field field" data-required="true" data-name="KPP">
                                    <span class="form-field__title">КПП</span>
                                    <div class="form-field__item">
                                        <div class="form-field__body">
                                            <input class="form-field__input field-result" type="text" data-multiple_block_field="KPP" name="KPP" placeholder="Введите значение">
                                        </div>
                                        <span class="form-field__error field-error"></span>
                                    </div>
                                </div>
                                <div class="form-field field" data-required="true" data-name="OGRN">
                                    <span class="form-field__title">ОГРН</span>
                                    <div class="form-field__item">
                                        <div class="form-field__body">
                                            <input class="form-field__input field-result" type="text" data-multiple_block_field="OGRN" name="OGRN" placeholder="Введите значение">
                                        </div>
                                        <span class="form-field__error field-error"></span>
                                    </div>
                                </div>
                                <div class="form-field field" data-required="true" data-name="address">
                                    <span class="form-field__title">Адрес</span>
                                    <div class="form-field__item">
                                        <div class="form-field__body">
                                            <input class="form-field__input field-result" type="text" data-multiple_block_field="address" name="address" placeholder="Введите значение">
                                        </div>
                                        <span class="form-field__error field-error"></span>
                                    </div>
                                </div>
                                <div class="form-field field" data-required="true" data-name="location">
                                    <span class="form-field__title">Место нахождения</span>
                                    <div class="form-field__item">
                                        <div class="form-field__body">
                                            <input class="form-field__input field-result" type="text" data-multiple_block_field="location" name="location" placeholder="Введите значение">
                                        </div>
                                        <span class="form-field__error field-error"></span>
                                    </div>
                                </div>
                                <div class="form-field field" data-required="true" data-name="telephone">
                                    <span class="form-field__title">Телефон</span>
                                    <div class="form-field__item">
                                        <div class="form-field__body">
                                            <input class="form-field__input field-result" type="text" data-multiple_block_field="telephone" name="telephone" placeholder="Введите значение">
                                        </div>
                                        <span class="form-field__error field-error"></span>
                                    </div>
                                </div>
                                <div class="form-field field" data-required="true" data-name="email">
                                    <span class="form-field__title">Адрес электронной почты</span>
                                    <div class="form-field__item">
                                        <div class="form-field__body">
                                            <input class="form-field__input field-result" type="text" data-multiple_block_field="email" name="email" placeholder="Введите значение">
                                        </div>
                                        <span class="form-field__error field-error"></span>
                                    </div>
                                </div>
                                <div class="multiple-block__item" data-block data-type="template" data-name="financing_source_no_data" data-active="false">
                                    <div class="form-field field" data-name="financing_source_no_data">
                                        <span class="form-field__title">Размер финансирования</span>
                                        <div class="form-field__item">
                                            <div class="radio form-field__body">
                                                <div class="radio__body">
                                                    <div class="radio__item" data-id="1">
                                                        <i class="radio__icon far fa-square"></i>
                                                        <span class="radio__text">Нет данных</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input class="form-field__result field-result" type="hidden" data-multiple_block_field="no_data" name="financing_source_no_data">
                                    </div>
                                    <div class="multiple-block__item" data-block data-type="template" data-name="percent" data-active="true">
                                        <div class="form-field field" data-required="true" data-name="percent" data-pattern="number">
                                            <span class="form-field__title">Процент финансирования</span>
                                            <div class="form-field__item">
                                                <div class="form-field__body">
                                                    <input class="form-field__input field-result" type="text" data-multiple_block_field="percent" name="percent" placeholder="Введите процент">
                                                </div>
                                                <span class="form-field__error field-error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--//Шаблон "Средства юридических лиц"//-->
                            <!--Шаблон "Собственные средства застройщика"-->
                            <div class="multiple-block__item" data-block data-type="template" data-name="builder_source" data-active="false">
                                <div class="multiple-block__item" data-block data-type="template" data-name="financing_source_no_data" data-active="false">
                                    <div class="form-field field" data-name="financing_source_no_data">
                                        <span class="form-field__title">Размер финансирования</span>
                                        <div class="form-field__item">
                                            <div class="radio form-field__body">
                                                <div class="radio__body">
                                                    <div class="radio__item" data-id="1">
                                                        <i class="radio__icon far fa-square"></i>
                                                        <span class="radio__text">Нет данных</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input class="form-field__result field-result" type="hidden" data-multiple_block_field="no_data" name="financing_source_no_data">
                                    </div>
                                    <div class="multiple-block__item" data-block data-type="template" data-name="percent" data-active="true">
                                        <div class="form-field field" data-required="true" data-name="percent" data-pattern="number">
                                            <span class="form-field__title">Процент финансирования</span>
                                            <div class="form-field__item">
                                                <div class="form-field__body">
                                                    <input class="form-field__input field-result" type="text" data-multiple_block_field="percent" name="percent" placeholder="Введите процент">
                                                </div>
                                                <span class="form-field__error field-error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--//Шаблон "Собственные средства застройщика"//-->
                            <!--Шаблон "Средства инвестора"-->
                            <div class="multiple-block__item" data-block data-type="template" data-name="investor" data-active="false">
                                <div class="multiple-block__item" data-block data-type="template" data-name="financing_source_no_data" data-active="false">
                                    <div class="form-field field" data-name="financing_source_no_data">
                                        <span class="form-field__title">Размер финансирования</span>
                                        <div class="form-field__item">
                                            <div class="radio form-field__body">
                                                <div class="radio__body">
                                                    <div class="radio__item" data-id="1">
                                                        <i class="radio__icon far fa-square"></i>
                                                        <span class="radio__text">Нет данных</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input class="form-field__result field-result" type="hidden" data-multiple_block_field="no_data" name="financing_source_no_data">
                                    </div>
                                    <div class="multiple-block__item" data-block data-type="template" data-name="percent" data-active="true">
                                        <div class="form-field field" data-required="true" data-name="percent" data-pattern="number">
                                            <span class="form-field__title">Процент финансирования</span>
                                            <div class="form-field__item">
                                                <div class="form-field__body">
                                                    <input class="form-field__input field-result" type="text" data-multiple_block_field="percent" name="percent" placeholder="Введите процент">
                                                </div>
                                                <span class="form-field__error field-error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--//Шаблон "Средства инвестора"//-->
                            <!--Шаблон действий-->
                            <div class="multiple-block__item" data-block data-name="actions" data-active="false">
                                <div class="multiple-block__actions">
                                    <div class="multiple-block__button save">
                                        <span class="multiple-block__text">Сохранить источник финансирования</span>
                                        <i class="multiple-block__icon fas fa-check"></i>
                                    </div>
                                    <div class="multiple-block__button cancel">
                                        <span class="multiple-block__text">Отмена</span>
                                        <i class="multiple-block__icon fas fa-times"></i>
                                    </div>
                                </div>
                            </div>
                            <!--//Шаблон действий//-->
                            <!--Шаблон сохраненного блока-->
                            <div class="multiple-block__item" data-block data-name="part_short" data-active="false">
                                <div class="multiple-block__short part-short">
                                    <span class="multiple-block__info" data-part_info></span>
                                    <i class="multiple-block__delete fas fa-times delete"></i>
                                </div>
                            </div>
                            <!--//Шаблон сохраненного блока//-->
                        </div>
                        <!--//Шаблоны источников финансирования//-->

                    </div>
                    <!--//Источники финансирования//-->
                </div>
            </div>

        </div>

        <div class="card-form application-form__card card" data-type="documentation">
            <div class="card-form__header card-expand">
                <span class="card-form__title">ДОКУМЕНТАЦИЯ</span>
                <i class="card-form__icon-expand fas fa-chevron-down arrow-down card-icon"></i>
            </div>
            <div class="card-form__body card-body">
                <div class="card-form__block" data-block data-name="empty_documentation" data-active="true">
                    <div class="card-form__message">
                        <i class="card-form__icon-message fas fa-exclamation"></i>
                        <span class="card-form__message-text">Для отображения структуры разделов документации выберите вид объекта</span>
                    </div>
                </div>
                <div class="card-form__block" data-block data-name="structureDocumentation1" data-active="false">
                    <div class="documentation field" data-id_file_field data-mapping_level_1="2" data-mapping_level_2="1">
                        <?php foreach ($_VT->getValue('structureDocumentation1') as $node): ?>
                            <div class="documentation__node" data-id_structure_node="<?= $node['id'] ?>">
                                <div class="documentation__header" data-title="<?= $node['is_header'] ? 'true' : 'false' ?>">
                                    <span class="documentation__name" style="padding-left: <?= $node['depth']*25 + 15 ?>px"><?= $node['name'] ?></span>
                                    <?php if (!$node['is_header']): ?>
                                        <i class="documentation__icon modal-file fas fa-plus"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="documentation__files files" data-depth="<?= $node['depth'] ?>"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="card-form__block" data-block data-name="structureDocumentation2" data-active="false">
                    <div class="documentation field" data-id_file_field data-mapping_level_1="2" data-mapping_level_2="2">
                        <?php foreach ($_VT->getValue('structureDocumentation2') as $node): ?>
                            <div class="documentation__node" data-id_structure_node="<?= $node['id'] ?>">
                                <div class="documentation__header" data-title="<?= $node['is_header'] ? 'true' : 'false' ?>">
                                    <span class="documentation__name" style="padding-left: <?= $node['depth']*25 + 15 ?>px"><?= $node['name'] ?></span>
                                    <?php if (!$node['is_header']): ?>
                                        <i class="documentation__icon modal-file fas fa-plus"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="documentation__files files" data-depth="<?= $node['depth'] ?>"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="misc_overlay" class="modal-overlay"></div>

<!--<div class="alert-overlay"></div>
<div class="modal alert-modal">
    <div class="alert-modal__info">
        <i class="alert-modal__icon fas fa-check"></i>
        <span class="alert-modal__text">Заявление сохранено</span>
    </div>
    <div class="alert-modal__actions">
        <span class="alert-modal__button application-button">Продолжить редактирование</span>
        <a href="/home/navigation" class="alert-modal__button application-button">Выйти в личный кабинет</a>
    </div>
</div>-->

<div class="save-overlay"></div>
<div class="modal save-modal">
    <div class="save-modal__info">
        <i class="save-modal__icon fas fa-check"></i>
        <span class="save-modal__text">Заявление сохранено</span>
    </div>
    <div class="save-modal__actions">
        <span class="save-modal__close application-button">Продолжить редактирование</span>
        <a href="/home/navigation" class="save-modal__link application-button">Выйти в личный кабинет</a>
    </div>
</div>