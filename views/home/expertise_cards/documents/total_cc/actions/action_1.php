
<?php $variablesTV = \Lib\Singles\VariableTransfer::getInstance(); ?>
<?php $_defaultParameters = $variablesTV->getValue('defaultParameters'); ?>

<div class="action__info">
    <span class="action__description">Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)</span>
</div>

<div class="application-form">
    
    <div class="application-form__sidebar sidebar-form">
        
        <div class="sidebar-form__row" data-card="">
            <span class="sidebar-form__text">Сведения о заявителе</span>
            <i class="sidebar-form__icon fas"></i>
        </div>
        <div class="sidebar-form__row" data-card="">
            <span class="sidebar-form__text">Основания для проведения экспертизы</span>
            <i class="sidebar-form__icon fas"></i>
        </div>
        <div class="sidebar-form__row" data-card="">
            <span class="sidebar-form__text">Сведения о положительном заключении государственной экологической экспертизы</span>
            <i class="sidebar-form__icon fas"></i>
        </div>
        <div class="sidebar-form__row" data-card="">
            <span class="sidebar-form__text">Сведения о составе документов, представленных для проведения экспертизы</span>
            <i class="sidebar-form__icon fas"></i>
        </div>
    </div>
    
    <div class="application-form__cards">
    
        <div class="application-form__card card-form card" data-type="applicant">
            <div class="card-form__header card-expand">
                <span class="card-form__title">СВЕДЕНИЯ О ЗАЯВИТЕЛЕ</span>
                <i class="card-form__icon-expand fas fa-chevron-down arrow-down card-icon"></i>
            </div>
            <div class="card-form__body body-card card-body">
                <?php $_applicantDetails = $_defaultParameters['applicantDetails'] ?>
                
                <div class="section" data-required="true">
                    <div class="section__header">Полное наименование</div>
                    <div class="section__body">
                        <textarea class="section__input field-result" name="" placeholder="Введите значение"><?= $_applicantDetails['full_name'] ?></textarea>
                    </div>
                </div>
                <div class="section" data-required="true">
                    <div class="section__header">ИНН</div>
                    <div class="section__body">
                        <input class="section__input field-result" name="" placeholder="Введите значение" value="<?= $_applicantDetails['INN'] ?>">
                    </div>
                </div>
            
    
                <!--Полное наименование-->
                <div class="application-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="application-field__title">Полное наименование</span>
                    <div class="application-field__item">
                        <div class="application-field__body filled">
                            <textarea data-form="application" class="application-field__input field-result" name=""></textarea>
                            <i class="application-field__icon-filled fas fa-check"></i>
                        </div>
                        <span class="application-field__error field-error"></span>
                    </div>
                </div>
                <!--//Полное наименование//-->
    
                <!--ИНН-->
                <div class="application-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="application-field__title">ИНН</span>
                    <div class="application-field__item">
                        <div class="application-field__body filled">
                            <input class="application-field__input field-result" type="text" name="INN" placeholder="Введите значение" value="1123499884">
                            <i class="application-field__icon-filled fas fa-check"></i>
                        </div>
    
                        <span class="application-field__error field-error"></span>
                    </div>
                </div>
                <!--//ИНН//-->
    
                <!--КПП-->
                <div class="application-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="application-field__title">КПП</span>
                    <div class="application-field__item">
                        <div class="application-field__body filled">
                            <input class="application-field__input field-result" type="text" name="KPP" placeholder="Введите значение" value="54459884">
                            <i class="application-field__icon-filled fas fa-check"></i>
                        </div>
            
                        <span class="application-field__error field-error"></span>
                    </div>
                </div>
                <!--//КПП//-->
    
                <!--ОГРН-->
                <div class="application-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="application-field__title">ОГРН</span>
                    <div class="application-field__item">
                        <div class="application-field__body filled">
                            <input class="application-field__input field-result" type="text" name="OGRN" placeholder="Введите значение" value="789959884">
                            <i class="application-field__icon-filled fas fa-check"></i>
                        </div>
            
                        <span class="application-field__error field-error"></span>
                    </div>
                </div>
                <!--//ОГРН//-->
    
                <!--Адрес-->
                <div class="application-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="application-field__title">Адрес</span>
                    <div class="application-field__item">
                        <div class="application-field__body filled">
                            <textarea data-form="application" class="application-field__input field-result" name="">Челябинск</textarea>
                            <i class="application-field__icon-filled fas fa-check"></i>
                        </div>
                        <span class="application-field__error field-error"></span>
                    </div>
                </div>
                <!--//Адрес//-->
    
                <!--Место нахождения-->
                <div class="application-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="application-field__title">Место нахождения</span>
                    <div class="application-field__item">
                        <div class="application-field__body filled">
                            <textarea data-form="application" class="application-field__input field-result" name="">Челябинск</textarea>
                            <i class="application-field__icon-filled fas fa-check"></i>
                        </div>
                        <span class="application-field__error field-error"></span>
                    </div>
                </div>
                <!--//Место нахождения//-->
    
                <!--Электронная почта-->
                <div class="application-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="application-field__title">Электронная почта</span>
                    <div class="application-field__item">
                        <div class="application-field__body filled">
                            <input class="application-field__input field-result" type="text" name="" placeholder="Введите значение" value="test@test.com">
                            <i class="application-field__icon-filled fas fa-check"></i>
                        </div>
            
                        <span class="application-field__error field-error"></span>
                    </div>
                </div>
                <!--//Электронная почта//-->
    
                <!--Телефон-->
                <div class="application-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="application-field__title">Телефон</span>
                    <div class="application-field__item">
                        <div class="application-field__body filled">
                            <input class="application-field__input field-result" type="text" name="KPP" placeholder="Введите значение" value="89122332423">
                            <i class="application-field__icon-filled fas fa-check"></i>
                        </div>
            
                        <span class="application-field__error field-error"></span>
                    </div>
                </div>
                <!--//Телефон//-->
                
            </div>
        </div>
    
        <div class="application-form__card card-form card" data-type="">
            <div class="card-form__header card-expand">
                <span class="card-form__title">ОСНОВАНИЯ ДЛЯ ПРОВЕДЕНИЯ ЭКСПЕРТИЗЫ</span>
                <i class="card-form__icon-expand fas fa-chevron-down arrow-down card-icon"></i>
            </div>
            <div class="card-form__body body-card card-body">
                <!--Договор-->
                <div class="application-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="application-field__title">Договор</span>
                    <div class="application-field__item">
                        <div class="body-card__list filled" >
                            <input class="body-card__cell field-result" type="text" name="contract" placeholder="Введите значение" value="Договор №123">
                            <input class="body-card__cell field-result" type="text" name="contract" placeholder="Введите значение" value="Договор №123">
                            <input class="body-card__cell field-result" type="text" name="contract" placeholder="Введите значение" value="Договор №123">
                        </div>
