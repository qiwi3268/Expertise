document.addEventListener('DOMContentLoaded', () => {
   let file_blocks = document.querySelectorAll('.files');

   file_blocks.forEach(block => {
      new FileBlock(block);
   });


});

class FileBlock {
   element;
   actions;

   file;
   unload_button;

   document_id;
   mapping_1;
   mapping_2;
   id_structure_node;

   constructor(block) {

      this.element = block;

      if (this.element.querySelector('.files__item')) {
         this.actions = this.element.querySelector('.files__actions');
         this.element.classList.add('filled');
         this.initFieldData();
         this.handleActionButtons();
      }

   }

   initFieldData() {
      let parent_field = this.element.closest('[data-mapping_level_1]');
      let parent_node = this.element.closest('[data-id_structure_node]');

      this.document_id = getIdApplication();

      this.mapping_1 = parent_field.dataset.mapping_level_1;
      this.mapping_2 = parent_field.dataset.mapping_level_1;

      if (parent_node) {
         this.id_structure_node = parent_field.dataset.id_structure_node;
      }

   }

   // Предназначен для добавления обработчиков кнопок действий с файлами

   handleActionButtons() {
      this.file = this.actions.closest('.files__item');

      this.unload_button = this.actions.querySelector('.files__unload');
      if (this.unload_button) {
         this.handleUnloadButton();
      }

      let delete_button = this.actions.querySelector('.files__delete');
      if (delete_button) {
         handleDeleteButton(delete_button);
      }
   }


   // Предназначен для добавления действия для скачивания файла
   // Принимает параметры-------------------------------
   // unload_button         Element : кнопка для скачивания файла
   handleUnloadButton() {

      this.unload_button.addEventListener('click', () => {

         API.checkFile(this.file.dataset.id, this.mapping_1, this.mapping_2, this.id_structure_node)
            .then(check_result => {
               location.href = API.getUnloadFileURN(check_result);
            })
            .catch(exc => {
               console.error('Ошибка при проверке файла во время скачивания: ' + exc);
            });

      });

   }

   // Предназначен для добавления действия удаления файла
   // Принимает параметры-------------------------------
   // delete_button         Element : кнопка для удаления
   static handleDeleteButton(delete_button) {
      let file = delete_button.closest('.files__item');
      let files = file.closest('.files');

      delete_button.addEventListener('click', () => {
         deleteFile(file);
         removeFileElement(file, files);
      });
   }


}





























