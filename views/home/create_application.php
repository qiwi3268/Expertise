<?php $variablesTV = VariableTransfer::getInstance(); ?>

<div class="application-form__header header-form">
    <div class="header-form__title">Заявление на экспертизу <?= $variablesTV->getValue('numerical_name') ?></div>
    <div class="header-form__actions">
        <div id="application_save" class="header-form__btn">
            <span class="header-form__text">Сохранить</span>
            <i class="header-form__icon fas fa-save"></i>
        </div>
        <div class="header-form__btn">
            <span class="header-form__text">Удалить</span>
            <i class="header-form__icon fas fa-trash"></i>
        </div>
    </div>
</div>
<div class="application-form__body">
    <div class="application-form__sidebar sidebar-form">
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
            <span class="sidebar-form__text">Плательщик</span>
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
    <form id="application" class="application-form__cards" action="" method="POST">
        <input type="hidden" value="<?= $variablesTV->getValue('id_application') ?>" name="id_application">

        <div class="application-form__card card-form" data-type="purpose">
            <div class="card-form__header">
                <span class="card-form__title">СВЕДЕНИЯ О ЦЕЛИ ОБРАЩЕНИЯ</span>
                <i class="card-form__icon-expand fas fa-chevron-down arrow-down"></i>
            </div>
            <div class="card-form__body body-card">
                <!--Цель обращения-->
                <div class="body-card__row field" data-row_name="expertise_purpose" data-required="true">
                    <span class="body-card__title field-title required">Цель обращения</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select field-select modal-select">
                                <span class="body-card__value field-value">Выберите значение</span>
                            </div>
                        </div>
                        <span class="body-card__error field-error">Поле обязательно для заполнения</span>
                    </div>

                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items">
                            <?php foreach ($variablesTV->getValue('expertise_purpose') as $pageNumber => $page): ?>
                                <div class="modal__page" data-page="<?= $pageNumber ?>">
                                    <?php foreach ($page as $item): ?>
                                        <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input class="body-card__result field-result" type="hidden" name="expertise_purpose">
                </div>
                <!--//Цель обращения//-->

                <!--Предмет экспертизы-->
                <div class="body-card__row field center" data-row_name="expertise_subjects" data-required="true">
                    <span class="body-card__title required">Предмет экспертизы</span>
                    <div class="body-card__item">
                        <div class="body-card__field radio" data-multiple="true" data-required="true">
                            <div class="radio__body">
                                <span class="radio__title">Выберите цель обращения</span>
                            </div>
                        </div>
                    </div>
                    <input class="body-card__result field-result" type="hidden" name="expertise_subjects">
                </div>
                <!--//Предмет экспертизы//-->

                <!--Дополнительная информация-->
                <div class="body-card__row field" data-row_name="additional_information" data-pattern="text">
                    <span class="body-card__title">Дополнительная информация</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <textarea class="body-card__input application-input body-card__result field-result" name="additional_information"></textarea>
                        </div>
                        <span class="body-card__error field-error"></span>
                    </div>
                </div>
                <!--//Дополнительная информация//-->
            </div>
        </div>

        <div class="application-form__card card-form" data-type="object">
            <div class="card-form__header">
                <span class="card-form__title">
                    СВЕДЕНИЯ ОБ ОБЪЕКТЕ
                </span>
                <i class="card-form__icon-expand fas fa-chevron-down arrow-down"></i>
            </div>
            <div class="card-form__body body-card">
                <!--Наименование объекта-->
                <div class="body-card__row field" data-row_name="object_name" data-required="true" data-pattern="text">
                    <span class="body-card__title required">Наименование объекта</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <textarea class="body-card__input body-card__result field-result application-input" name="object_name"></textarea>
                        </div>
                        <span class="body-card__error field-error"></span>
                    </div>
                </div>
                <!--//Наименование объекта//-->

                <!--Вид объекта-->
                <div class="body-card__row field" data-row_name="type_of_object" data-required="true">
                    <span class="body-card__title field-title required">Вид объекта</span>

                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select field-select modal-select">
                                <span class="body-card__value field-value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                        </div>
                        <span class="body-card__error field-error">Поле обязательно для заполнения</span>
                    </div>

                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items">
                            <?php foreach ($variablesTV->getValue('type_of_object') as $pageNumber => $page): ?>
                                <div class="modal__page" data-page="<?= $pageNumber ?>">
                                    <?php foreach ($page as $item): ?>
                                        <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input class="body-card__result field-result" type="hidden" name="type_of_object">
                </div>
                <!--//Вид объекта//-->

                <!--Функциональное назначение-->
                <div class="body-card__row field" data-row_name="functional_purpose" data-required="true">
                    <span class="body-card__title field-title required">Функциональное назначение</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select field-select modal-select">
                                <span class="body-card__value field-value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                        </div>
                        <span class="body-card__error field-error">Поле обязательно для заполнения</span>
                    </div>
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items">
                            <?php foreach ($variablesTV->getValue('functional_purpose') as $pageNumber => $page): ?>
                                <div class="modal__page" data-page="<?= $pageNumber ?>">
                                    <?php foreach ($page as $item): ?>
                                        <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input class="body-card__result field-result" type="hidden" name="functional_purpose">
                </div>
                <!--//Функциональное назначение//-->

                <!--Функциональное назначение. Подотрасль-->
                <div class="body-card__row field" data-row_name="functional_purpose_subsector" data-required="true">
                    <span class="body-card__title field-title required">Функциональное назначение. Подотрасль</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select field-select modal-select">
                                <span class="body-card__value field-value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-trash"></i>
                        </div>
                        <span class="body-card__error field-error">Поле обязательно для заполнения</span>
                    </div>
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items"></div>
                    </div>
                    <input class="body-card__result field-result" type="hidden" name="functional_purpose_subsector">
                </div>
                <!--//Функциональное назначение. Подотрасль//-->

                <!--Функциональное назначение. Группа-->
                <div class="body-card__row field" data-row_name="functional_purpose_group" data-required="true">
                    <span class="body-card__title field-title required">Функциональное назначение. Группа</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select field-select modal-select">
                                <span class="body-card__value field-value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-trash"></i>
                        </div>
                        <span class="body-card__error field-error">Поле обязательно для заполнения</span>
                    </div>
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items"></div>
                    </div>
                    <input class="body-card__result field-result" type="hidden" name="functional_purpose_group">
                </div>
                <!--//Функциональное назначение. Группа//-->

                <!--Блок производственные/непроизводственные объекты капитального строительства-->
                <div class="body-card__block" data-block_name="planning_documentation_approval" data-inactive="true">
                    <!--Номер утверждения документации по планировке территории-->
                    <div class="body-card__row field" data-row_name="number_planning_documentation_approval" data-pattern="number">
                        <span class="body-card__title">Номер утверждения документации по планировке территории</span>
                        <div class="body-card__item">
                            <div class="body-card__field">
                                <input class="body-card__input body-card__result field-result application-input" type="text" name="number_planning_documentation_approval" placeholder="Введите значение">
                            </div>
                            <span class="body-card__error field-error"></span>
                        </div>
                    </div>
                    <!--//Номер утверждения документации по планировке территории//-->
                    <!--Дата утверждения документации по планировке территории-->
                    <div class="body-card__row field" data-row_name="date_planning_documentation_approval" data-pattern="date">
                        <span class="body-card__title">Дата утверждения документации по планировке территории</span>
                        <div class="body-card__item">
                            <div class="body-card__field">
                                <div class="body-card__select field-select modal-calendar">
                                    <span class="body-card__value field-value">Выберите дату</span>
                                    <i class="body-card__icon fas fa-calendar-alt"></i>
                                </div>
                                <i class="body-card__icon-clear fas fa-calendar-times"></i>
                            </div>
                        </div>
                        <input class="body-card__result field-result" type="hidden" name="date_planning_documentation_approval">
                    </div>
                    <!--//Дата утверждения документации по планировке территории//-->
                </div>
                <!--//Блок производственные/непроизводственные объекты капитального строительства//-->


                <!--Блок линейные объекты капитального строительства-->
                <div class="body-card__block" data-block_name="GPZU" data-inactive="true">
                    <!--Номер ГПЗУ-->
                    <div class="body-card__row field" data-row_name="number_GPZU" data-pattern="">
                        <span class="body-card__title">Номер ГПЗУ</span>
                        <div class="body-card__item">
                            <div class="body-card__field">
                                <input class="body-card__input body-card__result field-result application-input" type="text" name="number_GPZU" placeholder="Введите значение">
                                <span class="body-card__error field-error"></span>
                            </div>
                        </div>
                    </div>
                    <!--//Номер ГПЗУ//-->
                    <!--Дата ГПЗУ-->
                    <div class="body-card__row field" data-row_name="date_GPZU" data-pattern="date">
                        <span class="body-card__title">Дата ГПЗУ</span>
                        <div class="body-card__item">
                            <div class="body-card__field">
                                <div class="body-card__select field-select modal-calendar">
                                    <span class="body-card__value field-value">Выберите дату</span>
                                    <i class="body-card__icon fas fa-calendar-alt"></i>
                                </div>
                                <i class="body-card__icon-clear fas fa-calendar-times"></i>
                            </div>
                        </div>
                        <input class="body-card__result field-result" type="hidden" name="date_GPZU">
                    </div>
                <!--//Дата ГПЗУ//-->
                </div>
                <!--//Блок линейные объекты капитального строительства//-->


                <!--Вид работ-->
                <div class="body-card__row field" data-row_name="type_of_work" data-required="true">
                    <span class="body-card__title field-title required">Вид работ</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select field-select modal-select">
                                <span class="body-card__value field-value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-trash"></i>
                        </div>
                        <span class="body-card__error field-error">Поле обязательно для заполнения</span>
                    </div>
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items"></div>
                    </div>
                    <input class="body-card__result field-result" type="hidden" name="type_of_work">
                </div>
                <!--//Вид работ//-->

                <!--Кадастровый номер земельного участка-->
                <div class="body-card__row field" data-row_name="cadastral_number" data-pattern="number">
                    <span class="body-card__title">Кадастровый номер земельного участка</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <input class="body-card__input body-card__result field-result application-input" type="text" name="cadastral_number" placeholder="Введите значение">
                        </div>
                        <span class="body-card__error field-error"></span>
                    </div>
                </div>
                <!--//Кадастровый номер земельного участка//-->

                <!--CHECKBOX Объект культурного наследия-->
                <div class="body-card__row field" data-row_name="cultural_object_type_checkbox" data-multiple="false">
                    <span class="body-card__title">Объект культурного наследия</span>
                    <div class="body-card__item">
                        <div class="body-card__field radio" data-required="true">
                            <div class="radio__body inline">
                                <div class="radio__item inline" data-id="1">
                                    <i class="radio__icon inline far fa-square"></i>
                                    <span class="radio__text">Да</span>
                                </div>
                                <div class="radio__item inline selected" data-id="0">
                                    <i class="radio__icon inline far fa-check-square"></i>
                                    <span class="radio__text">Нет</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input class="body-card__result field-result" type="hidden" name="cultural_object_type_checkbox">
                </div>
                <!--//CHECKBOX Объект культурного наследия//-->

                <!--Блок культурное наследие-->
                <div class="body-card__block" data-block_name="cultural_object_type" data-inactive="true">
                    <!--Тип объекта культурного наследия-->
                    <div class="body-card__row field" data-row_name="cultural_object_type" data-required="true">
                        <span class="body-card__title field-title required">Тип объекта культурного наследия</span>
                        <div class="body-card__item">
                            <div class="body-card__field">
                                <div class="body-card__select field-select modal-select">
                                    <span class="body-card__value field-value">Выберите значение</span>
                                    <i class="body-card__icon fas fa-bars"></i>
                                </div>
                                <i class="body-card__icon-clear fas fa-trash"></i>
                            </div>
                            <span class="body-card__error field-error">Поле обязательно для заполнения</span>
                        </div>
                        <div class="modal">
                            <i class="modal__close fas fa-times"></i>
                            <div class="modal__items">
                                <?php foreach ($variablesTV->getValue('cultural_object_type') as $pageNumber => $page): ?>
                                    <div class="modal__page" data-page="<?= $pageNumber ?>">
                                        <?php foreach ($page as $item): ?>
                                            <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <input class="body-card__result field-result" type="hidden" name="cultural_object_type">
                    </div>
                    <!--//Тип объекта культурного наследия//-->
                </div>
                <!--//Блок культурное наследие//-->


                <!--CHECKBOX Национальный проект-->
                <div class="body-card__row field center" data-row_name="national_project_checkbox">
                    <span class="body-card__title">Национальный проект</span>
                    <div class="body-card__item">
                        <div class="body-card__field radio" data-required="true">
                            <div class="radio__body inline">
                                <div class="radio__item inline" data-id="1">
                                    <i class="radio__icon inline far fa-square"></i>
                                    <span class="radio__text">Да</span>
                                </div>
                                <div class="radio__item inline selected" data-id="0">
                                    <i class="radio__icon inline far fa-check-square"></i>
                                    <span class="radio__text">Нет</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input class="body-card__result field-result" type="hidden" name="national_project_checkbox">
                </div>
                <!--//CHECKBOX Национальный проект//-->


                <!--Блок национальный проект-->
                <div class="body-card__block" data-block_name="national_project" data-inactive="true">
                    <!--Название национального проекта-->
                    <div class="body-card__row field" data-row_name="national_project" data-required="true">
                        <span class="body-card__title field-title required">Название национального проекта</span>
                        <div class="body-card__item">
                            <div class="body-card__field">
                                <div class="body-card__select field-select modal-select">
                                    <span class="body-card__value field-value">Выберите значение</span>
                                    <i class="body-card__icon fas fa-bars"></i>
                                </div>
                                <i class="body-card__icon-clear fas fa-trash"></i>
                            </div>
                            <span class="body-card__error field-error">Поле обязательно для заполнения</span>
                        </div>
                        <div class="modal">
                            <i class="modal__close fas fa-times"></i>
                            <div class="modal__items">
                                <?php foreach ($variablesTV->getValue('national_project') as $pageNumber => $page): ?>
                                    <div class="modal__page" data-page="<?= $pageNumber ?>">
                                        <?php foreach ($page as $item): ?>
                                            <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <input class="body-card__result field-result" type="hidden" name="national_project">
                    </div>
                    <!--//Название национального проекта//-->

                    <!--Название федерального проекта-->
                    <div class="body-card__row field" data-row_name="federal_project" data-required="true">
                        <span class="body-card__title field-title required">Название федерального проекта</span>
                        <div class="body-card__item">
                            <div class="body-card__field">
                                <div class="body-card__select field-select modal-select">
                                    <span class="body-card__value field-value">Выберите значение</span>
                                    <i class="body-card__icon fas fa-bars"></i>
                                </div>
                                <i class="body-card__icon-clear fas fa-trash"></i>
                            </div>
                            <span class="body-card__error field-error">Поле обязательно для заполнения</span>
                        </div>
                        <div class="modal">
                            <i class="modal__close fas fa-times"></i>
                            <div class="modal__items"></div>
                        </div>
                        <input class="body-card__result field-result" type="hidden" name="federal_project">
                    </div>
                    <!--//Название федерального проекта//-->

                    <!--Дата окончания строительства-->
                    <div class="body-card__row field" data-row_name="date_finish_building" data-pattern="date">
                        <span class="body-card__title">Дата окончания строительства</span>
                        <div class="body-card__item">
                            <div class="body-card__field">
                                <div class="body-card__select field-select modal-calendar">
                                    <span class="body-card__value field-value">Выберите дату</span>
                                    <i class="body-card__icon fas fa-calendar-alt"></i>
                                </div>
                                <i class="body-card__icon-clear fas fa-calendar-times"></i>
                            </div>
                        </div>
                        <input class="body-card__result field-result" type="hidden" name="date_finish_building">
                    </div>
                    <!--//Дата окончания строительства//-->
                </div>
                <!--//Блок национальный проект//-->


                <!--Куратор-->
                <div class="body-card__row field" data-row_name="curator" data-required="true">
                    <span class="body-card__title field-title required">Куратор</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select field-select modal-select">
                                <span class="body-card__value field-value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                        </div>
                        <span class="body-card__error field-error">Поле обязательно для заполнения</span>
                    </div>
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items">
                            <?php foreach ($variablesTV->getValue('curator') as $pageNumber => $page): ?>
                                <div class="modal__page" data-page="<?= $pageNumber ?>">
                                    <?php foreach ($page as $item): ?>
                                        <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input class="body-card__result field-result" type="hidden" name="curator">
                </div>
                <!--//Куратор//-->


                <!--Загрузчик файла-->
                <div class="body-card__row field" data-required="false" data-multiple="true" data-mapping_level_1="1" data-mapping_level_2="1">
                    <span class="body-card__title required">Файл ГРБС</span>
                    <div class="body-card__item">
                        <!--TODO стили-->
                        <div class="body-card__select field-select modal-file">
                            <span class="body-card__value">Загрузите файлы</span>
                            <i class="body-card__icon fas fa-file"></i>
                        </div>
                        <div class="files"></div>
                    </div>
                </div>
                <!--//Загрузчик файла//-->

                <!--Загрузчик файла2-->
                <div class="body-card__row field" data-required="false" data-multiple="false" data-mapping_level_1="1" data-mapping_level_2="1">
                    <span class="body-card__title required">Файл ГРБС_2</span>
                    <div class="body-card__item">
                        <div class="body-card__select field-select modal-file">
                            <span class="body-card__value">Загрузите файлы</span>
                            <i class="body-card__icon fas fa-file"></i>
                        </div>
                        <div class="files"></div>
                    </div>
                </div>
                <!--//Загрузчик файла2//-->
            </div>
        </div>

        <div class="application-form__card card-form" data-type="documentation">
            <div class="card-form__header">
                <span class="card-form__title">ДОКУМЕНТАЦИЯ</span>
                <i class="card-form__icon-expand fas fa-chevron-down arrow-down"></i>
            </div>
            <div class="card-form__body body-card">
                <div class="body-card__message" data-block_name="empty_documentation" data-inactive="false">
                    <i class="body-card__message-icon fas fa-exclamation"></i>
                    <span class="body-card__message-text">Для отображения структуры разделов документации выберите вид объекта</span>
                </div>
                <div class="documentation" data-block_name="structureDocumentation1" data-mapping_level_1="2" data-mapping_level_2="1" data-inactive="true">
                    <?php foreach ($variablesTV->getValue('structureDocumentation1') as ['id' => $id_structure_node, 'name' => $name, 'depth' => $depth]): ?>
                        <div class="documentation__node" data-id_structure_node="<?= $id_structure_node ?>">
                            <div class="documentation__header">
                                <span class="documentation__name" style="padding-left: <?= $depth*25 + 15 ?>px"><?= $name ?></span>
                                <i class="documentation__icon modal-file fas fa-plus"></i>
                            </div>
                            <div class="documentation__files files" style="padding-left: <?= $depth*25 + 15 ?>px"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="documentation" data-block_name="structureDocumentation2" data-mapping_level_1="2" data-mapping_level_2="2" data-inactive="true">
                    <?php foreach ($variablesTV->getValue('structureDocumentation2') as ['id' => $id_structure_node, 'name' => $name, 'depth' => $depth]): ?>
                        <div class="documentation__node" data-id_structure_node="<?= $id_structure_node ?>">
                            <div class="documentation__header">
                                <span class="documentation__name" style="padding-left: <?= $depth*25 + 15 ?>px"><?= $name ?></span>
                                <i class="documentation__icon modal-file fas fa-plus"></i>
                            </div>
                            <div class="documentation__files files" style="padding-left: <?= $depth*25 + 15 ?>px"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </form>
