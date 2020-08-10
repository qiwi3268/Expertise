document.addEventListener('DOMContentLoaded', () => {
   // Блоки, для которых нужна валидация
   let pattern_rows = document.querySelectorAll('[data-pattern]');

   pattern_rows.forEach(row => {
      let input = row.querySelector('.body-card__input');

      if (input) {
         let pattern = row.dataset.pattern;
         let is_required = row.dataset.required;

         input.addEventListener('keyup', () => {
            validateRow(input, pattern);
         });

         input.addEventListener('blur', () => {
            validateRow(input, pattern);

            if (is_required === 'true') {
               validateCard(input.closest('.card-form'));
            }
         });
      }

   });
});

// Предназначен для валидации модального окна
// Принимает параметры-------------------------------
// modal         Modal : объект модального окна
function validateModal(modal) {
   let row = modal.element.closest('.field');
   let row_value = row.querySelector('.modal-select');
   let error = row.querySelector('.field-error');

   if (row.dataset.required === 'true') {
      // Если не выбрано значение
      if (!modal.result_input.value) {
         row_value.classList.add('invalid');
         error.classList.add('active');
         changeParentCardMaxHeight(row);
      } else {
         row_value.classList.remove('invalid');
         error.classList.remove('active');
      }

      let parent_card = row_value.closest('.card-form');
      if (parent_card) {
         validateCard(row_value.closest('.card-form'));
      }
   }
}

// Предназначен для валидации поля для ввода
// Принимает параметры-------------------------------
// input         Element : поле для валидации
// pattern        string : тип поля
function validateRow(input, pattern) {
   let regex;
   let error_message;
   switch (pattern) {
      case 'number' :
         regex = '^\\d+$';
         error_message = 'Значение должно быть числом';
         break;
      case 'cost' :
         regex = '^\\d+([.,]\\d{1,3})?$';
         error_message = 'Значение должно быть целым или десятичным неотрицательным числом ' +
            'с 3 знаками после запятой';
         break;
      case 'email' :
         regex = '\\S+@\\S+\\.\\S+';
         error_message = 'Введите корректный адрес электронной почты';
         break;
      case 'kpp' :
         regex = '^\\d{9}}?$';
         error_message = 'Значение должно быть числом из 9 символов';
         break;
      default :
         regex = '^\\S+\.*$';
         error_message = 'Значение должно начинаться с непробельного символа';
   }

   validateInput(input, regex, error_message);
}

// Предназначен для валидации значения в поле
// Принимает параметры-------------------------------
// input         Element : поле для валидации
// regex          string : регулярное выражение, по которому проверяется значение
// message        string : сообщение с ошибкой для отображения
function validateInput(input, regex, message) {
   let value = input.value;
   let parent_row = input.closest('.field');
   let error_element = parent_row.querySelector('.field-error');

   let is_required = parent_row.dataset.required === 'true';
   let is_invalid = !value.match(regex) && (is_required || value);

   if (is_invalid) {
      input.classList.add('invalid');
      error_element.classList.add('active');

      // Если поле непустое
      if (value) {
         error_element.innerHTML = message;
      } else {
         error_element.innerHTML = 'Поле обязательно для заполнения';
      }

      changeParentCardMaxHeight(parent_row);
   } else {
      input.classList.remove('invalid');
      error_element.classList.remove('active');
   }

}


// Предназначен для валидации блока анкеты и отображения состояния в связанном элементе сайдбара
// Принимает параметры-------------------------------
// card         Element : блок для валидации
function validateCard(card) {
   let card_name = card.dataset.type;
   let is_valid = isValidCard(card);

   let sidebar_item = document.querySelector(`.sidebar-form__row[data-card=${card_name}]`);

   // Отображаем состояние проверки в связанном элементе сайдбара
   setSidebarItemState(sidebar_item, is_valid);
}


// Предназначен для валидации блока анкеты и отображения состояния в связанном элементе сайдбара
// Принимает параметры-------------------------------
// card         Element : блок для валидации
// Возвращает параметры------------------------------
// is_valid     boolean : заполнен ли блок
function isValidCard(card) {
   let required_rows = card.querySelectorAll('.field[data-required="true"]');
   let row_value;
   let is_valid = true;

   // Для всех обязательных полей, проверяем наличие значений
   required_rows.forEach(row => {
      if (row.dataset.inactive !== 'true') {
         row_value = row.querySelector('.field-result');

         if (!row_value.value) {
            is_valid = false;
         }
      }
   });

   return is_valid;
}

