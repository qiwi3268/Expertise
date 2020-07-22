
document.addEventListener('DOMContentLoaded', () => {

   let file_selects = document.querySelectorAll('.modal-file');

   let file_modal = document.querySelector('.modal.file-modal');
   let overlay = document.querySelector('.file-overlay');

   let drop_area = file_modal.querySelector('.file-modal__drop-area');
   let file_input = document.getElementById('upload_id');
   let modal_body = file_modal.querySelector('.file-modal__body');


   clearDefaultDropEvents();


   ;['dragenter', 'dragover'].forEach(eventName => {
      document.addEventListener(eventName, event => {
         drop_area.classList.add('active');
      });
   })

   ;['dragleave', 'drop'].forEach(eventName => {
      document.addEventListener(eventName, event => {
         drop_area.classList.remove('active');
      });
   })


   drop_area.addEventListener('drop', event => {
      let dt = event.dataTransfer;
      let files = Array.from(dt.files);

      // let files_from_input = Array.from(file_input.files);

      addFilesToModal(files, modal_body);
   });

   let upload_button = file_modal.querySelector('.file-modal__icon');


   upload_button.addEventListener('click', () => {

      file_input.click();
   });

   file_input.addEventListener('change', event => {
      // console.log(file_input.files);
   });


   file_selects.forEach(select => {
      select.addEventListener('click', () => {
         file_modal.classList.add('active');
         overlay.classList.add('active');
      });
   });


   overlay.addEventListener('click', () => {
      file_modal.classList.remove('active')
      overlay.classList.remove('active');
   });



   //=================================================
   // этот файл можешь привести к форматированию кода по своему типу

   let form = document.querySelector('#file_uploader');
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

function addFilesToModal(files) {
   let file_item;

   files.forEach(file => {
      file_item = document.createElement('DIV');
      file_item.classList.add('file-modal__item');
      file_item.innerHTML = file.name;

      modal_body.appendChild(file_item);
   });

}








