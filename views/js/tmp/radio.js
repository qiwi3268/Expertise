document.addEventListener('DOMContentLoaded', () => {
  /* let radio_blocks = document.querySelectorAll('.radio');

   // Добавляем обработку переключателей для каждого блока с переключателями
   radio_blocks.forEach(radio_elem => {
      initRadioItems(radio_elem);
   });*/

   initializeRadio(document);

});

function initializeRadio(block) {
   let radio_blocks = block.querySelectorAll('.radio');

   // Добавляем обработку переключателей для каждого блока с переключателями
   radio_blocks.forEach(radio_elem => {
      initRadioItems(radio_elem);
   });
}

// Предназначен для добавления обработчиков для переключателей
// Принимает параметры-------------------------------
// radio_elem         Element : блок с переключателями
function initRadioItems(radio_elem) {
   let parent_field = radio_elem.closest('.field');
   let body = radio_elem.querySelector('.radio__body');

   if (parent_field && body) {
      // Обязателен ли выбор хотя бы одного элемента
      let required = radio_elem.dataset.required === 'true';

      // Скрытый инпут, в который записывается json с выбранными элементами
      let result_input = parent_field.querySelector('.field-result');
      let multiple = radio_elem.dataset.multiple === 'true';
      let items = radio_elem.querySelectorAll('.radio__item');

      let is_changed;

      items.forEach(item => {
         item.addEventListener('click', () => {

            if (item.classList.contains('selected')) {
               is_changed = removeSelectedItem(item, required);
            } else if (multiple) {
               is_changed = addSelectedItem(item);
            } else {
               is_changed = changeSelectedItem(item);
            }

            if (is_changed) {
               // Записываем в результат json с id выбранных элементов
               result_input.value = getRadioResult(body, multiple, required);
               // handleDependentBlocks(result_input);
               DependenciesHandler.handleDependencies(result_input);

               if (required) {
                  validateCard(result_input.closest('.card-form'));
               }
            }
         });
      });
   }
}

// Предназначен для создания элемента переключателя
// Принимает параметры-------------------------------
// value         string : текст переключателя
// Возвращает параметры------------------------------
// item         Element : элемент переключателя
function createRadioItem(value) {
   let item = document.createElement('DIV');
   item.classList.add('radio__item', 'col');
   item.dataset.id = value.id;

   let icon = document.createElement('I');
   icon.classList.add('far', 'fa-square', 'radio__icon');

   let text = document.createElement('SPAN');
   text.classList.add('radio__text');
   text.innerHTML = value.name;

   item.appendChild(icon);
   item.appendChild(text);

   return item;
}

// Предназначен для добавления отметки выбора переключателя
// Принимает параметры-------------------------------
// radio_item         Element : элемент переключателя
function addSelectedItem(radio_item) {
   radio_item.classList.add('selected');

   // Меняем на иконку с галочкой
   let radio_icon = radio_item.querySelector('.radio__icon');
   radio_icon.classList.remove('fa-square');
   radio_icon.classList.add('fa-check-square');

   return true;
}


// Предназначен для снятия отметки выбора переключателя
// Принимает параметры-------------------------------
// radio_item       Element : элемент переключателя
// required         boolean : обязателен ли хотя бы один выбранный элемент
function removeSelectedItem(radio_item, required) {
   let selected_items = radio_item.parentElement.querySelectorAll('.selected');

   if (!required || selected_items.length > 1) {
      radio_item.classList.remove('selected');

      // Меняем на пустую иконку
      let radio_icon = radio_item.querySelector('.radio__icon');
      radio_icon.classList.remove('fa-check-square');
      radio_icon.classList.add('fa-square');
      return true;
   }

   return false;
}

// Предназначен для смены выбранного элемента блока переключателя
// Принимает параметры-------------------------------
// radio_item         Element : переключатель, который становится выбранным
function changeSelectedItem(radio_item) {
   let items = radio_item.parentElement;
   let selected_item = items.querySelector('.selected');

   if (!selected_item) {
      return addSelectedItem(radio_item);
   } else if (selected_item.dataset.id === radio_item.dataset.id) {
      return false;
   } else {
      removeSelectedItem(selected_item, false);
      return addSelectedItem(radio_item);
   }
}

// Предназначен для получения json с id выбранных элементов блока переключателей
// Принимает параметры-------------------------------
// radio_body      Element : блок переключателей
// Возвращает параметры------------------------------
// result             JSON : json с id выбранных элементов
function getRadioResult(radio_body, multiple) {
   let result = [];
   let selected_items = radio_body.querySelectorAll('.selected');

   selected_items.forEach(item => {
      result.push(item.dataset.id)
   });

   return multiple ? JSON.stringify(result) : result[0];
}