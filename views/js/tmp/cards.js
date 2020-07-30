// Массив хранящий зависимости полей
let dependencies;
let radio_dependency;

let block_dependencies;

document.addEventListener('DOMContentLoaded', () => {
   // dependencies = JSON.parse(document.querySelector('.row-dependencies').value);
   radio_dependency = document.querySelector('.radio__content-change-logic');
   block_dependencies = JSON.parse(document.querySelector('.block-dependencies').value);

   handleClearFieldButtons();


   // TODO вынести отдельно
   window.addEventListener('resize', () => {
      let cards = document.querySelectorAll('.card-form__body');

      cards.forEach(card_body => {
         if (card_body.style.maxHeight) {
            changeParentCardMaxHeight(card_body);
         }
      });

   });
});

function handleClearFieldButtons() {
   let clear_buttons = document.querySelectorAll('.body-card__icon-clear');

   clear_buttons.forEach(button => {
      button.addEventListener('click', () => {
         let parent_row = button.closest('.field');

         let row_result = parent_row.querySelector('.field-result');
         // Удаляем зависимые поля
         hideDependentRows(row_result);

         let parent_select = parent_row.querySelector('.modal-select');
         if (row_result.value) {

            removeRowValue(parent_row);

            let related_modal = parent_row.querySelector('.modal');
            if (related_modal) {
               let modal = getModalBySelect(parent_select);
               modal.clearRelatedModals();
               validateModal(modal);
            }

            validateCard(parent_row.closest('.card-form'));
         }
      });
   });
}

// Предназначен для добавления или удаления блоков, зависящих от значения поля на входе
// Принимает параметры-------------------------------------------
// parent_input  Element : скрытый инпут со значением родительского поля
function handleDependentRows(parent_input) {
   // Получаем массив с зависимостями всех значений родительского поля
   let values = block_dependencies[parent_input.name];

   if (values) {
      // Получаем зависимые поля для значения в родительском поле
      let dependent_block_names = block_dependencies[parent_input.name][parent_input.value];

      if (dependent_block_names) {
         Object.keys(dependent_block_names).forEach(block_name => {
            let dependent_blocks = document.querySelectorAll(`[data-block_name="${block_name}"]`);
            let is_display;

            dependent_blocks.forEach(block => {
               // Определяем показать или скрыть блок
               is_display = dependent_block_names[block_name];

               if (!is_display) {
                  block.dataset.inactive = 'true';
               } else {
                  block.dataset.inactive = 'false';
               }

               clearBlock(block);
            });
         });
      }

   }

   handleDependentRadios(parent_input);
   changeParentCardMaxHeight(parent_input);
}

function clearBlock(block) {
   let dependent_fields = block.querySelectorAll('.field');
   dependent_fields.forEach(field => {

      removeRowValue(field);

      if (block.querySelector('.files')) {
         removeDependentFiles(block);
      }

   });

}

function removeDependentFiles(block) {
   let files = block.querySelectorAll('.file__item');
   files.forEach(file => {

   });
}


// Предназначен для удаления значении в поле
// Принимает параметры-------------------------------
// row         Element : элемент поля
function removeRowValue(row) {
   // Удаляем записанное значение в зависимом поле
   row.querySelector('.field-result').value = '';

   let select = row.querySelector('.field-select');
   if (select) {
      select.classList.remove('filled');

      let value = row.querySelector('.field-value');
      // Если зависимое поле - дата, удаляем отображаемую дату
      if (select.classList.contains('modal-calendar')) {
         value.innerHTML = 'Выберите дату';
      } else if (value) {
         value.innerHTML = 'Выберите значение';
      }
   }
}


// Предназначен для формирования блока с переключателями в зависимости от значения другого поля
// Принимает параметры---------------------------------------------------------
// parent_input       Element : скрытый инпут со значением родительского поля
function handleDependentRadios(parent_input) {
   let dependency_inputs = radio_dependency.querySelectorAll(`input[data-when_change=${parent_input.name}]`);

   dependency_inputs.forEach(input => {
      // Все возможные значения для блока с переключателями
      let values = JSON.parse(input.value);

      // Берем нужные значения, по значению родительского поля
      let radio_values = values[parent_input.value][0];

      let dependent_row = document.querySelector(`[data-row_name=${input.dataset.target_change}]`);
      let dependent_radio = dependent_row.querySelector('.radio');

      if (dependent_radio) {
         let radio_body = dependent_radio.querySelector('.radio__body');
         let result_input = dependent_row.querySelector('.field-result');

         result_input.value = '';
         radio_body.innerHTML = '';

         // Для каждого значения создаем элемент переключателя
         radio_values.forEach(value => {
            let radio_item = createRadioItem(value);
            radio_body.appendChild(radio_item);
         });

         // Добавляем обработчики для переключателей
         initRadioItems(dependent_radio);
      }
   });
}

// Предназначен для удаления зависимых полей
// Принимает параметры-------------------------------
// parent_input         Element : скрытый инпут родительского поля
function hideDependentRows(parent_input) {
   let values = dependencies[parent_input.name];

   if (values) {
      // Получаем зависимые поля для значения в родительском поле
      let dependent_rows = dependencies[parent_input.name][parent_input.value];

      if (dependent_rows) {
         Object.keys(dependent_rows).forEach(row_name => {
            let dependent_row = document.querySelector(`[data-row_name="${row_name}"]`);
            dependent_row.dataset.inactive = 'true';

            removeRowValue(dependent_row);
         });
      }
   }
}





