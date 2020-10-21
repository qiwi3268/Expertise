document.addEventListener('DOMContentLoaded', () => {
   // GeFile.validate_results_storage = new Map();

   GeFile.initFiles();

});

/**
 * Представляет собой поле для файла, либо блок с документацией
 */
class FileField {

   /**
    * Поля с файлами
    *
    * @type {Map<number, FileField>}
    */
   static file_fields = new Map();

   /**
    * Счетчик полей с файлами
    *
    * @type {number}
    */
   static fields_counter = 0;

   /**
    * Блок поля с файлами
    *
    * @type {HTMLElement}
    */
   element;

   /**
    * Маппинг 1-го уровня
    *
    * @type {number}
    */
   mapping_1;

   /**
    * Маппинг 2-го уровня
    *
    * @type {number}
    */
   mapping_2;

   /**
    * Файлы, которые относятся к полю
    *
    * @type {GeFile[]}
    */
   files;

   /**
    * Создает объект файлового поля
    *
    * @param {HTMLElement} element - блок файлового поля
    */
   constructor (element) {
      this.element = element;
      this.mapping_1 = parseInt(this.element.dataset.mapping_level_1);
      this.mapping_2 = parseInt(this.element.dataset.mapping_level_2);
      this.files = [];

      let id = FileField.fields_counter++;
      this.element.dataset.id_file_field = id;
      FileField.file_fields.set(id, this);
   }

   /**
    * Получает объект файлового поля, к которому относится файл
    *
    * @param {GeFile} ge_file - файл, относящийся к полю
    * @returns {FileField} file_field - объект файлового поля
    */
   static getByFile(ge_file) {
      let field = ge_file.container.closest('[data-id_file_field]');
      let id = parseInt(field.dataset.id_file_field);
      return isNaN(id) ? new FileField(field) : this.file_fields.get(id);
   }

   /**
    * Добавляет файл в массив файлов поля
    *
    * @param {GeFile} ge_file - файл, относящийся к полю
    */
   addFile (ge_file) {
      // this.files.set(ge_file.id, ge_file);
      this.files.push(ge_file);
   }

   /**
    * Определяет скрыто ли файловое поле на странице
    *
    * @returns {boolean} активно ли файловое поле
    */
   isActive () {
      return !this.element.closest('[data-block][data-active="false"]');
   }

}

/**
 * Представляет собой загруженный на страницу файл
 */
class GeFile {

   /**
    * id файла
    *
    * @type {number}
    */
   id;

   /**
    * id файла
    *
    * @type {number}
    */
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
   actions;

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
    * id раздела документации
    *
    * @type {number}
    */
   id_structure_node;

   static initFiles () {
      let ge_files = [];
      let file_blocks = document.querySelectorAll('.files');
      file_blocks.forEach(block => {

         // todo убрать после изменения css
         // На просмотре отображаем блоки с файлами
         let files = block.querySelectorAll('.files__item');
         if (files.length > 0) {
            block.classList.add('filled');
         }

         files.forEach(file_element => {
            let ge_file = new GeFile(file_element, block);
            ge_files.push(ge_file);
            // ge_file.validateFileField();
            ge_file.handleActionButtons();

            if (ge_file.element.hasAttribute('data-validate_results')) {
               let validate_results = ge_file.element.dataset.validate_results;
               ge_file.setValidateResults(validate_results);
               ge_file.element.removeAttribute('data-validate_results');
            }

         });

      });

      ge_files.forEach(ge_file => ge_file.validateFileField());

   }

   static validate_results_storage = new Map();

   getValidateResults () {
      let validate_results;

      if (GeFile.validate_results_storage.has(this.id)) {
         validate_results = GeFile.validate_results_storage.get(this.id);
      } else {
         validate_results = '';
      }

      return validate_results;
   }

   setValidateResults (validate_results) {
      if (!GeFile.validate_results_storage.has(this.id) || validate_results !== '') {
         GeFile.validate_results_storage.set(this.id, validate_results);
      }
   }

