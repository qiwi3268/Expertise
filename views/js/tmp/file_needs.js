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

   // Предназначен для добавления файлов в массивы для сохранения и удаления
   // во всех блоках с файлами
   static putFilesToFileNeeds() {
      let file_blocks = document.querySelectorAll('.files');
      let parent_field;
      let is_active;
      let files;

      file_blocks.forEach(file_block => {
         if (file_block.innerHTML) {
            parent_field = file_block.closest('[data-mapping_level_1]');
            is_active = parent_field.dataset.inactive !== 'true';

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
   }

   // Предназначен для добавления файлов в массив для сохранения
   // Принимает параметры-------------------------------
   // files            Array[Element] : массив с элементами файлов
   // parent_field            Element : родительское поле блока с файлами
   static saveFiles(files, parent_field) {
      files.forEach(file => {
         FileNeeds.putFileToSave(
            file.dataset.id,
            parent_field.dataset.mapping_level_1,
            parent_field.dataset.mapping_level_2,
            file
         );
         file.dataset.saved = 'true';
      });
   }

   // Предназначен для добавления файла в массив для сохранения
   // Принимает параметры-------------------------------
   // id_file             string : id файла
   // mapping_level_1     string : первый маппинг
   // mapping_level_2     string : второй маппинг
   // file_item          Element : элемент файла
   static putFileToSave(id_file, mapping_level_1, mapping_level_2, file_item) {
      let is_file_saved = file_item.dataset.saved === 'true';
      if (!is_file_saved) {
         //TODO в метод
         let to_save = FileNeeds.file_needs.to_save;
         let file_data = FileNeeds.getFileData(id_file, mapping_level_1, mapping_level_2);
         to_save.push(file_data);
      }
   }

   static putSignToSave(id_sign, mapping_level_1, mapping_level_2) {
      let file_data = FileNeeds.getFileData(id_sign, mapping_level_1, mapping_level_2);
      FileNeeds.sign_needs.to_save.set(id_sign, file_data);
   }

   // Предназначен для получения объект с данными файла
   // Принимает параметры-------------------------------
   // id_file             string : id файла
   // mapping_level_1     string : первый маппинг
   // mapping_level_2     string : второй маппинг
   // Возвращает параметры------------------------------
   // file_data           Object : объект с данными файла
   static getFileData(id_file, mapping_level_1, mapping_level_2) {
      return {
         id_file: parseInt(id_file),
         mapping_level_1: parseInt(mapping_level_1),
         mapping_level_2: parseInt(mapping_level_2)
      }
   }

   // Предназначен для добавления файлов в массив для удаления
   // Принимает параметры-------------------------------
   // files            Array[Element] : массив с элементами файлов
   // parent_field            Element : родительское поле блока с файлами
   // file_block              Element : родительский блок с файлами
   static deleteFiles(files, parent_field, file_block) {
      files.forEach(file => {
         FileNeeds.putFileToDelete(
            file.dataset.id,
            parent_field.dataset.mapping_level_1,
            parent_field.dataset.mapping_level_2,
            file
         );
         removeFileElement(file, file_block);
      });
   }

   // Предназначен для добавления файла в массив для удаления
   // Принимает параметры-------------------------------
   // id_file             string : id файла
   // mapping_level_1     string : первый маппинг
   // mapping_level_2     string : второй маппинг
   // file_item          Element : элемент файла
   static putFileToDelete(id_file, mapping_level_1, mapping_level_2, file_item) {
      let is_file_saved = file_item.dataset.saved === 'true';
      if (is_file_saved) {
         let to_delete = FileNeeds.file_needs.to_delete;
         let file_data = FileNeeds.getFileData(id_file, mapping_level_1, mapping_level_2);
         to_delete.push(file_data);
      }
   }

   static putSignToDelete(id_sign, mapping_level_1, mapping_level_2) {
      if (FileNeeds.sign_needs.to_save.has(id_sign)) {
         FileNeeds.sign_needs.to_save.delete(id_sign);
      } else {
         let sign_data = FileNeeds.getFileData(id_sign, mapping_level_1, mapping_level_2);
         FileNeeds.sign_needs.to_delete.set(id_sign, sign_data);
      }
   }

   static addSigns() {
      FileNeeds.file_needs.to_save.concat(FileNeeds.sign_needs.to_save.values());
      FileNeeds.file_needs.to_delete.concat(FileNeeds.sign_needs.to_delete.values());
   }

   // Предназначен для получения объекта с массивами сохранения и удаления файлов
   static getFileNeeds() {
      return FileNeeds.file_needs;
   }

   // Предназначен для получения массивов сохранения и удаления файлов в формате json
   static getFileNeedsJSON() {
      return JSON.stringify(FileNeeds.file_needs);
   }

   // Предназначен для очистки массивов сохранения и удаления файлов
   static clear() {
      FileNeeds.file_needs.to_save = [];
      FileNeeds.file_needs.to_delete = [];
      FileNeeds.sign_needs.to_save = new Map();
      FileNeeds.sign_needs.to_delete = new Map();
   }

   // Предназначен для определения наличия файлов для сохранения или удаления
   // Возвращает параметры------------------------------
   // has_files     boolean : есть ли файлы для сохранения или удаления
   static hasFiles() {
      return FileNeeds.file_needs.to_save.length !== 0
         || FileNeeds.file_needs.to_delete.length !== 0
         || FileNeeds.sign_needs.to_save.size !== 0
         || FileNeeds.sign_needs.to_delete.size !== 0;
   }
}
