document.addEventListener('DOMContentLoaded', () => {

   Misc.initializeMiscSelects(document);

   Misc.overlay = document.getElementById('misc_overlay');
   Misc.overlay.addEventListener('click', () => {
      Misc.instance.close();
   });

});

/**
 * Представляет собой модальное окно со значениями справочника
 */
class Misc {

   /**
    * Проинициализированные справочники
    *
    * @type {Map<number, Misc>}
    */
   static miscs = new Map();

   /**
    * Счетчик проинициализированных справочников
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
    * @type {HTMLElement}
    */
   static overlay;

   /**
    * id справочника, для получения объекта
    *
    * @type {number}
    */
   id;

   /**
    * Блок, при нажатии на который открывается модальное окно
    *
    * @type {HTMLElement}
    */
   select;

   /**
    * Поле, к которому относится справочник
    *
    * @type {HTMLElement}
    */
   field;

   /**
    * Элемент модального окна справочника
    *
    * @type {HTMLElement}
    */
   modal;

   /**
    * Имя поля, к которому относится справочник
    *
    * @type {string}
    */
   name;

   /**
    * Функция обработки выбора элемента справочника
    *
    * @type {Function}
    */
   result_callback;

   /**
    * Скрытый инпут, в который записывается id выбранного элемента справочника
    *
    * @type {HTMLElement}
    */
   result_input;

   /**
    * Блок со страницами значений справочника
    *
    * @type {HTMLElement}
    */
   body;

   /**
    * Коллекция страниц значений справочника
    *
    * @type {NodeList}
    */
   pages;

   /**
    * Флаг, указывающий есть ли значения справочника для выбора
    *
    * @type {boolean}
    */
   is_empty = false;

   /**
    * Сообщении с ошибкой при инициализации справочника
    *
    * @type {string}
    */
   error_message;

   /**
    * Объект пагинации справочника
    *
    * @type {Pagination}
    */
   pagination;

   /**
    * Текущая страница справочника
    *
    * @type {HTMLElement}
    */
   active_page;

   /**
    * Создает модальное окно справочника
    *
    * @param {HTMLElement} select - Блок, при нажатии на который открывается модальное окно
    */
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

   /**
    * Добавляет обработчик кнопки закрытия справочника
    */
   handleCloseButton () {
      let close_btn = this.modal.querySelector('[data-misc_close]');
      close_btn.addEventListener('click', () => this.close());
   }

   /**
    * Закрывает модальное окно справочника
    */
   close () {

      this.modal.classList.remove('active');
      Misc.overlay.classList.remove('active');

      if (this.pagination) {
         this.pagination.element.classList.remove('active');
      }

      enableScroll();
   }

   /**
    * Инициализирует страницы с элементами справочника
    */
   initPages () {
      this.body = this.modal.querySelector('[data-misc_body]');
      this.pages = this.body.querySelectorAll('[data-misc_page]');

      // Если справочник зависит от значения другого поля создаем страницы
      if (this.pages.length === 0) {
         this.createNewPages();
      }
   }

   /**
    * Создает страницы справочника в зависимости от значения другого поля
    *
    */
   createNewPages () {
      // Контейнер, в котором хранятся все возможные значения
      let misc_values = document.querySelector(`[data-target_change="${this.name}"]`);

      if (misc_values) {


         // Инпут со значением родительского поля
         //todo добавить scope
         let parent_misc_result = document.querySelector(`[data-misc_result][name='${misc_values.dataset.when_change}']`);

         if (parent_misc_result) {
            // Массив массивов всех значений
            let related_items = JSON.parse(misc_values.value);


            console.log(related_items);

            // По значению родительского справочника берем нужный массив со страницами
            let new_pages = related_items[parent_misc_result.value];

            // Если родительское поле заполнено, добавляем значения, иначе ошибка
            if (new_pages) {
               this.putMiscValues(new_pages);
            } else {
               // Создаем сообщение, в котором записываем поле, которое нужно заполнить
               this.is_empty = true;
               let related_field = parent_misc_result.closest('.field');
               let related_field_name = related_field.querySelector('[data-misc_title]').innerHTML;
               this.error_message = `Выберите ${related_field_name.toLowerCase()}`;
            }
         }
      } else {
         // Если справочник пустой и нет связанного инпута
         this.is_empty = true;
         this.error_message = `Не найден input со значениями справочника, либо справочник не заполнен`;
      }

   }

