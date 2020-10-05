

<div class="action-info">Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)Капитальный ремонт здания школы в пос. Увельский, ул. Советская, 38 Увельского муниципального района Челябинской области (смета)</div>

<div class="description">

    <div class="description__card">
        <div class="description__header">Описательная часть</div>
        <textarea class="description__text" id="descriptive_part"></textarea>
    </div>
    
    <div class="description__card">
        
        <div class="description__header">Технико-экономические показатели</div>
        <div class="description__body">
    
            <!--Технико-экономические показатели-->
            <div class="multiple-block" data-block data-type="multiple" data-name="TEP">
        
                <div class="multiple-block__add" data-multiple_add>
                    <span class="multiple-block__text">Добавить технико-экономический показатель</span>
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
                                    <?php foreach ($variablesTV->getValue('budget_level') as $pageNumber => $page): ?>
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
                    <div class="multiple-block__item" data-block data-name="part_short" data-active="false">
                        <div class="multiple-block__short part-short">
                            <span class="multiple-block__info part-info"></span>
                            <i class="multiple-block__delete fas fa-times delete"></i>
                        </div>
                    </div>
                </div>
                <!--//Шаблоны источников финансирования//-->
    
            </div>
            <!--//Технико-экономические показатели//-->
        
        </div>
    
    </div>
    

</div>