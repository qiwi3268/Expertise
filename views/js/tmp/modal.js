/*
// Хранит объекты модальных окон заполненных полей
let modals;
// Текущее модальное окно
let modal;
// Фон модального окна
let overlay;


document.addEventListener('DOMContentLoaded', () => {
   modals = new Map();

   // Фон модального окна
   overlay = document.querySelector('.modal-overlay');

   overlay.addEventListener('click', () => {
      closeModal(modal);
   });

   initializeModalSelects(document);

});

function initializeModalSelects (block) {
   let modal_selects = block.querySelectorAll('.modal-select');
   modal_selects.forEach(select => {

      select.addEventListener('click', () => {
         modal = getModalBySelect(select);

         if (!modal.is_empty) {
            modal.show();
         } else {
            ErrorModal.open('Ошибка при получении значений справочника', modal.alert_message);
            // createAlert(modal.alert_message);
            // modal.alert_message = '';
         }
         disableScroll();

      });
   });
}

// Предназначен для получения объекта модального окна по родительскому полю
// Принимает параметры-------------------------------------------
// select     Element : поле, для коготого вызывается модальное окно
// Возвращает параметры------------------------------------------
// modal      Modal : объект модального окна
//
function getModalBySelect (select) {
   let id_modal = select.dataset.id_modal;
   let modal = id_modal ? modals.get(parseInt(id_modal)) : new Modal(select);

   // Если страниц больше 1 отображаем пагинацию
   if (modal.pages.length > 1) {
      modal.handlePagination();
   }

   return modal;
}

// Предназначен для закрытия модального окна
// Принимает параметры-------------------------------------------
// modal      (Element, Modal) : модальное окно, которое должно быть закрыто
//
function closeModal (modal) {
   if (modal instanceof Modal) {
      modal.close();
   } else {
      modal.classList.remove('active');
      overlay.classList.remove('active');
   }
   enableScroll();
}

// Предназначем для отображения оповещения с сообщением
// Принимает параметры-------------------------------------------
// message    string : отображаемое сообщение
//
function createAlert (message) {
   modal = document.querySelector('.alert-modal');
   modal.querySelector('.alert-modal__message').innerHTML = message;
   modal.classList.add('active');
   overlay.classList.add('active');

   createModalCloseButton(modal);
}

// Предназначем для создания в модальном окне кнопки для закрытия
// Принимает параметры-------------------------------------------
// modal      Element : модальное окно, в которое добавляется кнопка
//
function createModalCloseButton (modal) {
   let close_button = document.createElement('I');
   close_button.classList.add('modal__close', 'fas', 'fa-times');
   close_button.addEventListener('click', () => {
      closeModal(modal);
   });
   close_button.classList.add('active');

   modal.appendChild(close_button);
}

//Modal--------------------------------------------------------------------------------------------
class Modal {
   static modals_counter = 0;
   id;

   // Родительское поле
   parent_field;

   // Element модального окна
   element;

   // data-field_name родительского блока
   name;

   // Блок со страницами с элементами из справочника
   content;

   pages;
   active_page;
   close_button;

   // Скрытый инпут с id выбранного элемента
   result_input;

   result_callback;

   // Блок, в который подставляется имя элемента
   select;

   is_empty = false;
   alert_message;
   pagination;

   // Предназначен для создания объекта модального окна
   // Принимает параметры-------------------------------------------
   // select     Element : поле, для которого вызывается модальное окно
   constructor (select) {
      console.log('tua');
      this.id = Modal.modals_counter++;
      this.select = select;
      this.select.dataset.id_modal = this.id;

      this.parent_field = this.select.closest('.field');

      this.name = this.parent_field.dataset.name;
      this.element = this.parent_field.querySelector('.modal');
      this.content = this.element.querySelector('.modal__items');

      this.result_input = this.parent_field.querySelector('.field-result');
      this.result_callback = getModalResultCallback(this);

      this.close_button = this.element.querySelector('.modal__close');
      this.close_button.addEventListener('click', () => {
         closeModal(this);
      });

      //берем готовые страницы или создаем новые, если контент страниц зависит от другого поля
      this.initPages();

      //добавляем событие для выбора элемента
      this.initItems();

      modals.set(this.id, this);
   }

   // Предназначен для инициализации страниц с элементами из справочника модального окна
   //
   initPages () {

      this.pages = this.content.querySelectorAll('.modal__page');

      if (this.pages.length === 0) {

         this.is_empty = this.createNewPages();
      }
   }

   // Предназначен для заполнения модального окна страницами с элементами в зависимости от того,
   // что выбрано в другом поле
   // Возвращает параметры------------------------------------------
   // is_empty   boolean : false - было создано непустое модальное окно
   //                      true - нет элементов для добавления, создано сообщение для оповещения
   //
   createNewPages () {
      // Инпут, в котором хранятся все элементы для текущего модального окна
      let related_input = document.querySelector(`[data-target_change="${this.name}"]`);
      let related_modal_input;
      let is_empty = false;

      if (related_input) {
         // Инпут со значением поля, от которого зависит модальное окно
         related_modal_input = document.querySelector(
            `.field-result[name="${related_input.dataset.when_change}"]`
         );

         if (related_modal_input) {
            // Массив массивов всех значений
            let related_items = JSON.parse(related_input.value);
            // По значению родительского поля берем нужный массив со страницами
            let new_pages = related_items[related_modal_input.value];

            // Если родительское поле заполнено, добавляем значения, иначе создаем оповещение
            if (new_pages) {
               this.putItemsToModal(new_pages);
            } else {
               // Создаем сообщение, в котором записываем поле, которое нужно заполнить
               is_empty = true;

               // if (related_modal_input.closest) {
               let related_modal_card = related_modal_input.closest('.field');
               let related_modal_title = related_modal_card.querySelector('.field-title').innerHTML;
               this.alert_message = `Выберите ${related_modal_title.toLowerCase()}`;
               // }
            }
         }
      } else {
         // Если из базы пришел пустой справочник и нет связанного инпута
         is_empty = true;
         this.alert_message = `Заполните справочник`;
      }

      return is_empty;
   }

   // Добавляет элементы из массива в новые страницы и добавляет страницы в модальное окно
   // Принимает параметры-------------------------------------------
   // new_pages  Array : массив страниц для добавления
   //
   putItemsToModal (new_pages) {
      let modal_page;
      let modal_item;

      new_pages.forEach((page, index) => {
         modal_page = document.createElement('DIV');
         modal_page.dataset.page = index;
         modal_page.classList.add('modal__page');

         page.forEach(item => {
            modal_item = document.createElement('DIV');
            modal_item.dataset.id = item.id;
            modal_item.classList.add('modal__item');
            modal_item.innerHTML = item.name;

            modal_page.appendChild(modal_item);
         });

         this.content.appendChild(modal_page);
         this.pages = this.content.querySelectorAll('.modal__page');
      });
   }

   // Предназначен для добавления события каждому элементу страниц
   //
   initItems () {
      let items;

      this.pages.forEach(page => {
         items = page.querySelectorAll('.modal__item');
         items.forEach(item => {
            item.addEventListener('click', () => {

               if (this.result_input) {
                  // В результат записываем id элемента из справочника
                  this.result_input.value = item.dataset.id;

                  // В поле для выбора записываем значение
                  this.select.classList.add('filled');
                  this.select.querySelector('.field-value').innerHTML = item.innerHTML;

                  // Показывает или скрывает поля, зависящие от выбранного значения
                  DependenciesHandler.handleDependencies(this.result_input);

                  // Очищаем зависимые поля
                  this.clearRelatedModals();
                  validateModal(this);

               } else {
                  this.result_callback(item, this);
               }

               this.close();

            });
         });
      });
   }

   // Предназначен для удаления элементов и выбранных значений зависимых модальных окон
   //
   clearRelatedModals () {
      // Берем объекты модальных окон всех зависимых полей
      let dependent_modals = this.getDependentModals();

      dependent_modals.forEach(modal => {
         modal.clearRelatedModals();
         this.clearModal(modal);
         validateModal(modal);
      });
   }

   // Предназначен для удаления выбранного значения из родительского поля
   // Принимает параметры-------------------------------
   // modal         Modal : объект модального окна
   clearModal (modal) {
      modal.content.innerHTML = '';
      modal.result_input.value = '';
      modal.select.classList.remove('filled');

      let select_value = modal.select.querySelector('.field-value');
      select_value.innerHTML = 'Выберите значение';
      modal.select.removeAttribute('data-id_modal');
      modals.delete(modal.id);
   }

   // Предназначен для получения массива зависимых модальных окон
   // Возвращает параметры------------------------------------------
   // dependent_modals  Array[Modal] : массив с объектами зависимых модальных окон
   //
   getDependentModals () {
      let dependent_modals = [];
      let scope = this.element.closest('[data-dependency_scope]') || document;
      let dependent_inputs = document.querySelectorAll(`[data-when_change='${this.name}']`);

      dependent_inputs.forEach(input => {
         let dependent_field = scope.querySelector(`.field[data-name='${input.dataset.target_change}']`);
         let modal_select = dependent_field.querySelector('[data-id_modal]');

         if (modal_select) {
            let dependent_modal = modals.get(parseInt(modal_select.dataset.id_modal));
            dependent_modals.push(dependent_modal);
         }

      });

      return dependent_modals;
   }

   // Предназначен для закрытия объекта модального окна
   //
   close () {
      if (this.active_page) {
         this.active_page.classList.remove('active');
      }

      this.element.classList.remove('active');

      if (this.pagination) {
         this.pagination.element.style.display = 'none';
      }

      this.close_button.classList.remove('active');

      overlay.classList.remove('active');
   }

   // Предназначен для отображения на странице модального окна
   //
   show () {
      this.element.classList.add('active');
      this.active_page = this.pages[0];
      this.active_page.classList.add('active');
      this.close_button.classList.add('active');
      overlay.classList.add('active');
   }

   // Предназначен для смены отображаемой страницы модального окна
   // Принимает параметры-------------------------------------------
   // new_page_num  number : номер новой страницы
   //
   changeActivePage (new_page_num) {
      this.active_page.classList.remove('active');
      this.active_page = this.content.querySelector(`.modal__page[data-page="${new_page_num}"]`);
      this.active_page.classList.add('active');
   }

   // Предназначени для добавления в модальное окно блока с пагинацией
   //
   handlePagination () {
      if (!this.pagination) {
         this.pagination = new Pagination(this);
      }

      this.pagination.page_label.innerHTML = `1/${this.pages.length}`;
      this.pagination.arrow_left.style.visibility = 'hidden';
      this.pagination.arrow_right.style.visibility = 'visible';
      this.pagination.element.style.display = 'flex';
   }
}

//Modal--------------------------------------------------------------------------------------------

//Pagination----------------------------------------------------------------------------------------
class Pagination {
   // Element пагинации
   element;

   // Родительский объект модального окна
   modal;

   arrow_left;
   arrow_right;

   // Номер текущей страницы
   page_label;

   constructor (modal) {
      this.element = document.createElement('DIV');
      this.element.classList.add('modal__pagination', 'pagination');
      this.modal = modal;

      // Создаем стрелки и добавляем им события
      this.initArrows();

      this.page_label = document.createElement('SPAN');
      this.page_label.classList.add('pagination__item', 'pagination__current-page');

      this.element.appendChild(this.arrow_left);
      this.element.appendChild(this.page_label);
      this.element.appendChild(this.arrow_right);
      this.modal.element.appendChild(this.element);
   }

   // Предназначен для создания стрелок переключения страниц и добавления им событий
   //
   initArrows () {
      this.arrow_left = Pagination.createPaginationArrow('left');
      this.arrow_right = Pagination.createPaginationArrow('right');

      this.arrow_left.addEventListener('click', () => {
         let new_page_num = +this.modal.active_page.dataset.page - 1;
         this.modal.changeActivePage(new_page_num);

         this.page_label.innerHTML = `${1 + new_page_num}/${this.modal.pages.length}`;

         // Прячем стрелку на первой странице
         if (new_page_num === 0) {
            this.arrow_left.style.visibility = 'hidden';
         }

         // Показываем противоположную стрелку, если она была скрыта
         if (this.arrow_right.style.visibility !== 'visible') {
            this.arrow_right.style.visibility = 'visible';
         }
      });

      this.arrow_right.addEventListener('click', () => {
         let new_page_num = +this.modal.active_page.dataset.page + 1;
         this.modal.changeActivePage(new_page_num);

         this.page_label.innerHTML = `${1 + new_page_num}/${this.modal.pages.length}`;

         // Прячем стрелку на последней странице
         if (new_page_num === this.modal.pages.length - 1) {
            this.arrow_right.style.visibility = 'hidden';
         }

         // Показываем противоположную стрелку, если она была скрыта
         if (this.arrow_left.style.visibility !== 'visible') {
            this.arrow_left.style.visibility = 'visible';
         }
      });
   }

   // Предназначен для создания элемента переключения страниц
   // Принимает параметры-------------------------------------------
   // class_name    string : направление стрелки
   //
   static createPaginationArrow (class_name) {
      let arrow = document.createElement('I');
      arrow.classList.add(
         'pagination__item',
         `pagination__arrow-${class_name}`,
         'fa',
         `fa-chevron-${class_name}`
      );

      return arrow;
   }
}

//Pagination----------------------------------------------------------------------------------------

function getModalResultCallback (modal) {
   let callback;

   switch (modal.element.dataset.result_callback) {
      case 'application_field':
         callback = setApplicationFieldValue;
         break;
      case 'additional_section':
         callback = setAdditionalAction;
         break;

      default:

   }

   return callback;
}

function setApplicationFieldValue (selected_item, modal) {
   this.result_input = this.parent_field.querySelector('.field-result');

   // В результат записываем id элемента из справочника
   modal.result_input.value = selected_item.dataset.id;

   // В поле для выбора записываем значение
   modal.select.classList.add('filled');
   modal.select.querySelector('.field-value').innerHTML = selected_item.innerHTML;

   // Показывает или скрывает поля, зависящие от выбранного значения
   DependenciesHandler.handleDependencies(modal.result_input);

   // Очищаем зависимые поля
   modal.clearRelatedModals();
   validateModal(modal);
}

function setAdditionalAction (selected_item, modal) {
   modal.parent_field.dataset.id = selected_item.dataset.id;
   modal.parent_field.dataset.drop_area = '';
   modal.select.classList.remove('empty');
   modal.select.innerHTML = selected_item.innerHTML;
}*/
