document.addEventListener('DOMContentLoaded', () => {

   let file_selects = document.querySelectorAll('.modal-file');

   file_selects.forEach(select => {
      select.addEventListener('click', () => {

         FileUploader.getInstance().show(select);
      });
   });

});

class FileUploader {
   static instance;

   form;
   file_input;
   mapping_1;
   mapping_2;
   id_structure_node;

   modal;
   overlay;

   drop_area;
   modal_body;
   modal_title;
   progress_bar;

   is_uploading = false;

   parent_field;
   parent_node;


   static getInstance () {

      if (!this.instance) {
         this.instance = new FileUploader();
      }

      return this.instance;
   }

   constructor () {
      this.initModalElements();
      this.initActions();
   }

   initModalElements () {
      this.form = document.getElementById('file_uploader');
      this.file_input = this.form.querySelector('[name="download_files[]"]');

      this.modal = document.querySelector('.modal.file-modal');
      this.overlay = document.querySelector('.file-overlay');

      this.drop_area = this.modal.querySelector('.file-modal__drop-area');
      this.modal_body = this.modal.querySelector('.file-modal__body');

      this.modal_title = this.modal.querySelector('.file-modal__title');
      this.progress_bar = this.modal.querySelector('.file-modal__progress_bar');
   }

   initActions () {
      this.clearDefaultDropEvents();
      this.handleDropArea();
      this.handleFileUploadButton();
      this.handleSubmitButton();
      this.handleDeleteButton();

      let close_button = this.modal.querySelector('.modal__close');
      close_button.addEventListener('click', this.closeModal.bind(this));

      this.overlay.addEventListener('click', this.closeModal.bind(this));
   }

   clearDefaultDropEvents () {
      let events = ['dragenter', 'dragover', 'dragleave', 'drop'];
      events.forEach(event_name => {
         document.addEventListener(event_name, event => {
            event.preventDefault();
            event.stopPropagation();
         });
      });
   }

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
         let files;

         if (
            this.file_input.hasAttribute('multiple')
            || event.dataTransfer.files.length === 1
         ) {
            this.clearModal();
            files = event.dataTransfer.files;
            this.file_input.files = files;
            this.addFilesToModal(files);
         } else { // Попытка загрузить несколько файлов, где разрешен только 1
            //TODO error
         }
      });
   }

   clearModal () {
      this.modal_body.innerHTML = '';
      this.file_input.value = '';
   }

   addFilesToModal (files) {
      Array.from(files).forEach(file_data => {
         this.modal_body.appendChild(this.createFileModalItem(file_data));
      });
   }

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
      file_size.innerHTML = GeFile.getFileSizeString(file_data);

      file_item.appendChild(file_icon);
      file_info.appendChild(file_name);
      file_info.appendChild(file_size);
      file_item.appendChild(file_info);

      return file_item;
   }

   handleFileUploadButton () {
      let upload_button = this.modal.querySelector('.file-modal__upload');

      upload_button.addEventListener('click', () => {
         if (!this.is_uploading) {
            // Вызываем событие для выбора файла у стандартного инпута
            this.file_input.click();
            this.clearModal();

         }
      });

      this.file_input.addEventListener('change', () => {
         this.addFilesToModal(this.file_input.files);
      });
   }

   handleSubmitButton () {
      let submit_button = this.modal.querySelector('.file-modal__submit');
      submit_button.addEventListener('click', () => {
         if (!this.is_uploading && FileChecker.IsReadyToUpload(this.file_input.files)) {
            this.sendFiles();
         } else {
            console.log('Неправильные файлы');
         }
      });
   }

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

            return this.putFilesToRow(uploaded_files);

         })
         .then(() => {

            this.is_uploading = false;
            this.closeModal();

         })
         .catch(exc => {

            this.is_uploading = false;
            console.error('Ошибка при загрузке файлов на сервер:\n' + exc);

         });

   }

   // Предназначен для анимации состояния загрузки файлов
   // Принимает параметры-------------------------------
   // event      ProgressEvent : объект, содержащий информацию о состоянии загрузки
   uploadProgressCallback (event) {
      let download_percent = Math.round(100 * event.loaded / event.total);
      this.modal_title.innerHTML = `Загрузка ${download_percent}%`;
      this.progress_bar.style.width = download_percent + '%';
   }

   // Предназначен для добавления файлов в родительское поле
   // Принимает параметры-------------------------------
   // files         Array[Object] : массив с файлами
   async putFilesToRow (files) {
      let parent_select = this.parent_field.querySelector('.field-select');
      if (parent_select) {
         parent_select.classList.add('filled');
      }

      let files_body;
      // Если блок с документацией
      if (this.parent_node) {
         files_body = this.parent_node.querySelector('.files');
      } else {
         files_body = this.parent_field.querySelector('.files');
      }

      files_body.classList.add('filled');

      for (let file of files) {
         let actions = [GeFile.sign, GeFile.unload, GeFile.delete];
         let file_item = GeFile.createElement(file, files_body, actions);

         await this.putFile(file_item);
         changeParentCardMaxHeight(this.parent_field);
      }

      return 1;
   }

   putFile (file_item) {

      API.checkFile(file_item.dataset.id, this.mapping_1, this.mapping_2)
         .then(check_response => {
            return API.internalSignatureVerify(check_response.fs_name, this.mapping_1, this.mapping_2);
         })
         .then(validate_results => {

            if (validate_results) {

               file_item.dataset.validate_results = JSON.stringify(validate_results);
               file_item.dataset.is_internal = 'true';
               SignHandler.validateFileField(file_item);

            }

         })
         .catch(exc => {
            console.error('Ошибка при проверке подписи файла:\n' + exc);
         });
   }

   closeModal () {
      if (!this.is_uploading) {

         this.modal.classList.remove('active');
         this.overlay.classList.remove('active');
         this.parent_node = null;
         this.clearModal();
         enableScroll();
      }
   }

   putFileData (select) {
      this.parent_node = select.closest('[data-id_structure_node]');
      this.parent_field = select.closest('[data-mapping_level_1]');

      this.mapping_1 = this.parent_field.dataset.mapping_level_1;
      this.mapping_2 = this.parent_field.dataset.mapping_level_2;

      // Если блок с документацией
      if (this.parent_node) {
         this.id_structure_node = this.parent_node.dataset.id_structure_node;
      }

      if (this.parent_field.dataset.multiple !== 'false') {
         this.file_input.setAttribute('multiple', '');
      } else {
         this.file_input.removeAttribute('multiple');
      }

   }

   show (select) {
      this.putFileData(select);
      this.clearModalTitle();
      this.modal.classList.add('active');
      this.overlay.classList.add('active');
      disableScroll();
   }

   clearModalTitle () {
      this.progress_bar.style.transition = '0s';
      this.modal_title.innerHTML = 'Выберите или перетащите файлы';
      this.progress_bar.style.width = '0';
   }

   handleDeleteButton () {
      let delete_icon = this.modal.querySelector('.file-modal__delete');
      delete_icon.addEventListener('click', () => {
         if (!this.is_uploading) {
            this.clearModal();
         }
      });
   }

}





















