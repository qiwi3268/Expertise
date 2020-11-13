const SUCCESS_RESULT = 'ok';
const MISSING_POST_PARAMS_RESULT = 'mppr';
const MISSING_GET_PARAMS_RESULT = 'mgpr';

/**
 * Представляет собой вспомогательный класс для работы с API
 */
class API {

   static sendRequest (method, url, form = null, headers = null, responseType = null, timeout = 50000, uploadProgressCallback = null) {

      return new Promise((resolve, reject) => {

         XHR(method, url, form, headers, responseType, timeout, uploadProgressCallback)
            .then(response => {

               // if (response.result === null) {

               if (this.isJSON(response)) {
                  reject({message:'Не получен ответ от сервера. Обратитесь к администратору'});
               }

               switch (response.result) {
                  case SUCCESS_RESULT:
                     resolve(response);
                     break;
                  case MISSING_POST_PARAMS_RESULT:
                  case MISSING_GET_PARAMS_RESULT:
                     response.result = null;
                     reject(response);
                     break;
                  default:
                     reject(response);

               }

            })
            .catch(exc => {
               reject({message: `Непредвиденная ошибка при выполнении запроса: ${exc}`});
               // reject(exc);
            });

      });

   }

   static isJSON (response) {
      try {
         JSON.parse(response);
      } catch (e) {
         return false;
      }
      return true;
   }

   /**
    * Флаг, указывающий происходит ли обработка запроса на API
    *
    * @return {*}
    */
   static get is_in_progress() {
      return this._is_in_progress;
   }
   static set is_in_progress(is_in_progress) {
      this._is_in_progress = is_in_progress;
   }

   static login (form) {

      API.sendRequest('post', '/API_login', new FormData(form), null, 'json')
         .then(response => {
            location.href = response.ref;
         })
         .catch(exc => {
            ErrorModal.open('Ошибка авторизации', exc.message, exc.result);
         });

   }

   /**
    * Отправляет файлы на API загрузки файлов
    *
    * @param {File[]} files - файлы для загрузки на сервер
    * @param {number} mapping_1 - первый маппинг файлового поля
    * @param {number} mapping_2 - второй маппинг файлового поля
    * @param {number|null} id_structure_node - id раздела документации,
    * null - если файл не относится к документации
    * @param {function|null} upload_callback - callback для обработки запроса во время отправки данных на сервер
    * @return {Promise<unknown>}
    */
   static uploadFiles (files, mapping_1, mapping_2, id_structure_node = null, upload_callback = null) {

      return new Promise((resolve, reject) => {
         let form_data = this.getUploadFormData(files, mapping_1, mapping_2, id_structure_node);

         XHR(
            'post',
            '/home/API_file_uploader',
            form_data,
            null,
            'json',
            null,
            upload_callback
         )
            .then(response => {

               switch (response.result) {

                  case 13:
                     resolve(response.uploaded_files);
                     break;

                  case 3:
                     reject('Отсутствуют загруженные файлы');
                     break;

                  case 9:
                     // 1046 - Ошибка базы данных, если слишком длинное название у файла
                     if (response.code === 1046) {
                        reject({message: 'Слишком длинное название файла'});
                     } else {
                        // reject(`message: ${response.message}, code: ${response.code}`);
                        reject({message: response.message, code: response.code});
                     }
                     break;

                  default:
                     let message = response.error_message !== undefined ? response.error_message : response.message;
                     reject({message: message});
               }

            })
            .catch(exc => {
               reject({message: exc});
            });
      });

   }

   static getUploadFormData (files, mapping_1, mapping_2, id_structure_node) {
      let form_data = new FormData();

      form_data.append('id_application', getIdDocument());
      form_data.append('mapping_level_1', mapping_1);
      form_data.append('mapping_level_2', mapping_2);

      if (id_structure_node) {
         form_data.append('id_structure_node', id_structure_node);
      }

      try {
         files.forEach(file => {
            form_data.append('download_files[]', file);
         });
      } catch (exc) {
         //TODO код
         alert('В форму загрузки не передан массив файлов');
      }

      return form_data;
   }

   static checkFile (id_file, ge_file) {

      return new Promise((resolve, reject) => {
         let form_data = this.getFileCheckFormData(id_file, ge_file);

         this.sendRequest('post', '/home/API_file_checker', form_data, null, 'json')
            .then(response => {
               resolve(response);
            })
            .catch(exc => {
               reject(exc);
               console.error(exc);
            });

      });

   }

   static getFileCheckFormData (id_file, ge_file) {
      let form_data = new FormData();
      form_data.append('id_application', getIdDocument());
      form_data.append('id_file', id_file);
      form_data.append('mapping_level_1', ge_file.field.mapping_1);
      form_data.append('mapping_level_2', ge_file.field.mapping_2);
      return form_data;
   }

   // Предназначен для получения ссылки для скачивания файла
   // Принимает параметры-------------------------------
   // check_result     Object : объект, содержащий данные о расположении файла
   // Возвращает параметры------------------------------
   // url              string : относительный путь для скачивания файла
   static getUnloadFileURN (check_result) {
      return `/home/file_unloader?fs_name=${check_result.fs_name}&file_name=${check_result.file_name}`;
   }

   static externalSignatureVerify (fs_name_data, fs_name_sign, mapping_1, mapping_2) {

      return new Promise((resolve, reject) => {
         let form_data = this.getExternalVerifyFormData(fs_name_data, fs_name_sign, mapping_1, mapping_2);

         this.sendRequest('post',
            '/home/API_external_signature_verifier',
            form_data,
            null,
            'json',
            null,
            null)
            .then(response => {
               resolve(response)
            })
            .catch(exc => {
               reject(exc);
            });

      });

   }