</div>


<div class="modal-overlay"></div>
<div class="calendar-overlay"></div>
<div class="file-overlay"></div>
<div class="save-overlay"></div>
<div class="sign-overlay"></div>

<div class="modal alert-modal">
    <i class="alert-modal__icon fas fa-exclamation"></i>
    <span class="alert-modal__message"></span>
</div>

<div class="calendar">
    <div class="calendar__nav">
        <i class="calendar__arrow left fas fa-chevron-left"></i>
        <span class="calendar__selected_label"></span>
        <i class="calendar__arrow right fas fa-chevron-right"></i>
    </div>
    <div class="calendar__title">
        <div class="calendar__week-day">Пн</div>
        <div class="calendar__week-day">Вт</div>
        <div class="calendar__week-day">Ср</div>
        <div class="calendar__week-day">Чт</div>
        <div class="calendar__week-day">Пн</div>
        <div class="calendar__week-day">Сб</div>
        <div class="calendar__week-day">Вс</div>
    </div>
    <div class="calendar__body">

    </div>
</div>

<div class="modal file-modal">
    <i class="modal__close active fas fa-times"></i>

    <div class="file-modal__header">
        <div class="file-modal__title">Выберите или перетащите файлы</div>
        <div class="file-modal__progress_bar"></div>
    </div>

    <div class="file-modal__drop-area">
        <div class="file-modal__body"></div>
    </div>

    <div class="file-modal__actions">
        <div class="file-modal__button file-modal__upload">Выбрать</div>
        <div class="file-modal__button file-modal__submit">Загрузить</div>
        <div class="file-modal__button file-modal__delete">Удалить файлы</div>
    </div>

    <form id="file_uploader" action="" method="POST" enctype="multipart/form-data" >
        <input type="file" name="download_files[]" hidden/>
        <input name="id_application" value="<?= $variablesTV->getValue('id_application') ?>" type="hidden"/>
        <input name="mapping_level_1" type="hidden"/>
        <input name="mapping_level_2" type="hidden"/>
        <input name="id_structure_node" type="hidden"/>
    </form>
