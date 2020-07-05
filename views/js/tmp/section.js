document.addEventListener('DOMContentLoaded', () => {

   let cards = document.querySelectorAll('.card-form');
   let card_header;
   let card_arrow;
   let card_body;

   cards.forEach(card => {

      card_header = card.querySelector('.card-form__header');

      card_header.addEventListener('click', () => {

         // todo исправить
         expandCard(card);
         //раскрываем блок

         /*if (card_body.style.maxHeight) {
            card_body.style.maxHeight = null;
         } else {
            card_body.style.maxHeight = card_body.scrollHeight + "px";
         }*/
      });
   });


   let text_area = document.querySelector('.application-text-area');

   text_area.addEventListener('mousedown', () => {
      console.log('asd');
      // let card_body = text_area.closest('.card-form__body');
      // card_body.style.maxHeight = '100%';
      changeParentCardMaxHeight(text_area, '100%');
   });



   text_area.addEventListener('mouseup', () => {
      console.log('qwe');
      //let card_body = text_area.closest('.card-form__body');
      //card_body.style.maxHeight = card_body.scrollHeight + "px";

      changeParentCardMaxHeight(text_area);
   });

   /*text_area.addEventListener('blur', () => {
      console.log('zxc');
      //let card_body = text_area.closest('.card-form__body');
      //card_body.style.maxHeight = card_body.scrollHeight + "px";

      changeParentCardMaxHeight(text_area);
   });*/



});