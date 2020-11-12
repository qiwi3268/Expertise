/**
 * @typedef CertificateData
 * @type {object}
 * @property {string} subject_name - имя подписанта
 * @property {string} issuer_name - имя издателя сертификата
 * @property {Date} valid_from_date - дата начала действия
 * @property {Date} valid_to_date - дата окончания действия
 * @property {string} cert_message - описание состояния сертификата
 * @property {boolean} cert_status - статус валидности сертификата
 */

/**
 * Представляет собой модуль подписания файла,
 * расширяет модуль просмотра подписи
 */
class SignHandler extends SignView{

   /**
    * Объект модуля подписания
    *
    * @type {SignHandler}
    */
   static instance;

   /**
    * Модальное окно модуля подписания
    *
    * @type {HTMLElement}
    */
   modal;

   /**
    * Фон модального окна
    *
    * @type {HTMLElement}
    */
   overlay;

   /**
    * Флаг указывающий проинициализирован ли плагин криптоПТО
    *
    * @type {boolean}
    */
   is_plugin_initialized = false;

   /**
    * Флаг указывающий, что в данный момент идет процесс подписания
    *
    * @type {boolean}
    */
   is_signing = false;

   /**
    * Блок с информацией о версии плагина и криптопровайдера
    *
    * @type {HTMLElement}
    */
   plugin_info;

   /**
    * Блок с результатами проверки подписей
    *
    * @type {HTMLElement}
    */
   validate_info;

   /**
    * Блок с сертификатами пользователя
    *
    * @type {HTMLElement}
    */
   certs;

   /**
    * Блок с описанием выбранного сертификата
    *
    * @type {HTMLElement}
    */
   cert_info;

   /**
    * Кнопка загрузки файла открепленной подписи
    *
    * @type {HTMLElement}
    */
   upload_sign_btn;

   /**
    * Кнопка создания открепленной подписи
    *
    * @type {HTMLElement}
    */
   create_sign_btn;

   /**
    * Кнопка удаления открепленной подписи
    *
    * @type {HTMLElement}
    */
   delete_sign_btn;

   /**
    * Кнопка "Подписать" для создания открепленной подписи
    * после выбора сертификата
    *
    * @type {HTMLElement}
    */
   sign_btn;

   /**
    * Кнопка отмены действия создания открепленной подписи
    *
    * @type {HTMLElement}
    */
   cancel_btn;

   /**
    * Блок с кнопками подписания и отмены создания подписи
    *
    * @type {HTMLElement}
    */
   actions;

   /**
    * Инпут, в который загружается файл открепленной подписи
    *
    * @type {HTMLInputElement}
    */
   external_sign_input;

   /**
    * Файл, для которого открыт модуль подписания
    *
    * @type {GeFile}
    */
   ge_file;

   /**
    * Получает объект модуля подписания
    *
    * @returns {SignHandler}
    */
   static getInstance () {

      if (!this.instance) {
         this.instance = new SignHandler();
      }

      return this.instance;
   }

   /**
    * Удаляет открепленную подпись файла
    *
    * @param ge_file - файл, у которого удаляется подпись
    */
   static removeSign (ge_file) {

      FileNeeds.putSignToDelete(ge_file);

      // ge_file.is_internal_sign = false;

      GeFile.validate_results_storage.delete(ge_file.id);
      // ge_file.element.removeAttribute('data-id_sign');
      // ge_file.element.removeAttribute('data-validate_results');

      ge_file.setSignState('not_signed');
   }

   /**
    * Создает объект модуля подписания
    */
   constructor () {
      super();

      this.modal = mQS(document, '.sign-modal', 12);
      this.validate_info = mQS(this.modal, '.sign-modal__validate', 14);

      this.plugin_info = mQS(this.modal, '.sign-modal__header', 13);
      this.certs = mQS(this.modal, '.sign-modal__certs', 15);
      this.cert_info = mQS(this.modal, '.sign-modal__cert-info', 17);
      this.actions = mQS(this.modal, '.sign-modal__actions', 16);

      this.handleOverlay();

      this.handleCreateSignButton();
      this.handleUploadSignButton();
      this.handleDeleteSignButton();

      this.handleCancelButton();
      this.handleSignButton();
   }

