document.addEventListener('DOMContentLoaded', () => {

   // Меняем размер раскрытых блоков при изменении размера страницы
   window.addEventListener('resize', () => {
      let cards = document.querySelectorAll('.card-form__body');

      cards.forEach(card_body => {
         if (card_body.style.maxHeight) {
            changeParentCardMaxHeight(card_body);
         }
      });

   });

});

