
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
                <div class="body-card__row field" data-required="true" data-name="" data-pattern="text">
                    <span class="body-card__title">Полное наименование</span>
                    <div class="body-card__item">
                        <div class="body-card__field filled">
                            <textarea data-form="application" class="body-card__input field-result" name=""></textarea>
                            <i class="body-card__icon-filled fas fa-check"></i>
                        </div>
                        <span class="body-card__error field-error"></span>
                    </div>
                </div>
                <!--//Полное наименование//-->
    
                <!--ИНН-->
                <div class="body-card__row field" data-required="true" data-name="" data-pattern="text">
                    <span class="body-card__title">ИНН</span>
                    <div class="body-card__item">
                        <div class="body-card__field filled">
                            <input class="body-card__input field-result" type="text" name="INN" placeholder="Введите значение" value="1123499884">
                            <i class="body-card__icon-filled fas fa-check"></i>
                        </div>
    
                        <span class="body-card__error field-error"></span>
                    </div>
                </div>
                <!--//ИНН//-->
    
                <!--КПП-->
                <div class="body-card__row field" data-required="true" data-name="" data-pattern="text">
                    <span class="body-card__title">КПП</span>
                    <div class="body-card__item">
                        <div class="body-card__field filled">
                            <input class="body-card__input field-result" type="text" name="KPP" placeholder="Введите значение" value="54459884">
                            <i class="body-card__icon-filled fas fa-check"></i>
                        </div>
            
                        <span class="body-card__error field-error"></span>
                    </div>
                </div>
                <!--//КПП//-->
    
                <!--ОГРН-->
                <div class="body-card__row field" data-required="true" data-name="" data-pattern="text">
                    <span class="body-card__title">ОГРН</span>
                    <div class="body-card__item">
                        <div class="body-card__field filled">
                            <input class="body-card__input field-result" type="text" name="OGRN" placeholder="Введите значение" value="789959884">
                            <i class="body-card__icon-filled fas fa-check"></i>
                        </div>
            
                        <span class="body-card__error field-error"></span>
                    </div>
                </div>
                <!--//ОГРН//-->
    
                <!--Адрес-->
                <div class="body-card__row field" data-required="true" data-name="" data-pattern="text">
                    <span class="body-card__title">Адрес</span>
                    <div class="body-card__item">
                        <div class="body-card__field filled">
                            <textarea data-form="application" class="body-card__input field-result" name="">Челябинск</textarea>
                            <i class="body-card__icon-filled fas fa-check"></i>
                        </div>
                        <span class="body-card__error field-error"></span>
                    </div>
                </div>
                <!--//Адрес//-->
    
                <!--Место нахождения-->
                <div class="body-card__row field" data-required="true" data-name="" data-pattern="text">
                    <span class="body-card__title">Место нахождения</span>
                    <div class="body-card__item">
                        <div class="body-card__field filled">
                            <textarea data-form="application" class="body-card__input field-result" name="">Челябинск</textarea>
                            <i class="body-card__icon-filled fas fa-check"></i>
                        </div>
                        <span class="body-card__error field-error"></span>
                    </div>
                </div>
                <!--//Место нахождения//-->
    
                <!--Электронная почта-->
                <div class="body-card__row field" data-required="true" data-name="" data-pattern="text">
                    <span class="body-card__title">Электронная почта</span>
                    <div class="body-card__item">
                        <div class="body-card__field filled">
                            <input class="body-card__input field-result" type="text" name="" placeholder="Введите значение" value="test@test.com">
                            <i class="body-card__icon-filled fas fa-check"></i>
                        </div>
            
                        <span class="body-card__error field-error"></span>
                    </div>
                </div>
                <!--//Электронная почта//-->
    
                <!--Телефон-->
                <div class="body-card__row field" data-required="true" data-name="" data-pattern="text">
                    <span class="body-card__title">Телефон</span>
                    <div class="body-card__item">
                        <div class="body-card__field filled">
                            <input class="body-card__input field-result" type="text" name="KPP" placeholder="Введите значение" value="89122332423">
                            <i class="body-card__icon-filled fas fa-check"></i>
                        </div>
            
                        <span class="body-card__error field-error"></span>
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
                <div class="body-card__row field" data-required="true" data-name="" data-pattern="text">
                    <span class="body-card__title">Договор</span>
                    <div class="body-card__item">
                        <div class="body-card__list filled" >
                            <input class="body-card__cell field-result" type="text" name="contract" placeholder="Введите значение" value="Договор №123">
                            <input class="body-card__cell field-result" type="text" name="contract" placeholder="Введите значение" value="Договор №123">
                            <input class="body-card__cell field-result" type="text" name="contract" placeholder="Введите значение" value="Договор №123">
                        </div>
