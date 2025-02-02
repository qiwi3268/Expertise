/**
 * @typedef {Element | HTMLElement} HTMLElement
 */

/**
 * @typedef {HTMLElement | HTMLInputElement} HTMLInputElement
 */


document.addEventListener('DOMContentLoaded', () => {
   //todo в отдельный файл

   // Меняем размер раскрытых блоков при изменении размера страницы
   window.addEventListener('resize', () => {
      let cards = document.querySelectorAll('.card-body');

      cards.forEach(card_body => {
         if (card_body.style.maxHeight) {
            changeParentCardMaxHeight(card_body);
         }
      });

   });


   // todo вынести
   let alert_modal = document.querySelector('.save-modal');
   if (alert_modal) {
      handleAlertModal();
   }


});


function handleAlertModal () {
   let alert_overlay = document.querySelector('.save-overlay');
   alert_overlay.addEventListener('click', () => {
      closeSaveModal(alert_overlay);
   });

   let save_close_button = document.querySelector('.save-modal__close');
   save_close_button.addEventListener('click', () => {
      closeSaveModal(alert_overlay);
   });
}

function closeSaveModal (alert_overlay) {
   let alert_modal = document.querySelector('.save-modal');
   alert_modal.classList.remove('active');
   alert_overlay.classList.remove('active');
}

function showAlertModal () {
   let alert_modal = document.querySelector('.save-modal');
   let alert_overlay = document.querySelector('.save-overlay');
   alert_modal.classList.add('active');
   alert_overlay.classList.add('active');
}


function mQS (element, selector, error_code) {
   let result = element.querySelector(selector);

   if (result) {
      return result;
   } else {
      // ErrorHandler.createError(error_code)
      // throw new Error();
   }
}

function mClosest (element, selector, error_code) {
   let result = element.closest(selector);

   if (result) {
      return result;
   } else {
      // ErrorHandler.createError(error_code)
      // throw new Error();
   }
}

// Предназначен для создания объекта даты из строки
// Принимает параметры-------------------------------
// date_string     string : строка с датой
// Возвращает параметры------------------------------
// date            Date : объект даты из строки
function getDateFromString (date_string) {
   let date_parts = date_string.split('.');
   return new Date(
      parseInt(date_parts[2]),
      parseInt(date_parts[1]) - 1,
      parseInt(date_parts[0])
   );
}

function disableScroll () {
   document.body.style.height = '100%';
   document.body.style.overflow = 'hidden';
}

function enableScroll () {
   document.body.style.height = null;
   document.body.style.overflow = null;
}

// Предназначен для получения id текущего заявления
// Возвращает параметры------------------------------
// id         string : id текущего заявления
function getIdDocument () {
   let id_document_input = document.getElementById('id_document');
   let id_document;

   if (id_document_input) {
      id_document = id_document_input.value
   } else {
      let url = new URL(window.location.href);
      id_document = url.searchParams.get('id_document');

   }

   return id_document;
}

//---------

function getFileListFromFile (file) {
   let data_transfer = new DataTransfer();
   data_transfer.items.add(file);
   return data_transfer.files;
}

function getFileFromData (data, name) {
   let blob = new Blob([data], {type: 'text/plain'});
   return new File([blob], name, {lastModified: Date.now()});
}


function createErrorAlert (error_code) {

}

/**
 * Отключает стандартные действия переноса в браузере
 */
function clearDefaultDropEvents () {
   let events = ['dragenter', 'dragover', 'dragleave', 'drop'];
   events.forEach(event_name => {
      document.addEventListener(event_name, event => {
         event.preventDefault();
         event.stopPropagation();
      });
   });
}

