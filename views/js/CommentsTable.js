/**
 * Класс представляется собой таблицу с замечаниями
 * на страницах создания и редактирования описательной
 * части раздела
 */
class CommentsTable {

   /**
    * Элемент таблицы с замечаниями
    *
    * @type {HTMLElement}
    */
   element;

   /**
    * Блок с замечаниями
    *
    * @type {HTMLElement}
    */
   body;

   /**
    * Блок, в котором отмечаются файлы при создании замечаний
    *
    * @type {HTMLElement}
    */
   files_container;

   /**
    * Объект формы создания замечания
    *
    * @type {CommentCreator}
    */
   comment_creator;

   static get instance() {
      return this._instance;
   }

   static set instance(instance) {
      this._instance = instance;
   }

   /**
    * Возвращает единственный объект таблицы замечаний
    *
    * @returns {CommentsTable}
    */
   static getInstance () {

      if (!this.instance) {
         this.instance = new CommentsTable();
      }

      return this.instance;
   }

   /**
    * Создает объект таблицы замечаний
    */
   constructor () {
      this.comment_creator = CommentCreator.getInstance();

      this.element = document.getElementById('comments_table');
      this.body = document.getElementById('comments_table_body');
      this.files_container = document.getElementById('documentation');
   }

   /**
    * Добавляет замечание в таблицу
    *
    * @param {GeComment} comment - объект замечания
    * @param {HTMLElement|null} file - отмеченный файл,
    * null - если не требуется отметка файла
    */
   addComment (comment, file = null) {
      this.element.dataset.active = 'true';
      this.createActions(comment);

      let text_column = document.createElement('DIV');
      text_column.classList.add('comments-table__column');
      text_column.setAttribute('data-comment_text', '');
      text_column.setAttribute('data-comment_hash', comment.hash);
      text_column.innerHTML = comment.text;
      // data_row.appendChild(text_column);
      this.body.appendChild(text_column);

      let normative_column = document.createElement('DIV');
      normative_column.classList.add('comments-table__column');
      normative_column.setAttribute('data-comment_normative_document', '');
      normative_column.setAttribute('data-comment_hash', comment.hash);
      normative_column.innerHTML = comment.normative_document;
      // data_row.appendChild(normative_column);
      this.body.appendChild(normative_column);

      let criticality_column = document.createElement('DIV');
      criticality_column.classList.add('comments-table__column', 'comments-table__criticality');
      criticality_column.setAttribute('data-comment_criticality', '');
      criticality_column.setAttribute('data-comment_hash', comment.hash);
      criticality_column.innerHTML = comment.criticality_name;
      // data_row.appendChild(criticality_column);
      this.body.appendChild(criticality_column);

      let files_block = this.createFileBlock(comment);

      if (file !== null) {
         this.addFile(file, files_block);
      }
   }

   /**
    * Создает блок с кнопками действий с замечанием в таблице
    *
    * @param {GeComment} comment - объект замечания
    */
   createActions (comment) {
      let actions = document.createElement('DIV');
      actions.classList.add('comments-table__actions');
      actions.setAttribute('data-comment_hash', comment.hash);
      this.body.appendChild(actions);

      let edit_button = this.createActionButton('edit');
      edit_button.addEventListener('click', () => this.comment_creator.open(comment));
      actions.appendChild(edit_button);

      let delete_button = this.createActionButton('delete');
      delete_button.addEventListener('click', () => this.comment_creator.showAlert(comment.hash));
      actions.appendChild(delete_button);
   }

   /**
    * Создает кнопку действия замечания
    *
    * @param {string} action_class - класс действия
    * @returns {HTMLElement} кнопка действия
    */
   createActionButton (action_class) {
      let action = document.createElement('DIV');
      action.classList.add('comments-table__action', action_class);

      let action_icon = document.createElement('I');
      let icon_class = action_class === 'edit' ? 'fa-pen-alt' : 'fa-times';
      action_icon.classList.add('comments-table__icon-action', 'fas', icon_class);
      action.appendChild(action_icon);

      let action_text = document.createElement('SPAN');
      action_text.classList.add('comments-table__label-action');
      action_text.innerHTML = action_class === 'edit' ? 'Изменить' : 'Удалить';
      action.appendChild(action_text);

      return action;
   }

   /**
    * Создает файловый блок в строке таблцы с замечанием
    *
    * @param {GeComment} comment - объект замечания
    * @returns {HTMLElement} файловый блок
    */
   createFileBlock (comment) {
      let files_column = document.createElement('DIV');
      files_column.classList.add('comments-table__column');
      files_column.setAttribute('data-comment_hash', comment.hash);
      files_column.setAttribute('data-comment_files', '');

      let files_block = document.createElement('DIV');
      // files_block.classList.add('documentation__files', 'files', 'filled');
      files_block.classList.add('comment-table__file', 'files');
      files_block.setAttribute('data-id_file_field', '');
      files_block.setAttribute('data-mapping_level_1', this.files_container.dataset.mapping_level_1);
      files_block.setAttribute('data-mapping_level_2', this.files_container.dataset.mapping_level_2);

      files_column.appendChild(files_block);
      this.body.appendChild(files_column);

      return files_block;
   }

   /**
    * Добавляет отмеченный файл в строку с замечанием в таблице
    *
    * @param {HTMLElement} file - элемент файла из формы создания замечания
    * @param {HTMLElement} files_block - файловый блок в строке таблицы с замечанием
    */
   addFile (file, files_block) {
      let file_copy = file.cloneNode(true);
      file_copy.removeAttribute('style');
      let checkbox = file_copy.querySelector('.files__checkbox');
      checkbox.remove();
      let ge_file = new GeFile(file_copy, files_block);
      ge_file.handleActionButtons();
      files_block.appendChild(file_copy);
   }

   /**
    * Обновляет замечание в таблице
    *
    * @param {GeComment} comment - объект замечания
    * @param {HTMLElement|null} file - отмеченный файл,
    * null - если не требуется отмека файла
    */
   editComment (comment, file = null) {

      let text_column = this.element.querySelector(`[data-comment_hash="${comment.hash}"][data-comment_text]`);
      text_column.innerHTML = comment.text;

      let normative_column = this.element.querySelector(`[data-comment_hash="${comment.hash}"][data-comment_normative_document]`);
      normative_column.innerHTML = comment.normative_document;

      let criticality_column = this.element.querySelector(`[data-comment_hash="${comment.hash}"][data-comment_criticality]`);
      criticality_column.innerHTML = comment.criticality_name;

      let files_column = this.element.querySelector(`[data-comment_hash="${comment.hash}"][data-comment_files]`);
      let files_block = files_column.querySelector('.files');
      files_block.innerHTML = '';

      if (file !== null) {
         this.addFile(file, files_block);
      }
   }

   /**
    * Удаляет замечание из таблцы замечаний
    *
    * @param {number} comment_hash - хэш удаляемого замечания
    */
   removeComment (comment_hash) {
      let comment_columns = this.element.querySelectorAll(`[data-comment_hash="${comment_hash}"]`);
      comment_columns.forEach(column => column.remove());

      if (!this.body.querySelector('[data-comment_hash]')) {
         this.element.dataset.active = 'false';
      }
   }

}
