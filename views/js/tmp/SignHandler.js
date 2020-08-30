class SignHandler {

   // Объект модуля подписания
   static instance;

   // Модальное окно подписания
   modal;
   // Фон модального окна
   overlay;

   is_plugin_initialized = false;

   // Блок с информацией о версии плагина
   plugin_info;
   // Блок с информацией о проверки подписей
   validate_info;
   // Блок с сертификатами
   certs;
   // Блок с информацией о выбранном сертификате
   cert_info;

   // Кнопка загрузки файла открепленной подписи
   upload_sign_btn;
   // Кнопка создания открепленной подписи
   create_sign_btn;
   // Кнопка удаления открепленной подписи
   delete_sign_btn;
   // Кнопка "Подписать" для создания открепленной подписи
   // после выбора сертификата
   sign_btn;
   // Кнопка отмены действия создания открепленной подписи
   cancel_btn;
   // Блок с кнопками подписания и отмены
   actions;

   // Инпут, в который загружается файл открепленной подписи
   external_sign_input;

   // Данные файла, для которого открыт модуль подписания
   file_element;
   id_file;
   id_sign;
   mapping_level_1;
   mapping_level_2;
   //==================

   // Предназначен для получения объекта модуля подписания
   static getInstance() {

      if (!SignHandler.instance) {
         SignHandler.instance = new SignHandler();
      }

      return SignHandler.instance;
   }

   // Предназначен для отображения состояния проверки подписи в поле с файлом
   // Принимает параметры-------------------------------
   // file         Element : проверяемый файл
   static validateFileField(file) {
      let results_json = file.dataset.validate_results;

      if (results_json) {
         let results = JSON.parse(results_json);

         for (let result of results) {
            if (result.signature_verify.result && result.certificate_verify.result) {
               file.dataset.sign_state = 'valid';
            } else if (result.signature_verify.result) {
               file.dataset.sign_state = 'warning';
               break;
            } else {
               break;
            }
         }
      }
   }

   // Предназначен для удаления подписи файла
   // Принимает параметры-------------------------------
   // file         Element : файл, у которого удаляется подпись
   // mapping_1     string : первый маппинг
   // mapping_2     string : второй маппинг
   static removeSign(file, mapping_1, mapping_2) {

      FileNeeds.putSignToDelete(
         file.dataset.id_sign,
         mapping_1,
         mapping_2
      );

      file.removeAttribute('data-id_sign');
      file.removeAttribute('data-validate_results');
      file.removeAttribute('data-sign_state');
   }

   // Предназначен для инициализации модуля подписания
   constructor() {

      this.modal = mQS(document, '.sign-modal', 12);
      this.plugin_info = mQS(this.modal, '.sign-modal__header', 13);
      this.cert_info = mQS(this.modal, '.sign-modal__cert-info', 17);
      this.validate_info = mQS(this.modal, '.sign-modal__validate', 14);
      this.certs = mQS(this.modal, '.sign-modal__certs', 15);
      this.actions = mQS(this.modal, '.sign-modal__actions', 16);

      this.handleOverlay();

      this.handleCreateSignButton();
      this.handleUploadSignButton();
      this.handleDeleteSignButton();

      this.handleCancelButton();
      this.handleSignButton();
   }

   // Предназначен для обработки нажатия на фон модального окна
   handleOverlay() {
      this.overlay = mQS(document, '.sign-overlay', 17);
      this.overlay.addEventListener('click', () => this.closeModal());
   }

   // Предназначен для закрытия модуля подписания
   closeModal() {
      this.modal.classList.remove('active');
      this.overlay.classList.remove('active');

      this.create_sign_btn.dataset.active = 'false';
      this.upload_sign_btn.dataset.active = 'false';
      this.delete_sign_btn.dataset.active = 'false';

      this.closeInfoBlocks();
   }

   // Предназначен для скрывания блоков с информацией модуля подписания
   closeInfoBlocks() {
      this.certs.dataset.active = 'false';
      this.plugin_info.dataset.active = 'false';
      this.actions.dataset.active = 'false';
      this.validate_info.dataset.active = 'false';
   }

   // Предназначен для обработки кнопки создания открепленной подписи
   handleCreateSignButton() {
      this.create_sign_btn = document.getElementById('sign_create');
      this.create_sign_btn.addEventListener('click', () => {

         if (!this.is_plugin_initialized && BrowserHelper.checkBrowser()) {
            this.initializePlugin();
         } else {
            this.showCreateSignElements();
         }

      });

   }

   // Предназначен для инициализации плагина для подписания
   initializePlugin() {

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
            console.log('Ошибка инициализации плагина и заполнения списка сертификатов:\n' + exc);
            this.closeModal();
         });
   }

   // Добавляем в модальное окно версии плагина и криптопровайдера
   // Принимает параметры-------------------------------
   // plugin_data       Object : объект с версиями
   putPluginData(plugin_data) {
      document.getElementById('plugin_version').innerHTML = plugin_data.plugin_version;
      document.getElementById('csp_version').innerHTML = plugin_data.csp_version;
   }

   // Предназначен для
   // Принимает параметры-------------------------------
   // certs        Array[Object] : массив с данными сертификатов
   handleCertListSelect(certs) {
      this.certs_select = document.getElementById('cert_list_select');

      // Добавляем сертификаты на страницу
      this.fillCertListSelect(certs);

      GeCades.setCertificatesList(this.certs_select);

      this.certs_select.addEventListener('change', () => {
         // При выборе сертификата получаем информацию о нем
         GeCades.getCertInfo()
            // Добавляем на страницу данные о выбранном сертификате
            .then(cert_info => {
               this.fillCertInfo(cert_info);
            })
            .catch(exc => {
               console.log('Ошибка при получении информации о сертификате: ' + exc);
            });
      });
   }

   // Предназначен для заполнения селекта выбора сертификатов
   // Принимает параметры-------------------------------
   // certs       Array[Object] : массив с сертификатами
   fillCertListSelect(certs) {
      certs.forEach(cert => {
         let option = document.createElement('option');
         option.text = cert.text;
         option.value = cert.value;
         option.classList.add('sign-modal__cert');
         this.certs_select.options.add(option);
      });

   }

   // Предназначен для отображения элементов для создания подписи
   showCreateSignElements() {
      this.certs.dataset.active = 'true';
      this.plugin_info.dataset.active = 'true';

      this.actions.dataset.active = 'true';

      this.upload_sign_btn.dataset.active = 'false';
      this.create_sign_btn.dataset.active = 'false';

      // this.modal.querySelector('.sign-modal__buttons').style.display = 'none';
   }

   // Предназначен для добавления информации о выбранном сертификате
   // Принимает параметры-------------------------------
   // cert_info         Object : объект с информацией о сертификате
   fillCertInfo(cert_info) {
      document.getElementById('subject_name').innerHTML = cert_info.subject_name;
      document.getElementById('issuer_name').innerHTML = cert_info.issuer_name;
      document.getElementById('valid_from_date').innerHTML = GeCades.formattedDateTo_ddmmyyy_hhmmss(cert_info.valid_from_date);
      document.getElementById('valid_to_date').innerHTML = GeCades.formattedDateTo_ddmmyyy_hhmmss(cert_info.valid_to_date);
      document.getElementById('cert_message').innerHTML = cert_info.cert_message;
      document.getElementById('cert_message').style.color = cert_info.cert_status ? '#6cb37e' : '#db5151';
      this.cert_info.dataset.active = 'true';
   }

   // Предназначен для обработки кнопки загрузки файла открепленной подписи
   handleUploadSignButton() {
      this.upload_sign_btn = document.getElementById('sign_upload');
      this.upload_sign_btn.addEventListener('click', () => {
         this.external_sign_input.click();
      });

      this.external_sign_input = document.getElementById('external_sign');
      this.external_sign_input.addEventListener('change', () => {

         if (this.external_sign_input.files.length > 0) {
            let sign_files = Array.from(this.external_sign_input.files);
            this.sendSigns(sign_files);
         }

         // Удаляем загруженные файлы
         this.external_sign_input.value = '';

      });

   }

   // Предназначен для загрузки и валидации файла открепленной подписи
   // Принимает параметры-------------------------------
   // sign_files       Array[File] : загруженные файлы
   // (пока что предусмотрена загрузка только одного файла)
   sendSigns(sign_files) {
      let fs_name_data;
      let fs_name_sign;

      // Загружаем открепленную подпись на сервер
      uploadFiles(sign_files, this.mapping_level_1, this.mapping_level_2)
         // Проверяем подписываемый файл
         .then(uploaded_signs => {

            this.id_sign = uploaded_signs[0].id;
            return checkFile(this.id_file, this.mapping_level_1, this.mapping_level_2);

         })
         // Проверяем файл подписи
         .then(file_check_response => {

            fs_name_data = file_check_response.fs_name;
            return checkFile(this.id_sign, this.mapping_level_1, this.mapping_level_2);

         })
         // Валидируем открепленную подпись
         .then(sign_check_response => {

            fs_name_sign = sign_check_response.fs_name;
            return externalSignatureVerify(
               fs_name_data,
               fs_name_sign,
               this.mapping_level_1,
               this.mapping_level_2
            );

         })
         // Обрабатываем результаты валидации
         .then(validate_results => {

            this.handleValidateResults(validate_results);

            this.certs.dataset.active = 'false';
            this.actions.dataset.active = 'false';
            this.create_sign_btn.dataset.active = 'false';
            this.upload_sign_btn.dataset.active = 'false';
            this.delete_sign_btn.dataset.active = 'true';

         })
         .catch(exc => {
            console.error('Ошибка при загрузке файла открепленной подписи:\n' + exc);
         });


   }

   handleValidateResults(validate_results) {
      let results_json = JSON.stringify(validate_results);

      this.file_element.dataset.id_sign = this.id_sign;
      this.file_element.dataset.validate_results = results_json;

      SignHandler.validateFileField(this.file_element);

      FileNeeds.putSignToSave(this.id_sign, this.mapping_level_1, this.mapping_level_2);

      this.fillSignsInfo(results_json);
   }

   fillSignsInfo(validate_results_json) {
      this.validate_info.dataset.active = 'true';
      this.validate_info.innerHTML = '';

      let results = JSON.parse(validate_results_json);
      results.forEach(result => {
         this.validate_info.appendChild(this.createSignInfo(result));
      });
   }

   createSignInfo(result) {
      let sign = document.createElement('DIV');
      sign.classList.add('sign-modal__sign');

      let cert_state = result.certificate_verify;
      let cert_row = this.createInfoRow('Сертификат: ', cert_state.user_message, cert_state.result);

      let sign_state = result.signature_verify;
      let sign_row = this.createInfoRow('Подпись: ', sign_state.user_message, cert_state.result);

      let name_row = this.createInfoRow('Подписант: ', result.fio);
      let info_row = this.createInfoRow('Информация: ', result.certificate);

      sign.appendChild(cert_row);
      sign.appendChild(sign_row);
      sign.appendChild(name_row);
      sign.appendChild(info_row);

      return sign;
   }

   createInfoRow(label, text, state) {
      let row = document.createElement('DIV');
      row.classList.add('sign-modal__row');

      let label_span = document.createElement('SPAN');
      label_span.classList.add('sign-modal__label');
      label_span.innerHTML = label;

      let text_span = document.createElement('SPAN');
      text_span.classList.add('sign-modal__text');
      text_span.innerHTML = text;
      if (state !== undefined) {
         text_span.dataset.state = state;
      }

      row.appendChild(label_span);
      row.appendChild(text_span);

      return row;
   }

   handleDeleteSignButton() {
      this.delete_sign_btn = document.getElementById('signature_delete');
      this.delete_sign_btn.addEventListener('click', () => {

         this.id_sign = this.file_element.dataset.id_sign;

         SignHandler.removeSign(this.file_element);

         this.validate_info.dataset.active = 'false';
         this.delete_sign_btn.dataset.active = 'false';
         this.create_sign_btn.dataset.active = 'true';
         this.upload_sign_btn.dataset.active = 'true';
      });
   }

   handleCancelButton() {
      this.cancel_btn = document.getElementById('sign_cancel');

      this.cancel_btn.addEventListener('click', () => {

         this.upload_sign_btn.dataset.active = 'true';
         this.create_sign_btn.dataset.active = 'true';

         this.closeInfoBlocks();
      });

   }

   handleSignButton() {
      this.sign_btn = document.getElementById('signature_button');
      this.sign_btn.addEventListener('click', () => {
         if (GeCades.getSelectedCertificateFromGlobalMap()) {
            this.createSign();
         } else {
            alert('Выберите сертификат');
         }

      });

   }

   createSign() {
      let selected_algorithm;
      let file_name;
      let fs_name_data;

      checkFile(this.id_file, this.mapping_level_1, this.mapping_level_2)
         .then(file_check_response => {

            fs_name_data = file_check_response.fs_name;
            file_name = file_check_response.file_name;
            return GeCades.getSelectedCertificateAlgorithm();

         })
         .then(algorithm => {

            selected_algorithm = algorithm;
            return getFileHash(algorithm, fs_name_data);

         })
         .then(file_hash => {

            return GeCades.SignHash_Async(selected_algorithm, file_hash);

         })
         .then(sign_hash => {

            let sign_blob = new Blob([sign_hash], {type: 'text/plain'});
            let file = new File([sign_blob], `${file_name}.sig`);

            this.sendSigns([file]);

         })
         .catch(exc => {
            console.error('Произошла ошибка при создании открепленной подписи:\n' + exc);
         });

   }

   open(file) {
      this.modal.classList.add('active');
      this.overlay.classList.add('active');

      this.putFileData(file);
      this.addFileElement(file);

      if (!file.dataset.validate_results) {

         this.create_sign_btn.dataset.active = 'true';
         this.upload_sign_btn.dataset.active = 'true';

      } else {

         this.fillSignsInfo(file.dataset.validate_results);

         if (file.dataset.is_internal !== 'true') {
            this.delete_sign_btn.dataset.active = 'true';
         }

      }

   }

   putFileData(file) {
      let parent_field = file.closest('[data-mapping_level_1]');
      this.file_element = file;
      this.id_file = file.dataset.id;
      this.id_sign = file.dataset.id_sign;
      this.mapping_level_1 = parent_field.dataset.mapping_level_1;
      this.mapping_level_2 = parent_field.dataset.mapping_level_2;
   }

   addFileElement(file) {
      let file_info = file.querySelector('.files__info');
      let sign_file = this.modal.querySelector('.sign-modal__file');
      sign_file.innerHTML = file_info.innerHTML;
   }
}