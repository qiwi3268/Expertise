/**
 * @typedef ValidateResult
 * @type {object}
 * @property {SignatureVerify} signature_verify - результат проверки подписи
 * @property {CertificateVerify} certificate_verify - результат проверки сертификата
 * @property {string} fio - результат проверки сертификата
 * @property {string} certificate - результат проверки сертификата
 */

/**
 * @typedef SignatureVerify
 * @type {object}
 * @property {string} user_message - сообщение о проверке подписи
 * @property {boolean} result - результат проверки подписи
 */

/**
 * @typedef CertificateVerify
 * @type {object}
 * @property {string} user_message - сообщение о проверке сертификата
 * @property {boolean} result - результат проверки сертификата
 */

/**
 * Представляет собой модуль просмотра подписей файла
 */
class SignView {

   /**
    * Объект модуля просмотра подписей
    *
    * @type {SignView}
    */
   static instance;

   /**
    * Модальное окно просмотра подписей
    *
    * @type {HTMLElement}
    */
   modal;

   /**
    * Блок с результатами проверки подписей
    *
    * @type {HTMLElement}
    */
   validate_info;

   /**
    * Фон модального окна
    *
    * @type {HTMLElement}
    */
   overlay;

   /**
    * Предназначен для получения объекта модального окна
    * просмотра подписей файла
    *
    * @returns {SignView} объект модального окна просмотра подписей файла
    */
   static getInstance () {

      if (!this.instance) {
         this.instance = new SignView();
      }

      return this.instance;
   }

   /**
    * Создает объект модального окна просмотра подписей файла
    */
   constructor () {
      this.modal = mQS(document, '.sign-modal', 12);
      this.validate_info = mQS(this.modal, '.sign-modal__validate', 14);

      this.handleOverlay();
   }

   /**
    * Обрабатывает нажатие на фон модального окна
    */
   handleOverlay () {
      this.overlay = mQS(document, '.sign-overlay', 17);
      this.overlay.addEventListener('click', () => this.closeModal());
   }

   /**
    * Закрывает модальное окно просмотра подписей
    */
   closeModal () {
      this.modal.classList.remove('active');
      this.overlay.classList.remove('active');
      this.validate_info.dataset.active = 'false';
   }

   /**
    * Добавляет файл и результаты проверки подписей и
    * открывает модуль просмотра подписей
    *
    * @param {GeFile} ge_file - файл, для которого проматриваются результаты проверок
    */
   open (ge_file) {
      this.modal.classList.add('active');
      this.overlay.classList.add('active');

      this.addFileElement(ge_file);

      if (ge_file.element.dataset.validate_results) {
         this.fillSignsInfo(ge_file.element.dataset.validate_results);
      }

   }

   /**
    * Добвляет файл в модальное окно модуля просмотра подписей
    *
    * @param {GeFile} ge_file - файл, который добавляется в модальное окно
    */
   addFileElement (ge_file) {
      let file_info = ge_file.element.querySelector('.files__info');
      let sign_file = this.modal.querySelector('.sign-modal__file');
      sign_file.innerHTML = file_info.innerHTML;
   }

   /**
    * Добавляет результаты проверки подписей в модальное окно
    * просмотра подписей
    *
    * @param {string} validate_results_json - результаты проверки подписей
    * файла в формате json
    */
   fillSignsInfo (validate_results_json) {
      this.validate_info.dataset.active = 'true';
      this.validate_info.innerHTML = '';

      let results = JSON.parse(validate_results_json);
      results.forEach(result => {
         this.validate_info.appendChild(this.createSignInfo(result));
      });
   }

   /**
    * Создает блок в с информацией о подписи
    *
    * @param {ValidateResult} result - объект, содержащий информацию о подписи
    * @returns {HTMLElement} блок, в который добавлена информация о подписи
    */
   createSignInfo (result) {
      let sign = document.createElement('DIV');
      sign.classList.add('sign-modal__sign');

      let cert_state = result.certificate_verify;
      let cert_row = this.createInfoRow('Сертификат: ', cert_state.user_message, cert_state.result);

      let sign_state = result.signature_verify;
      let sign_row = this.createInfoRow('Подпись: ', sign_state.user_message, sign_state.result);

      let name_row = this.createInfoRow('Подписант: ', result.fio);
      let info_row = this.createInfoRow('Информация: ', result.certificate);

      sign.appendChild(cert_row);
      sign.appendChild(sign_row);
      sign.appendChild(name_row);
      sign.appendChild(info_row);

      return sign;
   }

   /**
    * Создает поле блока с информацией о подписи
    *
    * @param {string} label - имя поля блока с информацией о подписи
    * @param {string} text - содержимое поля блока с информацией о подписи
    * @param {boolean|null} state - статус проверки подписи или сертификата
    * @returns {HTMLElement} элемент строки блока с информацией о подписи
    */
   createInfoRow (label, text, state = null) {
      let row = document.createElement('DIV');
      row.classList.add('sign-modal__sign-row');

      let label_span = document.createElement('SPAN');
      label_span.classList.add('sign-modal__label');
      label_span.innerHTML = label;

      let text_span = document.createElement('SPAN');
      text_span.classList.add('sign-modal__text');
      text_span.innerHTML = text;

      if (state !== null) {
         text_span.dataset.state = state;
      }

      row.appendChild(label_span);
      row.appendChild(text_span);

      return row;
   }

   /**
    * Отображает состояние проверки подписи в поле с файлом
    *
    * @param {GeFile} ge_file - файл, для которого отображается статус подписания
    */
   static validateFileField (ge_file) {
      let results_json = ge_file.element.dataset.validate_results;
      let sign_state = 'not_signed';

      if (results_json) {
         let results = JSON.parse(results_json);

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
      GeFile.setSignState(ge_file, sign_state);
   }
}

