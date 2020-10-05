<?php $_data = \Lib\Singles\TemplateMaker::getSelfData(); ?>

<div class="multiple-block__add" data-multiple_add>
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
                <div class="form-field__body radio" data-required="true" data-result_callback="financing_type">
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
                    <?php foreach ($_data['budget_level'] as $pageNumber => $page): ?>
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
                    <div class="form-field__body radio">
                        <div class="radio__body">
                            <div class="radio__item" data-id="1">
                                <i class="radio__icon inline far fa-square"></i>
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
                    <div class="form-field__body radio">
                        <div class="radio__body">
                            <div class="radio__item" data-id="1">
                                <i class="radio__icon inline far fa-square"></i>
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
                    <div class="form-field__body radio">
                        <div class="radio__body">
                            <div class="radio__item" data-id="1">
                                <i class="radio__icon inline far fa-square"></i>
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
                    <div class="form-field__body radio">
                        <div class="radio__body">
                            <div class="radio__item" data-id="1">
                                <i class="radio__icon inline far fa-square"></i>
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
    <!--Шаблон "Размер финансирования"-->
    <div class="multiple-block__item" data-block data-type="template" data-name="financing_source_no_data" data-active="false">
        <div class="form-field field" data-name="financing_source_no_data">
            <span class="form-field__title">Размер финансирования</span>
            <div class="form-field__item">
                <div class="form-field__body radio">
                    <div class="radio__body">
                        <div class="radio__item" data-id="1">
                            <i class="radio__icon inline far fa-square"></i>
                            <span class="radio__text">Нет данных</span>
                        </div>
                    </div>
                </div>
            </div>
            <input class="form-field__result field-result" type="hidden" data-multiple_block_field="no_data" name="financing_source_no_data">
        </div>
    </div>
    <!--//Шаблон "Размер финансирования"//-->
    <!--Шаблон "Процент финансирования"-->
    <div class="multiple-block__item" data-block data-type="template" data-name="percent" data-active="false">
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
    <!--//Шаблон "Процент финансирования"//-->
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







