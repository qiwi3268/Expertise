document.addEventListener('DOMContentLoaded', () => {
   // Блоки, для которых нужна валидация
   let pattern_rows = document.querySelectorAll('[data-pattern]');

   pattern_rows.forEach(row => {
      let input = row.querySelector('.body-card__input');
      let pattern = row.dataset.pattern;

      input.addEventListener('keyup', () => {
         validateRow(input, pattern);
      });

      input.addEventListener('blur', () => {
         validateRow(input, pattern);
      });
   });
});

// Предназначен для валидации модального окна
// Принимает параметры-------------------------------
// modal         Modal : объект модального окна
function validateModal(modal) {
   let row = modal.parent;
   let row_value = row.querySelector('.body-card__select');
   let error = row.querySelector('.body-card__error');

   // Если значение обязательно
   if (row.dataset.required === 'true') {
      // Если не выбрано значение
      if (!modal.result_input.value) {
         row_value.classList.add('invalid-field');
         error.classList.add('active');
      } else {
         row_value.classList.remove('invalid-field');
         error.classList.remove('active');
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
         regex = '';
         error_message = '';
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
   let error_message = input.nextElementSibling;

   if (!value.match(regex)) {
      input.classList.add('invalid-field');
      error_message.classList.add('active');

      // Если поле непустое
      if (value) {
         error_message.innerHTML = message;
      } else {
         error_message.innerHTML = 'Поле обязательно для заполнения';
      }
   } else {
      input.classList.remove('invalid-field');
      error_message.classList.remove('active');
      validateCard(input.closest('.card-form'));
   }

}

function validateCard(card) {
   let card_name = card.dataset.type;
   let is_valid = isValidCard(card);
   let sidebar_item = document.querySelector(`.sidebar-form__row[data-card=${card_name}]`);

   setSidebarItemState(sidebar_item, is_valid);
}

function isValidCard(card) {
   let required_rows = card.querySelectorAll('[data-required="true"]');

   for (let i = 0; i < required_rows.length; i++) {
      let row_value = required_rows[i].querySelector('.body-card__result');

      if (!row_value.value) {
         return false;
      }
   }

   return true;
}