   /**
    * Закрывает модальное окно модуля подписания
    */
   closeModal () {
      if (!this.is_signing) {
         this.modal.classList.remove('active');
         this.overlay.classList.remove('active');

         this.create_sign_btn.dataset.active = 'false';
         this.upload_sign_btn.dataset.active = 'false';
         this.delete_sign_btn.dataset.active = 'false';

         this.closeInfoBlocks();
      }
   }

   /**
    * Скрывает информационные блоки модуля подписания
    */
   closeInfoBlocks () {
      this.certs.dataset.active = 'false';
      this.plugin_info.dataset.active = 'false';
      this.actions.dataset.active = 'false';
      this.validate_info.dataset.active = 'false';
   }

   /**
    * Инициализирует плагин и отображает элементы для создания подписи
    * при нажатии на кнопку "Создать открепленную подпись"
    */
   handleCreateSignButton () {
      this.create_sign_btn = document.getElementById('sign_create');
      this.create_sign_btn.addEventListener('click', () => {

         if (!this.is_plugin_initialized && BrowserHelper.checkBrowser()) {
            this.initializePlugin();
         } else {
            this.showCreateSignElements();
         }

      });

   }

   /**
    * Инициализирует плагин для подписания
    */
   initializePlugin () {

      // Берем объект плагина
      GeCades.getCadesPlugin()
         // Получаем информацию о версии плагина
         .then(() => {
            return GeCades.getPluginData();
         })
         // Получаем сертификаты пользователя
         .then(plugin_data => {
            this.putPluginData(plugin_data);
            return GeCades.getCerts();
         })
         // Добавляем блок с сертификатами
         .then(certs => {
            this.handleCertListSelect(certs);
            this.showCreateSignElements();
            this.is_plugin_initialized = true;
         })
         .catch(exc => {
            ErrorModal.open('Ошибка инициализации плагина и заполнения списка сертификатов', exc);
            this.closeModal();
         });
   }

   /**
    * Добавляет в модальное окно информацию о версии плагина и криптопровайдера
    *
    * @param {Object} plugin_data - объект с данными о плагине
    */
   putPluginData (plugin_data) {
      document.getElementById('plugin_version').innerHTML = plugin_data.plugin_version;
      document.getElementById('csp_version').innerHTML = plugin_data.csp_version;
   }

   /**
    * Обрабатывает список сертификаторв пользователя
    *
    * @param {Object[]} certs - массив объектов, содержащих имя и отпечаток сертификата
    */
   handleCertListSelect (certs) {
      this.certs_select = document.getElementById('cert_list_select');
      this.cert_list = document.getElementById('cert_list');

      // Добавляем сертификаты на страницу
      this.fillCertList(certs);

      GeCades.setCertificatesList(this.cert_list);
   }

   /**
    * Заполняет список сертификатов
    *
    * @param {Object[]} certs - массив объектов, содержащих имя и отпечаток сертификата
    */
   fillCertList (certs) {

      certs.forEach(cert => {
         let cert_item = document.createElement('DIV');
         cert_item.value = cert.value;
         cert_item.classList.add('sign-modal__cert');

         let cert_text = document.createElement('SPAN');
         cert_text.innerHTML = cert.text;
         cert_text.classList.add('sign-modal__cert-text');

         cert_item.appendChild(cert_text);
         this.cert_list.appendChild(cert_item);

         cert_item.addEventListener('click', () => {
            this.selectCert(cert_item);
         });
      });
   }

   /**
    * Устанавливает выбранный сертификат,
    * добаляет информацию о выбранном сертификате
    *
    * @param {HTMLElement} cert_item - элемент выбранного сертификата
    */
   selectCert(cert_item) {
      let selected_cert = this.cert_list.querySelector('.sign-modal__cert[data-selected="true"]');
      if (selected_cert) {
         selected_cert.dataset.selected = 'false';
         selected_cert.removeAttribute('data-state');
      }
      cert_item.dataset.selected = 'true';

      // При выборе сертификата получаем информацию о нем
      GeCades.getCertInfo()
         // Добавляем на страницу данные о выбранном сертификате
         .then(cert_info => {
            this.fillCertInfo(cert_info, cert_item);
         })
         .catch(exc => {
            ErrorModal.open('Ошибка при получении информации о сертификате', exc);
         });
   }

