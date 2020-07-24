// let file_modal;

// let forms_data;

document.addEventListener('DOMContentLoaded', () => {
   let results = new Map();
   let form = document.getElementById('file_uploader');
   let file_input = form.querySelector('[name="download_files[]"]');

   let file_selects = document.querySelectorAll('.modal-file');
   let file_modal = document.querySelector('.modal.file-modal');
   let overlay = document.querySelector('.file-overlay');

   let drop_area = file_modal.querySelector('.file-modal__drop-area');
   let modal_body = file_modal.querySelector('.file-modal__body');
   let delete_icon = file_modal.querySelector('.file-modal__delete-icon');
   let progress_bar = file_modal.querySelector('.file-modal__progress_bar');
   let current_field_name;


   let submit_button = file_modal.querySelector('.file-modal__submit');
   submit_button.addEventListener('click', () => {
      sendFiles();
   });

   clearDefaultDropEvents();

   file_selects.forEach(select => {
      select.addEventListener('click', () => {
         showFileModal(select);
      });
   });

   overlay.addEventListener('click', () => {
      closeFileModal();
   });

   handleDropArea();

   handleFileUploadButton();

   delete_icon.addEventListener('click', () => {
      clearFileModal();
   });


   // functions========================================

   function sendFiles() {
      let request_urn = '/API_file_uploader';

      XHR('post', request_urn, new FormData(form), null, 'json', null, uploadProgressCallback)
         .then(response => {
            console.log(response);
         })
         .catch(error => {

            // p.s. все сообщения об ошибках везде делаем однотипными
            console.error('XHR error: ', error);
            // Ошибка XHR запроса. Обратитесь к администратору
         });
   }

   function uploadProgressCallback(event){
      let download_percent = Math.round(100 * event.loaded / event.total);
      progress_bar.style.width = download_percent + '%';
   }

   function showFileModal(select) {
      file_modal.classList.add('active');
      overlay.classList.add('active');

      let parent_row = select.closest('.body-card__row');
      current_field_name = parent_row.dataset.row_name;

      // if (results.has(current_field_name)) {
      //    file_input.files = results.get(current_field_name);
      //    addUploadedFiles();
      // }
   }

   function closeFileModal() {
      file_modal.classList.remove('active')
      overlay.classList.remove('active');
      modal_body.innerHTML = '';

      results.set(current_field_name, file_input.files);
   }

   function handleDropArea() {
      ;['dragenter', 'dragover'].forEach(eventName => {
         drop_area.addEventListener(eventName, event => {
            drop_area.classList.add('active');
         });
      })

      ;['dragleave', 'drop'].forEach(eventName => {
         drop_area.addEventListener(eventName, event => {
            drop_area.classList.remove('active');
         });
      })

      drop_area.addEventListener('drop', event => {
         clearFileModal();
         let files = event.dataTransfer.files;
         file_input.files = files;
         addFilesToModal(files);
      });
   }

   function addFilesToModal(files) {
      Array.from(files).forEach(file => {
         createFileElement(file);
      });
   }

   function clearFileModal() {
      modal_body.innerHTML = '';
      file_input.value = '';
   }

   function createFileElement(file) {
      let file_item = document.createElement('DIV');
      file_item.classList.add('file-modal__item');
      file_item.innerHTML = file.name;

      modal_body.appendChild(file_item);
   }

   function handleFileUploadButton() {
      let upload_button = file_modal.querySelector('.file-modal__icon');

      upload_button.addEventListener('click', () => {
         clearFileModal();
         file_input.click();
      });

      file_input.addEventListener('change', () => {
         addFilesToModal(file_input.files)
      });
   }

   function addUploadedFiles() {
      Array.from(file_input.files).forEach(file => {
         createFileElement(file);
      });
   }


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














