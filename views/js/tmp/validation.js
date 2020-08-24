document.addEventListener('DOMContentLoaded', () => {
   // Поля, для которых нужна валидация
   let pattern_fields = document.querySelectorAll('[data-pattern]');

   pattern_fields.forEach(field => {
      let input = field.querySelector('.body-card__input');

      if (input) {
         let pattern = field.dataset.pattern;
         let is_required = field.dataset.required;

         input.addEventListener('keyup', () => {
            validateField(input, pattern);
         });

         input.addEventListener('blur', () => {
            validateField(input, pattern);

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
   let parent_field = modal.element.closest('.field');
   let field_value = parent_field.querySelector('.modal-select');
   let error = parent_field.querySelector('.field-error');

   if (parent_field.dataset.required === 'true') {
      // Если не выбрано значение
      if (!modal.result_input.value) {
         field_value.classList.add('invalid');
         error.classList.add('active');
         changeParentCardMaxHeight(parent_field);
      } else {
         field_value.classList.remove('invalid');
         error.classList.remove('active');
      }

      let parent_card = field_value.closest('.card-form');
      if (parent_card) {
         validateCard(field_value.closest('.card-form'));
      }
   }
}

// Предназначен для валидации поля для ввода
// Принимает параметры-------------------------------
// input         Element : поле для валидации
// pattern        string : тип поля
function validateField(input, pattern) {
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
         regex = '^\\d{9}$';
         error_message = 'Значение должно быть числом из 9 символов';
         break;
      case 'ogrn' :
         regex = '^\\d{13}$';
         error_message = 'Значение должно быть числом из 13 символов';
         break;
      case 'inn' :
         regex = '^\\d{10}$\|^d{12}$';
         error_message = 'Значение должно быть числом из 10 или 12 символов';
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
   let parent_field = input.closest('.field');
   let error_element = parent_field.querySelector('.field-error');

   let is_required = parent_field.dataset.required === 'true';
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

      changeParentCardMaxHeight(parent_field);
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
   //TODO проверка файлов
   let required_fields = card.querySelectorAll('.field[data-required="true"]:not([data-mapping_level_1])');
   let field_value;
   let is_valid = true;

   // Для всех обязательных полей, проверяем наличие значений
   required_fields.forEach(row => {
      if (row.dataset.inactive !== 'true') {
         field_value = row.querySelector('.field-result');

         if (!field_value.value) {
            is_valid = false;
         }
      }
   });

   return is_valid;
}

