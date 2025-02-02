document.addEventListener('DOMContentLoaded', () => {

   GeFile.initFiles();

});

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
    * Флаг указывающий, содержит ли файл встроенную подпись
    *
    * Равен undefined для всех файлов на страницах, на которых
    * нет возможности загружать файлы
    *
    * @type {boolean}
    */
   is_internal_sign;

   /**
    * Блок с файлами
    *
    * @type {HTMLElement}
    */
   container;

   /**
    * Элемент файла на странице
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

   static validate_results_storage = new Map();

   static initFiles () {
      let ge_files = [];
      let file_blocks = document.querySelectorAll('.files');
      file_blocks.forEach(block => {

         let files = block.querySelectorAll('.files__item');
         files.forEach(file_element => {
            let ge_file = new GeFile(file_element, block);
            ge_file.handleActionButtons();

            if (ge_file.element.hasAttribute('data-validate_results')) {
               let validate_results = ge_file.element.dataset.validate_results;
               ge_file.setValidateResults(validate_results);
               ge_file.element.removeAttribute('data-validate_results');
            }

            ge_files.push(ge_file);
         });

      });

      ge_files.forEach(ge_file => ge_file.validateFileField());
   }

   getValidateResults () {
      let validate_results = null;

      if (GeFile.validate_results_storage.has(this.id)) {
         validate_results = GeFile.validate_results_storage.get(this.id);
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

      this.unload_button = this.actions.querySelector('[data-file_unload]');
      if (this.unload_button) {
         this.handleUnloadButton();
      }

      this.delete_button = this.actions.querySelector('[data-file_delete]');
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
         this.field.element.classList.remove('filled');
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

         if (this.field.read_only && sign_state !== 'not_signed') {
            SignView.getInstance().open(this);
         } else if (!this.field.read_only && sign_state !== 'checking') {
            SignHandler.getInstance().open(this);
         }

      });
   }

   /**
    * Создает элемент и объект файла на странице,
    * добавляет элемент в файловое поле
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
    * Проверяет подписи файла и отображает статус подписания
    */
   handleInternalSigns () {

      API.checkFile(this.id, this)
         .then(check_response => {
            return API.internalSignatureVerify(check_response.fs_name, this);
         })
         .then(validate_results => {

            if (validate_results) {

               this.setValidateResults(JSON.stringify(validate_results));
               this.is_internal_sign = true;
               this.validateFileField();

            } else {
               this.setSignState('not_signed');
            }

         })
         .catch(exc => {
            ErrorModal.open('Ошибка при проверке подписи файла', exc.message, exc.code);
            this.removeElement();
         });
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
      unload_button.classList.add('files__action', 'unload', 'fas', 'fa-angle-double-down');
      unload_button.setAttribute('data-file_unload', '');
      this.actions.appendChild(unload_button);

      let delete_button = document.createElement('I');
      delete_button.classList.add('files__action', 'delete', 'fas', 'fa-minus');
      delete_button.setAttribute('data-file_delete', '');
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
