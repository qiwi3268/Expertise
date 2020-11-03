document.addEventListener('DOMContentLoaded', () => {

   let submit_btn = document.querySelector('[data-action_submit]');
   submit_btn.addEventListener('click', assignExperts);

   let add_section_btn = document.getElementById('add_section');
   let additional_sections = document.getElementById('additional_sections');
   let section_container = additional_sections.querySelector('.assignment__body-sections');

   add_section_btn.addEventListener('click', () => {
      additional_sections.dataset.active = 'true';
      createSection(section_container, additional_sections);
   });

});


/**
 *
 */
function assignExperts () {

   let sections = Array.from(document.querySelectorAll('[data-section]'))
      .filter(section => section.dataset.id !== '');

   if (!sections.find(section => !section.querySelector('[data-assigned_expert]'))) {

      let assigned_experts = getAssignedExperts(sections);
      let lead = assigned_experts.filter(expert => expert.lead === true);
      let has_common_part = assigned_experts.find(expert => expert.common_part === true);

      if (lead.length === 1 && has_common_part) {

         let form_data = new FormData();
         form_data.append('experts', JSON.stringify(assigned_experts));

         console.log(new Map(form_data));

         API.executeAction(form_data)
            .then(response => {
               location.href = response.ref;
            })
            .catch(exc => {
               ErrorModal.open('Ошибка при назначении экспертов', exc);

            });

      } else if (!has_common_part) {
         ErrorModal.open('Ошибка при назначении экспертов', 'Нет назначенных экспертов на общую часть');
      } else {
         ErrorModal.open('Ошибка при назначении экспертов', 'Должен быть назначен ведущий эксперт');
      }

   } else {
      ErrorModal.open('Ошибка при назначении экспертов', 'Не на все разделы назначены эксперты');
   }

}

function createSection (section_container, additional_sections) {
   let section_template = document.getElementById('section_template');
   let new_section = section_template.cloneNode(true);
   new_section.removeAttribute('id');
   new_section.dataset.active = 'true';
   new_section.setAttribute('data-section', '');

   let modal_select = new_section.querySelector('[data-modal_select="misc"]');
   modal_select.addEventListener('click', event => {

      if (!event.target.classList.contains('section__remove')) {

         Misc.instance = Misc.getMiscBySelect(modal_select);
         Misc.instance.open();

      }
   });

   section_container.appendChild(new_section);

   let remove_btn = new_section.querySelector('.section__remove');
   remove_btn.addEventListener('click', () => {
      new_section.remove();

      if (!additional_sections.querySelector('.section[data-active="true"]')) {
         additional_sections.dataset.active = 'false';
      }

   });

   modal_select.click();
}

/**
 * Возвращает наз
 * @param sections
 * @return {any[]}
 */
function getAssignedExperts (sections) {
   let experts = new Map();

   sections.forEach(section => {
      let id_section = parseInt(section.dataset.id);

      let section_experts = section.querySelectorAll('[data-assigned_expert]');
      section_experts.forEach(expert_elem => {
         let id_expert = parseInt(expert_elem.dataset.id);
         let expert;

         if (experts.has(id_expert)) {
            expert = experts.get(id_expert)
         } else {
            expert = new Expert(expert_elem);
            experts.set(expert.id_expert, expert);
         }

         expert.ids_main_block_341.push(id_section);

      });
   });

   console.log(experts);

   return Array.from(experts.values());
}

function Expert (expert) {
   this.id_expert = parseInt(expert.dataset.id);
   this.lead = expert.dataset.lead === 'true';
   this.common_part = expert.dataset.common_part === 'true';
   this.ids_main_block_341 = [];
}