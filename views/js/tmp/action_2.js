
document.addEventListener('DOMContentLoaded', () => {

   let submit_btn = document.querySelector('[data-action_submit]');
   submit_btn.addEventListener('click', () => {

      let action_path = window.location.pathname;
      let url = new URL(window.location.href);
      let id_document = url.searchParams.get('id_document');

      let form_data = new FormData();
      form_data.append('path_name', action_path);
      form_data.append('id_document', id_document);


      getAssignedExpertsJSON();

      console.log(new Map(form_data));

      XHR(
         'post',
         '/home/API_action_executor',
         form_data,
         null,
         'json'
      )
         .then(response => {

            // console.log('qwe');
            // console.log(response);

         })
         .catch(exc => {

            console.log('ошибка' + exc);

         });
   });

   let add_section_btn = document.querySelector('.assignment__add');
   let additional_sections = document.querySelector('.assignment__additional');

   add_section_btn.addEventListener('click', () => {

      if (additional_sections.dataset.active !== 'true') {
         additional_sections.dataset.active = 'true';
         let header = additional_sections.querySelector('.assignment__header');
         header.appendChild(add_section_btn);
      }

   });

});

function createSection () {

}

function getAssignedExpertsJSON () {
   DropArea.drop_areas.forEach(area => {
      console.log(area.getResult());
   })
}
