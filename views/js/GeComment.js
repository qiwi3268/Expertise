document.addEventListener('DOMContentLoaded', () => {
   GeComment.comments = new Map();
});

/**
 * Класс представляет собой замечание эксперта по разделу
 */
class GeComment {

   /**
    * Геттер для коллекции замечаний, которые есть на странице
    *
    * @returns {Map<number, GeComment>} comments - коллекция
    * с замечаниями на странице
    */
   static get comments() {
      return this._comments;
   }

   /**
    * Сеттер для коллекции замечаний
    *
    * @param {Map} comments
    */
   static set comments(comments) {
      this._comments = comments;
   }

   /**
    * Возвращает объект замечания по хэшу
    *
    * @param hash - хэш замечания
    * @returns {GeComment} объект замечания
    */
   static getByHash(hash) {
      return this.comments.get(parseInt(hash));
   }

   /**
    * id замечания сохраненного в БД
    *
    * @type {number}
    */
   id;

   /**
    * Хэш замечания, используется на странице как идентификатор
    *
    * @type {number}
    */
   hash;

   /**
    * Текст замечания
    *
    * @type {string}
    */
   text;

   /**
    * Ссылка на нормативный документ
    *
    * @type {string}
    */
   normative_document;

   /**
    * Наименование критичности замечания
    *
    * @type {string}
    */
   criticality_name;

   /**
    * id выбранного значения из справочника
    * критичности замечаний
    *
    * @type {string}
    */
   comment_criticality;

   /**
    * Текст замечания
    *
    * @type {string}
    */
   note;

   /**
    * Текст замечания
    *
    * @type {string}
    */
   no_files;

   /**
    * Текст замечания
    *
    * @type {number}
    */
   attached_file;

   /**
    * Создает объект замечания и добавляет в таблицу замечаний на странице
    *
    * @param {CommentData} data - данные о замечании из полей
    * модального окна создания замечания
    * @param {HTMLElement|null} file - отмеченный файл,
    * null - отметка файла не требуется
    */
   constructor (data, file = null) {

      Object.assign(this, data, {id: null});
      this.attached_file = file ? parseInt(file.dataset.id) : null;

      this.hash = CommentCreator.hash++;
      GeComment.comments.set(this.hash, this);

      CommentsTable.getInstance().addComment(this, file);
   }

   /**
    * Создает объекты замечаний для каждого отмеченного файла
    * или одно замечание, если не отмечен файл
    *
    * @param {CommentCreator} comment_creator - Объект формы
    * создания замечания
    */
   static create (comment_creator) {
      if (comment_creator.marked_files.size > 0) {

         comment_creator.marked_files.forEach(file => {
            new GeComment(comment_creator.comment_data, file);
         });

      } else {
         new GeComment(comment_creator.comment_data, null);
      }
   }

   /**
    * Обновляет созданное замечание и создает новые,
    * если были отмечены дополнительные файлы
    *
    * @param {CommentCreator} comment_creator - Объект формы
    * создания замечания
    */
   static edit (comment_creator) {

      delete comment_creator.editable_comment.normative_document;
      let comment = Object.assign(comment_creator.editable_comment, comment_creator.comment_data);

      let comment_table = CommentsTable.getInstance();

      // Обновляем исходное замечание
      if (comment_creator.marked_files.size > 0) {

         let iterator = comment_creator.marked_files.entries();
         let first_file = iterator.next().value;
         comment.attached_file = first_file[0];
         comment_table.editComment(comment, first_file[1]);

         comment_creator.marked_files.delete(first_file[0]);

      } else {
         comment.attached_file = null;
         comment_table.editComment(comment);
      }

      GeComment.comments.set(comment.hash, comment);

      // Для оставшихся отмеченных файлов создаем замечания
      comment_creator.marked_files.forEach(file => {
         let comment_copy = Object.assign({}, comment, {file: undefined, id:null});
         comment_copy.attached_file = parseInt(file.dataset.id);
         new GeComment(comment_creator.comment_data, file);
      });

   }


}

