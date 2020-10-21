<div class="overlay sign-overlay"></div>
<div class="modal sign-modal" data-plugin_loaded="false">
    
    <div class="sign-modal__header" data-active="false">
        <div class="sign-modal__row">
            <span class="sign-modal__plugin-label">Версия плагина: </span>
            <span id="plugin_version" class="sign-modal__text"></span>
        </div>
        <div class="sign-modal__row">
            <span class="sign-modal__plugin-label">Версия криптопровайдера: </span>
            <span id="csp_version" class="sign-modal__text"></span>
        </div>
    </div>
    
    <div class="sign-modal__file-body">
        <div class="sign-modal__file-info">
            <div class="sign-modal__file"></div>
        </div>
        
        <div class="sign-modal__buttons">
            <div id="signature_delete" class="sign-modal__btn sign-modal__upload" data-active="false">
                <span class="sign-modal__button-name">Удалить подпись</span>
                <i class="sign-modal__icon-button fas fa-eraser"></i>
            </div>
            <div id="sign_upload" class="sign-modal__btn sign-modal__upload" data-active="false">
                <span class="sign-modal__button-name">Загрузить открепленную подпись</span>
                <i class="sign-modal__icon-button fas fa-file-upload"></i>
            </div>
            <div id="sign_create" class="sign-modal__btn sign-modal__sign" data-active="false">
                <span class="sign-modal__button-name">Создать открепленную подпись</span>
                <i class="sign-modal__icon-button fas fa-pen-alt"></i>
            </div>
            <input id="external_sign" type="file" name="download_files[]" hidden/>
        </div>
    
    </div>
    
    <div class="sign-modal__validate" data-active="false"></div>
    
    <div class="sign-modal__empty"></div>
    
    <div class="sign-modal__certs" data-active="false">
        
        <div id="cert_list" class="sign-modal__cert-list">
            <div class="sign-modal__title">Выберите сертификат:</div>
        </div>
        
        <div class="sign-modal__cert-info" data-active="false">
            <div class="sign-modal__cert-row">
                <span class="sign-modal__label">Данные о выбранном сертификате:</span>
            </div>
            <div class="sign-modal__cert-row">
                <span class="sign-modal__label">Владелец: </span>
                <span id="subject_name" class="sign-modal__text"></span>
            </div>
            <div class="sign-modal__cert-row">
                <span class="sign-modal__label">Издатель: </span>
                <span id="issuer_name" class="sign-modal__text"></span>
            </div>
            <div class="sign-modal__cert-row">
                <span class="sign-modal__label">Дата выдачи: </span>
                <span id="valid_from_date" class="sign-modal__text"></span>
            </div>
            <div class="sign-modal__cert-row">
                <span class="sign-modal__label">Срок действия: </span>
                <span id="valid_to_date" class="sign-modal__text"></span>
            </div>
            <div class="sign-modal__cert-row">
                <span class="sign-modal__label">Статус: </span>
                <span id="cert_message" class="sign-modal__text"></span>
            </div>
        </div>
    
    </div>
    
    <div class="sign-modal__actions" data-active="false">
        <div id="signature_button" class="file-modal__button sign-modal__button">Подписать</div>
        <div id="sign_cancel" class="file-modal__button sign-modal__button">Отмена</div>
    </div>

</div>