<!--                        <i class="body-card__icon-filled fas fa-check"></i>-->
    
                        <span class="body-card__error field-error"></span>
                    </div>
                </div>
                <!--//Договор//-->
                <!--Дополнительное соглашение-->
                <div class="body-card__row field" data-required="true" data-active="false" data-name="" data-pattern="text">
                    <span class="body-card__title">Дополнительное соглашение</span>
                    <div class="body-card__item">
                        <div class="body-card__field filled">
                            <input class="body-card__input field-result" type="text" name="INN" placeholder="Введите значение" value="Дополнительное соглашение №345">
                            <i class="body-card__icon-filled fas fa-check"></i>
                        </div>
                        <span class="body-card__error field-error"></span>
                    </div>
                </div>
                <!--//Дополнительное соглашение//-->
                <!--Доверенность-->
                <div class="body-card__row field" data-required="true" data-name="" data-pattern="text">
                    <span class="body-card__title">Доверенность</span>
                    <div class="body-card__item">
                        <div class="body-card__field filled">
                            <input class="body-card__input field-result" type="text" name="INN" placeholder="Введите значение" value="Доверенность №123">
                            <i class="body-card__icon-filled fas fa-check"></i>
                        </div>
                        <span class="body-card__error field-error"></span>
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
    
                <div class="body-card__row field" data-name="finance_type" data-required="true">
                    <span class="body-card__title field-title">Вид финансирования</span>
                    <div class="body-card__item">
                        <div class="body-card__field radio" data-required="true">
                            <div class="radio__body">
                                <div class="radio__item selected" data-id="1">
                                    <i class="radio__icon inline far fa-check-square"></i>
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
                    <input class="body-card__result field-result" type="hidden" data-field="type" name="finance_type" value="1">
                </div>
                <div class="body-card__row field" data-misc_field="" data-name="budget_level" data-required="true">
                    <span class="body-card__title field-title">Уровень бюджета</span>
                    <div class="body-card__item">
                        <div class="body-card__field">
                            <div class="body-card__select field-select filled" data-misc_select="" data-id_misc="0">
                                <span class="body-card__value field-value" data-misc_value="">Федеральный бюджет</span>
                                <i class="body-card__icon fas fa-bars"></i>
                                <i class="body-card__icon-filled fas fa-check"></i>
                            </div>
                        </div>
                        <span class="body-card__error field-error">Поле обязательно для заполнения</span>
                    </div>
                    <div class="modal" data-misc_modal="" data-result_callback="application_field">
                        <i class="modal__close fas fa-times" data-misc_close=""></i>
                        <div class="modal__items" data-misc_body="">
                            <div class="modal__page active" data-misc_page="0">
                                <div class="modal__item" data-misc_item="" data-id="1">Бюджет территориального государственного внебюджетного фонда</div>
                                <div class="modal__item" data-misc_item="" data-id="2">Бюджет государственного внебюджетного фонда РФ</div>
                                <div class="modal__item" data-misc_item="" data-id="3">Федеральный бюджет</div>
                                <div class="modal__item" data-misc_item="" data-id="4">Бюджет субъекта РФ</div>
                                <div class="modal__item" data-misc_item="" data-id="5">Местный бюджет</div>
                                <div class="modal__item" data-misc_item="" data-id="6">Нет данных</div>
                            </div>
                        </div>
                    </div>
                    <input class="body-card__result field-result" data-misc_result="" type="hidden" data-field="budget_level" name="budget_level" value="3">
                </div>
        
        
        
            </div>
        </div>

    </div>
    
    
</div>