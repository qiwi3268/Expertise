/**
 * @typedef CommentData
 * @type {object}
 * @property {string|null} text - текст замечания
 * @property {string|null} normative_document - ссылка на нормативный документ
 * @property {string|null} comment_criticality - критичность замечания
 * @property {string|null} criticality_name - наименование выбранной критичности
 * @property {string|null} no_files - равняется "1", если к замечанию не требуется
 * отметка файлов, иначе - null
 * @property {string|null} note - личная заметка
 */

document.addEventListener('DOMContentLoaded', () => {
   let add_comment_btn = document.querySelector('[data-add_comment]');
   add_comment_btn.addEventListener('click', () => CommentCreator.getInstance().open());
});

/**
 * Класс представляет собой модальное окно формы создания замечаний
 */
class CommentCreator {

   /**
    * Элемент модального окна
    *
    * @type {HTMLElement}
    */
   modal;

   /**
    * Фон модального окна
    *
    * @type {HTMLElement}
    */
   overlay;

   /**
    * Отмеченные файлы
    *
    * @type {Map<number, HTMLElement>}
    */
   marked_files;

   /**
    * Поле ввода текста замечания
    *
    * @type {HTMLInputElement}
    */
   text_input;

   /**
    * Поле ввода ссылки на нормативный документ
    *
    * @type {HTMLInputElement}
    */
   normative_document_input;

   /**
    * Чекбокс "Отметка файла не требуется"
    *
    * @type {HTMLElement}
    */
   no_files_checkbox;

   /**
    * Поле для выбора критичности замечания
    *
    * @type {HTMLElement}
    */
   criticality_name_select;

   /**
    * Скрытый инпут со значением выбранной критичности
    *
    * @type {HTMLInputElement}
    */
   criticality_value_input;

   /**
    * Поле ввода личной заметки
    *
    * @type {HTMLInputElement}
    */
   note_input;

   /**
    * Данные замечания из полей модального окна
    *
    * @type {CommentData}
    */
   comment_data;

   /**
    * Объект редактируемого замечания
    *
    * @type {GeComment}
    */
   editable_comment;

   /**
    * Геттер для хэша формы создания замечаний
    *
    * @returns {number}
    */
   static get hash() {
      return this._hash;
   }

   /**
    * Сеттер для хэша формы создания замечаний
    *
    * Хэш представляет собой timestamp первого для текущей страницы открытия
    * модального окна, используется как инкрементируемый идентификатор
    * для создаваемых замечаний
    *
    * @param {number} hash
    */
   static set hash(hash) {
      this._hash = hash;
   }

   static get instance() {
      return this._instance;
   }

   static set instance(instance) {
      this._instance = instance;
   }

   /**
    * Возвращает единственный объект формы создания замечаний
    *
    * @returns {CommentCreator}
    */
   static getInstance () {

      if (!this.instance) {
         this.instance = new CommentCreator();
      }

      return this.instance;
   }

   /**
    * Создает объект формы создания замечаний
    */
   constructor () {
      this.modal = document.getElementById('comment_modal');
      this.overlay = document.getElementById('comment_overlay');

      this.text_input = document.getElementById('comment_text');
      this.normative_document_input = document.getElementById('normative_document');
      this.no_files_checkbox = document.getElementById('no_files');

      this.criticality_name_select = document.getElementById('comment_criticality_name');
      this.criticality_value_input = document.getElementById('comment_criticality_value');
      this.note_input = document.getElementById('comment_note');

      this.marked_files = new Map();

      CommentCreator.hash = Date.now();

      this.no_files_checkbox.addEventListener('click', () => {
         this.marked_files.forEach(this.removeFileCheckbox);
         this.marked_files.clear();
      });

      let save_button = this.modal.querySelector('[data-save_comment]');
      save_button.addEventListener('click', () => this.save());

      let cancel_button = this.modal.querySelector('[data-delete_comment]');
      cancel_button.addEventListener('click', () => this.close());

      this.handleFiles();
   }

   /**
    * Снимает отметку файла
    *
    * @param file_element - файл, у которого снимается отметка
    */
   removeFileCheckbox (file_element) {
      let checkbox = file_element.querySelector('.files__checkbox');
      checkbox.classList.add('fa-square', 'far');
      checkbox.classList.remove('fa-check-square', 'fas');
   }

   /**
    * Обрабатывает сохранение формы
    */
   save () {
      this.comment_data = this.getCommentData();

      let is_valid = this.validate();
      if (is_valid) {
         this.saveComment();
      }
   }

   /**
    * Возвращает объект с данными из полей формы создания замечаний
    *
    * @return {CommentData}
    */
   getCommentData () {
      let data = {};

      let field_inputs = this.modal.querySelectorAll('[data-field_result]');
      field_inputs.forEach(input => {

         if (!input.closest('[data-active="false"]')) {
            data[input.name] = input.value || null;
         } else {
            data[input.name] = null;
         }

      });
      data.criticality_name = this.criticality_name_select.innerHTML;

      return data;
   }

   /**
    * Валидирует форму создания замечаний
    *
    * @return {boolean} is_valid - правильно ли заполнена форма замечания
    */
   validate () {
      let is_valid = false;

      validateBlock(this.modal);

      if (
         !this.comment_data.text
         || !this.comment_data.comment_criticality
         || (!this.comment_data.normative_document && this.comment_data.comment_criticality !== '1')
      ) {
         ErrorModal.open('Ошибка при сохранении замечания', 'Не заполнены обязательные поля');
      } else if (this.marked_files.size === 0 && this.comment_data.no_files === null) {
         ErrorModal.open('Ошибка при сохранении замечания', 'Не отмечены файлы к замечанию');
      } else {
         is_valid = true;
      }

      return is_valid;
   }

