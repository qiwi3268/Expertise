

document.addEventListener('DOMContentLoaded', () => {
   // let file_needs = new FileNeeds();
   new FileNeeds();
});


class FileNeeds {
   static #file_needs;

   constructor() {
      FileNeeds.#file_needs = new Map();
      FileNeeds.#file_needs.set('to_save', []);
      FileNeeds.#file_needs.set('to_delete', []);
   }

   static getFileNeedsJSON() {
      return JSON.stringify(FileNeeds.#file_needs);
   }

   static putFileToSave(id_file, mapping_level_1, mapping_level_2) {
      let to_save = FileNeeds.#file_needs.get('to_save');
      to_save.push(FileNeeds.getFileData(id_file, mapping_level_1, mapping_level_2));
   }

   static putFileToDelete(id_file, mapping_level_1, mapping_level_2) {
      let to_save = FileNeeds.#file_needs.get('to_save');

      /*to_save.forEach(file_data => {

         if (file_data.id_file === id_file
            && file_data.mapping_level_1 === mapping_level_1
            && file_data.mapping_level_2 === mapping_level_2) {


            let to_delete = FileNeeds.#file_needs.get('to_save');
            to_delete.push(FileNeeds.getFileData(id_file, mapping_level_1, mapping_level_2));
         }
      });*/

   }


   static getFileData(id_file, mapping_level_1, mapping_level_2) {
      return {
         id_file: id_file,
         mapping_level_1: mapping_level_1,
         mapping_level_2: mapping_level_2
      }
   }
}