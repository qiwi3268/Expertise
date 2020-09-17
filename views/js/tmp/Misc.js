document.addEventListener('DOMContentLoaded', () => {

   Misc.initializeMiscSelects(document);

   Misc.overlay = document.getElementById('misc_overlay');
   Misc.overlay.addEventListener('click', () => {
      Misc.instance.close();
   });

});


class Misc {

   /**
    * Созданные модальные окна справочников
    *
    * @type {Map<number, Misc>}
    */
   static miscs = new Map();

   /**
    * Счетчик созданных модальный окон справочников
    *
    * @type {number}
    */
   static active_miscs_counter = 0;

   /**
    * Объект текущего справочника
    *
    * @type {Misc}
    */
   static instance;

   /**
    * Фон модального окна справочника
    *
    * @type {Element}
    */
   static overlay;


   id;
   select;
   field;

   modal;
   overlay;

   name;

   result_callback;


   body;
   pages;
   is_empty = false;
   error_message;

   pagination;

   constructor (select) {
      this.select = select;
      this.field = this.select.closest('[data-misc_field]');

      this.modal = this.field.querySelector('[data-misc_modal]');

      this.name = this.field.dataset.name;

      this.result_callback = getMiscResultCallback(this);


      this.handleCloseButton();


      this.initPages();

      if (!this.is_empty) {

         this.handleItems();
         this.id = Misc.active_miscs_counter++;
         this.select.dataset.id_misc = this.id;
         Misc.miscs.set(this.id, this);

      }


   }

   handleCloseButton () {
      let close_btn = this.modal.querySelector('[data-misc_close]');
      close_btn.addEventListener('click', () => this.close());
   }

   close () {

      this.modal.classList.remove('active');
      Misc.overlay.classList.remove('active');

      /*if (this.pagination) {
         this.pagination.element.style.display = 'none';
      }*/

   }

   initPages () {
      this.body = this.modal.querySelector('[data-misc_body]');
      this.pages = this.body.querySelectorAll('[data-misc_page]');

      if (this.pages.length === 0) {
         this.createNewPages();
      }

   }

   createNewPages () {
      // Контейнер со значениями для текущего справочника в зависимости от значения родительского справочника
      let misc_values = document.querySelector(`[data-target_change="${this.name}"]`);

      if (misc_values) {
         // Инпут со значением родительского справочника
         let parent_misc_result = document.querySelector(`[data-misc_result][name='${misc_values.dataset.when_change}']`);

         if (parent_misc_result) {
            // Массив массивов всех значений
            let related_items = JSON.parse(misc_values.value);
            // По значению родительского справочника берем нужный массив со страницами
            let new_pages = related_items[parent_misc_result.value];

            // Если родительское поле заполнено, добавляем значения, иначе создаем оповещение
            if (new_pages) {
               this.putMiscValues(new_pages);
            } else {
               // Создаем сообщение, в котором записываем поле, которое нужно заполнить

               this.is_empty = true;
               let related_field = parent_misc_result.closest('.field');
               let related_field_name = related_field.querySelector('.field-title').innerHTML;
               this.error_message = `Выберите ${related_field_name.toLowerCase()}`;

            }
         }
      } else {
         // Если из базы пришел пустой справочник и нет связанного инпута
         this.is_empty = true;
         this.error_message = `Не найден input со значениями справочника, либо справочник не заполнен`;

      }

   }

   putMiscValues (pages) {
      let modal_page;
      let modal_item;

      pages.forEach((page, index) => {
         modal_page = document.createElement('DIV');
         modal_page.dataset.misc_page = index;
         modal_page.classList.add('modal__page');

         page.forEach(item => {
            modal_item = document.createElement('SPAN');
            modal_item.dataset.id = item.id;
            modal_item.dataset.misc_item = item.id;
            modal_item.classList.add('modal__item');
            modal_item.innerHTML = item.name;

            modal_page.appendChild(modal_item);
         });

         this.body.appendChild(modal_page);
         this.pages = this.body.querySelectorAll('.modal__page');
      });
   }

   // Предназначен для добавления события каждому элементу страниц
   //
   handleItems () {
      let items;

      this.pages.forEach(page => {
         items = page.querySelectorAll('.modal__item');
         items.forEach(item => {
            item.addEventListener('click', () => {

               this.result_callback(item, this);
               this.close();

            });
         });
      });
   }

