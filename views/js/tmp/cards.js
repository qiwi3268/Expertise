// Массив хранящий зависимости блоков
let radio_dependency;
let block_dependencies;
let require_dependencies;

document.addEventListener('DOMContentLoaded', () => {
   radio_dependency = document.querySelector('.radio__content-change-logic');
   block_dependencies = JSON.parse(document.getElementById('block_dependencies').value);
   require_dependencies = JSON.parse(document.getElementById('require_dependencies').value);

   handleClearFieldButtons();
});

// Предназначен для обработки кнопок удаления значений полей
function handleClearFieldButtons() {
   let clear_buttons = document.querySelectorAll('.body-card__icon-clear');

   clear_buttons.forEach(button => {
      button.addEventListener('click', () => {
         let parent_field = button.closest('.field');

         let field_result = parent_field.querySelector('.field-result');
         // Удаляем зависимые поля
         DependenciesHandler.handleDependencies(field_result);
         // handleDependentBlocks(field_result);

         let parent_select = parent_field.querySelector('.modal-select');
         if (field_result.value) {

            removeRowValue(parent_field);

            let related_modal = parent_field.querySelector('.modal');
            if (related_modal) {
               let modal = getModalBySelect(parent_select);
               modal.clearRelatedModals();
               validateModal(modal);
            }

            validateCard(parent_field.closest('.card-form'));
         }
      });
   });
}


// Предназначен для очищения полей в блоке
// Принимает параметры-------------------------------
// block         Element : очищаемый блок
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
function removeRowValue(field) {
   // Удаляем записанное значение в зависимом поле
   field.querySelector('.field-result').value = '';

   let select = field.querySelector('.field-select');
   if (select) {
      select.classList.remove('filled');

      let value = field.querySelector('.field-value');
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
         // handleDependentBlocks(result_input);

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




