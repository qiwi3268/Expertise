
<?php //$variablesTV = \Lib\Singles\VariableTransfer::getInstance(); ?>
<?php //$_defaultParameters = $variablesTV->getValue('defaultParameters'); ?>

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
            <div class="card-form__body card-form card-body">
                
                
<!--                --><?php //$_applicantDetails = $_defaultParameters['applicantDetails'] ?>
                
<!--                <div class="section" data-required="true">
                    <div class="section__header">Полное наименование</div>
                    <div class="section__body">
                        <textarea class="section__input field-result" name="" placeholder="Введите значение"><?/*= $_applicantDetails['full_name'] */?></textarea>
                    </div>
                </div>
                <div class="section" data-required="true">
                    <div class="section__header">ИНН</div>
                    <div class="section__body">
                        <input class="section__input field-result" name="" placeholder="Введите значение" value="<?/*= $_applicantDetails['INN'] */?>">
                    </div>
                </div>-->
            
    
                <!--Полное наименование-->
                <div class="form-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="form-field__title">Полное наименование</span>
                    <div class="form-field__item">
                        <div class="form-field__body filled">
                            <textarea data-form="application" class="form-field__input field-result" name=""></textarea>
                            <i class="form-field__icon-filled fas fa-check"></i>
                        </div>
                        <span class="form-field__error field-error"></span>
                    </div>
                </div>
                <!--//Полное наименование//-->
    
                <!--ИНН-->
                <div class="form-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="form-field__title">ИНН</span>
                    <div class="form-field__item">
                        <div class="form-field__body filled">
                            <input class="form-field__input field-result" type="text" name="INN" placeholder="Введите значение" value="1123499884">
                            <i class="form-field__icon-filled fas fa-check"></i>
                        </div>
    
                        <span class="form-field__error field-error"></span>
                    </div>
                </div>
                <!--//ИНН//-->
    
                <!--КПП-->
                <div class="form-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="form-field__title">КПП</span>
                    <div class="form-field__item">
                        <div class="form-field__body filled">
                            <input class="form-field__input field-result" type="text" name="KPP" placeholder="Введите значение" value="54459884">
                            <i class="form-field__icon-filled fas fa-check"></i>
                        </div>
            
                        <span class="form-field__error field-error"></span>
                    </div>
                </div>
                <!--//КПП//-->
    
                <!--ОГРН-->
                <div class="form-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="form-field__title">ОГРН</span>
                    <div class="form-field__item">
                        <div class="form-field__body filled">
                            <input class="form-field__input field-result" type="text" name="OGRN" placeholder="Введите значение" value="789959884">
                            <i class="form-field__icon-filled fas fa-check"></i>
                        </div>
            
                        <span class="form-field__error field-error"></span>
                    </div>
                </div>
                <!--//ОГРН//-->
    
                <!--Адрес-->
                <div class="form-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="form-field__title">Адрес</span>
                    <div class="form-field__item">
                        <div class="form-field__body filled">
                            <textarea data-form="application" class="form-field__input field-result" name="">Челябинск</textarea>
                            <i class="form-field__icon-filled fas fa-check"></i>
                        </div>
                        <span class="form-field__error field-error"></span>
                    </div>
                </div>
                <!--//Адрес//-->
    
                <!--Место нахождения-->
                <div class="form-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="form-field__title">Место нахождения</span>
                    <div class="form-field__item">
                        <div class="form-field__body filled">
                            <textarea data-form="application" class="form-field__input field-result" name="">Челябинск</textarea>
                            <i class="form-field__icon-filled fas fa-check"></i>
                        </div>
                        <span class="form-field__error field-error"></span>
                    </div>
                </div>
                <!--//Место нахождения//-->
    
                <!--Электронная почта-->
                <div class="form-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="form-field__title">Электронная почта</span>
                    <div class="form-field__item">
                        <div class="form-field__body filled">
                            <input class="form-field__input field-result" type="text" name="" placeholder="Введите значение" value="test@test.com">
                            <i class="form-field__icon-filled fas fa-check"></i>
                        </div>
            
                        <span class="form-field__error field-error"></span>
                    </div>
                </div>
                <!--//Электронная почта//-->
    
                <!--Телефон-->
                <div class="form-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="form-field__title">Телефон</span>
                    <div class="form-field__item">
                        <div class="form-field__body filled">
                            <input class="form-field__input field-result" type="text" name="KPP" placeholder="Введите значение" value="89122332423">
                            <i class="form-field__icon-filled fas fa-check"></i>
                        </div>
            
                        <span class="form-field__error field-error"></span>
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
            <div class="card-form__body card-form card-body">
                <!--Договор-->
                <div class="form-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="form-field__title">Договор</span>
                    <div class="form-field__item">
                        <div class="card-form__list filled" >
                            <input class="card-form__cell field-result" type="text" name="contract" placeholder="Введите значение" value="Договор №123">
                            <input class="card-form__cell field-result" type="text" name="contract" placeholder="Введите значение" value="Договор №123">
                            <input class="card-form__cell field-result" type="text" name="contract" placeholder="Введите значение" value="Договор №123">
                        </div>
<!--                        <i class="form-field__icon-filled fas fa-check"></i>-->
    
                        <span class="form-field__error field-error"></span>
                    </div>
                </div>
                <!--//Договор//-->
                <!--Дополнительное соглашение-->
                <div class="form-field field" data-required="true" data-active="false" data-name="" data-pattern="text">
                    <span class="form-field__title">Дополнительное соглашение</span>
                    <div class="form-field__item">
                        <div class="form-field__body filled">
                            <input class="form-field__input field-result" type="text" name="INN" placeholder="Введите значение" value="Дополнительное соглашение №345">
                            <i class="form-field__icon-filled fas fa-check"></i>
                        </div>
                        <span class="form-field__error field-error"></span>
                    </div>
                </div>
                <!--//Дополнительное соглашение//-->
                <!--Доверенность-->
                <div class="form-field field" data-required="true" data-name="" data-pattern="text">
                    <span class="form-field__title">Доверенность</span>
                    <div class="form-field__item">
                        <div class="form-field__body filled">
                            <input class="form-field__input field-result" type="text" name="INN" placeholder="Введите значение" value="Доверенность №123">
                            <i class="form-field__icon-filled fas fa-check"></i>
                        </div>
                        <span class="form-field__error field-error"></span>
                    </div>
                </div>
                <!--//Доверенность//-->
                
            </div>
        </div>
    
        <div class="card-form application-form__card card" data-type="financing_sources">
            <div class="card-form__header card-expand">
                <span class="card-form__title">СВЕДЕНИЯ, СОДЕРЖАЩИЕСЯ В ДОКУМЕНТАХ, ПРЕДСТАВЛЕННЫХ ДЛЯ ПРОВЕДЕНИЯ ЭКСПЕРТИЗЫ ПРОЕКТНОЙ ДОКУМЕНТАЦИИ</span>
                <i class="card-form__icon-expand fas fa-chevron-down arrow-down card-icon"></i>
            </div>
            <div class="card-form__body card-body">
            
                <!--Источники финансирования-->
                <div class="multiple-block card-form__block" data-block data-type="multiple" data-name="financing_sources">
                    <?php \Lib\Singles\TemplateMaker::requireByName('editFinancingSources'); ?>
                </div>
                <!--//Источники финансирования//-->
                
            </div>
        </div>
    
        
    </div>
    
    
</div>

<div id="misc_overlay" class="modal-overlay"></div>
