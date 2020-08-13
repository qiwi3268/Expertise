document.addEventListener('DOMContentLoaded', () => {
   initializeFileBlocks();
});

// Предназначен для добавления действий для блока с файлами
function initializeFileBlocks() {
   let file_blocks = document.querySelectorAll('.files');
   let actions;

   file_blocks.forEach(files => {
      if (files.querySelector('.files__item')) {
         actions = files.querySelector('.files__actions');
         handleActionButtons(files);
         files.classList.add('filled');
      }
   });

}

// Предназначен для добавления обработчиков кнопок действий с файлами
// Принимает параметры-------------------------------
// actions         Element : блок с действиями
function handleActionButtons(actions) {
   let unload_button = actions.querySelector('.files__unload');
   if (unload_button) {
      handleUnloadButton(unload_button);
   }

   let delete_button = actions.querySelector('.files__delete');
   if (delete_button) {
      handleDeleteButton(delete_button);
   }
}

// Предназначен для добавления действия для скачивания файла
// Принимает параметры-------------------------------
// unload_button         Element : кнопка для скачивания файла
function handleUnloadButton(unload_button) {
   let file = unload_button.closest('.files__item');

   unload_button.addEventListener('click', () => {
      let form_data = createUnloadFileFormData(file);

      //Проверяем, что файл может быть скачан
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
   });

}

// Предназначен для создания объекта FormData для проверки файла
// Принимает параметры-------------------------------
// file          Element : блок с файлом
// Возвращает параметры------------------------------
// form_data    FormData : объект, в котором содержатся данные о файле
function createUnloadFileFormData(file) {
   let parent_row = file.closest('[data-mapping_level_1]');
   let form_data = new FormData();
   form_data.append('id_application', getIdApplication());
   form_data.append('id_file', file.dataset.id);
   form_data.append('mapping_level_1', parent_row.dataset.mapping_level_1);
   form_data.append('mapping_level_2', parent_row.dataset.mapping_level_2);
   return form_data;
}

// Предназначен для получения id текущего заявления
// Возвращает параметры------------------------------
// id         string : id текущего заявления
function getIdApplication() {
   return document.querySelector('[name="id_application"]').value;
}

// Предназначен для получения ссылки для скачивания файла
// Принимает параметры-------------------------------
// check_result     Object : объект, содержащий данные о расположении файла
// Возвращает параметры------------------------------
// url              string : относительный путь для скачивания файла
function getUnloadFileURN(check_result) {
   return `/home/file_unloader?fs_name=${check_result.fs_name}&file_name=${check_result.file_name}`;
}

// Предназначен для добавления блока с действиями к файлу
// Принимает параметры-------------------------------
// file         Element : блок с файлом, для которого добавляются действия
function addFileActions(file) {
   let file_actions = document.createElement('DIV');
   file_actions.classList.add('files__actions');
   file.appendChild(file_actions);

   let sign_button = document.createElement('I');
   sign_button.classList.add('files__sign', 'fas', 'fa-file-signature');
   file_actions.appendChild(sign_button);
   handleSignButton(sign_button, file);

   let unload_button = document.createElement('I');
   unload_button.classList.add('files__unload', 'fas', 'fa-file-download');
   file_actions.appendChild(unload_button);
   handleUnloadButton(unload_button);

   let delete_button = document.createElement('I');
   delete_button.classList.add('files__delete', 'fas', 'fa-trash');
   file_actions.appendChild(delete_button);
   handleDeleteButton(delete_button);
}

function handleSignButton(sign_button, file) {
   sign_button.addEventListener('click', () => {
      showSignModal(file);
   });
}

// Предназначен для добавления действия удаления файла
// Принимает параметры-------------------------------
// delete_button         Element : кнопка для удаления
function handleDeleteButton(delete_button) {
   let file = delete_button.closest('.files__item');
   let files = file.closest('.files');

   delete_button.addEventListener('click', () => {
      deleteFile(file);
      removeFileElement(file, files);
   });
}

// Предназначен для добавления файла в массив для удаления
// Принимает параметры-------------------------------
// file         Element : блок с удаляемым файлом
function deleteFile(file) {
   let parent_row = file.closest('[data-mapping_level_1]');
   let id_file = file.dataset.id;

   FileNeeds.putFileToDelete(
      id_file,
      parent_row.dataset.mapping_level_1,
      parent_row.dataset.mapping_level_2,
      file
   );
}

// Предназначен для удалений блока с файлом
// Принимает параметры-------------------------------
// file         Element : элемент с файлом
// files        Element : родительский блок с файлами
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






