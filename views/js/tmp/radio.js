document.addEventListener('DOMContentLoaded', () => {
    let radio_blocks = document.querySelectorAll('.radio');

    // Добавляем обработку переключателей для каждого блока с переключателями
    radio_blocks.forEach(radio_elem => {
        initRadioItems(radio_elem);
    });

});

// Предназначен для добавления обработчиков для переключателей
// Принимает параметры-------------------------------
// radio_elem         Element : блок с переключателями
function initRadioItems(radio_elem) {
    let parent_row = radio_elem.closest('.body-card__row');
    let body = radio_elem.querySelector('.radio__body');

    if (parent_row && body) {
        // Обязателен ли выбор хотя бы одного элемента
        let required = parent_row.dataset.required === 'true';

        // Скрытый инпут, в который записывается json с выбранными элементами
        let result_input = parent_row.querySelector('.body-card__result');
        let multiple = radio_elem.dataset.multiple === 'true';
        let items = radio_elem.querySelectorAll('.radio__item');

        items.forEach(item => {
            item.addEventListener('click', () => {

                if (item.classList.contains('selected')) {
                    removeSelectedItem(item, required);
                } else if (multiple) {
                    addSelectedItem(item);
                } else {
                    changeSelectedItem(item);
                }

                // Записываем в результат json с id выбранных элементов
                result_input.value = getRadioResult(body, multiple);
                validateCard(result_input.closest('.card-form'));
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
    item.classList.add('radio__item');
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
    }
}

// Предназначен для смены выбранного элемента блока переключателя
// Принимает параметры-------------------------------
// radio_item         Element : переключатель, который становится выбранным
function changeSelectedItem(radio_item) {
    let items = radio_item.parentElement;
    let selected_item = items.querySelector('.selected');

    if (selected_item) {
        removeSelectedItem(selected_item, false);
    }

    addSelectedItem(radio_item);
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