   /**
    * Показывает элементы для подписания файла
    */
   showCreateSignElements () {
      this.certs.dataset.active = 'true';
      this.plugin_info.dataset.active = 'true';

      this.actions.dataset.active = 'true';

      this.upload_sign_btn.dataset.active = 'false';
      this.create_sign_btn.dataset.active = 'false';
   }

   /**
    * Заполняет блок с информацией о выбранном сертификате
    *
    * @param {CertificateData} cert_info
    * @param cert_item - элемент выбранного сертификата
    */
   fillCertInfo (cert_info, cert_item) {
      document.getElementById('subject_name').innerHTML = cert_info.subject_name;
      document.getElementById('issuer_name').innerHTML = cert_info.issuer_name;
      document.getElementById('valid_from_date').innerHTML = GeCades.formattedDateTo_ddmmyyy_hhmmss(cert_info.valid_from_date);
      document.getElementById('valid_to_date').innerHTML = GeCades.formattedDateTo_ddmmyyy_hhmmss(cert_info.valid_to_date);

      let cert_message = document.getElementById('cert_message');
      cert_message.innerHTML = cert_info.cert_message;
      cert_message.dataset.state = cert_info.cert_status;
      cert_item.dataset.state = cert_info.cert_status;

      this.cert_info.dataset.active = 'true';
   }

   /**
    * Открывает окно загрузки файлов и загружает открепленные подписи
    * при нажатии на кнопку "Загрузить открепленную подпись"
    */
   handleUploadSignButton () {
      this.upload_sign_btn = document.getElementById('sign_upload');
      this.upload_sign_btn.addEventListener('click', () => {

         // Если не подписывается в данный момент, открываем окно загрузки файла
         if (!this.is_signing) {
            this.external_sign_input.click();
         }

      });

      this.external_sign_input = document.getElementById('external_sign');
      this.external_sign_input.addEventListener('change', () => {

         if (this.external_sign_input.files.length > 0) {
            let sign_files = Array.from(this.external_sign_input.files);
            this.is_signing = true;
            // Загружаем и проверяем открепленные подписи
            this.sendSigns(sign_files);
         }

         // Удаляем загруженные в инпут файлы
         this.external_sign_input.value = '';
      });
   }

   /**
    * Загружает и валидирует открепленные подписи
    * (пока что предусмотрена загрузка только одного файла)
    *
    * @param {File[]} sign_files - файлы открепленных подписей
    */
   sendSigns (sign_files) {
      let fs_name_data;
      let fs_name_sign;
      let id_sign;

      // Загружаем открепленную подпись на сервер
      API.uploadFiles(
         sign_files,
         this.ge_file.field.mapping_1,
         this.ge_file.field.mapping_2,
         this.ge_file.id_structure_node,
         null
      )
         // Проверяем подписываемый файл
         .then(uploaded_signs => {

            id_sign = uploaded_signs[0].id;
            return API.checkFile(this.ge_file.id, this.ge_file);

         })
         // Проверяем файл подписи
         .then(file_check_response => {

            fs_name_data = file_check_response.fs_name;
            return API.checkFile(id_sign, this.ge_file);

         })
         // Валидируем открепленную подпись
         .then(sign_check_response => {

            fs_name_sign = sign_check_response.fs_name;
            return API.externalSignatureVerify(
               fs_name_data,
               fs_name_sign,
               this.ge_file.field.mapping_1,
               this.ge_file.field.mapping_2
            );

         })
         // Обрабатываем результаты валидации
         .then(check_response => {

            this.handleValidateResults(id_sign, check_response.validate_results);

            this.certs.dataset.active = 'false';
            this.actions.dataset.active = 'false';
            this.plugin_info.dataset.active = 'false';

            this.create_sign_btn.dataset.active = 'false';
            this.upload_sign_btn.dataset.active = 'false';
            this.delete_sign_btn.dataset.active = 'true';

            this.is_signing = false;
         })
         .catch(exc => {
            this.is_signing = false;
            ErrorModal.open('Ошибка при загрузке файла открепленной подписи', exc);
            // console.error(exc);
         });
   }