</div>

<!--<div class="modal sign-modal" data-plugin_loaded="false">

    <div class="sign-modal__header">
        <div class="sign-modal__row">
            <span class="sign-modal__label">Версия плагина: </span>
            <span class="sign-modal__text" id="PlugInVersionTxt" lang="ru"></span>
        </div>
        <div class="sign-modal__row">
            <span class="sign-modal__label">Версия криптопровайдера: </span>
            <span class="sign-modal__text" id="CSPVersionTxt" lang="ru"></span>
        </div>
    </div>

    <div class="sign-modal__body">
        <div class="sign-modal__file-info">
            <div class="sign-modal__file"></div>
        </div>

        <select class="sign-modal__cert-list" size="4" name="CertListBox" id="CertListBox"></select>

        <div class="sign-modal__info">
            <div class="sign-modal__row">
                <span class="sign-modal__label">Данные о выбранном сертификате:</span>
            </div>
            <div class="sign-modal__row">
                <span class="sign-modal__label">Владелец: </span>
                <span class="sign-modal__text" id="SubjectName"></span>
            </div>
            <div class="sign-modal__row">
                <span class="sign-modal__label">Издатель: </span>
                <span class="sign-modal__text" id="IssuerName"></span>
            </div>
            <div class="sign-modal__row">
                <span class="sign-modal__label">Дата выдачи: </span>
                <span class="sign-modal__text" id="ValidFromDate"></span>
            </div>
            <div class="sign-modal__row">
                <span class="sign-modal__label">Срок действия: </span>
                <span class="sign-modal__text" id="ValidToDate"></span>
            </div>
            <div class="sign-modal__row">
                <span class="sign-modal__label">Статус: </span>
                <span class="sign-modal__text" id="CertMessage"></span>
            </div>
        </div>
    </div>

    <div class="sign-modal__buttons">
        <div class="file-modal__button file-modal__upload">Загрузить</div>
        <div id="signature_button" class="sign-modal__button sign-modal__sign">Подписать</div>
        <input id="external_sign" type="file" name="download_files[]" hidden/>
    </div>
