document.addEventListener('DOMContentLoaded', () => {
   let save_button = document.querySelector('#application_save');

   let form = document.querySelector('#application');
   let request_urn = '/home/application/API_save_form';

   save_button.addEventListener('click', () => {

      XHR('post', request_urn, new FormData(form), null, 'json', null, null)
         .then(response => {

            switch (response.result) {
               case 8:
                  sendFiles();
                  break;
               default:
                  console.log(response);
            }

         })
         .catch(error => {
            alert(error.result);
            alert(error.message);
            console.error('XHR error: ', error);
         });

   });




});

function sendFiles() {
   // FileNeeds.putFilesToSave();

   let request_urn = '/home/API_file_needs_setter';
   let form_data = getFilesNeedsFormData();

   XHR('post', request_urn, form_data, null, 'json', null, null)
      .then(response => {

         console.log(FileNeeds.getFileNeeds());


         switch (response.result) {
            case 8:
               FileNeeds.clear();
               break;
            default:
               console.log(response);
         }

         console.log(FileNeeds.getFileNeeds());


      })
      .catch(error => {
         alert(error.result);
         alert(error.message);
         console.error('XHR error: ', error);
      });

}

function getFilesNeedsFormData() {
   let form_data = new FormData();
   let id_application = getIdApplication();
   form_data.append('id_application', id_application);
   form_data.append('file_needs_json', FileNeeds.getFileNeedsJSON());
   return form_data;
}