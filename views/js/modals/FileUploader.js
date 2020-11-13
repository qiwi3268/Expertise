/**
 * @typedef UploadedFile
 * @type {object}
 * @property {number} id - id файла в БД
 * @property {string} name - наименование файла
 * @property {string} hash - хэш файла
 * @property {string} human_file_size - строка с размером для отображения на странице
 */

document.addEventListener('DOMContentLoaded', () => {

   let file_selects = document.querySelectorAll('[data-modal_select="file"]');

   file_selects.forEach(select => {
      select.addEventListener('click', () => {
         FileUploader.getInstance().open(select);
      });
   });


   console.log('URI');
   console.log(PageUtils.getURI());

});

/**
 * Представляет собой файловый загрузчик
 */
class FileUploader {

   /**
    * Объект модуля загрузки файлов
    *
    * @type {FileUploader}
    */
   static get instance() {
      return this._instance;
   }

   static set instance(instance) {
      this._instance = instance;
   }

   /**
    * Инпут, в который загружаются файлы
    *
    * @type {HTMLInputElement}
    */
   file_input;

   /**
    * Маппинг 1-го уровня файлового поля
    *
    * @type {number}
    */
   mapping_1;

   /**
    * Маппинг 2-го уровня файлового поля
    *
    * @type {number}
    */
   mapping_2;

   /**
    * id раздела документации файлового поля
    *
    * @type {number}
    */
   id_structure_node;

   /**
    * Модальное окно файлового загрузчика
    *
    * @type {HTMLElement}
    */
   modal;

   /**
    * Фон модального окна
    *
    * @type {HTMLElement}
    */
   overlay;

   /**
    * Блок, в который можно перенести файлы
    *
    * @type {HTMLElement}
    */
   drop_area;

   /**
    * Блок являющийся контейнером для файлов
    *
    * @type {HTMLElement}
    */
   modal_body;

   /**
    * Блок с заголовком модального окна
    *
    * @type {HTMLElement}
    */
   modal_title;

   /**
    * Индикатор процента загрузки файлов
    *
    * @type {HTMLElement}
    */
   progress_bar;

   /**
    * Флаг, указывающий происходит ли загрузка файлов
    *
    * @type {boolean}
    */
   is_uploading = false;

   /**
    * Флаг, указывающий открыт ли файловый загрузчик
    *
    * @type {boolean}
    */
   is_opened = false;

   /**
    * Файловое поле, для которого открывается файловый загрузчик
    */
   parent_field;

   /**
    * Раздел документации, для которого открывается файловый загрузчик
    */
   parent_node;

   /**
    * Возвращает единственный объект файлового загрузчика
    * @return {FileUploader}
    */
   static getInstance () {

      if (!this.instance) {
         this.instance = new FileUploader();
      }

      return this.instance;
   }

   /**
    * Создает объект файлового загрузчика
    */
   constructor () {
      this.initModalElements();
      this.initActions();
   }

   /**
    * Инициализирует элементы модального окна файлового загрузчика
    */
   initModalElements () {
      this.file_input = document.getElementById('file_uploader_input');

      this.modal = document.getElementById('file_modal');
      this.overlay = document.getElementById('file_overlay');

      this.drop_area = document.getElementById('files_drop_area');
      this.modal_body = document.getElementById('file_uploader_body');

      this.modal_title = document.getElementById('file_uploader_title');
      this.progress_bar = document.getElementById('file_uploader_progress_bar');
   }

   /**
    * Обрабатывает действия файлового загрузчика
    */
   initActions () {
      clearDefaultDropEvents();
      this.handleDropArea();
      this.handleFileUploadButton();
      this.handleSubmitButton();
      this.handleDeleteButton();

      let close_button = this.modal.querySelector('.modal__close');
      close_button.addEventListener('click', this.closeModal.bind(this));

      this.overlay.addEventListener('click', this.closeModal.bind(this));
   }

   /**
    * Обрабатывает перенос файлов в файловый загрузчик
    */
   handleDropArea () {
      ;['dragenter', 'dragover'].forEach(eventName => {
         this.drop_area.addEventListener(eventName, () => {
            this.drop_area.classList.add('active');
         });
      })

      ;['dragleave', 'drop'].forEach(eventName => {
         this.drop_area.addEventListener(eventName, () => {
            this.drop_area.classList.remove('active');
         });
      });

      this.drop_area.addEventListener('drop', event => {

         if (
            this.file_input.hasAttribute('multiple')
            || event.dataTransfer.files.length === 1
         ) {
            this.clearModal();
            let files = event.dataTransfer.files;
            this.file_input.files = files;
            this.addFilesToModal(files);
         } else {
            ErrorModal.open('Ошибка при загрузке файлов', 'Загрузить можно только 1 файл');
         }

      });
   }

   /**
    * Очищает модальное окно файлового загрузчика
    */
   clearModal () {
      this.modal_body.innerHTML = '';
      this.file_input.value = '';
   }

   /**
    * Добавляет переброшенные или выбранные файлы в модальное окно файлового загрузчика
    *
    * @param {FileList} files - выбранные или переброшенные файлы
    */
   addFilesToModal (files) {

      for (let file_data of Array.from(files)) {

         this.modal_body.appendChild(this.createFileModalItem(file_data));

   /*      if (!FileChecker.checkExtension(file_data.name)) {

            ErrorModal.open(
               'Ошибка при загрузке файла',
               'Загружен файл в неверном формате (доступные форматы: pdf, docx, xlsx, sig)'
            );
            this.closeModal();
            break;

         } else if (!FileChecker.checkSize(file_data.size)) {

            ErrorModal.open(
               'Ошибка при загрузке файла',
               'Загружен файл c размером больше 80 МБ'
            );
            this.closeModal();
            break;

         } else {
         }
*/
      }

   }