   static getExternalVerifyFormData (fs_name_data, fs_name_sign, mapping_1, mapping_2) {
      let form_data = new FormData();
      form_data.append('fs_name_data', fs_name_data);
      form_data.append('fs_name_sign', fs_name_sign);
      form_data.append('mapping_level_1', mapping_1);
      form_data.append('mapping_level_2', mapping_2);
      return form_data;
   }

   static getFileHash (algorithm, fs_name) {

      return new Promise((resolve, reject) => {
         let form_data = this.getFileHashFormData(algorithm, fs_name);

         XHR(
            'post',
            '/home/API_get_file_hash',
            form_data,
            null,
            'json',
            null,
            null
         )
            .then(response => {

               switch (response.result) {

                  case 9:
                     resolve(response.hash);
                     break;

                  default:
                     let message = response.error_message !== undefined ? response.error_message : response.message;
                     reject(`Ошибка при получении хэша файла: \n${message}`);

               }

            })
            .catch(exc => {
               reject('Ошибка при получении хэша файла: ' + exc);
            });

      });


   }

   static getFileHashFormData (algorithm, fs_name) {
      let form_data = new FormData();
      form_data.append('sign_algorithm', algorithm);
      form_data.append('fs_name', fs_name);
      return form_data;
   }

   static internalSignatureVerify (fs_name, ge_file, verify_callback = null) {

      return new Promise((resolve, reject) => {
         let form_data = this.getInternalVerifyFormData(fs_name, ge_file);

         this.sendRequest('post',
            '/home/API_internal_signature_verifier',
            form_data,
            null,
            'json',
            null,
            verify_callback)
            .then(response => {
               resolve(response.validate_results);
            })
            .catch(exc => {

               switch (exc.result) {
                  case 'finisr':
                     resolve();
                     break;
                  default:
                     reject(exc);
               }

            });


/*         XHR(
            'post',
            '/home/API_internal_signature_verifier',
            form_data,
            null,
            'json',
            null,
            verify_callback
         )
            .then(response => {

               switch (response.result) {
                  case 5.2:
                     alert('Открепленная подпись 2');
                     reject(`Ошибка при проверке встроенной подписи: \nЗагружена встроенная подпись`);
                     break;

                  case 5.1:
                     resolve();
                     break;

                  case 8:
                     resolve(response.validate_results);
                     break;

                  default:
                     let message = response.error_message !== undefined ? response.error_message : response.message;
                     reject(`Ошибка при проверке встроенной подписи: \n${message}`);
               }

            })
            .catch(exc => {
               reject('Ошибка при проверке встроенной подписи: ' + exc);
            });*/

      });
   }

   static getInternalVerifyFormData (fs_name, ge_file) {
      let form_data = new FormData();
      form_data.append('fs_name_sign', fs_name);
      form_data.append('mapping_level_1', ge_file.field.mapping_1);
      form_data.append('mapping_level_2', ge_file.field.mapping_2);
      return form_data;
   }

   static updateFileNeeds () {
      let form_data = this.getFilesNeedsFormData();

      console.log(FileNeeds.getFileNeedsJSON());

      this.sendRequest('post',
         '/home/API_file_needs_setter',
         form_data,
         null,
         'json',
         null,
         null)
         .then(response => {
            console.log(response);
            FileNeeds.clear();
         })
         .catch(exc => {
            ErrorModal.open('Ошибка при обновлении file needs', exc.message);
         });

   }

   static getFilesNeedsFormData () {
      let form_data = new FormData();
      form_data.append('id_application', getIdDocument());
      form_data.append('file_needs_json', FileNeeds.getFileNeedsJSON());
      return form_data;
   }

   static executeAction (form_data) {
      this.appendActionData(form_data);

      // console.log(new Map(form_data));

      return new Promise((resolve, reject) => {

         if (!this.is_in_progress) {
            this.is_in_progress = true;

            XHR(
               'post',
               '/home/API_action_executor',
               form_data,
               null,
               'json'
            )
               .then(response => {

                  this.is_in_progress = false;

                  if (response.result !== undefined) {
                     if (response.result === 19) {
                        resolve(response);
                     } else if (response.error_message !== undefined) {
                        reject(response.error_message);
                     } else {
                        reject(response.message);
                     }
                  } else {
                     reject('Отсутствует результат выполнения действия');
                  }

               })
               .catch(exc => {
                  this.is_in_progress = false;
                  reject(exc);
               });
         } else {
            reject('В настоящий момент выполняется действие');
         }

      });

   }

   static appendActionData (form_data) {
      let action_path = window.location.pathname;
      let url = new URL(window.location.href);
      let id_document = url.searchParams.get('id_document');

      console.log('uri');
      console.log(this.getURI());

      form_data.append('path_name', action_path);
      form_data.append('id_document', id_document);
   }

   static getURI () {
      let path = window.location.pathname;
      let url = new URL(window.location.href);
      let search = url.searchParams;

      let id_document;
      if (search.has('id_document')) {
         id_document = search.get('id_document');
      } else {
         let id_input = document.getElementById('id_document');
         if (!id_input) {
            ErrorModal.open(
               'Ошибка при получении параметра страницы',
               'Не найден параметр id_document'
            );
            return null;
         }

         id_document = id_input.value;
      }

      return `${path}?${id_document}`;
   }
}
