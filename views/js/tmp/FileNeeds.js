/**
 * Вспомогательный класс для проставления меток сохранения и удаления к файлам
 */
class FileNeeds {

   /**
    * Содержит массивы с файлами к сохранению и удалению
    * файлы хранятся в виде объектов:<br>
    * {<br>
    *    id_file,<br>
    *    mapping_level_1,<br>
    *    mapping_level_2<br>
    * }
    *
    * @type {{to_delete: Object[], to_save: Object[]}}
    */
   static file_needs = {
      to_save: [],
      to_delete: []
   };

   /**
    * Содержит пары ключ значение с подписями к сохранению и удалению<br>
    *    ключ - id подписи<br>
    *    значение - {
    *       id_file,
    *       mapping_level_1,
    *       mapping_level_2
    *    }
    *
    * @type {{to_delete: Map<number, Object>, to_save: Map<number, Object>}}
    */
   static sign_needs = {
      to_save: new Map(),
      to_delete: new Map()
   };

   /**
    * Добавляет файлы на странице в массивы для сохранения или удаления
    */
   static putFilesToFileNeeds () {

      FileField.file_fields.forEach(file_field => {

         // Если блок скрыт, удаляем файлы, иначе сохраняем
         if (file_field.isActive()) {
            FileNeeds.saveFiles(file_field);
         } else {
            FileNeeds.deleteFiles(file_field);
         }

      });

      FileNeeds.addSigns();
   }

   /**
    * Добавляет файлы в массив для сохранения
    *
    * @param {FileField} file_field - поле с файлами
    */
   static saveFiles (file_field) {
      file_field.files.forEach(ge_file => FileNeeds.putFileToSave(ge_file));
   }

   /**
    * Добавляет файл в массив для сохранения
    *
    * @param {GeFile} ge_file - сохраняемый файл
    */
   static putFileToSave (ge_file) {
      let is_file_saved = ge_file.element.dataset.saved === 'true';

      if (!is_file_saved) {
         let to_save = FileNeeds.file_needs.to_save;
         to_save.push(new FileData(ge_file));

         ge_file.element.dataset.saved = 'true';
      }
   }


   /**
    * Добавляет открепленную подпись в массив для сохранения
    *
    * @param {GeFile} ge_file - файл, к которому относится открепленная подпись
    */
   static putSignToSave (ge_file) {
      let file_data = {
         id_file: ge_file.id_sign,
         mapping_level_1: ge_file.field.mapping_1,
         mapping_level_2: ge_file.field.mapping_2
      }

      FileNeeds.sign_needs.to_save.set(ge_file.id_sign, file_data);
   }

   /**
    * Добавляет файлы в массив для удаления
    *
    * @param {FileField} file_field - поле с файлами
    */
   static deleteFiles (file_field) {

      file_field.files.forEach(ge_file => {

         FileNeeds.putFileToDelete(ge_file);

         // Если есть открпленная подпись, удаляем ее тоже
         if (ge_file.id_sign) {
            SignHandler.removeSign(ge_file);
         }

         ge_file.removeElement();
      });

   }

   /**
    * Добавляет файл в массив для удаления
    *
    * @param ge_file - удаляемый файл
    */
   static putFileToDelete (ge_file) {

      let is_file_saved = ge_file.element.dataset.saved === 'true';
      if (is_file_saved) {
         let to_delete = FileNeeds.file_needs.to_delete;
         to_delete.push(new FileData(ge_file));
      }
   }

   /**
    * Добавляет открепленную подпись в массив для удаления
    *
    * @param {GeFile} ge_file - файл, к которому относится открепленная подпись
    */
   static putSignToDelete (ge_file) {
      if (FileNeeds.sign_needs.to_save.has(ge_file.id_sign)) {
         FileNeeds.sign_needs.to_save.delete(ge_file.id_sign);
      }

      let sign_data = {
         id_file: ge_file.id_sign,
         mapping_level_1: ge_file.field.mapping_1,
         mapping_level_2: ge_file.field.mapping_2
      };

      FileNeeds.sign_needs.to_delete.set(ge_file.id_sign, sign_data);
   }

   /**
    * Добавляет массив с подписями к массиву с файлами для отправки на API file_needs_setter
    */
   static addSigns () {
      FileNeeds.file_needs.to_save = FileNeeds.file_needs.to_save.concat(Array.from(FileNeeds.sign_needs.to_save.values()));
      FileNeeds.file_needs.to_delete = FileNeeds.file_needs.to_delete.concat(Array.from(FileNeeds.sign_needs.to_delete.values()));
   }

   /**
    * Получает json с файлами для сохранения и удаления
    * для отправки на API file_needs_setter
    *
    * @returns {string} json c файлами
    */
   static getFileNeedsJSON () {
      return JSON.stringify(FileNeeds.file_needs);
   }

   /**
    * Очищает массивы с файлами и подписями после
    * отправки на API file_needs_setter
    */
   static clear () {
      FileNeeds.file_needs.to_save = [];
      FileNeeds.file_needs.to_delete = [];
      FileNeeds.sign_needs.to_save = new Map();
      FileNeeds.sign_needs.to_delete = new Map();
   }

   /**
    * Определяет наличие файлов и подписей для сохранения и удаления
    *
    * @returns {boolean} есть ли файлы для сохранения или удаления
    */
   static hasFiles () {
      return (
         FileNeeds.file_needs.to_save.length !== 0
         || FileNeeds.file_needs.to_delete.length !== 0
         || FileNeeds.sign_needs.to_save.size !== 0
         || FileNeeds.sign_needs.to_delete.size !== 0
      );
   }
}

/**
 * Создает объект с данными файла для добавления в массив
 * для удаления или сохранения
 *
 * @param {GeFile} ge_file - файл для добавления в массив
 * @constructor
 */
function FileData (ge_file) {
   this.id_file = ge_file.id;
   this.mapping_level_1 = ge_file.field.mapping_1;
   this.mapping_level_2 = ge_file.field.mapping_2;
}