   /**
    * Создает файловый элемент для отображения в модальном окне файлового загрузчика
    *
    * @param {File} file_data
    * @return {HTMLElement} файловый элемент
    */
   createFileModalItem (file_data) {
      let file_item = document.createElement('DIV');
      file_item.classList.add('file-modal__item');

      let file_icon = document.createElement('I');
      file_icon.classList.add('file-modal__icon', 'fas', GeFile.getFileIconClass(file_data.name));

      let file_info = document.createElement('DIV');
      file_info.classList.add('file-modal__info');

      let file_name = document.createElement('DIV');
      file_name.classList.add('file-modal__name');
      file_name.innerHTML = file_data.name;

      let file_size = document.createElement('DIV');
      file_size.classList.add('file-modal__size');
      file_size.innerHTML = GeFile.getFileSizeString(file_data.size);

      file_item.appendChild(file_icon);
      file_info.appendChild(file_name);
      file_info.appendChild(file_size);
      file_item.appendChild(file_info);

      return file_item;
   }

   /**
    * Обрабатывает кнопку выбора файла
    */
   handleFileUploadButton () {
      let upload_button = this.modal.querySelector('.file-modal__upload');

      upload_button.addEventListener('click', () => {
         if (!this.is_uploading && this.is_opened) {
            // Вызываем событие для выбора файла у стандартного инпута
            this.file_input.click();
            this.clearModal();
         }
      });

      this.file_input.addEventListener('change', () => {
         this.addFilesToModal(this.file_input.files);
      });
   }

   /**
    * Обрабатывает кнопку загрузки выбранных файлов на сервер
    */
   handleSubmitButton () {
      let submit_button = this.modal.querySelector('.file-modal__submit');
      submit_button.addEventListener('click', () => {

         if (this.file_input.files.length === 0) {
            ErrorModal.open('Ошибка при загрузке файлов', 'Не выбраны файлы для загрузки');
         } else if (!this.is_uploading) {
            this.sendFiles();
         }

      });
   }

   /**
    * Загружает файлы на сервер
    */
   sendFiles () {

      this.progress_bar.style.transition = '.15s';
      this.is_uploading = true;

      let files = Array.from(this.file_input.files);

      API.uploadFiles(
         files,
         this.mapping_1,
         this.mapping_2,
         this.id_structure_node,
         this.uploadProgressCallback.bind(this)
      )
         .then(uploaded_files => {

            console.log(uploaded_files);

            return this.putFilesToRow(uploaded_files);

         })
         .then(() => {

            this.is_uploading = false;
            this.closeModal();

         })
         .catch(exc => {

            this.is_uploading = false;

            this.closeModal();
            ErrorModal.open('Ошибка при загрузке файлов', exc);
         });

   }

   /**
    * Анимирует индикатор степени загрузки файлов
    *
    * @param {ProgressEvent} event - объект, содержащий информацию о состоянии загрузки
    */
   uploadProgressCallback (event) {
      let download_percent = Math.round(100 * event.loaded / event.total);
      this.modal_title.innerHTML = `Загрузка ${download_percent}%`;
      this.progress_bar.style.width = download_percent + '%';
   }

   /**
    * Добавляет загруженные файлы в файловое поле
    *
    * @param {UploadedFile[]} files
    */
   putFilesToRow (files) {
      this.parent_field.classList.add('filled');

      let files_body;
      // Если блок с документацией
      if (this.parent_node) {
         files_body = this.parent_node.querySelector('.files');
      } else {
         files_body = this.parent_field.querySelector('.files');
      }

      for (let file of files) {
         let ge_file = GeFile.createElement(file, files_body);
         ge_file.handleInternalSigns();
         resizeCard(this.parent_field);
      }

   }

   /**
    * Закрывает модальное окно файлового загрузчика
    */
   closeModal () {
      if (!this.is_uploading) {
         this.modal.classList.remove('active');
         this.overlay.classList.remove('active');
         this.parent_node = null;
         this.is_opened = false;
         this.clearModal();
         enableScroll();
      }
   }

   /**
    * Обрабатывает кнопку удаления выбранных файлов
    */
   handleDeleteButton () {
      let delete_icon = this.modal.querySelector('.file-modal__delete');
      delete_icon.addEventListener('click', () => {
         if (!this.is_uploading) {
            this.clearModal();
         }
      });
   }

   /**
    * Открывает файловый загрузчик
    *
    * @param {HTMLElement} select - файловое поле
    */
   open (select) {
      this.putFileData(select);
      this.clearModalTitle();
      this.modal.classList.add('active');
      this.overlay.classList.add('active');
      this.is_opened = true;
      disableScroll();
   }

   /**
    * Добавляет в файловый загрузчик данные о файловом поле
    *
    * @param {HTMLElement} select - файловое поле
    */
   putFileData (select) {
      this.parent_node = select.closest('[data-id_structure_node]');
      this.parent_field = select.closest('[data-mapping_level_1]');

      this.mapping_1 = parseInt(this.parent_field.dataset.mapping_level_1);
      this.mapping_2 = parseInt(this.parent_field.dataset.mapping_level_2);

      // Если блок с документацией
      if (this.parent_node) {
         this.id_structure_node = parseInt(this.parent_node.dataset.id_structure_node);
      }

      if (this.parent_field.dataset.multiple !== 'false') {
         this.file_input.setAttribute('multiple', '');
      } else {
         this.file_input.removeAttribute('multiple');
      }

   }

   /**
    * Очищает заголовок модального окна файлового загрузчика
    */
   clearModalTitle () {
      this.progress_bar.style.transition = '0s';
      this.modal_title.innerHTML = 'Выберите или перетащите файлы';
      this.progress_bar.style.width = '0';
   }

}





















