document.addEventListener('DOMContentLoaded', () => {
   let form = document.getElementById('file_uploader');
   let file_input = form.querySelector('[name="download_files[]"]');
   let mapping_input_1 = form.querySelector('[name="mapping_level_1"]');
   let mapping_input_2 = form.querySelector('[name="mapping_level_2"]');

   let file_selects = document.querySelectorAll('.modal-file');
   let file_modal = document.querySelector('.modal.file-modal');
   let overlay = document.querySelector('.file-overlay');

   let drop_area = file_modal.querySelector('.file-modal__drop-area');
   let modal_body = file_modal.querySelector('.file-modal__body');

   let modal_title = file_modal.querySelector('.file-modal__title');
   let progress_bar = file_modal.querySelector('.file-modal__progress_bar');

   let is_uploading = false;

   let parent_row;

   let submit_button = file_modal.querySelector('.file-modal__submit');
   submit_button.addEventListener('click', () => {
      if (!is_uploading) {
         sendFiles();
      }
   });

   clearDefaultDropEvents();

   file_selects.forEach(select => {
      select.addEventListener('click', () => {
         showFileModal(select);
      });
   });

   overlay.addEventListener('click', () => {
      if (!is_uploading) {
         closeFileModal();
      }
   });

   handleDropArea();

   handleFileUploadButton();

   let delete_icon = file_modal.querySelector('.file-modal__delete');
   delete_icon.addEventListener('click', () => {
      if (!is_uploading) {
         clearFileModal();
      }
   });

   let close_button = file_modal.querySelector('.modal__close');
   close_button.addEventListener('click', () => {
      if (!is_uploading) {
         closeFileModal();
      }
   });

   // functions========================================

   function sendFiles() {
      let request_urn = '/home/API_file_uploader';

      progress_bar.style.transition = '.15s';
      is_uploading = true;


      XHR('post', request_urn, new FormData(form), null, 'json', null, uploadProgressCallback)
         .then(response => {

            is_uploading = false;

            switch (response.result) {
               case 16:
                  putFilesToRow(response.uploaded_files);
                  FileNeeds.putFilesToSave();
                  closeFileModal();
                  break;
               default:
                  console.log(response);
            }


         })
         .catch(error => {

            is_uploading = false;

            // p.s. все сообщения об ошибках везде делаем однотипными
            console.error('XHR error: ', error);
            // Ошибка XHR запроса. Обратитесь к администратору
         });
   }

   function uploadProgressCallback(event){
      let download_percent = Math.round(100 * event.loaded / event.total);
      modal_title.innerHTML = `Загрузка ${download_percent}%`;
      progress_bar.style.width = download_percent + '%';
   }

   function putFilesToRow(files) {
      let parent_select = parent_row.querySelector('.field-select');
      if (parent_select) {
         parent_select.classList.add('filled');
      }

      let files_body = parent_row.querySelector('.files');
      files_body.classList.add('filled');

      files.forEach(file => {
         addFileElement(file, files_body)
      });

      changeParentCardMaxHeight(parent_row);
   }

   function addFileElement(file, files_body) {
      let file_item = document.createElement('DIV');
      file_item.classList.add('files__item');
      file_item.dataset.id = file.id;
      files_body.appendChild(file_item);

      addFileInfo(file_item, file);
      addFileActions(file_item);

      return file_item;
   }

   function addFileInfo(file_item, file) {
      let file_info = document.createElement('DIV');
      file_info.classList.add('files__info');
      file_item.appendChild(file_info);

      let file_icon = document.createElement('I');
      file_icon.classList.add('files__icon', 'fas', getFileIconClass(file.name));
      file_info.appendChild(file_icon);

      let file_name = document.createElement('DIV');
      file_name.classList.add('files__name');
      file_name.innerHTML = file.name;
      file_info.appendChild(file_name);
   }

   /*function putFilesToSave() {
      let files = parent_row.querySelectorAll('.files__item');
      files.forEach(file => {
         let id_file = file.dataset.id;

         FileNeeds.putFileToSave(id_file, mapping_input_1.value, mapping_input_2.value);
      });

   }*/

   function showFileModal(select) {
      clearModalTitle();
      file_modal.classList.add('active');
      overlay.classList.add('active');
      addFieldData(select);
   }

   function addFieldData(select) {
      parent_row = select.closest('[data-mapping_level_1]');

      mapping_input_1.value = parent_row.dataset.mapping_level_1;
      mapping_input_2.value = parent_row.dataset.mapping_level_2;

      if (parent_row.dataset.multiple === 'true') {
         file_input.setAttribute('multiple', '');
      } else {
         file_input.removeAttribute('multiple');
      }
   }

   function clearModalTitle() {
      progress_bar.style.transition = '0s';
      modal_title.innerHTML = 'Выберите или перетащите файлы';
      progress_bar.style.width = '0';
   }

   function closeFileModal() {
      file_modal.classList.remove('active')
      overlay.classList.remove('active');
      clearFileModal();
   }

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
      })

      drop_area.addEventListener('drop', event => {
         let files;
         clearFileModal();

         if (file_input.hasAttribute('multiple') || event.dataTransfer.files.length === 1) {
            files = event.dataTransfer.files;
            file_input.files = files;
            addFilesToModal(files);
         } else {
            //TODO error
         }


      });
   }

   function addFilesToModal(files) {
      Array.from(files).forEach(file => {
         modal_body.appendChild(createFileModalItem(file));
      });
   }

   function clearFileModal() {
      modal_body.innerHTML = '';
      file_input.value = '';
   }

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

   function handleFileUploadButton() {
      let upload_button = file_modal.querySelector('.file-modal__upload');

      upload_button.addEventListener('click', () => {
         if (!is_uploading) {
            clearFileModal();
            file_input.click();
         }
      });

      file_input.addEventListener('change', () => {
         addFilesToModal(file_input.files);
      });
   }

  /* function addUploadedFiles() {
      Array.from(file_input.files).forEach(file => {
         createFileElement(file);
      });
   }*/


   //--functions========================================




   //=================================================
   // этот файл можешь привести к форматированию кода по своему типу

   // let form = document.querySelector('#file_uploader');


   // файл чекер
   /*let form_check = document.querySelector('#form_check');

   form_check.addEventListener('submit', event => {

       event.preventDefault();

       XHR('post', '/API_file_checker', new FormData(form_check), null, 'json')
           .then(response => {

               console.log(response)

           })
           .catch(error => {
               console.error('XHR error: ', error);
           });
   });*/

   // когда идет загрузка файла на серер нужно запрещать клик по кнопке "загрузить файл"
   /*form.addEventListener('submit', event => {

      event.preventDefault();

      // тут делаешь все требуемые проверки, и если не удовлетворяет - сообщение и выход из функции
      // а еще лучше проверки делать в момент попадания файлов в input type="file", и нажатие на отправку
      // формы запрещать, если проверки не пройдены

      XHR('post', request_urn, new FormData(form), null, 'json', null, uploadProgressCallback)
         .then(response => {
            console.log(response);
         })
         .catch(error => {

            // p.s. все сообщения об ошибках везде делаем однотипными
            console.error('XHR error: ', error);
            // Ошибка XHR запроса. Обратитесь к администратору
         });
   });*/

});


function clearDefaultDropEvents() {
   let events = ['dragenter', 'dragover', 'dragleave', 'drop'];
   events.forEach(event_name => {
      document.addEventListener(event_name, event => {
         event.preventDefault();
         event.stopPropagation();
      });
   });
}










