// let file_modal;

// let forms_data;

document.addEventListener('DOMContentLoaded', () => {
   let form = document.querySelector('#file_uploader');
   let forms_data = new Map();
   let current_form_data;
   let file_number = 0;

   let file_selects = document.querySelectorAll('.modal-file');

   let file_modal = document.querySelector('.modal.file-modal');
   // file_modal = document.querySelector('.modal.file-modal');

   let overlay = document.querySelector('.file-overlay');

   let drop_area = file_modal.querySelector('.file-modal__drop-area');

   let file_input = document.getElementById('upload_id');

   let modal_body = file_modal.querySelector('.file-modal__body');

   let files_arr = [];

   clearDefaultDropEvents();

   file_selects.forEach(select => {
      select.addEventListener('click', () => {
         file_modal.classList.add('active');
         overlay.classList.add('active');

         let parent_row = select.closest('.body-card__row');
         let name = parent_row.dataset.row_name;

         if (forms_data.has(name)) {
            current_form_data = forms_data.get(name);
            /*let iterator = current_form_data.keys();
            let elem = iterator.next();
            while (!elem.done) {
               console.log(current_form_data.getAll(elem.value));
               elem = iterator.next();
            }*/
            // addUploadedFiles();
         } else {
            current_form_data = new FormData(form);
            forms_data.set(name, current_form_data);
         }



      });
   });

   overlay.addEventListener('click', () => {
      file_modal.classList.remove('active')
      overlay.classList.remove('active');
      modal_body.innerHTML = '';


      let json = JSON.stringify(files_arr);


      // console.log(JSON.stringify(files_arr));

      let arr = JSON.parse(json);

      console.log(arr);

      // current_form_data.append('files', files_arr);

      // console.log(current_form_data.get('files'));
      let counter = 0;

      let iterator = current_form_data.keys();
      let elem = iterator.next();
      while (!elem.done) {
         /*current_form_data.getAll(elem.value).forEach(file => {
            console.log(counter++);

            // if (file.name) {
            //    createFileElement(file);
            // }
         });*/
         elem = iterator.next();
      }

   });


   handleDropArea();

   handleFileUploadButton();


   // functions========================================

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
         let files = event.dataTransfer.files;
         addFilesToModal(files);
      });
   }

   function addFilesToModal(files) {
      // let file_item;

      Array.from(files).forEach(file => {
         createFileElement(file);
         /*file_item = document.createElement('DIV');
         file_item.classList.add('file-modal__item');
         file_item.innerHTML = file.name;

         modal_body.appendChild(file_item);*/

         // current_form_data.append(file_number++, file)


         // files_arr.push(file);

         current_form_data.append(file.name, file);
         //
         // console.log(current_form_data.getAll(file.name));

      });

      // console.log(files_arr);
      // current_form_data.append('files', files_arr);

      // console.log(current_form_data.get('files'));


      let iterator = current_form_data.keys();
      let elem = iterator.next();
      while (!elem.done) {
         // console.log(current_form_data.getAll(elem.value));
         elem = iterator.next();
      }

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
         file_input.click();
      });

      file_input.addEventListener('change', () => {
         addFilesToModal(file_input.files)
      });
   }

   function addUploadedFiles() {
      let iterator = current_form_data.keys();
      let elem = iterator.next();
      while (!elem.done) {
         current_form_data.getAll(elem.value).forEach(file => {
            console.log(file.name);

            // if (file.name) {
            //    createFileElement(file);
            // }
         });
         elem = iterator.next();
      }
   }

   /*function getFileNumber(form_data) {
      let file_id;
      let number = 0;

      new Map(form_data).forEach(file => {
         file_id = +file.name;


         if (file_id > number) {
            number = file_id;
         }
      });

      return number;
   }*/

   //--functions========================================




   //=================================================
   // этот файл можешь привести к форматированию кода по своему типу

   // let form = document.querySelector('#file_uploader');
   let progress_bar = document.querySelector('#progress_bar');

   let request_urn = '/API_file_uploader';

   // когда идет загрузка файла на серер нужно запрещать клик по кнопке "загрузить файл"
   form.addEventListener('submit', event => {

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
   });

   function uploadProgressCallback(event){
      let download_percent = Math.round(100 * event.loaded / event.total);
      progress_bar.textContent = download_percent + ' %';
   }

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


/*class FileUploader {
   select;
   result_input




}*/












