class GeFile {

   container;
   element;

   unload_button;
   delete_button;
   sign_button;

   constructor(file_element, files_block) {
      this.element = file_element;
      this.container = files_block;
   }

   // Предназначен для добавления обработчиков кнопок действий с файлами
   handleActionButtons() {
      this.actions = this.element.querySelector('.files__actions');

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

   static createElement(file_data, files_block, actions) {
      let file_item = document.createElement('DIV');
      file_item.classList.add('files__item');
      file_item.dataset.id = file_data.id;
      files_block.appendChild(file_item);

      let file = new GeFile(file_item, files_block);
      file.addInfo(file_data);
      file.addActions(file)
   }

   addActions(file, actions) {
      actions.forEach(action => action(file));
      file.handleActionButtons();
   }

   static sign(file) {
      let sign_button = document.createElement('I');
      sign_button.classList.add('files__sign', 'fas', 'fa-file-signature');
      file.actions.appendChild(sign_button);
   }

   static unload(file) {
      let unload_button = document.createElement('I');
      unload_button.classList.add('files__unload', 'fas', 'fa-file-download');
      file.element.appendChild(unload_button);
   }

   static delete(file) {
      let delete_button = document.createElement('I');
      delete_button.classList.add('files__delete', 'fas', 'fa-trash');
      file.element.appendChild(delete_button);
   }

   // Предназначен для добавления информации о файле в его блок
   // Принимает параметры-------------------------------
   // file_item     Element : блок с файлом
   // file           Object : объект, с информацией о файле
   addInfo(file_data) {
      let file_info = document.createElement('DIV');
      file_info.classList.add('files__info');
      this.element.appendChild(file_info);

      let file_icon = document.createElement('I');
      file_icon.classList.add('files__icon', 'fas', GeFile.getFileIconClass(file_data.name));
      file_info.appendChild(file_icon);

      let file_name = document.createElement('SPAN');
      file_name.classList.add('files__name');
      file_name.innerHTML = file_data.name;
      file_info.appendChild(file_name);
   }

   static getFileSizeString(file_data) {
      let size;
      let kb = file_data.size / 1024;

      if (kb > 1024) {
         size = Math.round(kb / 1024) + ' МБ'
      } else {
         size = Math.round(kb) + ' КБ'
      }

      return size;
   }

   static getFileIconClass(file_name) {
      let icon_class = 'fa-file-alt';

      if (file_name.includes('.pdf')) {
         icon_class = 'fa-file-pdf';
      } else if (file_name.includes('.docx')) {
         icon_class = 'fa-file-word';
      } else if (file_name.includes('.xlsx')) {
         icon_class = 'fa-file-excel';
      }

      return icon_class;
   }

}
