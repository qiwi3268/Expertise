
document.addEventListener('DOMContentLoaded', () => {

   let submit_btn = document.querySelector('[data-action_submit]');
   submit_btn.addEventListener('click', () => {

      let action_path = window.location.pathname;
      let url = new URL(window.location.href);
      let id_document = url.searchParams.get('id_document');


   });

});


