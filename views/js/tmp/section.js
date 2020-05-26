document.addEventListener('DOMContentLoaded', () => {

   let cards = document.querySelectorAll('.card-form');
   let card_header;
   let card_arrow;
   let card_body;

   cards.forEach(card => {

      card_header = card.querySelector('.card-form__header');

      card_header.addEventListener('click', () => {
         card_arrow = card.querySelector('.card-form__icon');
         card_body = card.querySelector('.card-form__body');

         //переворачиваем стрелку
         card_arrow.classList.toggle('arrow-down');
         card_arrow.classList.toggle('arrow-up');

         //раскрываем блок
         if (card_body.style.maxHeight) {
            card_body.style.maxHeight = null;
            // card_body.style.overflow = 'hidden';
         } else {
            card_body.style.maxHeight = card_body.scrollHeight + "px";
            // card_body.style.overflow = 'visible';
         }
      });
   });
});