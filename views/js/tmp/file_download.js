document.addEventListener('DOMContentLoaded', () => {

   let form = document.getElementById('file_uploader');
   let file_input = form.querySelector('[name="download_files[]"]');
   let mapping_input_1 = form.querySelector('[name="mapping_level_1"]');
   let mapping_input_2 = form.querySelector('[name="mapping_level_2"]');
   let id_structure_node_input = form.querySelector('[name="id_structure_node"]');

   let file_selects = document.querySelectorAll('.modal-file');
   let file_modal = document.querySelector('.modal.file-modal');
   let overlay = document.querySelector('.file-overlay');

   let drop_area = file_modal.querySelector('.file-modal__drop-area');
   let modal_body = file_modal.querySelector('.file-modal__body');

   let modal_title = file_modal.querySelector('.file-modal__title');
   let progress_bar = file_modal.querySelector('.file-modal__progress_bar');

   let is_uploading = false;

   let parent_field;
   let parent_node;

   clearDefaultDropEvents();

   file_selects.forEach(select => {
      select.addEventListener('click', () => {
         showFileModal(select);
      });
   });

   handleDropArea();

   handleFileUploadButton();

   let submit_button = file_modal.querySelector('.file-modal__submit');
   submit_button.addEventListener('click', () => {
      if (!is_uploading && FileChecker.IsReadyToUpload(file_input.files)) {
         sendFiles();
      } else {
         console.log('Неправильные файлы');
      }
   });

   let delete_icon = file_modal.querySelector('.file-modal__delete');
   delete_icon.addEventListener('click', () => {
      if (!is_uploading) {
         clearFileModal();
      }
   });

   let close_button = file_modal.querySelector('.modal__close');
   close_button.addEventListener('click', closeFileModal);

   overlay.addEventListener('click', closeFileModal);

   // functions========================================

   // Предназначен для удаления стандартных событий перетаскивания
   function clearDefaultDropEvents() {
      let events = ['dragenter', 'dragover', 'dragleave', 'drop'];
      events.forEach(event_name => {
         document.addEventListener(event_name, event => {
            event.preventDefault();
            event.stopPropagation();
         });
      });
   }

   // Предназначен для отображения модального окна для загрузки файлов
   // Принимает параметры-------------------------------
   // select         Element : родительское поле
   function showFileModal(select) {
      clearModalTitle();
      disableScroll();
      file_modal.classList.add('active');
      overlay.classList.add('active');
      addFieldData(select);
   }

   // Предназначен для очистки окна загрузки
   function clearModalTitle() {
      progress_bar.style.transition = '0s';
      modal_title.innerHTML = 'Выберите или перетащите файлы';
      progress_bar.style.width = '0';
   }

   // Предназначен для получения данных о родительском поле
   // Принимает параметры-------------------------------
   // select         Element : родительское поле
   function addFieldData(select) {
      parent_node = select.closest('[data-id_structure_node]');
      parent_field = select.closest('[data-mapping_level_1]');

      mapping_input_1.value = parent_field.dataset.mapping_level_1;
      mapping_input_2.value = parent_field.dataset.mapping_level_2;

      // Если блок с документацией
      if (parent_node) {
         id_structure_node_input.value = parent_node.dataset.id_structure_node;
      }

      if (parent_field.dataset.multiple !== 'false') {
         file_input.setAttribute('multiple', '');
      } else {
         file_input.removeAttribute('multiple');
      }
   }

   // Предназначен для добавления событий переноса файлов в окно загрузки
   function handleDropArea() {
      ;['dragenter', 'dragover'].forEach(eventName => {
         drop_area.addEventListener(eventName, () => {
            drop_area.classList.add('active');
         });
      })

      ;['dragleave', 'drop'].forEach(eventName => {
         drop_area.addEventListener(eventName, () => {
            drop_area.classList.remove('active');
         });
      });

      drop_area.addEventListener('drop', event => {
         let files;

         if (file_input.hasAttribute('multiple') || event.dataTransfer.files.length === 1) {
            clearFileModal();
            files = event.dataTransfer.files;
            file_input.files = files;
            addFilesToModal(files);
         } else { // Попытка загрузить несколько файлов, где разрешен только 1
            //TODO error
         }
      });
   }

   // Предназначен для удаления файлов из она загрузки
   function clearFileModal() {
      modal_body.innerHTML = '';
      file_input.value = '';
   }

   // Предназначен для добавления файлов в окно загрузки
   // Принимает параметры-------------------------------
   // files       FileList : файлы для добавления
   function addFilesToModal(files) {
      Array.from(files).forEach(file => {
         modal_body.appendChild(createFileModalItem(file));
      });
   }

   // Предназначен для создания элемента файла в окне загрузки
   // Принимает параметры-------------------------------
   // file        File : загруженный файл
   function createFileModalItem(file) {
      let file_item = document.createElement('DIV');
      file_item.classList.add('file-modal__item');

      let file_icon = document.createElement('I');
      file_icon.classList.add('file-modal__icon', 'fas', getFileIconClass(file.name));

      let file_info = document.createElement('DIV');
      file_info.classList.add('file-modal__info');

      let file_name = document.createElement('DIV');
      file_name.classList.add('file-modal__name');
      file_name.innerHTML = file.name;

      let file_size = document.createElement('DIV');
      file_size.classList.add('file-modal__size');
      file_size.innerHTML = getFileSizeString(file);

      file_item.appendChild(file_icon);
      file_info.appendChild(file_name);
      file_info.appendChild(file_size);
      file_item.appendChild(file_info);

      return file_item;
   }

   // Предназначен для получения размера файла в виде строки
   // Принимает параметры-------------------------------
   // file         File : загруженный файл
   // Возвращает параметры------------------------------
   // size       string : размер файла с единицами измерения
   function getFileSizeString(file) {
      let size;
      let kb = file.size / 1024;

      if (kb > 1024) {
         size = Math.round(kb / 1024) + ' МБ'
      } else {
         size = Math.round(kb) + ' КБ'
      }

      return size;
   }

   // Предназначен для получения класса иконки файла в зависимости от его типа
   // Принимает параметры-------------------------------
   // file_name         string : имя файла
   // Возвращает параметры------------------------------
   // icon_class        string : класс иконки
   function getFileIconClass(file_name) {
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

   // Предназначен для обработки кнопки для загрузки файлов
   function handleFileUploadButton() {
      let upload_button = file_modal.querySelector('.file-modal__upload');

      upload_button.addEventListener('click', () => {
         if (!is_uploading) {
            // Вызываем событие для выбора файла у стандартного инпута
            file_input.click();
            clearFileModal();

         }
      });

      file_input.addEventListener('change', () => {
         addFilesToModal(file_input.files);
      });
   }

   // Предназначен для закрытия окна для загрузки файлов
   function closeFileModal() {
      if (!is_uploading) {
         file_modal.classList.remove('active');
         overlay.classList.remove('active');
         parent_node = null;
         clearFileModal();
         enableScroll();
      }
   }

   // Предназначен для загрузки файлов на сервер
   function sendFiles() {

      progress_bar.style.transition = '.15s';
      is_uploading = true;

      let files = Array.from(file_input.files);

      uploadFiles(files, mapping_input_1.value, mapping_input_2.value, uploadProgressCallback)
         .then(uploaded_files => {

            return putFilesToRow(uploaded_files);

         })
         .then(() => {

            is_uploading = false;
            closeFileModal();

         })
         .catch(exc => {

            is_uploading = false;
            console.error('Ошибка при загрузке файлов на сервер:\n' + exc);

         });

   }

   // Предназначен для анимации состояния загрузки файлов
   // Принимает параметры-------------------------------
   // event      ProgressEvent : объект, содержащий информацию о состоянии загрузки
   function uploadProgressCallback(event){
      let download_percent = Math.round(100 * event.loaded / event.total);
      modal_title.innerHTML = `Загрузка ${download_percent}%`;
      progress_bar.style.width = download_percent + '%';
   }


   // Предназначен для добавления файлов в родительское поле
   // Принимает параметры-------------------------------
   // files         Array[Object] : массив с файлами
   async function putFilesToRow(files) {
      let parent_select = parent_field.querySelector('.field-select');
      if (parent_select) {
         parent_select.classList.add('filled');
      }

      let files_body;
      // Если блок с документацией
      if (parent_node) {
         files_body = parent_node.querySelector('.files');
      } else {
         files_body = parent_field.querySelector('.files');
      }

      files_body.classList.add('filled');


      for (let file of files) {
         let file_item = createFileElement(file, files_body);

         await putFile(file, file_item);
         changeParentCardMaxHeight(parent_field);

      }

      return 1;
   }

   function putFile(file, file_item) {

      checkFile(file_item.dataset.id, mapping_input_1.value, mapping_input_2.value)
         .then(check_response => {
            return internalSignatureVerify(check_response.fs_name, mapping_input_1.value, mapping_input_2.value);
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


   // Предназначен для создания элемента файла
   // Принимает параметры-------------------------------
   // file             Object : объект, с информацией о файле
   // Возвращает параметры------------------------------
   // files_body       Element : блок, в который добавляется файл
   function createFileElement(file, files_body) {
      let file_item = document.createElement('DIV');
      file_item.classList.add('files__item');
      file_item.dataset.id = file.id;
      files_body.appendChild(file_item);

      addFileInfo(file_item, file);
      addFileActions(file_item);

      return file_item;
   }

   // Предназначен для добавления информации о файле в его блок
   // Принимает параметры-------------------------------
   // file_item     Element : блок с файлом
   // file           Object : объект, с информацией о файле
   function addFileInfo(file_item, file) {
      let file_info = document.createElement('DIV');
      file_info.classList.add('files__info');
      file_item.appendChild(file_info);

      let file_icon = document.createElement('I');
      file_icon.classList.add('files__icon', 'fas', getFileIconClass(file.name));
      file_info.appendChild(file_icon);

      let file_name = document.createElement('SPAN');
      file_name.classList.add('files__name');
      file_name.innerHTML = file.name;
      file_info.appendChild(file_name);
   }

});













