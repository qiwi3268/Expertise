<?php $_VT = \Lib\Singles\VariableTransfer::getInstance(); ?>

<div class="overlay comment-overlay"></div>
<div class="modal comment-modal">

    <input id="comment_id" class="comment-modal__id field-result" name="id" type="hidden">
    <div class="comment-modal__form">

        <div class="comment-modal__header">
            <div class="comment-modal__title">Замечание</div>
        </div>
        <div class="comment-modal__body">

            <div class="comment-modal__actions">
                <div class="comment-modal__action form-button save" data-save_comment>
                    <span class="form-button__label">Сохранить замечание</span>
                    <i class="form-button__icon fas fa-check"></i>
                </div>
                <div class="comment-modal__action form-button delete" data-delete_comment>
                    <span class="form-button__label">Отмена</span>
                    <i class="form-button__icon fas fa-times"></i>
                </div>
            </div>

            <div class="field-card field" data-name="text" data-required="true" data-pattern="text">
                <div class="field-card__header">
                    <div class="field-card__title">Текст замечания</div>
                </div>
                <div class="field-card__body">
                    <textarea id="comment_text" class="field-card__input field-result" name="text" rows="10"></textarea>
                </div>
                <span class="form-field__error field-error">Поле обязательно для заполнения</span>
            </div>

            <div class="form-field field" data-misc_field data-name="comment_criticality" data-required="true">
                <span class="form-field__title field-title">Критичность замечания</span>
                <div class="form-field__item">
                    <div class="form-field__body">
                        <div class="form-field__select field-select" data-misc_select>
                            <span id="comment_criticality_name" class="form-field__value field-value" data-misc_value>Выберите критичность</span>
                            <i class="form-field__icon-misc fas fa-bars"></i>
                            <i class="form-field__icon-filled fas fa-check"></i>
                        </div>
                    </div>
                    <span class="form-field__error field-error">Поле обязательно для заполнения</span>
                </div>
                <div class="modal" data-misc_modal data-result_callback="document_field">
                    <i class="modal__close fas fa-times" data-misc_close></i>
                    <div class="modal__items" data-misc_body>
                        <?php foreach ($_VT->getValue('comment_criticality') as $pageNumber => $page): ?>
                            <div class="modal__page" data-misc_page="<?= $pageNumber ?>">
                                <?php foreach ($page as $item): ?>
                                    <div class="modal__item" data-misc_item data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <input id="comment_criticality_value" class="form-field__result field-result" type="hidden" data-misc_result name="comment_criticality">
            </div>

            <div class="comment-modal__block" data-block data-name="normative_document" data-active="false">
                <div class="field-card field" data-name="normative_document" data-required="true" data-pattern="text">
                    <div class="field-card__header">
                        <div class="field-card__title">Ссылка на нормативный документ</div>
                    </div>
                    <div class="field-card__body">
                        <textarea id="normative_document" class="field-card__input field-result" name="normative_document"></textarea>
                    </div>
                    <span class="form-field__error field-error">Поле обязательно для заполнения</span>
                </div>
            </div>


            <div class="form-field field" data-name="no_files" data-misc_field>
                <div class="form-field__item">
                    <div class="radio form-field__body">
                        <div class="radio__body">
                            <div id="no_files" class="radio__item" data-id="1">
                                <i class="radio__icon far fa-square"></i>
                                <span class="radio__text">Отметка файлов не требуется</span>
                            </div>
                        </div>
                    </div>
                </div>
                <input class="form-field__result field-result" type="hidden" name="no_files">
            </div>

            <div id="note" class="field-card field" data-name="note" data-required="false" data-pattern="text">

                <div class="field-card__header">
                    <div class="field-card__title">Личная заметка</div>
                    <i class="field-card__icon-secret fas fa-user-secret"></i>
                </div>
                <div class="field-card__body">
                    <textarea id="comment_note" class="field-card__input field-result" name="note"></textarea>
                </div>
                <span class="form-field__error field-error">Поле обязательно для заполнения</span>
            </div>


        </div>

    </div>

    <div class="comment-modal__files">
        <div class="comment-modal__header">
            <div class="comment-modal__title">Документация</div>
        </div>
        <div class="comment-modal__body">
            <div id="documentation" class="documentation" data-id_file_field data-mapping_level_1="<?= $_VT->getValue('documentation_mapping_level_1') ?>" data-mapping_level_2="<?= $_VT->getValue('documentation_mapping_level_2') ?>">
                <?php foreach ($_VT->getValue('documentation_files_in_structure') as $node): ?>
                    <div class="documentation__node">
                        <div class="documentation__header" data-title="<?= $node['is_header'] ? 'true' : 'false' ?>">
                            <span class="documentation__name" style="padding-left: <?= $node['depth']*25 + 15 ?>px"><?= $node['name'] ?></span>
                        </div>
                        <?php if (isset($node['files'])): ?>
                            <div class="documentation__files files">
                                <?php foreach ($node['files'] as $file): ?>
                                    <div class="files__item" data-read_only="true" data-id="<?= $file['id'] ?>" style="padding-left: <?= $node['depth']*25 + 7 ?>px" data-validate_results='<?= $file['validate_results'] ?>'>
                                        <div class="files__info">
                                            <i class="files__checkbox far fa-square"></i>
                                            <i class="files__icon fas <?= $file['file_icon'] ?>"></i>
                                            <div class="files__description">
                                                <span class="files__name"><?= $file['file_name'] ?></span>
                                                <span class="files__size"><?= $file['human_file_size'] ?></span>
                                            </div>
                                        </div>
                                        <div class="files__state"></div>
                                        <div class="files__actions">
                                            <i class="files__unload fas fa-angle-double-down"></i>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div id="misc_overlay" class="overlay modal-overlay"></div>

</div>

