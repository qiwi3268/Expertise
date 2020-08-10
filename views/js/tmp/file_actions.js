document.addEventListener('DOMContentLoaded', () => {
<<<<<<< HEAD
   initializeFileBlocks();
});

function initializeFileBlocks() {
   let file_blocks = document.querySelectorAll('.files');
   let actions;

   file_blocks.forEach(files => {
      if (files.querySelector('.files__item')) {
         actions = files.querySelector('.files__actions');
         handleActionButtons(files);
         files.classList.add('filled');
      }
=======
   initializeFileActions();
});

function initializeFileActions() {
   let action_blocks = document.querySelectorAll('.files__actions');

   action_blocks.forEach(actions => {
      handleActionButton(actions);
>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb
   });

}

<<<<<<< HEAD
function handleActionButtons(actions) {
=======
function handleActionButton(actions) {
>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb
   let unload_button = actions.querySelector('.files__unload');
   if (unload_button) {
      addUnloadButton(unload_button);
   }

   let delete_button = actions.querySelector('.files__delete');
   if (delete_button) {
      addDeleteButton(delete_button);
   }
}

function addFileActions(file) {
   let file_actions = document.createElement('DIV');
   file_actions.classList.add('files__actions');
   file.appendChild(file_actions);

   let unload_button = document.createElement('I');
   unload_button.classList.add('files__unload', 'fas', 'fa-file-download');
   file_actions.appendChild(unload_button);
   addUnloadButton(unload_button);

   let delete_button = document.createElement('I');
   delete_button.classList.add('files__delete', 'fas', 'fa-trash');
   file_actions.appendChild(delete_button);
   addDeleteButton(delete_button);
}

function addDeleteButton(delete_button) {
   let file = delete_button.closest('.files__item');
   let files = file.closest('.files');

   delete_button.addEventListener('click', () => {
<<<<<<< HEAD
      deleteFile(file);
      removeFileElement(file, files);
   });
}

function deleteFile(file) {
=======
      putFileToDelete(file);
      removeFileElement(file, files);
   });

}

function isDocumentationFile(file) {
   return file.closest('[data-id_structure_node]');
}

function removeFileElement(file, files) {
   file.remove();

   if (!files.querySelector('.files__item')) {
      files.classList.remove('filled');

      let parent_select = files.previousElementSibling;
      if (parent_select && parent_select.classList.contains('modal-file')) {
         parent_select.classList.remove('filled');
      }
   }
}

function putFileToDelete(file) {
>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb
   let parent_row = file.closest('[data-mapping_level_1]');
   let id_file = file.dataset.id;

   FileNeeds.putFileToDelete(
      id_file,
      parent_row.dataset.mapping_level_1,
      parent_row.dataset.mapping_level_2,
      file
   );

}

function addUnloadButton(unload_button) {
   let file = unload_button.closest('.files__item');

   unload_button.addEventListener('click', () => {
<<<<<<< HEAD
   let form_data = createUnloadFileFormData(file);

   XHR('post', '/home/API_file_checker', form_data, null, 'json')
      .then(response => {

         switch (response.result) {
            case 9:
               location.href = getUnloadFileURN(response);
               break;
            default:
               console.log(response);
         }

      })
      .catch(error => {
         console.error('XHR error: ', error);
      });
=======
      let form_data = createUnloadFileFormData(file);

      XHR('post', '/home/API_file_checker', form_data, null, 'json')
         .then(response => {

            switch (response.result) {
               case 9:
                  location.href = getUnloadFileURN(response);
                  break;
               default:
                  console.log(response);
            }

         })
         .catch(error => {
            console.error('XHR error: ', error);
         });
>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb
   });

}

function createUnloadFileFormData(file) {
   let parent_row = file.closest('[data-mapping_level_1]');
   let form_data = new FormData();
   form_data.append('id_application', getIdApplication());
   form_data.append('id_file', file.dataset.id);
   form_data.append('mapping_level_1', parent_row.dataset.mapping_level_1);
   form_data.append('mapping_level_2', parent_row.dataset.mapping_level_2);
   return form_data;
}

function getIdApplication() {
   return document.querySelector('[name="id_application"]').value;
}

function getUnloadFileURN(check_result) {
   return `/home/file_unloader?fs_name=${check_result.fs_name}&file_name=${check_result.file_name}`;
}

<<<<<<< HEAD
function removeFileElement(file, files) {
   file.remove();

   if (!files.querySelector('.files__item')) {
      files.classList.remove('filled');

      let parent_select = files.previousElementSibling;
      if (parent_select && parent_select.classList.contains('modal-file')) {
         parent_select.classList.remove('filled');
      }
   }
}
=======

>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb






