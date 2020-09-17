document.addEventListener('DOMContentLoaded', () => {

   // Фон модального окна
   // overlay = document.querySelector('.modal-overlay');

   // overlay.addEventListener('click', () => {
   //    closeModal(modal);
   // });

   Misc.initializeMiscSelects(document);

});

class Misc {
   static miscs = new Map();
   static active_miscs_counter = 0;

   /**
    * @type {Misc}
    */
   static instance;
   static overlay = document.getElementById('misc_overlay');


   id;
   select;
   field;

   modal;
   overlay;

   result_callback;

   // close_btn;

   pages;
   is_empty;

   constructor (select) {
      this.id = Misc.active_miscs_counter++;
      this.select = select;
      this.select.dataset.id_misc = this.id;
      this.field = this.select.closest('[data-misc_field]');

      this.modal = this.field.querySelector('[data-misc_modal]');

      this.result_callback = getMiscResultCallback(this);

      this.handleCloseButton();

      this.initPages();

   }

   handleCloseButton () {
      let close_btn = this.modal.querySelector('[data-misc_close]');
      close_btn.addEventListener('click', this.close);
   }

   close () {
      this.modal.classList.remove('active');
      Misc.overlay.classList.remove('active');
   }

   initPages () {
      this.pages = this.modal.querySelectorAll('[data-misc_page]');
      if (this.pages.length === 0) {
         this.is_empty = this.createNewPages();
      }

      if (this.is_empty) {
         ErrorModal.open('')
      }
   }

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

   open () {

   }


   static initializeMiscSelects (block) {
      let misc_selects = block.querySelectorAll('.modal-select');
      misc_selects.forEach(this.handleMiscSelect);
   }

   static handleMiscSelect (select) {
      select.addEventListener('click', () => {
         this.instance = this.getMiscBySelect(select);
         this.instance.open();

/*
         if (!this.instance.is_empty) {
            this.instance.open();
         } else {
            ErrorModal.open('Ошибка при получении значений справочника', this.instance.error_message);
         }
*/
         disableScroll();

      });
   }

   static getMiscBySelect (select) {
      let id_modal = parseInt(select.dataset.id_modal);
      let misc = !isNaN(id_modal) ? this.miscs.get(id_modal) : new Misc(select);

      // Если страниц больше 1 отображаем пагинацию
      if (misc.pages.length > 1) {
         misc.handlePagination();
      }

      return misc;
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
   let result_input = misc.field.querySelector('[data-misc_result]');
   // В результат записываем id элемента из справочника
   result_input.value = selected_item.dataset.id;

   // В поле для выбора записываем значение
   misc.select.classList.add('filled');

   let misc_value = misc.select.querySelector('.field-value');
   misc_value.innerHTML = selected_item.innerHTML;

   // Показывает или скрывает поля, зависящие от выбранного значения
   DependenciesHandler.handleDependencies(result_input);

   // Очищаем зависимые поля
   misc.clearRelatedModals();
   validateMisc(misc);
}

function setAdditionalAction (selected_item, misc) {
   misc.field.dataset.id = selected_item.dataset.id;
   misc.field.dataset.drop_area = '';
   misc.select.classList.remove('empty');
   misc.select.innerHTML = selected_item.innerHTML;
}