

<div class="action-info">Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)</div>

<div class="action-header">
    <div class="action-header__title">
        <span class="action-header__name">СОЗДАНИЕ ОПИСАНИЯ РАЗДЕЛА</span>
        <span class="action-header__document">Заявление на экспертизу № 2020-6-1179/2020-8-1025-С от 18.08.2020</span>
    </div>
    <div class="action-header__buttons">
        <span class="action-header__button" data-action_submit>ОК</span>
        <span class="action-header__button">Отмена</span>
    </div>
</div>

<div class="descriptive-part">
    
    <div class="descriptive-part__card card">
        <div class="descriptive-part__header card-expand">Описание</div>
        <div class="descriptive-part__editor card-body expanded">
            <textarea class="descriptive-part__text" id="description"></textarea>
        </div>
    </div>
    
    <div class="descriptive-part__card card">
        
        <div class="descriptive-part__header card-expand">Технико-экономические показатели</div>
        <div class="descriptive-part__body card-body expanded">
            
            <!--Технико-экономические показатели-->
            <div class="multiple-block descriptive-part__multiple-block" data-block data-type="multiple" data-name="TEP" data-saved="true">
                <div class="multiple-block__add" data-multiple_add="add_tep">
                    <span class="multiple-block__text">Добавить технико-экономический показатель</span>
                    <i class="multiple-block__icon fas fa-plus"></i>
                </div>
                <!--Шаблоны ТЭПа-->
                <div class="multiple-block__item" data-block data-name="templates_container">
                    <!--Шаблон элемента множественного блока-->
                    <div class="multiple-block__part" data-block data-name="multiple_block_part" data-active="false" data-validation_callback="TEP">
                        <div class="multiple-block__title" data-multiple_title>...</div>
                        <div class="multiple-block__item" data-block data-multiple_body>
                            <!--Шаблон ТЭПа-->
                            <div class="multiple-block__item" data-block data-type="template" data-name="TEP">
                                <div class="form-field field" data-required="true" data-name="indicator">
                                    <span class="form-field__title">Показатель</span>
                                    <div class="form-field__item">
                                        <div class="form-field__body">
                                            <input class="form-field__input field-result" type="text" data-multiple_block_field="indicator" name="indicator" placeholder="Введите показатель" data-multiple_title>
                                        </div>
                                        <span class="form-field__error field-error"></span>
                                    </div>
                                </div>
                                <div class="form-field field" data-required="true" data-name="measure">
                                    <span class="form-field__title">Единица измерения</span>
                                    <div class="form-field__item">
                                        <div class="form-field__body">
                                            <input class="form-field__input field-result" type="text" data-multiple_block_field="measure" name="measure" placeholder="Введите единицу измерения">
                                        </div>
                                        <span class="form-field__error field-error"></span>
                                    </div>
                                </div>
                                <div class="form-field field" data-required="true" data-name="value">
                                    <span class="form-field__title">Значение</span>
                                    <div class="form-field__item">
                                        <div class="form-field__body">
                                            <input class="form-field__input field-result" type="text" data-multiple_block_field="value" name="value" placeholder="Введите значение">
                                        </div>
                                        <span class="form-field__error field-error"></span>
                                    </div>
                                </div>
                                <div class="form-field field" data-required="false" data-name="note">
                                    <span class="form-field__title">Примечание</span>
                                    <div class="form-field__item">
                                        <div class="form-field__body">
                                            <input class="form-field__input field-result" type="text" data-multiple_block_field="note" name="note" placeholder="Введите примечание">
                                        </div>
                                        <span class="form-field__error field-error"></span>
                                    </div>
                                </div>
                            </div>
                            <!--//Шаблон ТЭПа//-->
                            <!--Шаблон действий-->
                            <div class="multiple-block__item" data-block data-name="actions">
                                <div class="multiple-block__actions">
                                    <div class="multiple-block__button save">
                                        <span class="multiple-block__text">Сохранить ТЭП</span>
                                        <i class="multiple-block__icon fas fa-check"></i>
                                    </div>
                                    <div class="multiple-block__button cancel">
                                        <span class="multiple-block__text">Отмена</span>
                                        <i class="multiple-block__icon fas fa-times"></i>
                                    </div>
                                </div>
                            </div>
                            <!--//Шаблон действий//-->
                        </div>
                    </div>
                    <!--Шаблон элемента множественного блока-->
                    <!--Шаблон сохраненного блока-->
                    <div class="multiple-block__item" data-block data-name="part_short" data-active="false">
                        <div class="multiple-block__short part-short">
                            <span class="multiple-block__info" data-part_info></span>
                            <i class="multiple-block__delete fas fa-times delete"></i>
                        </div>
                    </div>
                    <!--//Шаблон сохраненного блока//-->
                </div>
                <!--//Шаблоны ТЭПов//-->
            </div>
            <!--//Технико-экономические показатели//-->
        </div>
    </div>
    
    <div class="descriptive-part__card card">
        <div class="descriptive-part__header card-expand">Замечания</div>
        <div class="descriptive-part__comments card-body expanded">
            
            <div class="multiple-block__add" data-add_comment>
                <span class="multiple-block__text">Создать замечание</span>
                <i class="multiple-block__icon fas fa-plus"></i>
            </div>
            
            <table id="comments_table" class="comments-table" data-active="false">
                <thead class="comments-table__header">
                    <tr>
                        <th style="width: 40%" colspan="2">Текст замечания</th>
                        <th style="width: 15%">Нормативный документ</th>
                        <th style="width: 5%">Критичность</th>
                        <th style="width: 40%">Отмеченные файлы</th>
                    </tr>
                </thead>
                <tbody id="comments_table_body" class="comments-table__body">
                </tbody>
            </table>
            
            
        </div>
        
    </div>
    
</div>