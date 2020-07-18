document.addEventListener('DOMContentLoaded', () => {

   let cards = document.querySelectorAll('.card-form');
   let card_header;

   cards.forEach(card => {

      card_header = card.querySelector('.card-form__header');

      card_header.addEventListener('click', () => {
         // Раскрываем блок
         expandCard(card);
      });
   });

   let text_areas = document.querySelectorAll('textarea.application-input');

   text_areas.forEach(text_area => {
      // При изменении размера text area максимальная высота блока должна стать 100%
      text_area.addEventListener('mousedown', () => {
         changeParentCardMaxHeight(text_area, '100%');
      });

      // После изменении размера text area ставим максимальную высоту блока в пикселях для плавного сужения
      text_area.addEventListener('keypress', () => {
         changeParentCardMaxHeight(text_area);
      });
   });

});


// Предназначен для раскрытия или сужения блока анкеты
// Принимает параметры-------------------------------
// card       Element : блок, для раскрытия или сужения
function expandCard(card) {
   let card_arrow;
   let card_body;
   if (card) {
      card_arrow = card.querySelector('.card-form__icon-expand');
      card_body = card.querySelector('.card-form__body');

      //переворачиваем стрелку
      card_arrow.classList.toggle('arrow-down');
      card_arrow.classList.toggle('arrow-up');

      if (card_body.style.maxHeight) {
         // Сужаем блок
         card_body.style.maxHeight = null;
      } else {
         // Раскрываем блок
         changeParentCardMaxHeight(card_body);
      }
   }
}

// Предназначен для изменения максимальной высоты блока анкеты при изменении контенета
// Принимает параметры-------------------------------
// inner_element       Element : элемент, по которому получаем родительский блок анкеты
// value               string : значение высоты
function changeParentCardMaxHeight(inner_element, value) {
   let card_body = inner_element.closest('.card-form__body');

   if (value) {
      card_body.style.maxHeight = value;
   } else {
      card_body.style.maxHeight = card_body.scrollHeight + 'px';
   }
}