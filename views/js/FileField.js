/**
 * Представляет собой поле для файла, либо блок с документацией
 */
class FileField {

   /**
    * Поля с файлами
    *
    * @type {Map<number, FileField>}
    */
   static file_fields = new Map();

   /**
    * Счетчик полей с файлами
    *
    * @type {number}
    */
   static fields_counter = 0;

   /**
    * Блок поля с файлами
    *
    * @type {HTMLElement}
    */
   element;

   /**
    * Маппинг 1-го уровня
    *
    * @type {number}
    */
   mapping_1;

   /**
    * Маппинг 2-го уровня
    *
    * @type {number}
    */
   mapping_2;

   /**
    * Флаг, указывающий, что в файловом поле возможен только просмотр подписей
    *
    * @type {boolean}
    */
   read_only;

   /**
    * Файлы, которые относятся к полю
    *
    * @type {GeFile[]}
    */
   files;

   /**f
    * Создает объект файлового поля
    *
    * @param {HTMLElement} element - блок файлового поля
    */
   constructor (element) {
      this.element = element;
      this.mapping_1 = parseInt(this.element.dataset.mapping_level_1);
      this.mapping_2 = parseInt(this.element.dataset.mapping_level_2);
      this.read_only = this.element.hasAttribute('data-read_only');
      this.files = [];

      let id = FileField.fields_counter++;
      this.element.dataset.id_file_field = id;
      FileField.file_fields.set(id, this);
   }

   /**
    * Получает объект файлового поля, к которому относится файл
    *
    * @param {GeFile} ge_file - файл, относящийся к полю
    * @returns {FileField} file_field - объект файлового поля
    */
   static getByFile(ge_file) {
      let field = ge_file.container.closest('[data-id_file_field]');
      let id = parseInt(field.dataset.id_file_field);
      return isNaN(id) ? new FileField(field) : this.file_fields.get(id);
   }

   /**
    * Добавляет файл в массив файлов поля
    *
    * @param {GeFile} ge_file - файл, относящийся к полю
    */
   addFile (ge_file) {
      // this.files.set(ge_file.id, ge_file);
      this.files.push(ge_file);
   }

   /**
    * Определяет скрыто ли файловое поле на странице
    *
    * @returns {boolean} активно ли файловое поле
    */
   isActive () {
      return !this.element.closest('[data-block][data-active="false"]');
   }

}