   /**
    * Создает элементы значений справочника и добавляет новые страницы в модальное окно
    *
    * @param {Array.<Object[]>} pages - массив страниц со значениями
    */
   putMiscValues (pages) {
      let modal_page;
      let modal_item;

      pages.forEach((page, index) => {
         modal_page = document.createElement('DIV');
         modal_page.dataset.misc_page = index;
         modal_page.classList.add('misc__page');

         page.forEach(item => {
            modal_item = document.createElement('SPAN');
            modal_item.dataset.id = item.id;
            // modal_item.dataset.misc_item = item.id;
            modal_item.classList.add('misc__item');
            modal_item.innerHTML = item.name;

            modal_page.appendChild(modal_item);
         });

         this.body.appendChild(modal_page);
         this.pages = this.body.querySelectorAll('.misc__page');
      });
   }

   /**
    * Добавляет обработчики для значений справочника
    */
   handleItems () {
      let items;

      this.pages.forEach(page => {
         items = page.querySelectorAll('.misc__item');
         items.forEach(item => {
            item.addEventListener('click', () => {

               this.result_callback(item, this);
               this.close();

            });
         });
      });
   }

   /**
    * Отображает модальное окно справочника или выводит ошибку заполнения
    */
   open () {
      if (!this.is_empty) {
         this.modal.classList.add('active');

         if (this.active_page) {
            this.active_page.classList.remove('active');
         }

         this.active_page = this.pages[0];
         this.active_page.classList.add('active');

         // Устанавливаем максимальную высоту модального окна по высоте первой страницы
         let body = this.modal.querySelector('.misc__body');
         body.style.height = body.offsetHeight + 'px';

         Misc.overlay.classList.add('active');
      } else {
         ErrorModal.open('Ошибка справочника', this.error_message);
      }

      disableScroll();
   }

   /**
    * Добавляет в модальное окно блок с пагинацией
    */
   handlePagination () {
      this.pagination = Pagination.getInstance();
      this.pagination.addToModal(this);
   }

   /**
    * Меняет отображаемую страницу модального окна
    *
    * @param {number} new_page_num
    */
   changeActivePage (new_page_num) {
      this.active_page.classList.remove('active');
      this.active_page = this.body.querySelector(`[data-misc_page='${new_page_num}']`);
      this.active_page.classList.add('active');
   }

   /**
    * Инициализирует поля справочников
    *
    * @param {(Document | HTMLElement)} block - Блок, внутри которого инициализируются поля справочников
    * @static
    */
   static initializeMiscSelects (block) {
      let misc_selects = block.querySelectorAll('[data-modal_select="misc"]');
      misc_selects.forEach(this.handleMiscSelect);
   }

   /**
    * Добавляет обработку вызова справочника
    *
    * @param {HTMLElement} select
    * @static
    */
   static handleMiscSelect (select) {
      select.addEventListener('click', () => {
         Misc.instance = Misc.getMiscBySelect(select);
         Misc.instance.open();

         disableScroll();
      });
   }

   /**
    * Получает ранее созданный справочник, либо создает новый
    *
    * @param {HTMLElement} select - блок, для которого вызывается справочник
    * @returns {Misc} - проинициализированный справочник
    * @static
    */
   static getMiscBySelect (select) {
      // Если справочник уже создан, в поле записан его id;
      let id_misc = parseInt(select.dataset.id_misc);
      let misc = !isNaN(id_misc) ? this.miscs.get(id_misc) : new Misc(select);

      if (misc.pages.length > 1) {
         misc.handlePagination();
      }

      return misc;
   }

   /**
    * Удаляет значения в зависимых справочниках
    */
   clearRelatedMiscs () {
      let dependent_miscs = this.getDependentMiscs();

      dependent_miscs.forEach(misc => {
         misc.clearRelatedMiscs();
         this.clearModal(misc);
         validateMisc(misc);
      });
   }

