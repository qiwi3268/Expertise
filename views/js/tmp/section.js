document.addEventListener('DOMContentLoaded', () => {

   let cards = document.querySelectorAll('.card-form');
   let card_header;

   cards.forEach(card => {

      card_header = card.querySelector('.card-form__header');

      card_header.addEventListener('click', () => {
         //раскрываем блок
         expandCard(card);
      });
   });

   let text_areas = document.querySelectorAll('.application-text-area');

   text_areas.forEach(text_area => {
      text_area.addEventListener('mousedown', () => {
         changeParentCardMaxHeight(text_area, '100%');
      });

      text_area.addEventListener('keypress', () => {
         changeParentCardMaxHeight(text_area);
      });
   });

});

function expandCard(card) {
   let card_arrow;
   let card_body;
   if (card) {
      card_arrow = card.querySelector('.card-form__icon-expand');
      card_body = card.querySelector('.card-form__body');

      //переворачиваем стрелку
      card_arrow.classList.toggle('arrow-down');
      card_arrow.classList.toggle('arrow-up');

      // Раскрываем блок
      // todo плавное сужение
      if (card_body.style.maxHeight) {
         card_body.style.maxHeight = null;
      } else {
         changeParentCardMaxHeight(card_body);
      }

   }
}

function changeParentCardMaxHeight(inner_element, value) {
   let card_body = inner_element.closest('.card-form__body');

   if (value) {
      card_body.style.maxHeight = value;
   } else {
      card_body.style.maxHeight = card_body.scrollHeight + 'px';
   }
}