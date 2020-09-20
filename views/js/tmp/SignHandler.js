class SignHandler extends SignView{

   // Объект модуля подписания
   static instance;

   // Модальное окно подписания
   modal;
   // Фон модального окна
   overlay;

   is_plugin_initialized = false;
   is_signing = false;

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

   ge_file;

   // id_sign;

   // Предназначен для получения объекта модуля подписания
   static getInstance () {

      if (!this.instance) {
         this.instance = new SignHandler();
      }

      return this.instance;
   }

   // Предназначен для удаления подписи файла
   // Принимает параметры-------------------------------
   // file         Element : файл, у которого удаляется подпись
   // mapping_1     string : первый маппинг
   // mapping_2     string : второй маппинг
   static removeSign (ge_file) {

      FileNeeds.putSignToDelete(ge_file);

      ge_file.element.removeAttribute('data-id_sign');
      ge_file.element.removeAttribute('data-validate_results');

      GeFile.setSignState(ge_file.element, 'not_signed');
   }

   // Предназначен для инициализации модуля подписания
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

   // Предназначен для закрытия модуля подписания
   closeModal () {
      this.modal.classList.remove('active');
      this.overlay.classList.remove('active');

      this.create_sign_btn.dataset.active = 'false';
      this.upload_sign_btn.dataset.active = 'false';
      this.delete_sign_btn.dataset.active = 'false';

      this.closeInfoBlocks();
   }

   // Предназначен для скрывания блоков с информацией модуля подписания
   closeInfoBlocks () {
      this.certs.dataset.active = 'false';
      this.plugin_info.dataset.active = 'false';
      this.actions.dataset.active = 'false';
      this.validate_info.dataset.active = 'false';
   }

   // Предназначен для обработки кнопки создания открепленной подписи
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

   // Предназначен для инициализации плагина для подписания
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

   // Добавляем в модальное окно версии плагина и криптопровайдера
   // Принимает параметры-------------------------------
   // plugin_data       Object : объект с версиями
   putPluginData (plugin_data) {
      document.getElementById('plugin_version').innerHTML = plugin_data.plugin_version;
      document.getElementById('csp_version').innerHTML = plugin_data.csp_version;
   }

   // Предназначен для
   // Принимает параметры-------------------------------
   // certs        Array[Object] : массив с данными сертификатов
   handleCertListSelect (certs) {
      this.certs_select = document.getElementById('cert_list_select');
      this.cert_list = document.getElementById('cert_list');

      // Добавляем сертификаты на страницу
      this.fillCertList(certs);

      GeCades.setCertificatesList(this.cert_list);
   }

   // Предназначен для заполнения селекта выбора сертификатов
   // Принимает параметры-------------------------------
   // certs       Array[Object] : массив с сертификатами
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

   // Предназначен для отображения элементов для создания подписи
   showCreateSignElements () {
      this.certs.dataset.active = 'true';
      this.plugin_info.dataset.active = 'true';

      this.actions.dataset.active = 'true';

      this.upload_sign_btn.dataset.active = 'false';
      this.create_sign_btn.dataset.active = 'false';
   }

   // Предназначен для добавления информации о выбранном сертификате
   // Принимает параметры-------------------------------
   // cert_info         Object : объект с информацией о сертификате
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

   // Предназначен для обработки кнопки загрузки файла открепленной подписи
   handleUploadSignButton () {
      this.upload_sign_btn = document.getElementById('sign_upload');
      this.upload_sign_btn.addEventListener('click', () => {

         if (!this.is_signing) {
            this.external_sign_input.click();
         }

      });

      this.external_sign_input = document.getElementById('external_sign');
      this.external_sign_input.addEventListener('change', () => {

         if (this.external_sign_input.files.length > 0) {
            let sign_files = Array.from(this.external_sign_input.files);
            this.is_signing = true;
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
   sendSigns (sign_files) {
      let fs_name_data;
      let fs_name_sign;

      // Загружаем открепленную подпись на сервер
      API.uploadFiles(
         sign_files,
         this.ge_file.field.mapping_1,
         this.ge_file.field.mapping_2,
         this.ge_file.id_structure_node
      )
         // Проверяем подписываемый файл
         .then(uploaded_signs => {

            this.id_sign = uploaded_signs[0].id;
            return API.checkFile(this.ge_file.id, this.ge_file);

         })
         // Проверяем файл подписи
         .then(file_check_response => {

            fs_name_data = file_check_response.fs_name;
            return API.checkFile(this.id_sign, this.ge_file);

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
         .then(validate_results => {

            this.handleValidateResults(validate_results);

            this.certs.dataset.active = 'false';
            this.actions.dataset.active = 'false';
            this.create_sign_btn.dataset.active = 'false';
            this.upload_sign_btn.dataset.active = 'false';
            this.delete_sign_btn.dataset.active = 'true';

            this.is_signing = false;
         })
         .catch(exc => {
            this.is_signing = false;
            ErrorModal.open('Ошибка при загрузке файла открепленной подписи', exc);
         });


   }

   handleValidateResults (validate_results) {
      let results_json = JSON.stringify(validate_results);

      this.ge_file.element.dataset.id_sign = this.id_sign;
      this.ge_file.element.dataset.validate_results = results_json;

      SignView.validateFileField(this.ge_file.element);

      FileNeeds.putSignToSave(this.id_sign, this.ge_file);

      this.fillSignsInfo(results_json);
   }


   handleDeleteSignButton () {
      this.delete_sign_btn = document.getElementById('signature_delete');
      this.delete_sign_btn.addEventListener('click', () => {

         this.id_sign = this.ge_file.element.dataset.id_sign;

         SignHandler.removeSign(this.ge_file);

         this.validate_info.dataset.active = 'false';
         this.delete_sign_btn.dataset.active = 'false';
         this.create_sign_btn.dataset.active = 'true';
         this.upload_sign_btn.dataset.active = 'true';
      });
   }

   handleCancelButton () {
      this.cancel_btn = document.getElementById('sign_cancel');

      this.cancel_btn.addEventListener('click', () => {

         this.upload_sign_btn.dataset.active = 'true';
         this.create_sign_btn.dataset.active = 'true';

         this.closeInfoBlocks();
      });

   }

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

   createSign () {
      let selected_algorithm;
      let file_name;
      let fs_name_data;

      this.is_signing = true;

      API.checkFile(this.ge_file.id, this.ge_file)
         .then(file_check_response => {

            fs_name_data = file_check_response.fs_name;
            file_name = file_check_response.file_name;
            return GeCades.getSelectedCertificateAlgorithm();

         })
         .then(algorithm => {

            selected_algorithm = algorithm;
            return API.getFileHash(algorithm, fs_name_data);

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
            this.is_signing = false;
            ErrorModal.open('Ошибка при создании открепленной подписи', exc);
         });

   }

   open (ge_file) {
      this.modal.classList.add('active');
      this.overlay.classList.add('active');

      this.ge_file = ge_file;
      this.addFileElement(ge_file.element);

      if (!ge_file.element.dataset.validate_results) {

         this.create_sign_btn.dataset.active = 'true';
         this.upload_sign_btn.dataset.active = 'true';

      } else {

         this.fillSignsInfo(ge_file.element.dataset.validate_results);

         if (ge_file.element.dataset.is_internal !== 'true') {
            this.delete_sign_btn.dataset.active = 'true';
         }

      }

   }

}