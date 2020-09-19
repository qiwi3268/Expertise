/**
 * @typedef {Element | HTMLElement} HTMLElement
 */


document.addEventListener('DOMContentLoaded', () => {

   // Меняем размер раскрытых блоков при изменении размера страницы
   window.addEventListener('resize', () => {
      let cards = document.querySelectorAll('.card-form__body');

      cards.forEach(card_body => {
         if (card_body.style.maxHeight) {
            changeParentCardMaxHeight(card_body);
         }
      });

   });

});

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

function noScroll () {
   // window.scrollTo(0, 0);
}

function disableScroll () {
   // document.body.style.position = 'fixed';
   // document.body.style.top = `-${window.scrollY}px`;
   // document.body.classList.add('stop-scrolling');
}

function enableScroll () {
   // document.body.style.position = '';
   // document.body.style.top = '';
   // document.body.classList.remove('stop-scrolling');
}

// Предназначен для получения id текущего заявления
// Возвращает параметры------------------------------
// id         string : id текущего заявления
function getIdDocument () {
   return document.querySelector('[name="id_application"]').value;
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




