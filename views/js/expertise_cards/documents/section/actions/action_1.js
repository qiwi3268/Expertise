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
