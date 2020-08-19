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

function uploadFiles(files, mapping_1, mapping_2, upload_callback = null) {
   // return new Promise((resolve, reject) => {
   let form_data = getUploadFormData(files, mapping_1, mapping_2);

   return XHR('post', '/home/API_file_uploader', form_data, null, 'json', null, upload_callback)
      .then(response => {

         if (response.result === 16) {
            return (response.uploaded_files);
         }

      })
      .catch(exc => {
         console.log('file upload exception: ' + exc);
      });

   // });
}

function getUploadFormData(files, mapping_1, mapping_2) {
   let form_data = new FormData();
   form_data.append('id_application', getIdApplication());
   form_data.append('mapping_level_1', mapping_1);
   form_data.append('mapping_level_2', mapping_2);

   files.forEach(file => {
      form_data.append('download_files[]', file);
   });

   return form_data;
}

function checkFile(id_file, mapping_1, mapping_2) {
   let form_data = getFileCheckFormData(id_file, mapping_1, mapping_2);

   return XHR('post', '/home/API_file_checker', form_data, null, 'json')
      .then(response => {

         if (response.result === 9) {
            return response;
         }

      })
      .catch(exc => {
         console.log('file check exception: ' + exc);
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
      let form_data = getSignVerifyFormData(fs_name_data, fs_name_sign, mapping_1, mapping_2);

      return XHR('post', '/home/API_external_signature_verifier', form_data, null, 'json', null, null)
         .then(response => {

            switch (response.result) {

               case 9:
                  resolve(response.validate_results);
                  break;
               case 6.1:
                  alert(response.error_message);
                  reject('Загружен обычный файл');
                  break;

            }

         })
         .catch(exc => {
            console.log('external verify exception: ' + exc);
         });
   });

}

function getSignVerifyFormData(fs_name_data, fs_name_sign, mapping_1, mapping_2) {
   let form_data = new FormData();
   form_data.append('fs_name_data', fs_name_data);
   form_data.append('fs_name_sign', fs_name_sign);
   form_data.append('mapping_level_1', mapping_1);
   form_data.append('mapping_level_2', mapping_2);
   return form_data;
}

function getFileHash(algorithm, fs_name) {
   let form_data = getFileHashFormData(algorithm, fs_name);

   return XHR('post', '/home/API_get_file_hash', form_data, null, 'json', null, null)
      .then(response => {

         if (response.result === 9) {
            return response.hash;
         }

      })
      .catch(exc => {
         console.log('get file hash exception: ' + exc);

      });

}

function getFileHashFormData(algorithm, fs_name) {
   let form_data = new FormData();
   form_data.append('sign_algorithm', algorithm);
   form_data.append('fs_name', fs_name);
   return form_data;
}
