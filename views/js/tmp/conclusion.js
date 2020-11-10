document.addEventListener('DOMContentLoaded', () => {


   let sections = document.querySelectorAll('.hierarchy__section');
   sections.forEach(section => {
      section.addEventListener('click', () => {
         let number = section.dataset.number;

         let card = document.querySelector(`.application-form__card[data-number='${number}']`);
         if (card) {
            card.scrollIntoView({block: "start", behavior: "smooth"});

            let card_elem = card.closest('.card');
            if (card_elem) {
               let body = card_elem.querySelector('.card-body');
               if (body) {
                  if (!body.style.maxHeight) {
                     expandCard(card_elem);
                  }
               }

            }
         }



      })
   });



});