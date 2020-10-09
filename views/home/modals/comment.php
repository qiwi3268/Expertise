<?php $_VT = \Lib\Singles\VariableTransfer::getInstance(); ?>

<div class="comment-overlay"></div>
<div class="modal comment-modal">
    
    <div class="comment-modal__form">
    
        <div class="comment-modal__header">
            <div class="comment-modal__title">Замечание</div>
        </div>
        <div class="comment-modal__body">
            <div class="form-field field" data-name="text" data-pattern="text" data-required="true">
                <span class="form-field__title">Текст замечания</span>
                <div class="form-field__item">
                    <div class="form-field__body">
                        <textarea class="form-field__input field-result" name="text"></textarea>
                    </div>
                    <span class="form-field__error field-error"></span>
                </div>
            </div>
            <div class="form-field field" data-name="normative" data-pattern="text" data-required="true">
                <span class="form-field__title">Ссылка на нормативный документ</span>
                <div class="form-field__item">
                    <div class="form-field__body">
                        <textarea class="form-field__input field-result" name="normative"></textarea>
                    </div>
                    <span class="form-field__error field-error"></span>
                </div>
            </div>
            <div class="form-field field" data-name="no_files" data-misc_field>
                <div class="form-field__item">
                    <div class="radio form-field__body">
                        <div class="radio__body">
                            <div class="radio__item" data-id="1">
                                <i class="radio__icon far fa-square"></i>
                                <span class="radio__text">Отметка файлов не требуется</span>
                            </div>
                        </div>
                    </div>
                </div>
                <input class="form-field__result field-result" type="hidden" name="no_files">
            </div>
            <div class="form-field field" data-misc_field data-name="criticality" data-required="true">
                <span class="form-field__title field-title">Критичность</span>
                <div class="form-field__item">
                    <div class="form-field__body">
                        <div class="form-field__select field-select" data-misc_select>
                            <span class="form-field__value field-value" data-misc_value>Выберите критичность</span>
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
                <input class="form-field__result field-result" type="hidden" data-misc_result name="criticality">
            </div>
        </div>
        
    </div>
    
    <div class="comment-modal__files">
        <div class="comment-modal__header">
            <div class="comment-modal__title">Документация</div>
        </div>
        <div class="comment-modal__body">
            <div class="documentation" data-id_file_field data-mapping_level_1="<?= $_VT->getValue('documentation_mapping_level_1') ?>" data-mapping_level_2="<?= $_VT->getValue('documentation_mapping_level_2') ?>">
                <?php foreach ($_VT->getValue('documentation_files_in_structure') as $node): ?>
                    <div class="documentation__node">
                        <div class="documentation__header" data-title="<?= $node['is_header'] ? 'true' : 'false' ?>">
                            <span class="documentation__name" style="padding-left: <?= $node['depth']*25 + 15 ?>px"><?= $node['name'] ?></span>
                        </div>
                        <?php if (isset($node['files'])): ?>
                            <div class="documentation__files files" >
                                <?php foreach ($node['files'] as $file): ?>
                                    <div class="files__item" data-read_only="true" data-id="<?= $file['id'] ?>" style="padding-left: <?= $node['depth']*25 + 7 ?>px" data-validate_results='<?= $file['validate_results'] ?>'>
                                        <div class="files__info">
                                            <i class="files__icon fas <?= $file['file_icon'] ?>"></i>
                                            <div class="files__description">
                                                <span class="files__name"><?= $file['file_name'] ?></span>
                                                <span class="files__size"><?= $file['human_file_size'] ?></span>
                                            </div>
                                        </div>
                                        <div class="files__state"></div>
                                        <div class="files__actions">
                                            <i class="files__unload fas fa-file-download"></i>
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
    
</div>

<div id="misc_overlay" class="modal-overlay"></div>
