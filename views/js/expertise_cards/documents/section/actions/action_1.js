document.addEventListener('DOMContentLoaded', () => {

   let submit_btn = document.querySelector('[data-action_submit]');
   submit_btn.addEventListener('click', saveSection);

   initEditor();

});

/**
 * Инициализирует текстовый редактор описательной части раздела
 */
function initEditor () {
   tinymce.init({
      selector: "textarea#description",
      min_height: 400,
      max_height: 1000,
      table_default_styles: {
         width: '50%'
      },
      elementpath: false,
      statusbar: false,
      language: 'ru',
      placeholder: 'Введите текст описательной части',
      plugins: [
         "advlist autolink lists link image charmap print preview anchor",
         "searchreplace visualblocks code fullscreen",
         "insertdatetime media table paste code help wordcount autoresize"
      ],
      toolbar:
         "undo redo | " +
         "bold italic underline | alignleft aligncenter " +
         "alignright alignjustify | bullist numlist outdent indent | " +
         "table",
      menubar: 'file edit format insert view help',
      menu: {
         file: {title: 'File', items: 'newdocument | preview | print '},
         edit: {title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace'},
         view: {title: 'View', items: 'visualblocks'},
         insert: {title: 'Insert', items: 'inserttable charmap insertdatetime'},
         format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | removeformat'},
         help: {title: 'Дополнительно', items: 'wordcount help'}
      },
      skin: "CUSTOM",
      content_css: "CUSTOM"
   })
      .then(result => {
         let editor = result[0];
         let text_area = editor.getElement();

         // todo если не срабатывает событие
         editor.on('ObjectResizeStart', () => changeParentCardMaxHeight(text_area, '100%'));
         editor.on('ObjectResized', () => changeParentCardMaxHeight(text_area));
         editor.on('focus', () => changeParentCardMaxHeight(text_area, '100%'));
         editor.on('blur', () => changeParentCardMaxHeight(text_area));

      })
      .catch(exc => {
         ErrorModal.open('Ошибка при инициализации редактора', exc);
      });
}

/**
 * Выполняет действие сохранения раздела
 */
function saveSection () {
   let form_data = new FormData();

   // Добавляем в форму ТЭПы
   let multiple_blocks = MultipleBlock.appendMultipleBlocks(form_data);

   //Добавляем описательную часть
   form_data.append('description', tinymce.get('description').getContent());

   appendComments(form_data);

   API.executeAction(form_data)

      .then(result => {

         console.log(result);

         MultipleBlock.saveMultipleBlocks(multiple_blocks);
         setCommentIDs(result.add.created_ids);

      })
      .catch((exc) => {
         ErrorModal.open('Ошибка при сохранении общей части', exc);
      });


   console.log(new Map(form_data));
}

/**
 * Проставляет id созданным на странице замечаниям
 *
 * @param {Object[]} created_comments_ids - замечания в виде: id, hash
 */
function setCommentIDs (created_comments_ids) {
   created_comments_ids.forEach(comment_data => {
      let comment = GeComment.getByHash(comment_data.hash);
      comment.id = comment_data.id.toString();
   });
}

/**
 * Добавляет в форму замечания со страницы
 *
 * @param {FormData} form_data - форма для запроса создания описательной части раздела
 */
function appendComments (form_data) {

   let comments = Array.from(GeComment.comments.values());
   let comments_to_json = comments.map((comment) => {
      return Object.assign({}, comment, {criticality_name: undefined});
   });
   console.log(comments_to_json);
   form_data.append('comments', JSON.stringify(comments_to_json));
}

/**
 * Удаляет перенесенного эксперта из раздела
 *
 * @param {DropArea} drop_area - область, относящаяся к разделу, в которую переносятся эксперты
 */
function removeExpert (drop_area) {
   let assigned_experts = drop_area.area.querySelector('.section__experts');
   if (!drop_area.container.querySelector('[data-drop_element]')) {
      assigned_experts.dataset.active = 'false';
   }
}

/**
 * Отображает блок с экспертами раздела при переносе
 *
 * @param drop_area - область, относящаяся к разделу, в которую переносятся эксперты
 */
function showExpertsBlock (drop_area) {
   let assigned_experts = drop_area.area.querySelector('.section__experts');
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
function createSectionExpert (expert) {
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
   new_expert.setAttribute('data-assigned_expert', '');
   new_expert.dataset.id = expert.dataset.id;
   new_expert.dataset.drag_element = '';
   new_expert.dataset.drop_element = '';
   new_expert.dataset.drag_callback = 'section_expert';

   let expert_name = document.createElement('SPAN');
   expert_name.classList.add('section__name');
   expert_name.innerHTML = expert.innerHTML;
   new_expert.appendChild(expert_name);

   let lead_icon = document.createElement('I');
   lead_icon.classList.add('section__lead', 'fas', 'fa-crown');
   lead_icon.dataset.drag_inactive = 'true';
   lead_icon.addEventListener('click', () => changeLeadExpert(new_expert));
   new_expert.dataset.lead = !!isLeadExpert(new_expert);
   new_expert.appendChild(lead_icon);

   let common_icon = document.createElement('I');
   common_icon.classList.add('section__common_part', 'fas', 'fa-file-signature');
   common_icon.dataset.drag_inactive = 'true';
   common_icon.addEventListener('click', () => toggleCommonPart(new_expert));
   new_expert.dataset.common_part = !!isCommonPartExpert(new_expert);
   new_expert.appendChild(common_icon);

   let remove_btn = document.createElement('SPAN');
   remove_btn.classList.add('section__icon-remove', 'fas', 'fa-minus');
   remove_btn.dataset.drag_inactive = '';
   remove_btn.dataset.drop_remove = '';
   remove_btn.dataset.remove_callback = 'remove_expert';
   new_expert.appendChild(remove_btn);

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
   let common_part_expert = document.querySelector('.section__expert[data-common_part="true"]');
   return common_part_expert && expert.dataset.id === common_part_expert.dataset.id;
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