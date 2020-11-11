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
 * Выполняет действие назначения экспертов
 */
function assignExperts () {

   // Берем разделы, у которых есть id блока из 341 приказа
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

/**
 * Получает массив объектов назначенных экспертов
 *
 * @param {HTMLElement[]} sections - элементы разделов со страницы
 * @return {Expert[]} массив назначенных экспертов
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
            expert = getExpertData(expert_elem);
            experts.set(expert.id_expert, expert);
         }

         expert.ids_main_block_341.push(id_section);

      });
   });

   return Array.from(experts.values());
}

/**
 * Создает дополнительный раздел при назначении экспертов
 *
 * @param {HTMLElement} section_container - контейнер с дополнительными разделами
 * @param {HTMLElement} additional_sections - блок дополнительных разделов
 */
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
 * Объект, содержащий информацию о назначенном эксперте
 *
 * @typedef Expert
 * @type {Object}
 * @property {number} id_expert - id эксперта из БД
 * @property {boolean} lead - отмечен ли эксперт как ведущий
 * @property {boolean} common_part - назначен ли эксперт на общую часть
 * @property {number[]} ids_main_block_341 - id разделов из 341 приказа,
 * на которые назначен эксперт
 */

/**
 * Создает объект назначенного эксперта из элемента на странице
 *
 * @param {HTMLElement} expert_element
 * @returns {Expert} Объект назначенного эксперта
 */
function getExpertData (expert_element) {
   let expert_data = {};

   expert_data.id_expert = parseInt(expert_element.dataset.id);
   expert_data.lead = expert_element.dataset.lead === 'true';
   expert_data.common_part = expert_element.dataset.common_part === 'true';
   expert_data.ids_main_block_341 = [];

   return expert_data;
}

/**
 * Удаляет перенесенного эксперта из раздела
 *
 * @param {DropArea} drop_area - область, относящаяся к разделу, в которую переносятся эксперты
 */
function removeExpert (drop_area) {
   let assigned_experts = drop_area.element.querySelector('.section__experts');
   if (!drop_area.container.querySelector('[data-drop_element]')) {
      assigned_experts.dataset.active = 'false';
   }
}

/**
 * Отображает блок с экспертами раздела при переносе
 *
 * @param {HTMLElement} drop_area - область, относящаяся к разделу, в которую переносятся эксперты
 */
function showExpertsBlock (drop_area) {
   let assigned_experts = drop_area.element.querySelector('.section__experts');
   assigned_experts.dataset.active = 'true';
}

/**
 * Создает переносимую копию эксперта при переносе из списка экспертов
 *
 * @param {HTMLElement} expert - исходный элемент эксперта
 * @return {HTMLElement} копия эксперта для переноса
 */
function createExpertAvatar (expert) {
   let expert_avatar = expert.cloneNode(true);
   expert_avatar.classList.remove('assignment__expert');
   expert_avatar.classList.add('avatar');
   return expert_avatar;
}

/**
 * Создает переносимую копию эксперта при переносе из раздела
 *
 * @param {HTMLElement} expert - исходный элемент эксперта
 * @return {HTMLElement} копия эксперта для переноса
 */
function createSectionExpertAvatar (expert) {

   let expert_avatar = document.createElement('DIV');
   expert_avatar.dataset.id = expert.dataset.id;
   expert_avatar.innerHTML = expert.querySelector('.section__name').innerHTML;
   expert_avatar.classList.add('avatar');

   return expert_avatar;
}

/**
 * Преобразует копию переносимого эксперта для отображения в разделе
 *
 * @param {HTMLElement} expert - исходный элемент эксперта
 * @return {HTMLElement} элемент эксперта для отображения в разделе
 */
function transformExpert (expert) {

   let new_expert = document.createElement('DIV');
   new_expert.classList.add('section__expert');
   new_expert.dataset.id = expert.dataset.id;
   new_expert.setAttribute('data-assigned_expert', '');
   new_expert.setAttribute('data-drag_element', '');
   new_expert.setAttribute('data-drop_element', '');

   let expert_name = document.createElement('SPAN');
   expert_name.classList.add('section__name');
   expert_name.innerHTML = expert.innerHTML;
   new_expert.appendChild(expert_name);

   let lead_icon = document.createElement('I');
   lead_icon.classList.add('section__lead', 'fas', 'fa-crown');
   lead_icon.dataset.drag_inactive = 'true';
   lead_icon.addEventListener('click', () => changeLeadExpert(new_expert));
   new_expert.dataset.lead = isLeadExpert(new_expert);
   new_expert.appendChild(lead_icon);

   let common_icon = document.createElement('I');
   common_icon.classList.add('section__common_part', 'fas', 'fa-file-signature');
   common_icon.dataset.drag_inactive = 'true';
   common_icon.addEventListener('click', () => toggleCommonPart(new_expert));
   new_expert.dataset.common_part = isCommonPartExpert(new_expert);
   new_expert.appendChild(common_icon);

   let remove_btn = document.createElement('SPAN');
   remove_btn.classList.add('section__icon-remove', 'fas', 'fa-minus');
   remove_btn.setAttribute('data-drag_inactive', '');
   remove_btn.setAttribute('data-drop_remove', '');
   remove_btn.dataset.remove_callback = 'remove_expert';
   new_expert.appendChild(remove_btn);

   return new_expert;
}

/**
 * Преобразует копию эксперта из одного раздела при переносе в другой
 *
 * @param {HTMLElement} expert - исходный элемент эксперта
 * @return {HTMLElement} элемент эксперта для отображения в разделе
 */
function transformSectionExpert (expert) {
   let drop_area = expert.closest('[data-drop_area]');
   if (drop_area.querySelectorAll('[data-drop_element]').length <= 1) {
      let assigned_experts = drop_area.querySelector('.section__experts');
      assigned_experts.dataset.active = 'false';
   }

   let new_expert = expert.cloneNode(true);
   new_expert.style.display = null;

   let lead_icon = new_expert.querySelector('.section__lead');
   lead_icon.addEventListener('click', () => changeLeadExpert(new_expert));

   let common_icon = new_expert.querySelector('.section__common_part');
   common_icon.addEventListener('click', () => toggleCommonPart(new_expert));

   expert.remove();

   return new_expert;
}

/**
 * Меняет метку ведущего эксперта
 *
 * @param {HTMLElement} expert - новый ведущий эксперт
 */
function changeLeadExpert (expert) {
   if (!isLeadExpert(expert)) {
      removeLeadExpert();
      setLeadExpert(expert);
   } else {
      removeLeadExpert();
   }
}

/**
 * Проверяет, отмечен ли эксперт ведущим
 *
 * @param {HTMLElement} expert - эксперт для проверки
 * @return {boolean} ведущий ли эксперт
 */
function isLeadExpert (expert) {
   let lead_expert = document.querySelector('.section__expert[data-lead="true"]');
   return lead_expert && expert.dataset.id === lead_expert.dataset.id;
}

/**
 * Удаляет метку ведущего эксперта
 */
function removeLeadExpert () {
   let lead_experts = document.querySelectorAll('.section__expert[data-lead="true"]');
   lead_experts.forEach(lead_expert => lead_expert.dataset.lead = 'false');
}

/**
 * Устанавливает метку ведущего эксперта
 *
 * @param {HTMLElement} expert - эксперт, которому проставляется метка
 */
function setLeadExpert (expert) {
   let current_expert = document.querySelectorAll(`.section__expert[data-id='${expert.dataset.id}']`);
   current_expert.forEach(expert_copy => {
      expert_copy.dataset.lead = 'true';
   });
}

/**
 * Проверяет, назначен ли эксперт на общую часть
 *
 * @param {HTMLElement} expert - эксперт для проверки
 * @return {boolean}
 */
function isCommonPartExpert (expert) {
   let common_part_expert = document.querySelector(`.section__expert[data-common_part='true'][data-id='${expert.dataset.id}']`);
   return common_part_expert !== null;
}

/**
 * Переключает метку общей части у эксперта
 *
 * @param {HTMLElement} expert
 */
function toggleCommonPart (expert) {
   let is_common_part = (expert.dataset.common_part !== 'true').toString();
   let current_expert = document.querySelectorAll(`.section__expert[data-id='${expert.dataset.id}']`);
   current_expert.forEach(expert_copy => {
      expert_copy.dataset.common_part = is_common_part;
   });
}

/**
 * Устанавливает название дополнительного раздела, если такой раздел не был создан
 * и добавляет возможность перенести в него экспертов
 *
 * @param {HTMLElement} selected_item - выбранный элемент справочника
 * @param {Misc} misc - объект справочника, к которому отностится элемент
 */
function setAdditionalSection (selected_item, misc) {

   if (
      !document.querySelector(`[data-misc_field][data-id='${selected_item.dataset.id}']`)
      || misc.field.dataset.id === selected_item.dataset.id
   ) {
      misc.field.dataset.id = selected_item.dataset.id;
      misc.field.setAttribute('data-drop_area', '');
      misc.select.classList.remove('empty');
      let misc_value = misc.select.querySelector('[data-field_value]');
      misc_value.innerHTML = selected_item.innerHTML;
   } else {
      let section = selected_item.closest('[data-misc_field]');
      section.remove();
      ErrorModal.open('Ошибка при добавлении раздела', 'Такой раздел уже создан');
   }

}