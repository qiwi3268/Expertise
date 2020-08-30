document.addEventListener('DOMContentLoaded', () => {

   let file_selects = document.querySelectorAll('.modal-file');

   file_selects.forEach(select => {
      select.addEventListener('click', () => {
         FileUploader.getInstance().show(select);
      });
   });

   FileUploader.clearDefaultDropEvents();

});

class FileUploader {
   static instance

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


   static getInstance() {

      if (!this.instance) {
         this.instance = new FileUploader();
      }

      return this.instance;
   }

   constructor() {
      this.form = document.getElementById('file_uploader');
      this.file_input = this.form.querySelector('[name="download_files[]"]');
      // this.mapping_1 = form.querySelector('[name="mapping_level_1"]');
      // this.mapping_2 = form.querySelector('[name="mapping_level_2"]');
      this.file_input = this.form.querySelector('[name="id_structure_node"]');

      this.modal = document.querySelector('.modal.file-modal');
      this.overlay = document.querySelector('.file-overlay');

      this.drop_area = this.modal.querySelector('.file-modal__drop-area');
      this.modal_body = this.modal.querySelector('.file-modal__body');

      this.modal_title = this.modal.querySelector('.file-modal__title');
      this.progress_bar = this.modal.querySelector('.file-modal__progress_bar');

      this.handleDropArea();
      this.handleFileUploadButton();
   }

   init(select) {
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

   show() {
      this.clearModalTitle();
      disableScroll();
      this.modal.classList.add('active');
      this.overlay.classList.add('active');
   }

   clearModalTitle() {
      this.progress_bar.style.transition = '0s';
      this.modal_title.innerHTML = 'Выберите или перетащите файлы';
      this.progress_bar.style.width = '0';
   }

   static clearDefaultDropEvents() {
      let events = ['dragenter', 'dragover', 'dragleave', 'drop'];
      events.forEach(event_name => {
         document.addEventListener(event_name, event => {
            event.preventDefault();
            event.stopPropagation();
         });
      });
   }

   handleDropArea() {
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
            this.clearFileModal();
            files = event.dataTransfer.files;
            this.file_input.files = files;
            this.addFilesToModal(files);
         } else { // Попытка загрузить несколько файлов, где разрешен только 1
            //TODO error
         }
      });
   }

   clearFileModal() {
      this.modal_body.innerHTML = '';
      this.file_input.value = '';
   }

   addFilesToModal(files) {
      Array.from(files).forEach(file => {
         this.modal_body.appendChild(this.createFileModalItem(file));
      });
   }

   createFileModalItem(file) {
      let file_item = document.createElement('DIV');
      file_item.classList.add('file-modal__item');

      let file_icon = document.createElement('I');
      file_icon.classList.add('file-modal__icon', 'fas', this.getFileIconClass(file.name));

      let file_info = document.createElement('DIV');
      file_info.classList.add('file-modal__info');

      let file_name = document.createElement('DIV');
      file_name.classList.add('file-modal__name');
      file_name.innerHTML = file.name;

      let file_size = document.createElement('DIV');
      file_size.classList.add('file-modal__size');
      file_size.innerHTML = this.getFileSizeString(file);

      file_item.appendChild(file_icon);
      file_info.appendChild(file_name);
      file_info.appendChild(file_size);
      file_item.appendChild(file_info);

      return file_item;
   }

   getFileSizeString(file) {
      let size;
      let kb = file.size / 1024;

      if (kb > 1024) {
         size = Math.round(kb / 1024) + ' МБ'
      } else {
         size = Math.round(kb) + ' КБ'
      }

      return size;
   }

   getFileIconClass(file_name) {
      let icon_class = 'fa-file-alt';

      if (file_name.includes('.pdf')) {
         icon_class = 'fa-file-pdf';
      } else if (file_name.includes('.docx')) {
         icon_class = 'fa-file-word';
      } else if (file_name.includes('.xlsx')) {
         icon_class = 'fa-file-excel';
      }

      return icon_class;
   }

   handleFileUploadButton() {
      let upload_button = this.modal.querySelector('.file-modal__upload');

      upload_button.addEventListener('click', () => {
         if (!this.is_uploading) {
            // Вызываем событие для выбора файла у стандартного инпута
            this.file_input.click();
            this.clearFileModal();

         }
      });

      this.file_input.addEventListener('change', () => {
         this.addFilesToModal(this.file_input.files);
      });
   }

   closeFileModal() {
      if (!this.is_uploading) {
         this.modal.classList.remove('active');
         this.overlay.classList.remove('active');
         this.parent_node = null;
         this.clearFileModal();
         enableScroll();
      }
   }

   sendFiles() {

      this.progress_bar.style.transition = '.15s';
      this.is_uploading = true;

      let files = Array.from(this.file_input.files);


      API.uploadFiles(files, this.mapping_1, this.mapping_2, this.id_structure_node, this.uploadProgressCallback)
         .then(uploaded_files => {

            return this.putFilesToRow(uploaded_files);

         })
         .then(() => {

            this.is_uploading = false;
            this.closeFileModal();

         })
         .catch(exc => {

            this.is_uploading = false;
            console.error('Ошибка при загрузке файлов на сервер:\n' + exc);

         });

   }

   // Предназначен для анимации состояния загрузки файлов
   // Принимает параметры-------------------------------
   // event      ProgressEvent : объект, содержащий информацию о состоянии загрузки
   uploadProgressCallback(event){
      let download_percent = Math.round(100 * event.loaded / event.total);
      this.modal_title.innerHTML = `Загрузка ${download_percent}%`;
      this.progress_bar.style.width = download_percent + '%';
   }

   // Предназначен для добавления файлов в родительское поле
   // Принимает параметры-------------------------------
   // files         Array[Object] : массив с файлами
   async putFilesToRow(files) {
      let parent_select = parent_field.querySelector('.field-select');
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
         let file_item = this.createFileElement(file, files_body);

         await this.putFile(file, file_item);
         changeParentCardMaxHeight(this.parent_field);

      }

      return 1;
   }

   putFile(file, file_item) {


      API.checkFile(file_item.dataset.id, mapping_input_1.value, mapping_input_2.value)
         .then(check_response => {
            return API.internalSignatureVerify(check_response.fs_name, mapping_input_1.value, mapping_input_2.value);
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

   createFileElement(file, files_body) {
      let file_item = document.createElement('DIV');
      file_item.classList.add('files__item');
      file_item.dataset.id = file.id;
      files_body.appendChild(file_item);

      addFileInfo(file_item, file);
      addFileActions(file_item);

      return file_item;
   }
}





















