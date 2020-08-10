// Массив хранящий зависимости полей
let dependencies;
let radio_dependency;

let block_dependencies;

document.addEventListener('DOMContentLoaded', () => {
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
         handleDependentBlocks(row_result);

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
function handleDependentBlocks(parent_input) {
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
   });

   let parent_card_body = block.closest('.card-form__body');
   if (parent_card_body.style.maxHeight) {
      changeParentCardMaxHeight(block);
   }
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