   /**
    * Создает объект загруженного на страницу файла
    *
    * @param {HTMLElement} file_element - элемент файла на странице
    * @param {HTMLElement} files_block - файловый блок, к которому относится файл
    */
   constructor (file_element, files_block) {

      this.element = file_element;
      this.id = parseInt(this.element.dataset.id);

      this.container = files_block || file_element.closest('.files');

      this.field = FileField.getByFile(this);
      this.field.addFile(this);

      // Если файл относится к документации
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
               ErrorModal.open('Ошибка при проверке файла во время скачивания', exc);
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

   /**
    * Удаляет файл со страницы
    */
   removeElement () {
      this.element.remove();

      if (!this.container.querySelector('.files__item')) {
         this.container.classList.remove('filled');

         let parent_select = this.field.element.querySelector('.modal-file');
         if (parent_select) {
            parent_select.classList.remove('filled');
         }
      }
   }

   /**
    * Обрабатывает кнопку подписания файла
    * Открывает при нажатии в зависимости от типа страницы
    * модальное окно в режиме просмотра или в режиме создания подписи
    */
   handleSignButton () {
      this.sign_button.addEventListener('click', () => {
         let sign_state = this.element.dataset.state;

         if (this.element.dataset.read_only && sign_state !== 'not_signed') {
            SignView.getInstance().open(this);
         } else if (!this.element.dataset.read_only && sign_state !== 'checking') {
            SignHandler.getInstance().open(this);
         }

      });
   }

   /**
    * Создает элемент и объект файла на странице
    *
    * @param file_data - данные файла, полученные с API file_uploader
    * @param files_block - файловый блок, в который добавляется файл
    * @returns {GeFile} ge_file - объект загруженного файла
    */
   static createElement (file_data, files_block) {
      let file_item = document.createElement('DIV');
      file_item.classList.add('files__item');
      file_item.dataset.id = file_data.id;
      files_block.appendChild(file_item);

      let ge_file = new GeFile(file_item, files_block);

      ge_file.addInfo(file_data);
      ge_file.addState();
      ge_file.addActions();

      return ge_file;
   }

   /**
    * Добавляет имя, размер и иконку файла для отображения на странице
    *
    * @param file_data - данные файла, полученные с API file_uploader
    */
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

   /**
    * Добавляет блок со статусом подписи файла
    */
   addState() {
      let sign_state = document.createElement('DIV');
      sign_state.classList.add('files__state');
      this.element.appendChild(sign_state);
      this.setSignState('checking');
      this.spinStateIcon(this);
   }

   /**
    * Крутит иконку статуса подписи во время проверки
    *
    * @param {GeFile} file - файл к которому относится иконка
    */
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

   /**
    * Устанавливает статус подписи файла
    *
    * @param {string} state - строковое значение статуса подписи
    */
   setSignState(state) {
      let file_state = this.element.querySelector('.files__state');
      file_state.innerHTML = '';

      let state_icon = document.createElement('I');
      state_icon.classList.add('files__state-icon', 'fas');
      file_state.appendChild(state_icon);

      this.element.dataset.state = state;

      if (file_state.dataset.type !== 'short') {
         let state_text = document.createElement('SPAN');
         state_text.classList.add('files__state-text');
         file_state.appendChild(state_text);

         switch (state) {
            case 'checking':
               state_icon.classList.add('fa-spinner');
               state_text.innerHTML = 'Проверка';
               break;
            case 'valid':
               state_icon.classList.add('fa-pen-alt');
               state_text.innerHTML = 'Подписано';
               break;
            case 'invalid':
               state_icon.classList.add('fa-times');
               state_text.innerHTML = 'Подпись недействительна';
               break;
            case 'not_signed':
               state_icon.classList.add('fa-times');
               state_text.innerHTML = 'Не подписано';
               break;
            case 'warning':
               state_icon.classList.add('fa-exclamation');
               state_text.innerHTML = 'Ошибка сертификата';
               break;
         }
      } else {
         state_icon.classList.add('fa-pen-alt');
      }

   }

   /**
    * Добавляет файлу блок с кнопками действий
    */
   addActions () {
      this.actions = document.createElement('DIV');
      this.actions.classList.add('files__actions');
      this.element.appendChild(this.actions);

      let unload_button = document.createElement('I');
      unload_button.classList.add('files__unload', 'fas', 'fa-angle-double-down');
      this.actions.appendChild(unload_button);

      let delete_button = document.createElement('I');
      delete_button.classList.add('files__delete', 'fas', 'fa-minus');
      this.actions.appendChild(delete_button);

      this.handleActionButtons();
   }

   /**
    * Получает строку с размером файла по размеру в байтах
    *
    * @param {number} file_size - размер файла в байтах
    * @returns {string} размер файла с единицой измерения
    */
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

   /**
    * Возвращает строку с классом иконки файла в зависимости от его типа
    *
    * @param {string} file_name - имя файла
    * @returns {string} класс иконки файла
    */
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

   /**
    * Отображает состояние проверки подписи в поле с файлом
    *
    */
   validateFileField () {
      let validate_results = this.getValidateResults();
      let sign_state = 'not_signed';

      if (validate_results) {
         let results = JSON.parse(validate_results);

         for (let result of results) {
            if (result.signature_verify.result && result.certificate_verify.result) {
               sign_state = 'valid';
            } else if (result.signature_verify.result) {
               sign_state = 'warning';
               break;
            } else {
               sign_state = 'invalid';
               break;
            }
         }

      }
      this.setSignState(sign_state);
   }

}
