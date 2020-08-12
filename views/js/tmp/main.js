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


function noScroll() {
   window.scrollTo(0, 0);
}

function disableScroll() {
   // document.body.style.position = 'fixed';
   // document.body.style.top = `-${window.scrollY}px`;
   document.body.classList.add("stop-scrolling");
}

function enableScroll() {
   // document.body.style.position = '';
   // document.body.style.top = '';
   document.body.classList.remove("stop-scrolling");
}