   /**
    * Обрабатывает результаты проверки открепленной подписи
    *
    * @param {number} id_sign - id открпленной подписи, загруженной на сервер
    * @param {ValidateResult} validate_results - результаты проверки подписи
    */
   handleValidateResults (id_sign, validate_results) {
      let results_json = JSON.stringify(validate_results);

      this.ge_file.id_sign = id_sign;
      // this.ge_file.element.dataset.id_sign = id_sign;
      this.ge_file.setValidateResults(results_json);
      // this.ge_file.element.dataset.validate_results = results_json;

      // Добавляем статус подписания в поле с файлом
      this.ge_file.validateFileField();

      FileNeeds.putSignToSave(this.ge_file);

      // Добавляем результаты проверки в модальное окно
      this.fillSignsInfo(results_json);
   }

   /**
    * Удаляет открепленную подпись при нажатии на кнопку "Удалить подпись"
    */
   handleDeleteSignButton () {
      this.delete_sign_btn = document.getElementById('signature_delete');
      this.delete_sign_btn.addEventListener('click', () => {

         SignHandler.removeSign(this.ge_file);

         this.validate_info.dataset.active = 'false';
         this.delete_sign_btn.dataset.active = 'false';
         this.create_sign_btn.dataset.active = 'true';
         this.upload_sign_btn.dataset.active = 'true';

      });
   }

   /**
    * Скрывает элементы для создания подписи при нажатии на кнопку "Отмена"
    */
   handleCancelButton () {
      this.cancel_btn = document.getElementById('sign_cancel');

      this.cancel_btn.addEventListener('click', () => {

         this.upload_sign_btn.dataset.active = 'true';
         this.create_sign_btn.dataset.active = 'true';

         this.closeInfoBlocks();
      });

   }

   /**
    * Создает открепленную подпись при нажатии на кнопку "Подписать"
    */
   handleSignButton () {
      this.sign_btn = document.getElementById('signature_button');
      this.sign_btn.addEventListener('click', () => {
         if (!this.is_signing) {

            if (GeCades.getSelectedCertificateFromGlobalMap()) {
               this.createSign();
            } else {
               ErrorModal.open('Ошибка при подписании файла', 'Не выбран сертификат');
            }

         }
      });
   }

   /**
    * Создает файл открепленной подписи и загружает его на сервер
    */
   createSign () {
      let selected_algorithm;
      let file_name;
      let fs_name_data;

      this.is_signing = true;

      // Проверяем файл, который подписываем
      API.checkFile(this.ge_file.id, this.ge_file)
         .then(file_check_response => {

            fs_name_data = file_check_response.fs_name;
            file_name = file_check_response.file_name;
            return GeCades.getSelectedCertificateAlgorithm();

         })
         // Получаем выбранный сертификат
         .then(algorithm => {

            selected_algorithm = algorithm;
            return API.getFileHash(algorithm, fs_name_data);

         })
         // Создаем хэш подписи
         .then(file_hash => {

            return GeCades.SignHash_Async(selected_algorithm, file_hash);

         })
         // Преобразуем хэш в файл
         .then(sign_hash => {

            let sign_blob = new Blob([sign_hash], {type: 'text/plain'});
            let file = new File([sign_blob], `${file_name}.sig`);

            // Загружаем и проверяем открепленную подпись
            this.sendSigns([file]);

         })
         .catch(exc => {
            this.is_signing = false;
            ErrorModal.open('Ошибка при создании открепленной подписи', exc);
         });

   }

   /**
    * Открывает модуль подписания
    *
    * @param {GeFile} ge_file - файл, для которого открывается модуль подписания
    */
   open (ge_file) {
      this.modal.classList.add('active');
      this.overlay.classList.add('active');

      this.ge_file = ge_file;
      this.addFileElement(ge_file);

      let validate_results = ge_file.getValidateResults();
      if (!validate_results) {

         this.create_sign_btn.dataset.active = 'true';
         this.upload_sign_btn.dataset.active = 'true';

      } else {

         this.fillSignsInfo(validate_results);

         if (!ge_file.is_internal_sign) {
            this.delete_sign_btn.dataset.active = 'true';
         }

      }

   }

}