<!--                        <i class="application-field__icon-filled fas fa-check"></i>-->
    
                        <span class="application-field__error field-error"></span>
                    </div>
                </div>
                <!--//Договор//-->
                <!--Дополнительное соглашение-->
                <div class="application-field field" data-required="true" data-active="false" data-name="" data-pattern="text">
                    <span class="application-field__title">Дополнительное соглашение</span>
                    <div class="application-field__item">
                        <div class="application-field__body filled">
                            <input class="application-field__input field-result" type="text" name="INN" placeholder="Введите значение" value="Дополнительное соглашение №345">
                            <i class="application-field__icon-filled fas fa-check"></i>
                        </div>
                        <span class="application-field__error field-error"></span>
                    </div>
                </div>
                <!--//Дополнительное соглашение//-->
                <!--Доверенность-->
                <div class="application-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="application-field__title">Доверенность</span>
                    <div class="application-field__item">
                        <div class="application-field__body filled">
                            <input class="application-field__input field-result" type="text" name="INN" placeholder="Введите значение" value="Доверенность №123">
                            <i class="application-field__icon-filled fas fa-check"></i>
                        </div>
                        <span class="application-field__error field-error"></span>
                    </div>
                </div>
                <!--//Доверенность//-->
                
                
                
            </div>
        </div>
    
        <div class="application-form__card card-form card" data-type="">
            <div class="card-form__header card-expand">
                <span class="card-form__title">СВЕДЕНИЯ, СОДЕРЖАЩИЕСЯ В ДОКУМЕНТАХ, ПРЕДСТАВЛЕННЫХ ДЛЯ ПРОВЕДЕНИЯ ЭКСПЕРТИЗЫ ПРОЕКТНОЙ ДОКУМЕНТАЦИИ</span>
                <i class="card-form__icon-expand fas fa-chevron-down arrow-down card-icon"></i>
            </div>
            <div class="card-form__body body-card card-body">
    
                <div class="body-card__block multiple-block block" data-type="multiple" data-name="finance_sources">
    
                    <div class="body-card__block block" data-name="part" data-active="true" data-dependency_scope>
    
                        <div class="body-card__block block" data-type="part" data-name="type" data-active="true">
                            <div class="application-field field" data-name="finance_type" data-required="true">
                                <span class="application-field__title field-title">Вид финансирования</span>
                                <div class="application-field__item">
                                    <div class="application-field__body radio" data-required="true">
                                        <div class="radio__body">
                                            <div class="radio__item" data-id="1">
                                                <i class="radio__icon inline far fa-square"></i>
                                                <span class="radio__text" data-part_title="1">Бюджетные средства</span>
                                            </div>
                                            <div class="radio__item" data-id="2">
                                                <i class="radio__icon inline far fa-square"></i>
                                                <span class="radio__text" data-part_title="2">Средства юридических лиц, указанных в ч. 2 статьи 48.2 ГрК</span>
                                            </div>
                                            <div class="radio__item" data-id="3">
                                                <i class="radio__icon inline far fa-square"></i>
                                                <span class="radio__text" data-part_title="3">Собственные средства застройщика</span>
                                            </div>
                                            <div class="radio__item" data-id="4">
                                                <i class="radio__icon inline far fa-square"></i>
                                                <span class="radio__text" data-part_title="4">Средства инвестора</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input class="application-field__result field-result" type="hidden" data-multiple_block_field="type" name="finance_type">
                            </div>
                            <div class="body-card__block block" data-type="part" data-name="budget" data-active="true">
                                <div class="application-field field" data-misc_field data-name="budget_level" data-required="true">
                                    <span class="application-field__title field-title">Уровень бюджета</span>
                                    <div class="application-field__item">
                                        <div class="application-field__body">
                                            <div class="application-field__select field-select" data-misc_select>
                                                <span class="application-field__value field-value" data-misc_value>Выберите значение</span>
                                                <i class="application-field__icon-misc fas fa-bars"></i>
                                                <i class="application-field__icon-filled fas fa-check"></i>
                                            </div>
                                        </div>
                                        <span class="application-field__error field-error">Поле обязательно для заполнения</span>
                                    </div>
                                    <!--<div class="modal" data-misc_modal data-result_callback="application_field">
                                        <i class="modal__close fas fa-times" data-misc_close></i>
                                        <div class="modal__items" data-misc_body>
                                            <?php /*foreach ($variablesTV->getValue('budget_level') as $pageNumber => $page): */?>
                                                <div class="modal__page" data-misc_page="<?/*= $pageNumber */?>">
                                                    <?php /*foreach ($page as $item): */?>
                                                        <div class="modal__item" data-misc_item data-id="<?/*= $item['id'] */?>"><?/*= $item['name'] */?></div>
                                                    <?php /*endforeach; */?>
                                                </div>
                                            <?php /*endforeach; */?>
                                        </div>
                                    </div>-->
                                    <input class="application-field__result field-result" data-misc_result type="hidden" data-multiple_block_field="budget_level" name="budget_level">
                                </div>
                                <div class="body-card__block block" data-type="part" data-name="no_data" data-active="true">
                                    <div class="application-field field center" data-name="no_data">
                                        <span class="application-field__title">Размер финансирования</span>
                                        <div class="application-field__item">
                                            <div class="application-field__body radio">
                                                <div class="radio__body">
                                                    <div class="radio__item" data-id="1">
                                                        <i class="radio__icon inline far fa-square"></i>
                                                        <span class="radio__text">Нет данных</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input class="application-field__result field-result" type="hidden" data-multiple_block_field="no_data" name="no_data">
                                    </div>
                                    <div class="body-card__block block" data-type="part" data-name="percent" data-active="true">
                                        <div class="application-field field" data-required="true" data-name="percent" data-pattern="number">
                                            <span class="application-field__title">Процент финансирования</span>
                                            <div class="application-field__item">
                                                <div class="application-field__body">
                                                    <input class="application-field__input field-result" type="text" data-multiple_block_field="percent" name="percent" placeholder="Введите процент">
                                                </div>
                                                <span class="application-field__error field-error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
    
                    <!--Шаблоны источников финансирования-->
                    <div class="body-card__block block" data-name="templates_container">
                        <!--Шаблон элемента множественного блока-->
                        <div class="body-card__block block" data-name="part" data-active="false" data-dependency_scope></div>
                        <!--Шаблон элемента множественного блока-->
                        <!--Шаблон "Вид финансирования"-->
                        <div class="body-card__block block" data-type="part" data-name="type" data-active="false">
                            <div class="application-field field" data-name="finance_type" data-required="true">
                                <span class="application-field__title field-title">Вид финансирования</span>
                                <div class="application-field__item">
                                    <div class="application-field__body radio" data-required="true">
                                        <div class="radio__body">
                                            <div class="radio__item" data-id="1">
                                                <i class="radio__icon inline far fa-square"></i>
                                                <span class="radio__text" data-part_title="1">Бюджетные средства</span>
                                            </div>
                                            <div class="radio__item" data-id="2">
                                                <i class="radio__icon inline far fa-square"></i>
                                                <span class="radio__text" data-part_title="2">Средства юридических лиц, указанных в ч. 2 статьи 48.2 ГрК</span>
                                            </div>
                                            <div class="radio__item" data-id="3">
                                                <i class="radio__icon inline far fa-square"></i>
                                                <span class="radio__text" data-part_title="3">Собственные средства застройщика</span>
                                            </div>
                                            <div class="radio__item" data-id="4">
                                                <i class="radio__icon inline far fa-square"></i>
                                                <span class="radio__text" data-part_title="4">Средства инвестора</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input class="application-field__result field-result" type="hidden" data-multiple_block_field="type" name="finance_type">
                            </div>
                        </div>
                        <!--//Шаблон "Вид финансирования"//-->
                        <!--Шаблон "Бюджетные средства"-->
                        <div class="body-card__block block" data-type="part" data-name="budget" data-active="false">
                            <div class="application-field field" data-misc_field data-name="budget_level" data-required="true">
                                <span class="application-field__title field-title">Уровень бюджета</span>
                                <div class="application-field__item">
                                    <div class="application-field__body">
                                        <div class="application-field__select field-select" data-misc_select>
                                            <span class="application-field__value field-value" data-misc_value>Выберите значение</span>
                                            <i class="application-field__icon-misc fas fa-bars"></i>
                                            <i class="application-field__icon-filled fas fa-check"></i>
                                        </div>
                                    </div>
                                    <span class="application-field__error field-error">Поле обязательно для заполнения</span>
                                </div>
                                <div class="modal" data-misc_modal data-result_callback="application_field">
                                    <i class="modal__close fas fa-times" data-misc_close></i>
                                    <div class="modal__items" data-misc_body>
                                        <?php foreach ($variablesTV->getValue('budget_level') as $pageNumber => $page): ?>
                                            <div class="modal__page" data-misc_page="<?= $pageNumber ?>">
                                                <?php foreach ($page as $item): ?>
                                                    <div class="modal__item" data-misc_item data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <input class="application-field__result field-result" data-misc_result type="hidden" data-multiple_block_field="budget_level" name="budget_level">
                            </div>
                            <div class="body-card__block block" data-type="part" data-name="no_data" data-active="false">
                                <div class="application-field field center" data-name="no_data">
                                    <span class="application-field__title">Размер финансирования</span>
                                    <div class="application-field__item">
                                        <div class="application-field__body radio">
                                            <div class="radio__body">
                                                <div class="radio__item" data-id="1">
                                                    <i class="radio__icon inline far fa-square"></i>
                                                    <span class="radio__text">Нет данных</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input class="application-field__result field-result" type="hidden" data-multiple_block_field="no_data" name="no_data">
                                </div>
                                <div class="body-card__block block" data-type="part" data-name="percent" data-active="true">
                                    <div class="application-field field" data-required="true" data-name="percent" data-pattern="number">
                                        <span class="application-field__title">Процент финансирования</span>
                                        <div class="application-field__item">
                                            <div class="application-field__body">
                                                <input class="application-field__input field-result" type="text" data-multiple_block_field="percent" name="percent" placeholder="Введите процент">
                                            </div>
                                            <span class="application-field__error field-error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--//Шаблон "Бюджетные средства"//-->
                        <!--Шаблон "Средства юридических лиц"-->
                        <div class="body-card__block block" data-type="part" data-name="organization" data-active="false">
                            <div class="application-field field" data-required="true" data-name="full_name">
                                <span class="application-field__title">Полное наименование</span>
                                <div class="application-field__item">
                                    <div class="application-field__body">
                                        <input class="application-field__input field-result" type="text" data-multiple_block_field="full_name" name="full_name" placeholder="Введите значение">
                                    </div>
                                    <span class="application-field__error field-error"></span>
                                </div>
                            </div>
                            <div class="application-field field" data-required="true" data-name="INN" data-pattern="inn">
                                <span class="application-field__title">ИНН</span>
                                <div class="application-field__item">
                                    <div class="application-field__body">
                                        <input class="application-field__input field-result" type="text" data-multiple_block_field="INN" name="INN" placeholder="Введите значение">
                                    </div>
                                    <span class="application-field__error field-error"></span>
                                </div>
                            </div>
                            <div class="application-field field" data-required="true" data-name="KPP">
                                <span class="application-field__title">КПП</span>
                                <div class="application-field__item">
                                    <div class="application-field__body">
                                        <input class="application-field__input field-result" type="text" data-multiple_block_field="KPP" name="KPP" placeholder="Введите значение">
                                    </div>
                                    <span class="application-field__error field-error"></span>
                                </div>
                            </div>
                            <div class="application-field field" data-required="true" data-name="OGRN">
                                <span class="application-field__title">ОГРН</span>
                                <div class="application-field__item">
                                    <div class="application-field__body">
                                        <input class="application-field__input field-result" type="text" data-multiple_block_field="OGRN" name="OGRN" placeholder="Введите значение">
                                    </div>
                                    <span class="application-field__error field-error"></span>
                                </div>
                            </div>
                            <div class="application-field field" data-required="true" data-name="address">
                                <span class="application-field__title">Адрес</span>
                                <div class="application-field__item">
                                    <div class="application-field__body">
                                        <input class="application-field__input field-result" type="text" data-multiple_block_field="address" name="address" placeholder="Введите значение">
                                    </div>
                                    <span class="application-field__error field-error"></span>
                                </div>
                            </div>
                            <div class="application-field field" data-required="true" data-name="location">
                                <span class="application-field__title">Место нахождения</span>
                                <div class="application-field__item">
                                    <div class="application-field__body">
                                        <input class="application-field__input field-result" type="text" data-multiple_block_field="location" name="location" placeholder="Введите значение">
                                    </div>
                                    <span class="application-field__error field-error"></span>
                                </div>
                            </div>
                            <div class="application-field field" data-required="true" data-name="telephone">
                                <span class="application-field__title">Телефон</span>
                                <div class="application-field__item">
                                    <div class="application-field__body">
                                        <input class="application-field__input field-result" type="text" data-multiple_block_field="telephone" name="telephone" placeholder="Введите значение">
                                    </div>
                                    <span class="application-field__error field-error"></span>
                                </div>
                            </div>
                            <div class="application-field field" data-required="true" data-name="email">
                                <span class="application-field__title">Адрес электронной почты</span>
                                <div class="application-field__item">
                                    <div class="application-field__body">
                                        <input class="application-field__input field-result" type="text" data-multiple_block_field="email" name="email" placeholder="Введите значение">
                                    </div>
                                    <span class="application-field__error field-error"></span>
                                </div>
                            </div>
                            <div class="body-card__block block" data-type="part" data-name="no_data" data-active="false">
                                <div class="application-field field center" data-name="no_data">
                                    <span class="application-field__title">Размер финансирования</span>
                                    <div class="application-field__item">
                                        <div class="application-field__body radio">
                                            <div class="radio__body">
                                                <div class="radio__item" data-id="1">
                                                    <i class="radio__icon inline far fa-square"></i>
                                                    <span class="radio__text">Нет данных</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input class="application-field__result field-result" type="hidden" data-multiple_block_field="no_data" name="no_data">
                                </div>
                                <div class="body-card__block block" data-type="part" data-name="percent" data-active="true">
                                    <div class="application-field field" data-required="true" data-name="percent" data-pattern="number">
                                        <span class="application-field__title">Процент финансирования</span>
                                        <div class="application-field__item">
                                            <div class="application-field__body">
                                                <input class="application-field__input field-result" type="text" data-multiple_block_field="percent" name="percent" placeholder="Введите процент">
                                            </div>
                                            <span class="application-field__error field-error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--//Шаблон "Средства юридических лиц"//-->
                        <!--Шаблон "Собственные средства застройщика"-->
                        <div class="body-card__block block" data-type="part" data-name="builder_source" data-active="false">
                            <div class="body-card__block block" data-type="part" data-name="no_data" data-active="false">
                                <div class="application-field field center" data-name="no_data">
                                    <span class="application-field__title">Размер финансирования</span>
                                    <div class="application-field__item">
                                        <div class="application-field__body radio">
                                            <div class="radio__body">
                                                <div class="radio__item" data-id="1">
                                                    <i class="radio__icon inline far fa-square"></i>
                                                    <span class="radio__text">Нет данных</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input class="application-field__result field-result" type="hidden" data-multiple_block_field="no_data" name="no_data">
                                </div>
                                <div class="body-card__block block" data-type="part" data-name="percent" data-active="true">
                                    <div class="application-field field" data-required="true" data-name="percent" data-pattern="number">
                                        <span class="application-field__title">Процент финансирования</span>
                                        <div class="application-field__item">
                                            <div class="application-field__body">
                                                <input class="application-field__input field-result" type="text" data-multiple_block_field="percent" name="percent" placeholder="Введите процент">
                                            </div>
                                            <span class="application-field__error field-error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--//Шаблон "Собственные средства застройщика"//-->
                        <!--Шаблон "Средства инвестора"-->
                        <div class="body-card__block block" data-type="part" data-name="investor" data-active="false">
                            <div class="body-card__block block" data-type="part" data-name="no_data" data-active="false">
                                <div class="application-field field center" data-name="no_data">
                                    <span class="application-field__title">Размер финансирования</span>
                                    <div class="application-field__item">
                                        <div class="application-field__body radio">
                                            <div class="radio__body">
                                                <div class="radio__item" data-id="1">
                                                    <i class="radio__icon inline far fa-square"></i>
                                                    <span class="radio__text">Нет данных</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input class="application-field__result field-result" type="hidden" data-multiple_block_field="no_data" name="no_data">
                                </div>
                                <div class="body-card__block block" data-type="part" data-name="percent" data-active="true">
                                    <div class="application-field field" data-required="true" data-name="percent" data-pattern="number">
                                        <span class="application-field__title">Процент финансирования</span>
                                        <div class="application-field__item">
                                            <div class="application-field__body">
                                                <input class="application-field__input field-result" type="text" data-multiple_block_field="percent" name="percent" placeholder="Введите процент">
                                            </div>
                                            <span class="application-field__error field-error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--//Шаблон "Средства инвестора"//-->
                        <!--Шаблон "Размер финансирования"-->
                        <div class="body-card__block block" data-type="part" data-name="no_data" data-active="false">
                            <div class="application-field field center" data-name="no_data">
                                <span class="application-field__title">Размер финансирования</span>
                                <div class="application-field__item">
                                    <div class="application-field__body radio">
                                        <div class="radio__body">
                                            <div class="radio__item" data-id="1">
                                                <i class="radio__icon inline far fa-square"></i>
                                                <span class="radio__text">Нет данных</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input class="application-field__result field-result" type="hidden" data-multiple_block_field="no_data" name="no_data">
                            </div>
                        </div>
                        <!--//Шаблон "Размер финансирования"//-->
                        <!--Шаблон "Процент финансирования"-->
                        <div class="body-card__block block" data-type="part" data-name="percent" data-active="false">
                            <div class="application-field field" data-required="true" data-name="percent" data-pattern="number">
                                <span class="application-field__title">Процент финансирования</span>
                                <div class="application-field__item">
                                    <div class="application-field__body">
                                        <input class="application-field__input field-result" type="text" data-multiple_block_field="percent" name="percent" placeholder="Введите процент">
                                    </div>
                                    <span class="application-field__error field-error"></span>
                                </div>
                            </div>
                        </div>
                        <!--//Шаблон "Процент финансирования"//-->
                    </div>
                    <!--//Шаблоны источников финансирования//-->


                </div>
    
            </div>
        </div>

    </div>
    
    
</div>

<div id="misc_overlay" class="modal-overlay"></div>
