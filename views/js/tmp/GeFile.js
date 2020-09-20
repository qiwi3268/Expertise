document.addEventListener('DOMContentLoaded', () => {
   let file_blocks = document.querySelectorAll('.files');
   file_blocks.forEach(block => {

      // На просмотре отображаем блоки с файлами
      let files = block.querySelectorAll('.files__item');
      if (files.length > 0) {
         block.classList.add('filled');
      }

      // Добавляем результаты проверок подписей и обрабатки кнопкой действий
      files.forEach(file_element => {
         let file = new GeFile(file_element, block);
         SignView.validateFileField(file.element);
         file.handleActionButtons();
      });
   });

});

class FileField {

   static file_fields = new Map();
   static fields_counter = 0;

   element;

   mapping_1;
   mapping_2;

   files;

   is_active;

   constructor (element) {
      this.element = element;
      this.mapping_1 = parseInt(this.element.dataset.mapping_level_1);
      this.mapping_2 = parseInt(this.element.dataset.mapping_level_2);
      this.files = new Map();

      let id = FileField.fields_counter++;
      this.element.dataset.id_file_field = id;
      FileField.file_fields.set(id, this);
   }

   static getByFile(ge_file) {
      let field = ge_file.container.closest('[data-id_file_field]');
      let id = parseInt(field.dataset.id_file_field);
      return isNaN(id) ? new FileField(field) : this.file_fields.get(id);
   }

   addFile (ge_file) {
      this.files.set(ge_file.id, ge_file);
   }

   isActive () {
      return !this.element.closest('.block[data-active="false"]');
   }

}

/**
 * Представляет собой загруженный на страницу файл
 */
class GeFile {

   static file_blocks = new Map();

   id;

   id_sign;

   /**
    * Блок с файлами
    *
    * @type {HTMLElement}
    */
   container;

   /**
    * Блок файла на странице
    *
    * @type {HTMLElement}
    */
   element;

   /**
    * Блок с кнопками действий файла
    *
    * @type {HTMLElement}
    */
   actions

   /**
    * Кнопка для скачивания файла
    *
    * @type {HTMLElement}
    */
   unload_button;

   /**
    * Кнопка для удаления
    *
    * @type {HTMLElement}
    */
   delete_button;

   /**
    * Кнопка для перехода в окно просмотра или создания подписи,
    * содержит статус подписания файла
    *
    * @type {HTMLElement}
    */
   sign_button;

   /**
    * Поле, к которому относится файл
    *
    * @type {FileField}
    */
   field;

   /**
    * HTMLElement - раздел документации к которому относится файл
    *
    * null - если файл не относится к документации
    *
    * @type {HTMLElement}
    */
   // node;

   /**
    * Первый маппинг
    *
    * @type {number}
    */
   // mapping_1;

   /**
    * Второй маппинг
    *
    * @type {number}
    */
   // mapping_2;

   /**
    * id раздела документации
    *
    * @type {number}
    */
   id_structure_node;


   constructor (file_element, files_block) {
      this.element = file_element;
      this.id = parseInt(this.element.dataset.id);

      this.container = files_block || file_element.closest('.files');

      this.field = FileField.getByFile(this);
      this.field.addFile(this);


      let node = this.container.closest('[data-id_structure_node]');
      if (node) {
         this.id_structure_node = parseInt(node.dataset.id_structure_node);
      }

   }

   /**
    * Добавляет обработчики кнопок действий с файлом
    */
   handleActionButtons () {
      this.actions = this.element.querySelector('.files__actions');

      this.unload_button = this.actions.querySelector('.files__unload');
      if (this.unload_button) {
         this.handleUnloadButton();
      }

      this.delete_button = this.actions.querySelector('.files__delete');
      if (this.delete_button) {
         this.handleDeleteButton();
      }

      this.sign_button = this.element.querySelector('.files__state');
      if (this.sign_button) {
         this.handleSignButton();
      }

   }

   /**
    * Обрабатывает действие скачивания файла
    */
   handleUnloadButton () {

      this.unload_button.addEventListener('click', () => {

         API.checkFile(this.id, this)
            .then(check_result => {
               location.href = API.getUnloadFileURN(check_result);
            })
            .catch(exc => {
               console.error('Ошибка при проверке файла во время скачивания: ' + exc);
            });

      });

   }

   /**
    * Обрабатывает действие удаления
    */
   handleDeleteButton () {
      this.delete_button.addEventListener('click', () => {

         FileNeeds.putFileToDelete(this);

         if (this.id_sign) {
            SignHandler.removeSign(this);
         }

         this.removeElement();
      });
   }

