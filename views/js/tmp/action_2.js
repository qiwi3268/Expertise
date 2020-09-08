document.addEventListener('DOMContentLoaded', () => {
   clearDefaultDropEvents();

   let drag_containers = document.querySelectorAll('[data-drag_container]');
   drag_containers.forEach(container => {
      new DragContainer(container, transformExpert);
   });

   /*  let drop_areas = document.querySelectorAll('[data-drop_area]');
     drop_areas.forEach(area => {
        new DropArea(area);
     });*/

});

function transformExpert (expert) {
   let new_expert = document.createElement('DIV');
   new_expert.classList.add('section__expert');
   new_expert.dataset.id = expert.dataset.id;
   new_expert.dataset.drag_element = '';

   let expert_name = document.createElement('SPAN');
   expert_name.classList.add('section__name');
   expert_name.innerHTML = expert.innerHTML;
   new_expert.appendChild(expert_name);

   let remove_btn = document.createElement('SPAN');
   remove_btn.classList.add('section__icon-remove', 'fas', 'fa-minus');
   remove_btn.dataset.remove = '';
   new_expert.appendChild(remove_btn);

   return new_expert;
}