class Sign_Handler {

   // Объект SignHandler
   static instance;

   // Модальное окно модуля подписания
   modal;
   // Фон модального окна
   overlay;

   // Проинициализирован ли плагин подписания
   is_plugin_initialized = false;

   // Блок с информацией
   plugin_info;
   validate_info;
   certs;

   create_sign_btn;
   upload_sign_btn;
   delete_sign_btn;
   sign_btn;
   cancel_btn;
   actions;

   external_sign_input;


   // file_data;

   // file_element;
   // id_file;
   // id_sign;

   // mapping_level_1;
   // mapping_level_2;

   static getInstance() {

      if (!Sign_Handler.instance) {
         Sign_Handler.instance = new Sign_Handler();
      }

      return Sign_Handler.instance;
   }


   static clearFileSign(file) {
      file.element.removeAttribute('data-id_sign');
      file.element.removeAttribute('data-validate_results');
      file.element.removeAttribute('data-sign_state');

      file.removeSign();

      FileNeeds.putSignToDelete(
         file.sign.id,
         file.mapping_1,
         file.mapping_2
      );
   }

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

   handleOverlay() {
      this.overlay = mQS(document, '.sign-overlay', 17);
      this.overlay.addEventListener('click', () => this.closeModal());
   }

   closeModal() {
      this.modal.classList.remove('active');
      this.overlay.classList.remove('active');

      this.create_sign_btn.dataset.inactive = 'true';
      this.upload_sign_btn.dataset.inactive = 'true';
      this.delete_sign_btn.dataset.inactive = 'true';

      this.closeInfoBlocks();
   }

   closeInfoBlocks() {
      this.certs.dataset.inactive = 'true';
      this.plugin_info.dataset.inactive = 'true';
      this.actions.dataset.inactive = 'true';
      this.validate_info.dataset.inactive = 'true';
   }

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

   initializePlugin() {

      GeCades.getCadesPlugin()
         .then(() => {
            return GeCades.getPluginData();
         })
         .then(plugin_data => {
            this.putPluginData(plugin_data);
            return GeCades.getCerts();
         })
         .then(certs => {
            this.handleCertListSelect(certs);
            this.showCreateSignElements();
            this.is_plugin_initialized = true;
         })
         .catch(exc => {
            console.log('Ошибка инициализации плагина и заполнения списка сертификатов:\n' + exc);
            this.cancelPluginInitialization();
         });
   }

   putPluginData(plugin_data) {
      document.getElementById('plugin_version').innerHTML = plugin_data.plugin_version;
      document.getElementById('csp_version').innerHTML = plugin_data.csp_version;
   }

   handleCertListSelect(certs) {
      this.certs_select = document.getElementById('cert_list_select');

      this.fillCertListSelect(certs);
      GeCades.setCertificatesList(this.certs_select);

      this.certs_select.addEventListener('change', () => {

         GeCades.getCertInfo()
            .then(cert_info => {
               this.fillCertInfo(cert_info);
            })
            .catch(exc => {
               console.log('Ошибка при получении информации о сертификате: ' + exc);
            });
      });
   }

   fillCertListSelect(certs) {
      certs.forEach(cert => {
         let option = document.createElement('option');
         option.text = cert.text;
         option.value = cert.value;
         option.classList.add('sign-modal__cert');
         this.certs_select.options.add(option);
      });

   }

   showCreateSignElements() {
      this.certs.dataset.inactive = 'false';
      this.plugin_info.dataset.inactive = 'false';

      this.actions.dataset.inactive = 'false';

      this.upload_sign_btn.dataset.inactive = 'true';
      this.create_sign_btn.dataset.inactive = 'true';
   }

   cancelPluginInitialization() {
      this.is_plugin_initialized = false;
      this.closeModal();
   }


