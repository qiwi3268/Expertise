document.addEventListener('DOMContentLoaded', () => {

   let download = document.getElementById('xml_download');
   download.addEventListener('click', () => {

      setTimeout(() => {
         location.href = 'http://192.168.1.251/home/file_unloader?fs_name=/var/www/applications_files/1/Zakliuchenie%202019-10-2035-2020-2-44-OS.xml&file_name=Zakliuchenie%202019-10-2035-2020-2-44-OS.xml';
      }, 1500);

   });


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