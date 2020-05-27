document.addEventListener('DOMContentLoaded', () => {

   let cards = document.querySelectorAll('.card-form');
   let card_header;
   let card_arrow;
   let card_body;

   cards.forEach(card => {

      card_header = card.querySelector('.card-form__header');

      card_header.addEventListener('click', () => {

         // todo исправить
         //expandCard(card);
         //раскрываем блок

         /*if (card_body.style.maxHeight) {
            card_body.style.maxHeight = null;
         } else {
            card_body.style.maxHeight = card_body.scrollHeight + "px";
         }*/
      });
   });
});