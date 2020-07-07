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

   let text_area = document.querySelector('.application-text-area');

   text_area.addEventListener('mousedown', () => {
      changeParentCardMaxHeight(text_area, '100%');
   });

   text_area.addEventListener('keypress', () => {
      changeParentCardMaxHeight(text_area);
   });

});