class FileNeeds {
   static #file_needs = {
      to_save: [],
      to_delete: []
   };

   static putFilesToSave() {
      let file_blocks = document.querySelectorAll('.modal-file');
      let parent_row;
      let files;

      file_blocks.forEach(file_block => {
         parent_row = file_block.closest('[data-mapping_level_1]');

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
      });

   }

   static getFileNeeds() {
      return FileNeeds.#file_needs;
   }

   static getFileNeedsJSON() {
      return JSON.stringify(FileNeeds.#file_needs);
   }

   static putFileToSave(id_file, mapping_level_1, mapping_level_2) {
      let to_save = FileNeeds.#file_needs.to_save;
      let file_data = FileNeeds.getFileData(id_file, mapping_level_1, mapping_level_2);

      if (FileNeeds.getFileToSaveIndex(file_data) === null) {
         to_save.push(FileNeeds.getFileData(id_file, mapping_level_1, mapping_level_2));
      }
   }

   static putFileToDelete(id_file, mapping_level_1, mapping_level_2) {
      let file_data = FileNeeds.getFileData(id_file, mapping_level_1, mapping_level_2);
      let to_save = FileNeeds.#file_needs.to_save;

      let to_save_index = FileNeeds.getFileToSaveIndex(file_data);
      if (to_save_index !== null) {
         to_save.splice(to_save_index, 1);
      } else {
         let to_delete = FileNeeds.#file_needs.to_delete;
         to_delete.push(FileNeeds.getFileData(id_file, mapping_level_1, mapping_level_2));
      }
   }

   static getFileToSaveIndex(file_data) {
      let index = null;

      let to_save = FileNeeds.#file_needs.to_save;
      for (let i = 0; i < to_save.length; i++) {

         if (to_save[i].id_file === file_data.id_file
            && to_save[i].mapping_level_1 === file_data.mapping_level_1
            && to_save[i].mapping_level_2 === file_data.mapping_level_2
         ) {
            index = i;
         }
      }

      return index;
   }

   static getFileData(id_file, mapping_level_1, mapping_level_2) {
      return {
         id_file: parseInt(id_file),
         mapping_level_1: parseInt(mapping_level_1),
         mapping_level_2: parseInt(mapping_level_2)
      }
   }

   static clear() {
      FileNeeds.#file_needs.to_save = [];
      FileNeeds.#file_needs.to_delete = [];
   }

   static isHasFiles() {
      return FileNeeds.#file_needs.to_save.length !== 0 || FileNeeds.#file_needs.to_delete.length !== 0;
   }
}
