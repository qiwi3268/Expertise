class FileNeeds {
   static file_needs = {
      to_save: [],
      to_delete: []
   };

   static putFilesToSave() {
      let file_blocks = document.querySelectorAll('.files');
<<<<<<< HEAD
      let parent_field;
      let is_active;
=======
      let parent_row;
>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb
      let files;

      file_blocks.forEach(file_block => {
         if (file_block.innerHTML) {
<<<<<<< HEAD
            parent_field = file_block.closest('[data-mapping_level_1]');
            is_active = parent_field.dataset.inactive !== 'true';

            files = parent_field.querySelectorAll('.files__item');
            if (is_active) {
               FileNeeds.saveFiles(files, parent_field);
            } else {
               FileNeeds.deleteFiles(files, parent_field, file_block);
=======
            parent_row = file_block.closest('[data-mapping_level_1]');

            if (parent_row.dataset.inactive !== 'true') {
               files = parent_row.querySelectorAll('.files__item');

               files.forEach(file => {
                  if (file.dataset.saved !== 'true') {
                     let id_file = file.dataset.id;
                     let mapping_level_1 = parent_row.dataset.mapping_level_1;
                     let mapping_level_2 = parent_row.dataset.mapping_level_2;

                     FileNeeds.putFileToSave(id_file, mapping_level_1, mapping_level_2);
                     file.dataset.saved = 'true';
                  }
               });
>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb
            }
         }

      });
<<<<<<< HEAD
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
=======

>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb
   }

   static getFileNeeds() {
      return FileNeeds.file_needs;
   }

   static getFileNeedsJSON() {
      return JSON.stringify(FileNeeds.file_needs);
   }

<<<<<<< HEAD
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
=======
   static putFileToSave(id_file, mapping_level_1, mapping_level_2) {
      let to_save = FileNeeds.file_needs.to_save;
      let file_data = FileNeeds.getFileData(id_file, mapping_level_1, mapping_level_2);

      to_save.push(file_data);
   }

   static putFileToDelete(id_file, mapping_level_1, mapping_level_2, file_item) {
      let file_data = FileNeeds.getFileData(id_file, mapping_level_1, mapping_level_2);

      let is_file_saved = file_item.dataset.saved;
      if (is_file_saved) {
         let to_delete = FileNeeds.file_needs.to_delete;
>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb
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
