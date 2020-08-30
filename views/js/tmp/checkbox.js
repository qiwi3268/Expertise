/*
document.addEventListener('DOMContentLoaded', () => {
   let checkbox_blocks = document.querySelectorAll('.checkbox');

   checkbox_blocks.forEach(checkbox => {
      let items = checkbox.querySelectorAll('.checkbox__item');

      items.forEach(item => {
         let parent_field = item.closest('.field');
         let result_input = parent_field.querySelector('.body-card__result');

         item.addEventListener('click', () => {
            let icon = item.querySelector('.checkbox__icon');
            icon.classList.toggle('fa-circle');
            icon.classList.toggle('fa-check-circle');
            item.classList.toggle('selected');

            result_input.value = item.classList.contains('selected') ? 1 : 0;
         });

      });

   });

});*/
