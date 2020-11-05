<div id="file_overlay" class="overlay file-overlay"></div>
<div id="file_modal" class="modal file-modal">
    <i class="modal__close fas fa-times"></i>

    <div class="file-modal__header">
        <div id="file_uploader_title" class="file-modal__title">Выберите или перетащите файлы</div>
        <div id="file_uploader_progress_bar" class="file-modal__progress_bar"></div>
    </div>

    <div id="files_drop_area" class="file-modal__drop-area">
        <div id="file_uploader_body" class="file-modal__body"></div>
    </div>

    <div class="file-modal__actions">
        <div class="file-modal__button file-modal__upload">Выбрать</div>
        <div class="file-modal__button file-modal__submit">Загрузить</div>
        <div class="file-modal__button file-modal__delete">Удалить файлы</div>
    </div>

    <input id="file_uploader_input" type="file" name="download_files[]" hidden/>
</div>