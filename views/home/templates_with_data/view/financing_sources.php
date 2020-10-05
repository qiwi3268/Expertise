<?php
    $_data = \Lib\Singles\TemplateMaker::getSelfData();
    $_financingSources = $_data['financing_sources'];
?>

<?php var_dump($_data); ?>

<?php if (!empty($_financingSources['type_1'])): ?>
    <?php foreach ($_financingSources['type_1'] as $source): ?>
        <div class="multiple-block__part short" data-block data-name="multiple_block_part" data-active="true" data-dependency_scope>
            <div class="multiple-block__title" data-multiple_title>Бюджетные средства</div>
            <div class="multiple-block__item" data-block data-type="template" data-name="type" data-active="false">
                <div class="form-field field" data-name="financing_type" data-required="true">
                    <span class="form-field__title field-title">Вид финансирования</span>
                    <div class="form-field__item">
                        <div class="radio form-field__body" data-required="true" data-result_callback="financing_type">
                            <div class="radio__body">
                                <div class="radio__item" data-id="1" data-selected="true">
                                    <i class="radio__icon inline far fa-check-square"></i>
                                    <span class="radio__text" data-part_title="1">Бюджетные средства</span>
                                </div>
                                <div class="radio__item" data-id="2">
                                    <i class="radio__icon inline far fa-square"></i>
                                    <span class="radio__text" data-part_title="2">Средства юридических лиц, указанных в ч. 2 статьи 48.2 ГрК</span>
                                </div>
                                <div class="radio__item" data-id="3">
                                    <i class="radio__icon inline far fa-square"></i>
                                    <span class="radio__text"
                                          data-part_title="3">Собственные средства застройщика</span>
                                </div>
                                <div class="radio__item" data-id="4">
                                    <i class="radio__icon inline far fa-square"></i>
                                    <span class="radio__text" data-part_title="4">Средства инвестора</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input class="form-field__result field-result" type="hidden" data-multiple_block_field="type"
                           name="financing_type" value="1">
                </div>
                <div class="multiple-block__item" data-block data-type="template" data-name="budget" data-active="true">
                    <div class="form-field field" data-misc_field data-name="budget_level" data-required="true">
                        <span class="form-field__title field-title">Уровень бюджета</span>
                        <div class="form-field__item">
                            <div class="form-field__body">
                                <div class="form-field__select field-select filled" data-misc_select>
                                    <span class="form-field__value field-value"
                                          data-misc_value><?= !empty($source['budget_level']) ? $source['budget_level']['name'] : 'Не выбрано';  ?></span>
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
                        <input class="form-field__result field-result" data-misc_result type="hidden"
                               data-multiple_block_field="budget_level" name="budget_level" value="<?= !empty($source['budget_level']) ? $source['budget_level']['id'] : ''; ?>">
                    </div>
                    <div class="multiple-block__item" data-block data-type="template" data-name="financing_source_no_data"
                         data-active="<?= !$source['no_data'] ?>">
                        <div class="form-field field" data-name="financing_source_no_data">
                            <span class="form-field__title">Размер финансирования</span>
                            <div class="form-field__item">
                                <div class="radio form-field__body">
                                    <div class="radio__body">
                                        <?php if ($source['no_data']): ?>
                                            <div class="radio__item" data-id="1" data-selected="true">
                                                <i class="radio__icon inline far fa-check-square"></i>
                                                <span class="radio__text">Нет данных</span>
                                            </div>
                                        <?php else: ?>
                                            <div class="radio__item" data-id="1" data-selected="false">
                                                <i class="radio__icon inline far fa-square"></i>
                                                <span class="radio__text">Нет данных</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <input class="form-field__result field-result" type="hidden"
                                   data-multiple_block_field="no_data" name="financing_source_no_data" value="<?php if ($source['no_data']): ?>1<?php endif; ?>">
                        </div>
                        <div class="multiple-block__item" data-block data-type="template" data-name="percent"
                             data-active="<?= $source['no_data'] ? 'false' : 'true' ?>">
                            <div class="form-field field" data-required="true" data-name="percent"
                                 data-pattern="number">
                                <span class="form-field__title">Процент финансирования</span>
                                <div class="form-field__item">
                                    <div class="form-field__body">
                                        <input class="form-field__input field-result" type="text"
                                               data-multiple_block_field="percent" name="percent"
                                               placeholder="Не указан" value="<?= $source['percent'] ?>">
                                    </div>
                                    <span class="form-field__error field-error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="multiple-block__item" data-block data-name="actions" data-active="false">
                <div class="multiple-block__actions">
                    <div class="multiple-block__button save">
                        <span class="multiple-block__text">Сохранить источник финансирования</span>
                        <i class="multiple-block__icon fas fa-check"></i>
                    </div>
                </div>
            </div>
            <div class="multiple-block__item" data-block data-name="part_short" data-active="true">
                <div class="multiple-block__short part-short">
                    <span class="multiple-block__info part-info">Бюджетные средства</span>
                    <i class="multiple-block__delete fas fa-times delete"></i>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($_financingSources['type_2'])): ?>
    <?php foreach ($_financingSources['type_2'] as $source): ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($_financingSources['type_3'])): ?>
    <?php foreach ($_financingSources['type_3'] as $source): ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($_financingSources['type_4'])): ?>
    <?php foreach ($_financingSources['type_4'] as $source): ?>
    <?php endforeach; ?>
<?php endif; ?>
