<div class="file-overlay"></div>
<div class="modal file-modal">
    <i class="modal__close active fas fa-times"></i>
    
    <div class="file-modal__header">
        <div class="file-modal__title">Выберите или перетащите файлы</div>
        <div class="file-modal__progress_bar"></div>
    </div>
    
    <div class="file-modal__drop-area">
        <div class="file-modal__body"></div>
    </div>
    
    <div class="file-modal__actions">
        <div class="file-modal__button file-modal__upload">Выбрать</div>
        <div class="file-modal__button file-modal__submit">Загрузить</div>
        <div class="file-modal__button file-modal__delete">Удалить файлы</div>
    </div>
    
    <!--todo убрать-->
    <form id="file_uploader" action="" method="POST" enctype="multipart/form-data" >
        <input type="file" name="download_files[]" hidden/>
    </form>
</div>