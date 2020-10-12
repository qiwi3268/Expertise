document.addEventListener('DOMContentLoaded', () => {

   let submit_btn = document.querySelector('[data-action_submit]');
   submit_btn.addEventListener('click', saveCommonPart);

});

function saveCommonPart () {
   let form_data = getCommonPartFormData();

   let multiple_blocks = MultipleBlock.appendMultipleBlocks(form_data);

   API.executeAction(form_data)
      .then(response => {
         MultipleBlock.saveMultipleBlocks(multiple_blocks);

         location.href = response.ref;
      })
      .catch(exc => {
         ErrorModal.open('Ошибка при сохранении общей части', exc);

      });

}

function getCommonPartFormData () {
   let form_data = new FormData();


   console.log(new Map(form_data));

   //todo вынести
   let fields = document.querySelectorAll('.field-result:not([data-multiple_block_field])');
   fields.forEach(field => {

      if (!field.closest('[data-block][data-active="false"]')) {
         form_data.append(field.name, field.value);
      } else {
         form_data.append(field.name, '');
      }

   });

   console.log(new Map(form_data));

   return form_data;
}