   fillCertInfo(cert_info) {
      document.getElementById('subject_name').innerHTML = cert_info.subject_name;
      document.getElementById('issuer_name').innerHTML = cert_info.issuer_name;
      document.getElementById('valid_from_date').innerHTML = GeCades.formattedDateTo_ddmmyyy_hhmmss(cert_info.valid_from_date);
      document.getElementById('valid_to_date').innerHTML = GeCades.formattedDateTo_ddmmyyy_hhmmss(cert_info.valid_to_date);
      document.getElementById('cert_message').innerHTML = cert_info.cert_message;
      document.getElementById('cert_message').style.color = cert_info.cert_status ? '#6cb37e' : '#db5151';
      this.cert_info.dataset.inactive = 'false';
   }

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

      });

   }

   sendSigns(sign_files) {
      let fs_name_data;
      let fs_name_sign;
      let id_sign;

      uploadFiles(sign_files, this.file.mapping_1, this.file.mapping_2)
         .then(uploaded_signs => {

            id_sign = uploaded_signs[0].id;

            return checkFile(this.file.id, this.file.mapping_1, this.file.mapping_2);

         })
         .then(file_check_response => {

            fs_name_data = file_check_response.fs_name;
            return checkFile(id_sign, this.file.mapping_1, this.file.mapping_2);

         })
         .then(sign_check_response => {

            fs_name_sign = sign_check_response.fs_name;
            return externalSignatureVerify(
               fs_name_data,
               fs_name_sign,
               this.file.mapping_1,
               this.file.mapping_2
            );

         })
         .then(validate_results => {

            this.file.createSign(validate_results, id_sign);
            FileNeeds.putSignToSave(this.file.sign.id, this.file.mapping_1, this.file.mapping_2);
            this.fillSignsInfo(validate_results);

            //this.handleValidateResults(validate_results);

            this.certs.dataset.inactive = 'true';
            this.actions.dataset.inactive = 'true';
            this.create_sign_btn.dataset.inactive = 'true';
            this.upload_sign_btn.dataset.inactive = 'true';
            this.delete_sign_btn.dataset.inactive = 'false';

         })
         .catch(exc => {
            console.error('Ошибка при загрузке файла открепленной подписи:\n' + exc);
         });


   }

   handleValidateResults(validate_results) {
      // let results_json = JSON.stringify(validate_results);

      // this.file_data.element.dataset.id_sign = this.file_data.id_sign;
      // this.file_data.element.dataset.validate_results = results_json;

      // Sign_Handler.validateFileField(this.file_data.element);

      // FileNeeds.putSignToSave(this.file_data.id_sign, this.mapping_1, this.mapping_2);

      // this.fillSignsInfo(results_json);
   }

   fillSignsInfo(validate_results) {
      this.validate_info.dataset.inactive = 'false';
      this.validate_info.innerHTML = '';

      validate_results.forEach(result => {
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

         //this.file_data.id_sign = this.file_data.element.dataset.id_sign;
         this.removeSign();

      });
   }

   removeSign() {

     /* FileNeeds.putSignToDelete(
         this.id_sign,
         this.mapping_1,
         this.mapping_2
      );*/

      this.file.removeSign();

      //SignHandler.clearFileSign(this.file);

      this.external_sign_input.value = '';

      this.validate_info.dataset.inactive = 'true';
      this.certs.dataset.inactive = 'true';

      this.delete_sign_btn.dataset.inactive = 'true';
      this.create_sign_btn.dataset.inactive = 'false';
      this.upload_sign_btn.dataset.inactive = 'false';
   }

   handleCancelButton() {
      this.cancel_btn = document.getElementById('sign_cancel');

      this.cancel_btn.addEventListener('click', () => {

         this.upload_sign_btn.dataset.inactive = 'false';
         this.create_sign_btn.dataset.inactive = 'false';

         this.closeInfoBlocks();
      });

   }

   handleSignButton() {
      this.sign_btn = document.getElementById('signature_button');
      this.sign_btn.addEventListener('click', () => {
         this.createSign();
      });

   }

   createSign() {
      let selected_algorithm;
      let file_name;
      let fs_name_data;

      checkFile(this.file_data.id, this.mapping_1, this.mapping_2)
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

   open(file_element) {
      this.modal.classList.add('active');
      this.overlay.classList.add('active');

      this.file = new GeFile(file_element);
      this.addFileElement(file_element);

      if (!this.file.sign) {

         this.create_sign_btn.dataset.inactive = 'false';
         this.upload_sign_btn.dataset.inactive = 'false';

      } else {

         this.fillSignsInfo(this.file.sign.validate_results);

         if (!this.file.sign.is_internal) {
            this.delete_sign_btn.dataset.inactive = 'false';
         }

      }

   }

   addFileElement(file_element) {
      let file_info = file_element.querySelector('.files__info');
      let sign_file = this.modal.querySelector('.sign-modal__file');
      sign_file.innerHTML = file_info.innerHTML;
   }
}