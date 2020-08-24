document.addEventListener('DOMContentLoaded', () => {

   // Меняем размер раскрытых блоков при изменении размера страницы
   window.addEventListener('resize', () => {
      let cards = document.querySelectorAll('.card-form__body');

      cards.forEach(card_body => {
         if (card_body.style.maxHeight) {
            changeParentCardMaxHeight(card_body);
         }
      });

   });

});

function mQS(element, selector, error_code) {
   let result = element.querySelector(selector);

   if (result) {
      return result;
   } else {
      ErrorHandler.createError(error_code)
      // throw new Error();
   }
}

function mClosest(element, selector, error_code) {
   let result = element.closest(selector);

   if (result) {
      return result;
   } else {
      ErrorHandler.createError(error_code)
      // throw new Error();
   }
}

// Предназначен для создания объекта даты из строки
// Принимает параметры-------------------------------
// date_string     string : строка с датой
// Возвращает параметры------------------------------
// date            Date : объект даты из строки
function getDateFromString(date_string) {
   let date_parts = date_string.split('.');
   return new Date(
      parseInt(date_parts[2]),
      parseInt(date_parts[1]) - 1,
      parseInt(date_parts[0])
   );
}

function noScroll() {
   // window.scrollTo(0, 0);
}

function disableScroll() {
   // document.body.style.position = 'fixed';
   // document.body.style.top = `-${window.scrollY}px`;
   // document.body.classList.add('stop-scrolling');
}

function enableScroll() {
   // document.body.style.position = '';
   // document.body.style.top = '';
   // document.body.classList.remove('stop-scrolling');
}

// Предназначен для получения id текущего заявления
// Возвращает параметры------------------------------
// id         string : id текущего заявления
function getIdApplication() {
   return document.querySelector('[name="id_application"]').value;
}

//---------

function getFileListFromFile(file) {
   let data_transfer = new DataTransfer();
   data_transfer.items.add(file);
   return data_transfer.files;
}

function getFileFromData(data, name) {
   let blob = new Blob([data], {type: 'text/plain'});
   return new File([blob], name, {lastModified: Date.now()});
}



function createErrorAlert(error_code) {

}

function uploadFiles(files, mapping_1, mapping_2, id_structure_node = null, upload_callback = null) {

   return new Promise((resolve, reject) => {
      let form_data = getUploadFormData(files, mapping_1, mapping_2, id_structure_node);

      XHR('post', '/home/API_file_uploader', form_data, null, 'json', null, upload_callback)
         .then(response => {

            switch (response.result) {

               case 16:
                  resolve(response.uploaded_files);
                  break;

               case 3:
                  alert('Отсутствуют загруженные файлы');
                  reject('Отсутствуют загруженные файлы');
                  break;

               default:
                  reject(`Ошибка при загрузке файла на сервер:\n${response.error_message ? response.error_message : response}`);

            }

         })
         .catch(exc => {
            reject('Ошибка при загрузке файла на сервер: ' + exc);
         });
   });

}

function getUploadFormData(files, mapping_1, mapping_2, id_structure_node) {
   let form_data = new FormData();
   form_data.append('id_application', getIdApplication());
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

function checkFile(id_file, mapping_1, mapping_2) {

   return new Promise((resolve, reject) => {
      let form_data = getFileCheckFormData(id_file, mapping_1, mapping_2);

      XHR('post', '/home/API_file_checker', form_data, null, 'json')
         .then(response => {

            switch (response.result) {

               case 9:
                  resolve(response);
                  break;

               default:
                  reject(`Ошибка при проверке файла:\n${response.error_message ? response.error_message : response}`);

            }

         })
         .catch(exc => {
            reject('Ошибка при проверке файла: ' + exc);
         });

   });

}

function getFileCheckFormData(id_file, mapping_1, mapping_2) {
   let form_data = new FormData();
   form_data.append('id_application', getIdApplication());
   form_data.append('id_file', id_file);
   form_data.append('mapping_level_1', mapping_1);
   form_data.append('mapping_level_2', mapping_2);
   return form_data;
}

function externalSignatureVerify(fs_name_data, fs_name_sign, mapping_1, mapping_2) {

   return new Promise((resolve, reject) => {
      let form_data = getExternalVerifyFormData(fs_name_data, fs_name_sign, mapping_1, mapping_2);

      return XHR('post', '/home/API_external_signature_verifier', form_data, null, 'json', null, null)
         .then(response => {

            switch (response.result) {

               case 9:
                  resolve(response.validate_results);
                  break;

               case 6.1:
                  alert(response.error_message);
                  reject('Загружен файл без открепленной подписи');
                  break;

               default:
                  reject(`Ошибка при проверке открепленной подписи:\n${response.error_message ? response.error_message : response}`);

            }

         })
         .catch(exc => {
            reject('Ошибка при проверке открепленной подписи: ' + exc);
         });
   });

}

function getExternalVerifyFormData(fs_name_data, fs_name_sign, mapping_1, mapping_2) {
   let form_data = new FormData();
   form_data.append('fs_name_data', fs_name_data);
   form_data.append('fs_name_sign', fs_name_sign);
   form_data.append('mapping_level_1', mapping_1);
   form_data.append('mapping_level_2', mapping_2);
   return form_data;
}

function getFileHash(algorithm, fs_name) {

   return new Promise((resolve, reject) => {
      let form_data = getFileHashFormData(algorithm, fs_name);

      XHR('post', '/home/API_get_file_hash', form_data, null, 'json', null, null)
         .then(response => {

            switch (response.result) {

               case 9:
                  resolve(response.hash);
                  break;

               default:
                  reject(`Ошибка при получении хэша файла: \n${response.error_message ? response.error_message : response}`);

            }

         })
         .catch(exc => {
            reject('Ошибка при получении хэша файла: ' + exc);
         });

   });


}

function getFileHashFormData(algorithm, fs_name) {
   let form_data = new FormData();
   form_data.append('sign_algorithm', algorithm);
   form_data.append('fs_name', fs_name);
   return form_data;
}

function internalSignatureVerify(fs_name, mapping_1, mapping_2, verify_callback = null) {

   return new Promise((resolve, reject) => {
      let form_data = getInternalVerifyFormData(fs_name, mapping_1, mapping_2);

      XHR('post', '/home/API_internal_signature_verifier', form_data, null, 'json', null, verify_callback)
         .then(response => {

            switch (response.result) {
               case 5.2:
                  alert('Открепленная подпись 2');
                  //TODO на удаление
                  break;

               case 5.1:
                  resolve();
                  break;

               case 8:
                  resolve(response.validate_results);
                  break;

               default:
                  reject(`Ошибка при проверке встроенной подписи: \n${response.error_message ? response.error_message : response}`);

            }

         })
         .catch(exc => {
            reject('Ошибка при проверке встроенной подписи: ' + exc);
         });

   });
}

function getInternalVerifyFormData(fs_name, mapping_1, mapping_2) {
   let form_data = new FormData();
   form_data.append('fs_name_sign', fs_name);
   form_data.append('mapping_level_1', mapping_1);
   form_data.append('mapping_level_2', mapping_2);
   return form_data;
}

function getFileData(file) {
   let data = {};
   let parent_field = mClosest(file, '[data-mapping_level_1]', 20);

   data.element = file;
   data.id = file.dataset.id;
   data.id_sign = file.dataset.id_sign ? file.dataset.id_sign : '';
   data.mapping_1 = parent_field.dataset.mapping_level_1;
   data.mapping_2 = parent_field.dataset.mapping_level_2;

   return data;
}



