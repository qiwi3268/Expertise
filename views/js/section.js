// todo вынести в отдельный файл

document.addEventListener('DOMContentLoaded', () => {
   let cards = document.querySelectorAll('.card');
   cards.forEach(card => {
      let card_header = card.querySelector('.card-expand');
      card_header.addEventListener('click', () => expandCard(card));
   });

   handleTextAreasResize();
});

// Сразу делаем блоки раскрытыми без анимации развертывания
window.addEventListener('load', () => {
   let cards_view = document.querySelectorAll('.card-body.expanded');
   cards_view.forEach(card_body => {
      changeParentCardMaxHeight(card_body);
      card_body.classList.remove('expanded');
   });
});

// Предназначен для обавления событие изменения размера текстового поля для нормального отображения размера блока
function handleTextAreasResize () {
   let text_areas = document.querySelectorAll('textarea');
   text_areas.forEach(text_area => {
      text_area.addEventListener('mousedown', () => {
         // При изменении размера текстового поля для расширения блока
         // ставим максимальную высоту в процентах
         changeParentCardMaxHeight(text_area, '100%');

         // При изменении размера текстового поля добавляем разовый обработчик,
         // который при отпускании мыши ставит высоту блока в пикселях
         document.addEventListener(
            'mouseup',
            expandListener.bind(this, text_area),
            {
               once: true,
            });
      });
   });
}

// Предназначен для раскрытия или сужения блока анкеты
// Принимает параметры-------------------------------
// card       Element : блок, для раскрытия или сужения
function expandCard (card) {
   let card_body = card.querySelector('.card-body');
   let card_arrow = card.querySelector('.card-icon');

   //переворачиваем стрелку
   if (card_arrow) {
      card_arrow.classList.toggle('arrow-down');
      card_arrow.classList.toggle('arrow-up');
   }
   if (card_body.style.maxHeight) {
      // Сужаем блок
      card_body.style.maxHeight = null;
   } else {
      // Раскрываем блок
      changeParentCardMaxHeight(card_body);
   }

}

// Предназначен для изменения максимальной высоты блока анкеты при изменении контенета
// Принимает параметры-------------------------------
// inner_element       Element : элемент, по которому получаем родительский блок анкеты
// value               string : значение высоты
function changeParentCardMaxHeight (inner_element, value = null) {
   let card_body = inner_element.closest('.card-body');

   // card_body.style.transition = `${card_body.scrollHeight / 2500 + 0.2}s`;
   // card_body.style.transition = `${card_body.scrollHeight / 2500 + 0.2}s cubic-bezier(0.65, 0, 0.35, 1)`;

   if (!value) {
      card_body.style.maxHeight = card_body.scrollHeight + 'px';
   } else {
      card_body.style.maxHeight = value;
   }

}

function resizeCard (inner_element) {
   let card_body = inner_element.closest('.card-body');

   if (card_body.style.maxHeight) {
      // Раскрываем блок
      changeParentCardMaxHeight(card_body);
   }
}

// Предназначен для обработки события отпускания кнопки мыши
// при изменении размера текстового поля
// Принимает параметры-------------------------------
// text_area       Element : текстовое поле, которое изменяется
function expandListener (text_area) {
   changeParentCardMaxHeight(text_area);
}