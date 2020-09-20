// Предназначен для проставления меток к файлам для сохранения и удаления
class FileNeeds {
   static file_needs = {
      to_save: [],
      to_delete: []
   };

   static sign_needs = {
      to_save: new Map(),
      to_delete: new Map()
   };

/*   // Предназначен для добавления файлов в массивы для сохранения и удаления
   // во всех блоках с файлами
   static putFilesToFileNeeds () {
      let file_blocks = document.querySelectorAll('.files');
      let parent_field;
      let parent_block;
      let is_active;
      let files;

      file_blocks.forEach(file_block => {
         if (file_block.innerHTML) {
            parent_field = file_block.closest('[data-mapping_level_1]');
            parent_block = parent_field.closest('.block');

            is_active = parent_block.dataset.active !== 'false';

            files = parent_field.querySelectorAll('.files__item');
            // Если блок не скрыт, сохраняем все файлы в нем, иначе - удаляем

            if (is_active) {
               FileNeeds.saveFiles(files, parent_field);
            } else {
               FileNeeds.deleteFiles(files, parent_field, file_block);
            }
         }
      });

      FileNeeds.addSigns();
   }*/

   static putFilesToFileNeeds () {

      console.log(FileField.file_fields);

      FileField.file_fields.forEach(file_field => {

         if (file_field.isActive()) {
            FileNeeds.saveFiles(file_field);
         } else {
            FileNeeds.deleteFiles(file_field);
         }

      });

      FileNeeds.addSigns();

   }

   // Предназначен для добавления файлов в массив для сохранения
   // Принимает параметры-------------------------------
   // files            Array[Element] : массив с элементами файлов
   // parent_field            Element : родительское поле блока с файлами
   static saveFiles (file_field) {
      file_field.files.forEach(ge_file => FileNeeds.putFileToSave(ge_file));
   }

   // Предназначен для добавления файла в массив для сохранения
   // Принимает параметры-------------------------------
   // id_file             string : id файла
   // mapping_level_1     string : первый маппинг
   // mapping_level_2     string : второй маппинг
   // file_item          Element : элемент файла
   static putFileToSave (ge_file) {
      let is_file_saved = ge_file.element.dataset.saved === 'true';

      if (!is_file_saved) {
         let to_save = FileNeeds.file_needs.to_save;
         to_save.push(new FileData(ge_file));

         ge_file.element.dataset.saved = 'true';
      }
   }


   static putSignToSave (ge_file) {
      let file_data = {
         id_file: ge_file.id_sign,
         mapping_level_1: ge_file.field.mapping_1,
         mapping_level_2: ge_file.field.mapping_2
      }

      FileNeeds.sign_needs.to_save.set(ge_file.id_sign, file_data);
   }

   // Предназначен для добавления файлов в массив для удаления
   // Принимает параметры-------------------------------
   // files            Array[Element] : массив с элементами файлов
   // parent_field            Element : родительское поле блока с файлами
   // file_block              Element : родительский блок с файлами
   static deleteFiles (file_field) {

      file_field.files.forEach(ge_file => {

         FileNeeds.putFileToDelete(ge_file);

         if (ge_file.id_sign) {
            SignHandler.removeSign(ge_file);
         }

         ge_file.removeElement();

      });

   }

   // Предназначен для добавления файла в массив для удаления
   // Принимает параметры-------------------------------
   // id_file             string : id файла
   // mapping_level_1     string : первый маппинг
   // mapping_level_2     string : второй маппинг
   // file_item          Element : элемент файла
   static putFileToDelete (ge_file) {

      let is_file_saved = ge_file.element.dataset.saved === 'true';
      if (is_file_saved) {
         let to_delete = FileNeeds.file_needs.to_delete;
         to_delete.push(new FileData(ge_file));
      }
   }

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

   static addSigns () {
      FileNeeds.file_needs.to_save = FileNeeds.file_needs.to_save.concat(Array.from(FileNeeds.sign_needs.to_save.values()));
      FileNeeds.file_needs.to_delete = FileNeeds.file_needs.to_delete.concat(Array.from(FileNeeds.sign_needs.to_delete.values()));
   }

   // Предназначен для получения объекта с массивами сохранения и удаления файлов
   static getFileNeeds () {
      return FileNeeds.file_needs;
   }

   // Предназначен для получения массивов сохранения и удаления файлов в формате json
   static getFileNeedsJSON () {
      return JSON.stringify(FileNeeds.file_needs);
   }

   // Предназначен для очистки массивов сохранения и удаления файлов
   static clear () {
      FileNeeds.file_needs.to_save = [];
      FileNeeds.file_needs.to_delete = [];
      FileNeeds.sign_needs.to_save = new Map();
      FileNeeds.sign_needs.to_delete = new Map();
   }

   // Предназначен для определения наличия файлов для сохранения или удаления
   // Возвращает параметры------------------------------
   // has_files     boolean : есть ли файлы для сохранения или удаления
   static hasFiles () {
      return (
         FileNeeds.file_needs.to_save.length !== 0
         || FileNeeds.file_needs.to_delete.length !== 0
         || FileNeeds.sign_needs.to_save.size !== 0
         || FileNeeds.sign_needs.to_delete.size !== 0
      );
   }
}

function FileData (ge_file) {
   this.id_file = ge_file.id;
   this.mapping_level_1 = ge_file.field.mapping_1;
   this.mapping_level_2 = ge_file.field.mapping_2;
}