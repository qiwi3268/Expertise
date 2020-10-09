<div class="comment-overlay"></div>
<div class="modal comment-modal">
    
    <div class="comment-modal__form">
    
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
    
            
            
            
            <div class="form-field  field" data-name="comment_without_files">
                <span class="form-field__title">Документация</span>
                <div class="form-field__item">
                    <div class="radio form-field__body">
                        <div class="radio__body">
                            <div class="radio__item" data-id="1">
                                <i class="radio__icon far fa-square"></i>
                                <span class="radio__text">Отметка файлов не требуется</span>
                            </div>
                        </div>
                    </div>
                    <span class="form-field__error field-error"></span>
    
                    <div class="form-field field" data-block data-name="mark_files">
                        <div class="form-field__item">
                            <div class="radio form-field__body">
                                <div class="multiple-block__add" data-mark_files>
                                    <span class="multiple-block__text">Отметить файлы</span>
                                    <i class="multiple-block__icon fas fa-plus"></i>
                                </div>
                                <div class="files form-field__files"></div>
                            </div>
                        </div>
                        <input class="form-field__result field-result" type="hidden" name="mark_files">
                    </div>
    
                </div>
                <input class="form-field__result field-result" type="hidden" name="comment_without_files">
            </div>
        </div>
    
    <div class="comment-modal__files">s</div>
    
</div>