   /**
    * Получает массив зависимых справочников
    *
    * @returns {Misc[]} зависимые справочники
    */
   getDependentMiscs () {
      let dependent_miscs = [];
      // Если есть блоки с одинаковыми справочниками, берем только из текущего блока
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

   /**
    * Удаляет объект справочника, значения в модальном окне,
    * очищает поле справочника
    *
    * @param {Misc} misc
    */
   clearModal (misc) {
      misc.body.innerHTML = '';

      misc.result_input = misc.field.querySelector('[data-misc_result]');
      misc.result_input.value = '';
      // misc.select.classList.remove('filled');
      misc.field.classList.remove('filled');

      let select_value = misc.select.querySelector('[data-field_value]');
      select_value.innerHTML = 'Выберите значение';
      misc.select.removeAttribute('data-id_misc');
      Misc.miscs.delete(misc.id);
   }

}

/**
 * Представляет собой блок с пагинацией в модальном окне
 */
class Pagination {

   /**
    * Объект пагинации
    *
    * @type {Pagination}
    */
   static instance;

   /**
    * Элемент пагинации
    *
    * @type {HTMLElement}
    */
   element;

   /**
    * Родительский справочник
    *
    * @type {Misc}
    */
   misc;

   /**
    * Левая стрелка
    *
    * @type {HTMLElement}
    */
   arrow_left;

   /**
    * Правая стрелка
    *
    * @type {HTMLElement}
    */
   arrow_right;

   /**
    * Номер текущей страницы
    *
    * @type {HTMLElement}
    */
   page_label;

   /**
    *
    * @returns {Pagination}
    */
   static getInstance () {
      if (!this.instance) {
         this.instance = new Pagination();
      }

      return this.instance;
   }

   /**
    * Добавляет в модальное окно справочника блок с пагинацией
    *
    * @param {Misc} misc
    */
   addToModal (misc) {
      this.misc = misc;

      let misc_wrapper = this.misc.modal.querySelector('.misc__wrapper');
      misc_wrapper.appendChild(this.element);

      this.page_label.innerHTML = `1/${this.misc.pages.length}`;
      this.arrow_left.style.visibility = 'hidden';
      this.arrow_right.style.visibility = 'visible';
      this.element.classList.add('active');
   }

   /**
    * Создает объект пагинации
    */
   constructor () {
      this.element = document.createElement('DIV');
      this.element.classList.add('misc__pagination', 'pagination');

      this.initArrows();

      this.page_label = document.createElement('SPAN');
      this.page_label.classList.add('pagination__item', 'pagination__current-page');

      this.element.appendChild(this.arrow_left);
      this.element.appendChild(this.page_label);
      this.element.appendChild(this.arrow_right);
   }

   /**
    * Создает стрелки переключения страниц справочника
    */
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

   /**
    * Создает элемент стрелки переключения страниц справочника
    *
    * @param {string} class_name - класс, указывающий направление стрелки
    * @returns {HTMLElement} элемент стрелки
    * @static
    */
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

/**
 * Получает функцию обработки выбора элемента справочника
 *
 * @param {Misc} misc - объект справочника, в котором выбран элемент
 * @returns {Function}
 */
function getMiscResultCallback (misc) {
   let callback;

   switch (misc.modal.dataset.result_callback) {
      case 'document_field':
         callback = setDocumentFieldValue;
         break;
      case 'additional_section':
         callback = setAdditionalSection;
         break;

      default:
         ErrorModal.open('Ошибка справочника', 'Не указан result_callback');
         break;
   }

   return callback;
}

/**
 * Добавляет значение справочника в поле формы анкеты
 *
 * @param {HTMLElement} selected_item - выбранный элемент справочника
 * @param {Misc} misc - объект справочника, к которому отностится элемент
 */
function setDocumentFieldValue (selected_item, misc) {
   misc.result_input = misc.field.querySelector('[data-misc_result]');
   // В результат записываем id элемента из справочника
   misc.result_input.value = selected_item.dataset.id;

   // misc.select.classList.add('filled');
   misc.field.classList.add('filled');
   let misc_value = misc.field.querySelector('[data-field_value]');
   misc_value.innerHTML = selected_item.innerHTML;

   // Показываем или скрываем блоки, зависящие от выбранного значения
   DependenciesHandler.handleDependencies(misc.result_input);

   // Очищаем зависимые поля
   misc.clearRelatedMiscs();
   validateMisc(misc);
}

