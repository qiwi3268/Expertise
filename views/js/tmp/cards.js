// Массив хранящий зависимости полей
let dependencies;
let radio_dependency;

document.addEventListener('DOMContentLoaded', () => {
   dependencies = JSON.parse(document.querySelector('.row-dependencies').value);
   radio_dependency = document.querySelector('.radio__content-change-logic');

   handleClearFieldButtons();
});

function handleClearFieldButtons() {
   let clear_buttons = document.querySelectorAll('.body-card__icon-clear');

   clear_buttons.forEach(button => {
      button.addEventListener('click', () => {
         let parent_row = button.closest('.body-card__row');
         let default_value = 'Выберите значение';

         if (parent_row.dataset.pattern === 'date') {
            default_value = 'Выберите дату';
         }

         let row_result = parent_row.querySelector('.body-card__result');
         // Удаляем зависимые поля
         hideDependentRows(row_result);
         row_result.value = '';

         let parent_select = parent_row.querySelector('.body-card__select');
         parent_select.classList.remove('filled');

         let row_value = parent_row.querySelector('.field-value');
         row_value.innerHTML = default_value;
      });

   });

}

// Предназначен для добавления или удаления блоков, зависящих от значения поля на входе
// Принимает параметры-------------------------------------------
// parent_input  Element : скрытый инпут со значением родительского поля
function handleDependentRows(parent_input) {
   // Получаем массив с зависимостями всех значений родительского поля
   let values = dependencies[parent_input.name];

   if (values) {
      // Получаем зависимые поля для значения в родительском поле
      let dependent_rows = dependencies[parent_input.name][parent_input.value];

      if (dependent_rows) {
         Object.keys(dependent_rows).forEach(row_name => {
            let dependent_row = document.querySelector(`[data-row_name="${row_name}"]`);
            let is_display;

            if (dependent_row) {
               // Определяем показать или скрыть блок
               is_display = dependent_rows[row_name];

               if (!is_display) {
                  dependent_row.dataset.inactive = 'true';
               } else {
                  dependent_row.dataset.inactive = 'false';
               }

               removeRowValue(dependent_row);
            }
         });
      }
   }

   handleDependentRadios(parent_input);
}

// Предназначен для удаления значении в поле
// Принимает параметры-------------------------------
// row         Element : элемент поля
function removeRowValue(row) {
   // Удаляем записанное значение в зависимом поле
   row.querySelector('.body-card__result').value = '';

   let select = row.querySelector('.body-card__select');
   if (select) {
      select.classList.remove('filled');
   }

   let value = row.querySelector('.field-value');
   if (value) {
      value.value = '';
      value.innerHTML = '';
   }

   // Если зависимое поле - дата, удаляем отображаемую дату
   let dependent_date = row.querySelector('.modal-calendar__value');
   if (dependent_date) {
      dependent_date.innerHTML = 'Выберите дату';
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
      let radio_values = values[parent_input.value];

      let dependent_row = document.querySelector(`[data-row_name=${input.dataset.target_change}]`);
      let dependent_radio = dependent_row.querySelector('.radio');

      if (dependent_radio) {
         let radio_body = dependent_radio.querySelector('.radio__body');
         let result_input = dependent_row.querySelector('.body-card__result');

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