</div>-->

<div class="modal sign-modal" data-plugin_loaded="false">

    <div class="sign-modal__header" data-inactive="true">
        <div class="sign-modal__row">
            <span class="sign-modal__label">Версия плагина: </span>
            <span class="sign-modal__text" id="PlugInVersionTxt" lang="ru"></span>
        </div>
        <div class="sign-modal__row">
            <span class="sign-modal__label">Версия криптопровайдера: </span>
            <span class="sign-modal__text" id="CSPVersionTxt" lang="ru"></span>
        </div>
    </div>


    <div class="sign-modal__body">
        <div class="sign-modal__file-info">
            <div class="sign-modal__file"></div>
            <div class="sign-modal__validate" data-inactive="true">
                <div class="sign-modal__row">
                    <span class="sign-modal__label">Сертификат: </span>
                    <span class="sign-modal__text" id="ValidateCertState"></span>
                </div>
                <div class="sign-modal__row">
                    <span class="sign-modal__label">Подпись: </span>
                    <span class="sign-modal__text" id="ValidateSignState"></span>
                </div>
                <span class="sign-modal__validate-cert"></span>
                <span class="sign-modal__validate-sign"></span>
            </div>
        </div>
        
        <div class="sign-modal__buttons">
            <div id="signature_delete" class="sign-modal__upload application-button" data-inactive="true">Удалить подпись</div>
            <div id="sign_upload" class="sign-modal__upload application-button" data-inactive="true">Загрузить открепленную подпись</div>
            <div id="sign_create" class="sign-modal__sign application-button" data-inactive="true">Создать открепленную подпись</div>
            <input id="external_sign" type="file" name="download_files[]" hidden/>
            <!--            <input id="external_sign" type="file" name="download_files[]" hidden/>-->
        </div>
    </div>

    <div class="sign-modal__cert-body">

        <div class="sign-modal__select" data-inactive="true">
            <span class="sign-modal__title">Выберите сертификат:</span>
            <select class="sign-modal__cert-list" size="4" name="CertListBox" id="CertListBox"></select>
    
            <div class="sign-modal__info" data-inactive="true">
                <div class="sign-modal__row">
                    <span class="sign-modal__label">Данные о выбранном сертификате:</span>
                </div>
                <div class="sign-modal__row">
                    <span class="sign-modal__label">Владелец: </span>
                    <span class="sign-modal__text" id="SubjectName"></span>
                </div>
                <div class="sign-modal__row">
                    <span class="sign-modal__label">Издатель: </span>
                    <span class="sign-modal__text" id="IssuerName"></span>
                </div>
                <div class="sign-modal__row">
                    <span class="sign-modal__label">Дата выдачи: </span>
                    <span class="sign-modal__text" id="ValidFromDate"></span>
                </div>
                <div class="sign-modal__row">
                    <span class="sign-modal__label">Срок действия: </span>
                    <span class="sign-modal__text" id="ValidToDate"></span>
                </div>
                <div class="sign-modal__row">
                    <span class="sign-modal__label">Статус: </span>
                    <span class="sign-modal__text" id="CertMessage"></span>
                </div>
            </div>
            
        </div>


        
    </div>
    
    <div class="sign-modal__actions" data-inactive="true">
        <div id="signature_button" class="file-modal__button sign-modal__button">Подписать</div>
        <div id="sign_cancel" class="file-modal__button sign-modal__button">Отмена</div>
    </div>


</div>

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




</body>

</html>