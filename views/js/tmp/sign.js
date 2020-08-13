document.addEventListener('DOMContentLoaded', () => {
   let sign_modal = document.querySelector('.sign-modal');

   let signature_button = document.getElementById('signature_button');
   signature_button.addEventListener('click', () => {



      GeCades.getSelectedCertificateAlgorithm()
         .then(algorithm => {

            console.log(algorithm);

            let form_data = new FormData();
            form_data.append('sign_algorithm', algorithm);
            form_data.append('id_file', sign_modal.dataset.id_file);
            form_data.append('mapping_level_1', sign_modal.dataset.mapping_level_1);
            form_data.append('mapping_level_2', sign_modal.dataset.mapping_level_2);

            console.log(new Map(form_data));

               //Проверяем, что файл может быть скачан
               XHR('post', '/home/API_file_checker', form_data, null, 'json')
                  .then(response => {

                     console.log(response);

                     switch (response.result) {
                        case 9:

                           // location.href = getUnloadFileURN(response);
                           break;
                        default:
                           console.log(response);
                     }

                  })
                  .catch(error => {
                     console.error('XHR error: ', error);
                  });

         })
         .catch(ex => {

         });
   });



   let sign_overlay = document.querySelector('.sign-overlay');
   sign_overlay.addEventListener('click', () => {
      closeSignModal(sign_overlay);
   });
});

function showSignModal(file) {
   let sign_modal = document.querySelector('.sign-modal');
   let sign_overlay = document.querySelector('.sign-overlay');
   sign_modal.classList.add('active');
   sign_overlay.classList.add('active');

   if (sign_modal.dataset.plugin_loaded === 'false') {
      initializePlugin(sign_modal);
      sign_modal.dataset.plugin_loaded = 'true';
   }

   console.log(file);

   let parent_field = file.closest('[data-mapping_level_1]');
   sign_modal.dataset.id_file = file.dataset.id;
   sign_modal.dataset.mapping_level_1 = parent_field.dataset.mapping_level_1;
   sign_modal.dataset.mapping_level_2 = parent_field.dataset.mapping_level_2;


   let file_info = file.querySelector('.files__info');
   let sign_file = sign_modal.querySelector('.sign-modal__file');
   sign_file.innerHTML = file_info.innerHTML;


}

function initializePlugin() {

   // Блок проверок на непподерживаемые браузеры
   if(BrowserPropertiesHelper.isInternetExplorer()){
      console.log('Браузер не соответствует требованиям АИС (Internet Explorer не поддерживается)');
      return;
   } else if(isEdge()) {
      console.log('Браузер не соответствует требованиям АИС (Edge не поддерживается)');
      return;
   } else if(!BrowserPropertiesHelper.canPromise()) {
      console.log('Браузер не соответствует требованиям АИС (отсутствует поддержка promise)');
      return;
   } else {
      cadesplugin
         .then(() => {

            let canAsync = !!cadesplugin.CreateObjectAsync;
            if(canAsync){

               GeCades.CheckForPlugIn_Async('PlugInVersionTxt', 'CSPVersionTxt');

               GeCades.FillCertList_Async('CertListBox');

            }else{
               console.log('Браузер не соответствует требованиям АИС (отсутствует поддержка async)');
            }
         })
         .catch(ex => {
            console.log('Ошибка при инициализации cadesplugin:' + ex);
         });
   }
}

// Проверка на браузер IE
function isIE(){
   let retVal = (("Microsoft Internet Explorer" == navigator.appName) || // IE < 11
      navigator.userAgent.match(/Trident\/./i)); // IE 11
   return retVal;
}

// Проверка на браузер Edge
function isEdge(){
   let retVal = navigator.userAgent.match(/Edge\/./i);
   return retVal;
}

function closeSignModal(sign_overlay) {
   let sign_modal = document.querySelector('.sign-modal');
   sign_modal.classList.remove('active');
   sign_overlay.classList.remove('active');
}