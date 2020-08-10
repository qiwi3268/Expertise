class FileNeeds {
   static file_needs = {
      to_save: [],
      to_delete: []
   };

   static putFilesToSave() {
      let file_blocks = document.querySelectorAll('.files');
      let parent_field;
      let is_active;
      let files;

      file_blocks.forEach(file_block => {
         if (file_block.innerHTML) {
            parent_field = file_block.closest('[data-mapping_level_1]');
            is_active = parent_field.dataset.inactive !== 'true';

            files = parent_field.querySelectorAll('.files__item');
            if (is_active) {
               FileNeeds.saveFiles(files, parent_field);
            } else {
               FileNeeds.deleteFiles(files, parent_field, file_block);
            }
         }

      });
   }

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

   static getFileNeeds() {
      return FileNeeds.file_needs;
   }

   static getFileNeedsJSON() {
      return JSON.stringify(FileNeeds.file_needs);
   }

   static putFileToSave(id_file, mapping_level_1, mapping_level_2, file_item) {
      let is_file_saved = file_item.dataset.saved === 'true';
      if (!is_file_saved) {
         let to_save = FileNeeds.file_needs.to_save;
         let file_data = FileNeeds.getFileData(id_file, mapping_level_1, mapping_level_2);
         to_save.push(file_data);
      }
   }

   static putFileToDelete(id_file, mapping_level_1, mapping_level_2, file_item) {
      let is_file_saved = file_item.dataset.saved === 'true';
      if (is_file_saved) {
         let to_delete = FileNeeds.file_needs.to_delete;
         let file_data = FileNeeds.getFileData(id_file, mapping_level_1, mapping_level_2);
         to_delete.push(file_data);
      }
   }

   static getFileData(id_file, mapping_level_1, mapping_level_2) {
      return {
         id_file: parseInt(id_file),
         mapping_level_1: parseInt(mapping_level_1),
         mapping_level_2: parseInt(mapping_level_2)
      }
   }

   static clear() {
      FileNeeds.file_needs.to_save = [];
      FileNeeds.file_needs.to_delete = [];
   }

   static isHasFiles() {
      return FileNeeds.file_needs.to_save.length !== 0 || FileNeeds.file_needs.to_delete.length !== 0;
   }
}
