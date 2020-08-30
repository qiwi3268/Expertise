class GeFile {

   container;
   element;

   unload_button;
   delete_button;
   sign_button;

   constructor(file_element, files_block) {
      this.element = file_element;
      this.container = files_block;

      this.actions = this.element.querySelector('.files__actions');

      this.handleActionButtons();
   }

   // Предназначен для добавления обработчиков кнопок действий с файлами
   handleActionButtons() {

      this.unload_button = this.actions.querySelector('.files__unload');
      if (this.unload_button) {
         this.handleUnloadButton();
      }

      this.delete_button = this.actions.querySelector('.files__delete');
      if (this.delete_button) {
         this.handleDeleteButton();
      }

      this.sign_button = this.actions.querySelector('.files__sign');
      if (this.sign_button) {
         this.handleSignButton();
      }

   }


   // Предназначен для добавления действия для скачивания файла
   handleUnloadButton() {

      this.unload_button.addEventListener('click', () => {

         API.checkFile(
            this.element.dataset.id,
            this.container.mapping_1,
            this.container.mapping_2,
            this.container.id_structure_node
         )
            .then(check_result => {
               location.href = API.getUnloadFileURN(check_result);
            })
            .catch(exc => {
               console.error('Ошибка при проверке файла во время скачивания: ' + exc);
            });

      });

   }

   // Предназначен для добавления действия удаления файла
   handleDeleteButton() {
      this.delete_button.addEventListener('click', () => {

         FileNeeds.putFileToDelete(
            this.element.dataset.id,
            this.container.mapping_1,
            this.container.mapping_2,
            this.element
         );

         if (this.element.dataset.id_sign) {
            SignHandler.removeSign(this.element);
         }

         this.removeElement();
      });
   }



   // Предназначен для удалений блока с файлом
   removeElement() {
      this.element.remove();

      if (!this.container.querySelector('.files__item')) {
         this.container.classList.remove('filled');

         let parent_select = this.container.previousElementSibling;
         if (parent_select && parent_select.classList.contains('modal-file')) {
            parent_select.classList.remove('filled');
         }
      }
   }

   handleSignButton() {
      this.sign_button.addEventListener('click', () => {
         SignHandler.getInstance().open(this.element);
      });
   }


}
