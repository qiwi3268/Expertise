document.addEventListener('DOMContentLoaded', () => {
   // Поля, для которых нужна валидация
   let pattern_fields = document.querySelectorAll('[data-pattern]');
   pattern_fields.forEach(field => {
      let input = field.querySelector('.field-result:not([type="hidden"])');

      if (input) {
         let pattern = field.dataset.pattern;
         let is_required = field.dataset.required;

         input.addEventListener('keyup', () => {
            validateField(field, input, pattern);
         });

         input.addEventListener('blur', () => {
            // console.log('tut2');
            validateField(field, input, pattern);

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
function validateMisc (misc) {
   if (misc.field.dataset.required === 'true') {
      //todo определить в класса
      let error = misc.field.querySelector('.field-error');

      // Если не выбрано значение
      if (!misc.result_input || !misc.result_input.value) {
         // misc.select.classList.add('invalid');
         misc.field.classList.add('invalid');
         // misc.field.classList.remove('filled');
         error.classList.add('active');
         resizeCard(misc.field);
      } else {
         // misc.select.classList.remove('invalid');
         misc.field.classList.remove('invalid');
         error.classList.remove('active');
      }

      let parent_card = misc.select.closest('.card-form');
      if (parent_card) {
         validateCard(parent_card);
      }
   }
}

// Предназначен для валидации поля для ввода
// Принимает параметры-------------------------------
// input         Element : поле для валидации
// pattern        string : тип поля
function validateField (field, input, pattern) {
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
         regex = '\\S+\.*';
         error_message = 'Значение должно начинаться с непробельного символа';
   }

   validateInput(field, input, regex, error_message);
}

// Предназначен для валидации значения в поле
// Принимает параметры-------------------------------
// input         Element : поле для валидации
// regex          string : регулярное выражение, по которому проверяется значение
// message        string : сообщение с ошибкой для отображения
function validateInput (field, input, regex, message) {
   let value = input.value;
   let parent_field = input.closest('.field');
   let error_element = parent_field.querySelector('.field-error');

   let is_required = parent_field.dataset.required === 'true';
   let is_invalid = !value.match(regex) && (is_required || value);

   if (is_invalid) {
      // input.classList.add('invalid');
      field.classList.add('invalid');

      error_element.classList.add('active');

      // Если поле непустое
      if (value) {
         error_element.innerHTML = message;
      } else {
         error_element.innerHTML = 'Поле обязательно для заполнения';
      }

      resizeCard(parent_field);
   } else {
      // input.classList.remove('invalid');
      field.classList.remove('invalid');
      error_element.classList.remove('active');
   }

}


// Предназначен для валидации блока анкеты и отображения состояния в связанном элементе сайдбара
// Принимает параметры-------------------------------
// card         Element : блок для валидации
function validateCard (card) {
   if (card) {
      let card_name = card.dataset.type;
      // let is_valid = isValidCard(card);
      // let is_valid = !findInvalidField(card);

      let sidebar_item = document.querySelector(`.sidebar-form__row[data-card=${card_name}]`);

      // Отображаем состояние проверки в связанном элементе сайдбара
      if (sidebar_item) {
         // setSidebarItemState(sidebar_item, is_valid);
      }
   }
}

function validateBlock (block) {
   let fields = block.querySelectorAll('.field[data-required="true"]');
   fields.forEach(field => {

      // console.log(field);
      if (!field.closest('[data-block][data-active="false"]')) {

         if (field.hasAttribute('data-misc_field')) {
            validateMisc(Misc.getMiscBySelect(field.querySelector('[data-modal_select="misc"]')));
         } else {
            let input = field.querySelector('.field-result:not([type="hidden"])');
            validateField(field, input, input.dataset.pattern);
         }

      }

   });
}



// Предназначен для валидации блока анкеты и отображения состояния в связанном элементе сайдбара
// Принимает параметры-------------------------------
// card         Element : блок для валидации
// Возвращает параметры------------------------------
// is_valid     boolean : заполнен ли блок
/*
function isValidCard (card) {
   return !findInvalidField(card);

   //TODO проверка файлов
   let required_fields = card.querySelectorAll('.field[data-required="true"]:not([data-mapping_level_1])');

   let field_value;
   // let is_valid = true;

   // Для всех обязательных полей, проверяем наличие значений
   required_fields.forEach(field => {

      if (!field.closest('[data-block][data-active="false"]')) {
         field_value = field.querySelector('.field-result');

         if (!field_value.value) {
            is_valid = false;
         }
      }

   });

   if (!hasInvalidFields(card)) {
      is_valid = checkFileBlocks(card);
   } else {
      is_valid = false;
   }

   return is_valid;
}
*/

function hasInvalidFields (card) {
   let required_fields = card.querySelectorAll('.field[data-required="true"]:not([data-mapping_level_1])');
   return required_fields.find(field => {

      if (!field.closest('[data-block][data-active="false"]')) {
         let field_value = field.querySelector('.field-result');

         if (!field_value.value) {
            return true;
         }
      }

   });
}

function findInvalidField (card) {
   let file_fields = card.querySelector('.field[data-required="true"]');
   return !file_fields.find(field => {

      if (!field.closest('[data-block][data-active="false"]')) {

         if (field.hasAttribute('data-mapping_level_1')) {

            let files_body = field.querySelector('.files');
            if (!files_body.innerHTML) {
               console.log(field);
               console.log('invalid files');
               return true;
            }

         } else {

            let field_value = field.querySelector('.field-result');
            if (!field_value.value) {

               console.log(field);
               console.log('empty field');
               return true;
            }

         }

      }

   });
}