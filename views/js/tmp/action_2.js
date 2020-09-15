
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

      let leading_expert;
      let general_part_experts;

      console.log(new Map(form_data));
      // console.log(getAssignedExpertsJSON());

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

   // let add_section_btn = document.querySelector('.assignment__add');
   let add_section_btn = document.getElementById('add_section');
   // let additional_sections = document.querySelector('.assignment__additional');
   let additional_sections = document.getElementById('additional_sections');
   let section_container = additional_sections.querySelector('.assignment__body-sections');

   add_section_btn.addEventListener('click', () => {
      additional_sections.dataset.active = 'true';

      createSection(section_container);
   });

});

function createSection (section_container) {
   let section_template = document.getElementById('section_template');
   let new_section = section_template.cloneNode(true);
   new_section.removeAttribute('id');
   new_section.dataset.active = 'true';
   section_container.appendChild(new_section);

   let modal_select = new_section.querySelector('.modal-select');
   modal_select.addEventListener('click', () => {

      // todo вынести в отдельный метод
      modal = getModalBySelect(modal_select);

      if (!modal.is_empty) {
         modal.show();
      } else {
         createAlert(modal.alert_message);
         modal.alert_message = '';
      }
      disableScroll();

   });

}

function getAssignedExpertsJSON () {
   let experts = new Map();



   DropArea.drop_areas.forEach(area => {
      let section_assignment = area.getResult();

      if (section_assignment.id === '') {
         ErrorModal.open('Ошибка при назначении экспертов', 'Отсутствует id раздела');
      }

      console.log(area.getResult());
   })
}