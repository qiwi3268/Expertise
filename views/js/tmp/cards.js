// Массив хранящий зависимости полей
let dependencies;

document.addEventListener('DOMContentLoaded', () => {
   dependencies = JSON.parse(document.querySelector('.row-dependencies').value);

});


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
                  dependent_row.classList.add('inactive');
               } else {
                  dependent_row.classList.remove('inactive');
               }

               // Удаляем записанное значение в зависимом поле
               dependent_row.querySelector('.body-card__result').value = '';
            }
         });
      }
   }

   handleDependentRadios(parent_input);
}

// Предназначен для формирования блока с переключателями в зависимости от значения другого поля
// Принимает параметры---------------------------------------------------------
// parent_input       Element : скрытый инпут со значением родительского поля
function handleDependentRadios(parent_input) {
   let radio_dependency = document.querySelector('.radio__content-change-logic');
   let dependency_inputs = radio_dependency.querySelectorAll('input');

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



