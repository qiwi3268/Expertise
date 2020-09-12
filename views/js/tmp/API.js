class API {

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

                  case 16:
                     resolve(response.uploaded_files);
                     break;

                  case 3:
                     reject('Отсутствуют загруженные файлы');
                     break;

                  case 11:
                     // todo получить из сообщения
                     reject('Слишком длинное название файла');
                     break;

                  default:
                     console.log(response);
                     reject(`Ошибка при загрузке файла на сервер:\n${response.error_message || response.message.message}`);

               }

            })
            .catch(exc => {
               reject('Ошибка при загрузке файла на сервер: ' + exc);
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

   static checkFile (id_file, mapping_1, mapping_2) {

      return new Promise((resolve, reject) => {
         let form_data = this.getFileCheckFormData(id_file, mapping_1, mapping_2);

         XHR(
            'post',
            '/home/API_file_checker',
            form_data,
            null,
            'json'
         )
            .then(response => {

               switch (response.result) {

                  case 9:
                     resolve(response);
                     break;

                  default:
                     reject(`Ошибка при проверке файла:\n${response.error_message || response.message}`);

               }

            })
            .catch(exc => {
               reject('Ошибка при проверке файла: ' + exc);
            });

      });

   }

   static getFileCheckFormData (id_file, mapping_1, mapping_2) {
      let form_data = new FormData();
      form_data.append('id_application', getIdDocument());
      form_data.append('id_file', id_file);
      form_data.append('mapping_level_1', mapping_1);
      form_data.append('mapping_level_2', mapping_2);
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

         return XHR(
            'post',
            '/home/API_external_signature_verifier',
            form_data,
            null,
            'json',
            null,
            null
         )
            .then(response => {

               switch (response.result) {

                  case 9:
                     resolve(response.validate_results);
                     break;

                  case 6.1:
                     reject('Загружен файл без открепленной подписи');
                     break;

                  default:
                     reject(`Ошибка при проверке открепленной подписи:\n${response.error_message || response.message}`);

               }

            })
            .catch(exc => {
               reject('Ошибка при проверке открепленной подписи: ' + exc);
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
                     reject(`Ошибка при получении хэша файла: \n${response.error_message || response.message}`);

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

   static internalSignatureVerify (fs_name, mapping_1, mapping_2, id_file, verify_callback = null) {

      return new Promise((resolve, reject) => {
         let form_data = this.getInternalVerifyFormData(fs_name, mapping_1, mapping_2);

         XHR(
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
                     reject(`Ошибка при проверке встроенной подписи: \n${response.error_message || response.message}`);

               }

            })
            .catch(exc => {
               reject('Ошибка при проверке встроенной подписи: ' + exc);
            });

      });
   }

   static getInternalVerifyFormData (fs_name, mapping_1, mapping_2) {
      let form_data = new FormData();
      form_data.append('fs_name_sign', fs_name);
      form_data.append('mapping_level_1', mapping_1);
      form_data.append('mapping_level_2', mapping_2);
      return form_data;
   }


}