   open () {
      if (!this.is_empty) {
         this.modal.classList.add('active');

         if (this.active_page) {
            this.active_page.classList.remove('active');
         }

         this.active_page = this.pages[0];
         this.active_page.classList.add('active');


         Misc.overlay.classList.add('active');
      } else {
         ErrorModal.open('Ошибка справочника', this.error_message);
      }

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

   // Предназначен для смены отображаемой страницы модального окна
   // Принимает параметры-------------------------------------------
   // new_page_num  number : номер новой страницы
   //
   changeActivePage (new_page_num) {
      this.active_page.classList.remove('active');
      this.active_page = this.body.querySelector(`[data-misc_page='${new_page_num}']`);
      this.active_page.classList.add('active');
   }

   static initializeMiscSelects (block) {
      let misc_selects = block.querySelectorAll('[data-misc_select]');
      misc_selects.forEach(this.handleMiscSelect);
   }

   static handleMiscSelect (select) {
      select.addEventListener('click', () => {
         Misc.instance = Misc.getMiscBySelect(select);
         Misc.instance.open();
         disableScroll();
      });
   }

   static getMiscBySelect (select) {
      let id_misc = parseInt(select.dataset.id_misc);
      let misc = !isNaN(id_misc) ? this.miscs.get(id_misc) : new Misc(select);

      // Если страниц больше 1 отображаем пагинацию
      if (misc.pages.length > 1) {
         misc.handlePagination();
      } else {
         console.log(misc.pagination);
      }

      return misc;
   }

   // Предназначен для удаления элементов и выбранных значений зависимых модальных окон
   //
   clearRelatedMiscs () {
      // Берем объекты модальных окон всех зависимых полей
      let dependent_miscs = this.getDependentMiscs();

      dependent_miscs.forEach(misc => {
         misc.clearRelatedMiscs();
         this.clearModal(misc);
         validateMisc(misc);
      });
   }

   // Предназначен для удаления выбранного значения из родительского поля
   // Принимает параметры-------------------------------
   // modal         Modal : объект модального окна
   clearModal (misc) {
      misc.body.innerHTML = '';

      misc.result_input = misc.field.querySelector('[data-misc_result]');
      misc.result_input.value = '';
      misc.select.classList.remove('filled');

      let select_value = misc.select.querySelector('[data-misc_value]');
      select_value.innerHTML = 'Выберите значение';
      misc.select.removeAttribute('data-id_misc');
      Misc.miscs.delete(misc.id);
   }

   // Предназначен для получения массива зависимых модальных окон
   // Возвращает параметры------------------------------------------
   // dependent_modals  Array[Modal] : массив с объектами зависимых модальных окон
   //
   getDependentMiscs () {
      let dependent_miscs = [];
      let scope = this.field.closest('[data-dependency_scope]') || document;
      let dependent_inputs = document.querySelectorAll(`[data-when_change='${this.name}']`);

      dependent_inputs.forEach(input => {
         let dependent_field = scope.querySelector(`.field[data-name='${input.dataset.target_change}']`);
         let misc_select = dependent_field.querySelector('[data-id_misc]');

         if (misc_select) {
            let dependent_misc = Misc.miscs.get(parseInt(misc_select.dataset.id_misc));
            dependent_miscs.push(dependent_misc);
         }

      });

      return dependent_miscs;
   }

}

class Pagination {
   // Element пагинации
   element;

   // Родительский объект модального окна
   misc;

   arrow_left;
   arrow_right;

   // Номер текущей страницы
   page_label;

   constructor (misc) {
      this.element = document.createElement('DIV');
      this.element.classList.add('modal__pagination', 'pagination');
      this.misc = misc;

      // Создаем стрелки и добавляем им события
      this.initArrows();

      this.page_label = document.createElement('SPAN');
      this.page_label.classList.add('pagination__item', 'pagination__current-page');

      this.element.appendChild(this.arrow_left);
      this.element.appendChild(this.page_label);
      this.element.appendChild(this.arrow_right);
      this.misc.modal.appendChild(this.element);
   }

   // Предназначен для создания стрелок переключения страниц и добавления им событий
   //
   initArrows () {
      this.arrow_left = Pagination.createPaginationArrow('left');
      this.arrow_right = Pagination.createPaginationArrow('right');

      this.arrow_left.addEventListener('click', () => {
         let new_page_num = parseInt(this.misc.active_page.dataset.misc_page) - 1;
         this.misc.changeActivePage(new_page_num);

         this.page_label.innerHTML = `${1 + new_page_num}/${this.misc.pages.length}`;

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
         let new_page_num = parseInt(this.misc.active_page.dataset.misc_page) + 1;
         this.misc.changeActivePage(new_page_num);

         this.page_label.innerHTML = `${1 + new_page_num}/${this.misc.pages.length}`;

         // Прячем стрелку на последней странице
         if (new_page_num === this.misc.pages.length - 1) {
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

function getMiscResultCallback (misc) {
   let callback;

   switch (misc.modal.dataset.result_callback) {
      case 'application_field':
         callback = setApplicationFieldValue;
         break;
      case 'additional_section':
         callback = setAdditionalAction;
         break;

      default:
         ErrorModal.open('Ошибка справочника', 'Не указан result_callback');
         break;
   }

   return callback;
}

function setApplicationFieldValue (selected_item, misc) {
   misc.result_input = misc.field.querySelector('[data-misc_result]');
   // В результат записываем id элемента из справочника
   misc.result_input.value = selected_item.dataset.id;

   // В поле для выбора записываем значение
   misc.select.classList.add('filled');

   let misc_value = misc.select.querySelector('[data-misc_value]');
   misc_value.innerHTML = selected_item.innerHTML;

   // Показывает или скрывает поля, зависящие от выбранного значения
   DependenciesHandler.handleDependencies(misc.result_input);

   // Очищаем зависимые поля
   misc.clearRelatedMiscs();
   validateMisc(misc);
}

function setAdditionalAction (selected_item, misc) {
   misc.field.dataset.id = selected_item.dataset.id;
   misc.field.dataset.drop_area = '';
   misc.select.classList.remove('empty');

   let misc_value = misc.select.querySelector('[data-misc_value]');
   // misc.select.innerHTML = selected_item.innerHTML;
   misc_value.innerHTML = selected_item.innerHTML;
}