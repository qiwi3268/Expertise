<?php $variablesTV = VariableTransfer::getInstance(); ?>

<div class="application-form__header header-form">
    <div class="header-form__title"><?= $variablesTV->getValue('applicationNumericalName') ?></div>
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
        <input type="hidden" value="<?= $variablesTV->getValue('applicationId') ?>"
               name="<?= _PROPERTY_IN_APPLICATION['id_application'] ?>">
        
        <div class="application-form__card card-form" data-type="purpose">
            <div class="card-form__header">
                <span class="card-form__title">СВЕДЕНИЯ О ЦЕЛИ ОБРАЩЕНИЯ</span>
                <i class="card-form__icon-expand fas fa-chevron-down arrow-down"></i>
            </div>
            <div class="card-form__body body-card">
                <!--Цель обращения-->
                <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['expertise_purpose'] ?>"
                     data-required="true">
                    <span class="body-card__title required">Цель обращения</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select modal-select">
                                <span class="body-card__value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                    </div>
                    
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items">
                            <?php foreach ($variablesTV->getValue('expertisePurposes') as $pageNumber => $page): ?>
                                <div class="modal__page" data-page="<?= $pageNumber ?>">
                                    <?php foreach ($page as $item): ?>
                                        <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input class="body-card__result" type="hidden"
                           name="<?= _PROPERTY_IN_APPLICATION['expertise_purpose'] ?>">
                </div>
                <!--//Цель обращения//-->
                
                <!--Предмет экспертизы-->
                <div class="body-card__row center" data-row_name="<?= _PROPERTY_IN_APPLICATION['expertise_subjects'] ?>"
                     data-required="true">
                    <span class="body-card__title required">Предмет экспертизы</span>
                    <div class="body-card__item">
                        <div class="body-card__field radio" data-multiple="true">
                            <div class="radio__body">
                                <span class="radio__title">Выберите цель обращения</span>
                            </div>
                        </div>
                    </div>
                    <input class="body-card__result" type="hidden"
                           name="<?= _PROPERTY_IN_APPLICATION['expertise_subjects'] ?>">
                </div>
                <!--//Предмет экспертизы//-->
                
                <!--Дополнительная информация-->
                <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['additional_information'] ?>"
                     data-pattern="text">
                    <span class="body-card__title">Дополнительная информация</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <textarea class="body-card__input application-input"
                                      name="<?= _PROPERTY_IN_APPLICATION['additional_information'] ?>"></textarea>
                        </div>
                        <span class="body-card__error"></span>
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
                <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['object_name'] ?>"
                     data-required="true" data-pattern="text">
                    <span class="body-card__title required">Наименование объекта</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <textarea class="body-card__input body-card__result application-input"
                                      name="<?= _PROPERTY_IN_APPLICATION['object_name'] ?>"></textarea>
                        </div>
                        <span class="body-card__error"></span>
                    </div>
                </div>
                <!--//Наименование объекта//-->
                
                <!--Вид объекта-->
                <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['type_of_object'] ?>"
                     data-required="true">
                    <span class="body-card__title required">Вид объекта</span>
                    
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select application-input modal-select">
                                <span class="body-card__value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-trash"></i>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                    </div>
                    
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items">
                            <?php foreach ($variablesTV->getValue('typeOfObjects') as $pageNumber => $page): ?>
                                <div class="modal__page" data-page="<?= $pageNumber ?>">
                                    <?php foreach ($page as $item): ?>
                                        <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input class="body-card__result" type="hidden"
                           name="<?= _PROPERTY_IN_APPLICATION['type_of_object'] ?>">
                </div>
                <!--//Вид объекта//-->
                
                <!--Функциональное назначение-->
                <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['functional_purpose'] ?>"
                     data-required="true">
                    <span class="body-card__title required">Функциональное назначение</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select modal-select">
                                <span class="body-card__value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-trash"></i>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                    </div>
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items">
                            <?php foreach ($variablesTV->getValue('functionalPurposes') as $pageNumber => $page): ?>
                                <div class="modal__page" data-page="<?= $pageNumber ?>">
                                    <?php foreach ($page as $item): ?>
                                        <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input class="body-card__result" type="hidden"
                           name="<?= _PROPERTY_IN_APPLICATION['functional_purpose'] ?>">
                </div>
                <!--//Функциональное назначение//-->
                
                <!--Функциональное назначение. Подотрасль-->
                <div class="body-card__row"
                     data-row_name="<?= _PROPERTY_IN_APPLICATION['functional_purpose_subsector'] ?>"
                     data-required="true">
                    <span class="body-card__title required">Функциональное назначение. Подотрасль</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select modal-select">
                                <span class="body-card__value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-trash"></i>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                    </div>
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items"></div>
                    </div>
                    <input class="body-card__result" type="hidden"
                           name="<?= _PROPERTY_IN_APPLICATION['functional_purpose_subsector'] ?>">
                </div>
                <!--//Функциональное назначение. Подотрасль//-->
                
                <!--Функциональное назначение. Группа-->
                <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['functional_purpose_group'] ?>"
                     data-required="true">
                    <span class="body-card__title required">Функциональное назначение. Группа</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select modal-select">
                                <span class="body-card__value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-trash"></i>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                    </div>
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items"></div>
                    </div>
                    <input class="body-card__result" type="hidden"
                           name="<?= _PROPERTY_IN_APPLICATION['functional_purpose_group'] ?>">
                </div>
                <!--//Функциональное назначение. Группа//-->
                
                
                <!--Блок производственные/непроизводственные объекты капитального строительства-->
                <!--Номер утверждения документации по планировке территории-->
                <div class="body-card__row" data-inactive="true"
                     data-row_name="<?= _PROPERTY_IN_APPLICATION['number_planning_documentation_approval'] ?>"
                     data-pattern="number">
                    <span class="body-card__title">Номер утверждения документации по планировке территории</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <input class="body-card__input body-card__result application-input" type="text"
                                   name="<?= _PROPERTY_IN_APPLICATION['number_planning_documentation_approval'] ?>"
                                   placeholder="Введите значение">
                            <span class="body-card__error"></span>
                        </div>
                    </div>
                </div>
                <!--//Номер утверждения документации по планировке территории//-->
                <!--Дата утверждения документации по планировке территории-->
                <div class="body-card__row" data-inactive="true"
                     data-row_name="<?= _PROPERTY_IN_APPLICATION['date_planning_documentation_approval'] ?>"
                     data-pattern="date">
                    <span class="body-card__title">Дата утверждения документации по планировке территории</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select modal-calendar">
                                <span class="body-card__value">Выберите дату</span>
                                <i class="body-card__icon fas fa-calendar-alt"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-calendar-times"></i>
                        </div>
                    </div>
                    <input class="body-card__result" type="hidden"
                           name="<?= _PROPERTY_IN_APPLICATION['date_planning_documentation_approval'] ?>">
                </div>
                <!--//Дата утверждения документации по планировке территории//-->
                <!--//Блок производственные/непроизводственные объекты капитального строительства//-->
                
                <!--Блок линейные объекты капитального строительства-->
                <!--Номер ГПЗУ-->
                <div class="body-card__row" data-inactive="true"
                     data-row_name="<?= _PROPERTY_IN_APPLICATION['number_GPZU'] ?>" data-pattern="">
                    <span class="body-card__title">Номер ГПЗУ</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <input class="body-card__input body-card__result application-input" type="text"
                                   name="<?= _PROPERTY_IN_APPLICATION['number_GPZU'] ?>" placeholder="Введите значение">
                            <span class="body-card__error"></span>
                        </div>
                    </div>
                </div>
                <!--//Номер ГПЗУ//-->
                <!--Дата ГПЗУ-->
                <div class="body-card__row" data-inactive="true"
                     data-row_name="<?= _PROPERTY_IN_APPLICATION['date_GPZU'] ?>" data-pattern="date">
                    <span class="body-card__title">Дата ГПЗУ</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select modal-calendar">
                                <span class="body-card__value">Выберите дату</span>
                                <i class="body-card__icon fas fa-calendar-alt"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-calendar-times"></i>
                        </div>
                    </div>
                    <input class="body-card__result" type="hidden" name="<?= _PROPERTY_IN_APPLICATION['date_GPZU'] ?>">
                </div>
                <!--//Дата ГПЗУ//-->
                <!--//Блок линейные объекты капитального строительства//-->
                
                
                <!--Вид работ-->
                <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['type_of_work'] ?>"
                     data-required="true">
                    <span class="body-card__title required">Вид работ</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select modal-select">
                                <span class="body-card__value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-trash"></i>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                    </div>
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items"></div>
                    </div>
                    <input class="body-card__result" type="hidden"
                           name="<?= _PROPERTY_IN_APPLICATION['type_of_work'] ?>">
                </div>
                <!--//Вид работ//-->
                
                <!--Кадастровый номер земельного участка-->
                <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['cadastral_number'] ?>"
                     data-pattern="number">
                    <span class="body-card__title">Кадастровый номер земельного участка</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <input class="body-card__input body-card__result application-input" type="text"
                                   name="<?= _PROPERTY_IN_APPLICATION['cadastral_number'] ?>"
                                   placeholder="Введите значение">
                        </div>
                        <span class="body-card__error"></span>
                    </div>
                </div>
                <!--//Кадастровый номер земельного участка//-->
                
                <!--CHECKBOX Объект культурного наследия-->
                <div class="body-card__row"
                     data-row_name="<?= _PROPERTY_IN_APPLICATION['cultural_object_type_checkbox'] ?>"
                     data-multiple="false">
                    <span class="body-card__title">Объект культурного наследия</span>
                    <div class="body-card__item">
                        <div class="body-card__field radio">
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
                    <input class="body-card__result" type="hidden"
                           name="<?= _PROPERTY_IN_APPLICATION['cultural_object_type_checkbox'] ?>">
                </div>
                <!--//CHECKBOX Объект культурного наследия//-->
                
                <!--Тип объекта культурного наследия-->
                <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['cultural_object_type'] ?>"
                     data-required="true" data-inactive="true">
                    <span class="body-card__title required">Тип объекта культурного наследия</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select modal-select">
                                <span class="body-card__value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-trash"></i>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                    </div>
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items">
                            <?php foreach ($variablesTV->getValue('culturalObjectTypes') as $pageNumber => $page): ?>
                                <div class="modal__page" data-page="<?= $pageNumber ?>">
                                    <?php foreach ($page as $item): ?>
                                        <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input class="body-card__result" type="hidden"
                           name="<?= _PROPERTY_IN_APPLICATION['cultural_object_type'] ?>">
                </div>
                <!--//Тип объекта культурного наследия//-->
                
                <!--CHECKBOX Национальный проект-->
                <div class="body-card__row center"
                     data-row_name="<?= _PROPERTY_IN_APPLICATION['national_project_checkbox'] ?>">
                    <span class="body-card__title">Национальный проект</span>
                    <div class="body-card__item">
                        <div class="body-card__field radio">
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
                    <input class="body-card__result" type="hidden"
                           name="<?= _PROPERTY_IN_APPLICATION['national_project_checkbox'] ?>">
                </div>
                <!--//CHECKBOX Национальный проект//-->
                
                <!--Название национального проекта-->
                <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['national_project'] ?>"
                     data-required="true" data-inactive="true">
                    <span class="body-card__title required">Название национального проекта</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select modal-select">
                                <span class="body-card__value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-trash"></i>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                    </div>
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items">
                            <?php foreach ($variablesTV->getValue('nationalProjects') as $pageNumber => $page): ?>
                                <div class="modal__page" data-page="<?= $pageNumber ?>">
                                    <?php foreach ($page as $item): ?>
                                        <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input class="body-card__result" type="hidden"
                           name="<?= _PROPERTY_IN_APPLICATION['national_project'] ?>">
                </div>
                <!--//Название национального проекта//-->
                
                <!--Название федерального проекта-->
                <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['federal_project'] ?>"
                     data-required="true" data-inactive="true">
                    <span class="body-card__title required">Название федерального проекта</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select modal-select">
                                <span class="body-card__value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-trash"></i>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                    </div>
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items"></div>
                    </div>
                    <input class="body-card__result" type="hidden"
                           name="<?= _PROPERTY_IN_APPLICATION['federal_project'] ?>">
                </div>
                <!--//Название федерального проекта//-->
                
                <!--Дата окончания строительства-->
                <div class="body-card__row" data-inactive="true"
                     data-row_name="<?= _PROPERTY_IN_APPLICATION['date_finish_building'] ?>" data-pattern="date">
                    <span class="body-card__title">Дата окончания строительства</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select modal-calendar">
                                <span class="body-card__value">Выберите дату</span>
                                <i class="body-card__icon fas fa-calendar-alt"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-calendar-times"></i>
                        </div>
                    </div>
                    <input class="body-card__result" type="hidden"
                           name="<?= _PROPERTY_IN_APPLICATION['date_finish_building'] ?>">
                </div>
                <!--//Дата окончания строительства//-->
                
                <!--Куратор-->
                <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['curator'] ?>"
                     data-required="true">
                    <span class="body-card__title required">Куратор</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select modal-select">
                                <span class="body-card__value">Выберите значение</span>
                                <i class="body-card__icon fas fa-bars"></i>
                            </div>
                            <i class="body-card__icon-clear fas fa-trash"></i>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                    </div>
                    <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items">
                            <?php foreach ($variablesTV->getValue('curators') as $pageNumber => $page): ?>
                                <div class="modal__page" data-page="<?= $pageNumber ?>">
                                    <?php foreach ($page as $item): ?>
                                        <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input class="body-card__result" type="hidden" name="<?= _PROPERTY_IN_APPLICATION['curator'] ?>">
                </div>
                <!--//Куратор//-->
            
            </div>
        </div>
    </form>
</div>


<div class="modal-overlay"></div>
<div class="calendar-overlay"></div>

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

<div class="modal" data-type="file">
    <form id="file_uploader" action="/" enctype="multipart/form-data">
    
    </form>
</div>

</body>

</html>