   // Предназначен для удалений блока с файлом
   removeElement () {
      this.element.remove();

      if (!this.container.querySelector('.files__item')) {
         this.container.classList.remove('filled');

         // todo вынести выше
         let parent_select = this.container.previousElementSibling;
         if (parent_select && parent_select.classList.contains('modal-file')) {
            parent_select.classList.remove('filled');
         }
      }
   }

   handleSignButton () {
      this.sign_button.addEventListener('click', () => {
         let sign_state = this.element.dataset.state;

         if (this.element.dataset.read_only && sign_state !== 'not_signed') {

            //todo один класс в другой
            SignView.getInstance().open(this);

         } else if (!this.element.dataset.read_only && sign_state !== 'checking') {

            SignHandler.getInstance().open(this);

         }

      });
   }

   static createElement (file_data, files_block, actions) {
      let file_item = document.createElement('DIV');
      file_item.classList.add('files__item');
      file_item.dataset.id = file_data.id;
      files_block.appendChild(file_item);

      let file = new GeFile(file_item, files_block);

      file.addInfo(file_data);
      file.addState();
      file.addActions(actions);

      return file;
   }

   addState() {
      let sign_state = document.createElement('DIV');
      sign_state.classList.add('files__state');
      this.element.appendChild(sign_state);
      GeFile.setSignState(this.element, 'checking');
      this.spinStateIcon(this);
   }

   spinStateIcon(file) {
      let state_icon = file.element.querySelector('.files__state-icon');
      let degrees = 0;

      let spin = setInterval(() => {
         degrees++;
         state_icon.style.transform = 'rotate(' + degrees + 'deg)';

         if (file.element.dataset.state !== 'checking') {
            clearInterval(spin);
         }

      }, 5);

   }


   static setSignState(file, state) {
      let file_state = file.querySelector('.files__state');
      file_state.innerHTML = '';

      let state_icon = document.createElement('I');
      state_icon.classList.add('files__state-icon', 'fas');
      file_state.appendChild(state_icon);

      let state_text = document.createElement('SPAN');
      state_text.classList.add('files__state-text');
      file_state.appendChild(state_text);

      switch (state) {
         case 'checking':
            state_icon.classList.add('fa-spinner');
            state_text.innerHTML = 'Проверка';
            file.dataset.state = 'checking';
            break;
         case 'valid':
            state_icon.classList.add('fa-pen-alt');
            state_text.innerHTML = 'Подписано';
            file.dataset.state = 'valid';
            break;
         case 'invalid':
            state_icon.classList.add('fa-times');
            state_text.innerHTML = 'Подпись недействительна';
            file.dataset.state = 'invalid';
            break;
         case 'not_signed':
            state_icon.classList.add('fa-times');
            state_text.innerHTML = 'Не подписано';
            file.dataset.state = 'not_signed';
            break;
         case 'warning':
            state_icon.classList.add('fa-exclamation');
            state_text.innerHTML = 'Ошибка сертификата';
            file.dataset.state = 'warning';
            break;

      }

   }

   addActions (actions) {
      this.actions = document.createElement('DIV');
      this.actions.classList.add('files__actions');
      this.element.appendChild(this.actions);

      actions.forEach(action => action(this));
      this.handleActionButtons();
   }

   static unload (file) {
      let unload_button = document.createElement('I');
      unload_button.classList.add('files__unload', 'fas', 'fa-download');
      file.actions.appendChild(unload_button);
   }

   static delete (file) {
      let delete_button = document.createElement('I');
      delete_button.classList.add('files__delete', 'fas', 'fa-minus');
      file.actions.appendChild(delete_button);
   }



   // Предназначен для добавления информации о файле в его блок
   // Принимает параметры-------------------------------
   // file_item     Element : блок с файлом
   // file           Object : объект, с информацией о файле
   addInfo (file_data) {
      let file_info = document.createElement('DIV');
      file_info.classList.add('files__info');
      this.element.appendChild(file_info);

      let file_icon = document.createElement('I');
      file_icon.classList.add('files__icon', 'fas', GeFile.getFileIconClass(file_data.name));
      file_info.appendChild(file_icon);

      let file_description = document.createElement('DIV');
      file_description.classList.add('files__description');
      file_info.appendChild(file_description);

      let file_name = document.createElement('SPAN');
      file_name.classList.add('files__name');
      file_name.innerHTML = file_data.name;
      file_description.appendChild(file_name);

      let file_size = document.createElement('SPAN');
      file_size.classList.add('files__size');
      file_size.innerHTML = file_data.human_file_size;
      file_description.appendChild(file_size);
   }

   static getFileSizeString (file_size) {
      let size;
      let kb = file_size / 1024;

      if (kb > 1024) {
         size = Math.round(kb / 1024) + ' МБ'
      } else {
         size = Math.round(kb) + ' КБ'
      }

      return size;
   }

   static getFileIconClass (file_name) {
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