   /**
    * Создает или обновляет объекты и таблицу замечаний
    */
   saveComment () {
      this.editable_comment === null ? GeComment.create(this) : GeComment.edit(this);
      resizeCard(CommentsTable.getInstance().element);
      this.close();
   }

   /**
    * Закрывает модальное окно формы создания замечаний
    */
   close () {
      this.clearModal();
      this.modal.classList.remove('active');
      this.overlay.classList.remove('active');
   }

   /**
    * Очищает форму, снимая отметки с файлов и класс невалидности полей
    */
   clearModal() {
      this.marked_files.forEach(this.removeFileCheckbox);
      this.marked_files.clear();

      let fields = this.modal.querySelectorAll('.field');
      fields.forEach(field => field.classList.remove('invalid'));
   }

   /**
    * Обрабатывает файлы формы создания замечаний
    */
   handleFiles () {
      let files = this.modal.querySelectorAll('.files__item');
      files.forEach(file => {
         let file_info = file.querySelector('.files__info');
         file_info.addEventListener('click', () => this.markFile(file));
      });
   }

   /**
    * Обрабатывает отметку файла
    *
    * @param file - отмечаемый файл
    */
   markFile (file) {
      let file_id = parseInt(file.dataset.id);

      if (this.no_files_checkbox.dataset.selected !== "true") {

         this.marked_files.has(file_id)
            ? this.marked_files.delete(file_id)
            : this.marked_files.set(file_id, file);

         this.toggleFileCheckbox(file);

      } else {
         ErrorModal.open(
            'Ошибка при отметке файла',
            'Выбрана опция: "Отметка файла не требуется"'
         );
      }
   }

   /**
    * Переключает чекбокс отметки у файла
    *
    * @param file_element - файл
    */
   toggleFileCheckbox (file_element) {
      let checkbox = file_element.querySelector('.files__checkbox');
      checkbox.classList.toggle('fa-check-square');
      checkbox.classList.toggle('far');
      checkbox.classList.toggle('fa-square');
      checkbox.classList.toggle('fas');
   }

   /**
    * Открывает модальное окно формы создания замечаний
    *
    * @param {GeComment|null} comment - редактируемое замечание,
    * null - если создается новое замечание
    */
   open (comment = null) {
      this.modal.classList.add('active');
      this.overlay.classList.add('active');

      this.init(comment);
   }

   /**
    * Инициализирует форму создания замечаний
    *
    * @param {GeComment|null} comment - редактируемое замечание,
    * null - если создается новое замечание
    */
   init (comment) {

      this.setFieldValues(comment);

      let criticality_field = this.criticality_name_select.closest('.field');
      if (comment !== null) {

         this.editable_comment = comment;

         let normative_block = this.normative_document_input.closest('[data-block]');
         normative_block.dataset.active = this.criticality_value_input.value !== '1' ? 'true' : 'false';

         criticality_field.classList.add('filled');
         if (this.editable_comment.attached_file) {
            let file = this.modal.querySelector(`.files__item[data-id="${this.editable_comment.attached_file}"]`);
            this.setFileCheckbox(file);
            this.marked_files.set(this.editable_comment.attached_file, file);
         }

      } else {
         criticality_field.classList.remove('filled');
         this.editable_comment = null;
      }
   }

   /**
    * Проставляет значения полей формы создания замечаний
    *
    * @param {GeComment|null} comment - редактируемое замечание,
    * null - если создается новое замечание
    */
   setFieldValues (comment) {
      this.text_input.value = comment ? comment.text : null;
      this.normative_document_input.value = comment ? comment.normative_document : null;
      this.criticality_name_select.innerHTML = comment ? comment.criticality_name : 'Выберите критичность';
      this.criticality_value_input.value = comment ? comment.comment_criticality : null;

      let checkbox_icon = this.no_files_checkbox.querySelector('.radio__icon');
      let checkbox_field = this.no_files_checkbox.closest('.field[data-name="no_files"]');
      let checkbox_input = checkbox_field.querySelector('[data-field_result]');
      if (comment === null || comment.no_files === null) {
         this.no_files_checkbox.dataset.selected = 'false';
         checkbox_icon.classList.add('fa-square');
         checkbox_icon.classList.remove('fa-check-square');
         checkbox_input.value = null;
      } else {
         this.no_files_checkbox.dataset.selected = 'true';
         checkbox_icon.classList.add('fa-check-square');
         checkbox_icon.classList.remove('fa-square');
         checkbox_input.value = "1";
      }

      this.note_input.value = comment ? comment.note : null;
   }

   /**
    * Проставляет отметку файла
    *
    * @param file_element - файл, для которого проставляется отметка
    */
   setFileCheckbox (file_element) {
      let checkbox = file_element.querySelector('.files__checkbox');
      checkbox.classList.add('fa-check-square', 'fas');
      checkbox.classList.remove('fa-square', 'far');
   }

   // todo убрать в отдельный модуль
   showAlert(comment_hash) {
      let alert_modal = document.getElementById('alert_modal');
      let alert_overlay = document.getElementById('alert_overlay');

      alert_modal.classList.add('active');
      alert_overlay.classList.add('active');

      let delete_comment = () => {

         GeComment.comments.delete(comment_hash);
         CommentsTable.getInstance().removeComment(comment_hash);

         alert_modal.classList.remove('active');
         alert_overlay.classList.remove('active');
      };

      let confirm_button = document.getElementById('alert_confirm');
      confirm_button.addEventListener('click', delete_comment, {once: true});

      let cancel_button = document.getElementById('alert_cancel');
      cancel_button.addEventListener('click', () => {
         alert_modal.classList.remove('active');
         alert_overlay.classList.remove('active');
         confirm_button.removeEventListener('click', delete_comment);

      }, {once: true});

   }

}

