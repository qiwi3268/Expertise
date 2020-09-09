document.addEventListener('DOMContentLoaded', () => {
   clearDefaultDropEvents();



   let drag_containers = document.querySelectorAll('[data-drag_container]');
   drag_containers.forEach(container => {
      let drag_container = new DragContainer(container);
      drag_container.initElements();
   });

   /*  let drop_areas = document.querySelectorAll('[data-drop_area]');
     drop_areas.forEach(area => {
        new DropArea(area);
     });*/



});


class DropArea {
   static areas_counter = 0;
   static drop_areas = new Map();

   area;
   container;

   // elements;

   multiple;

   drag_container;

   constructor (drop_area) {
      this.area = drop_area;

      this.container = this.area.querySelector('[data-drop_container]');
      this.multiple = this.container.dataset.drop_multiple === 'true';

      if (this.container.hasAttribute('data-drag_container')) {
         this.drag_container = new DragContainer(this.container);
      }

      this.area.dataset.id = DropArea.areas_counter.toString();
      DropArea.drop_areas.set(DropArea.areas_counter++, this);

      this.elements = new Map();

   }

   addElement (element) {
      let id = parseInt(element.dataset.id);

      if (!this.elements.has(id) || this.multiple) {

         if (this.drag_container) {
            this.drag_container.addElement(element);
         } else {
            this.container.appendChild(element);
         }
         this.elements.set(id, element);

      }

   }

  

   static getDropArea (area) {
      let id = parseInt(area.dataset.id);
      return this.drop_areas.has(id) ? this.drop_areas.get(id) : new DropArea(area);
   }

}

class DragElement {

   ancestor;
   drag_container;

   createAvatar;

   avatar;

   transformed_elem;

   remove_button;

   constructor (ancestor, mouse_down_event, container) {

      this.ancestor = ancestor;
      this.drag_container = container;

      document.body.style.userSelect = 'none';
      document.onmousemove = event => {

         // this.move(mouse_down_event);

         if (!this.avatar) {
            this.createAvatar = getAvatarCreationCallback(this.ancestor);
            this.avatar = this.createAvatar(this.ancestor);
            this.avatar.hidden = true;
            document.body.appendChild(this.avatar);
         }

         //todo один раз
         if (!this.drag_container.multiple) {
            this.ancestor.style.display = 'none';
         }
         this.avatar.hidden = false;

         this.move(event);

      };

      document.onmouseup = event => {

         document.onmousemove = null;
         document.body.style.userSelect = null;


         this.avatar.hidden = true;



         let drop_area = this.findDropArea(event);


         // this.avatar.hidden = false;

         // console.log(drop_area);
         // console.log(DropArea.drop_areas);

         if (drop_area) {

            /*if (this.drag_container.container.hasAttribute('data-drop_container')) {
               console.log(this.ancestor);
               console.log(drop_area.elements);
               console.log(drop_area.elements.has(parseInt(this.ancestor.dataset.id)));

            }*/

            if (
               this.drag_container.container !== drop_area.container
               && this.drag_container.container.hasAttribute('data-drop_container')
            ) {
               console.log('123');
               let area_id = parseInt(this.drag_container.container.dataset.id);
               let area = DropArea.drop_areas.get(area_id);

               console.log(area);
            }

               if (this.drag_container.container === drop_area.container) {

               console.log('old');
               this.ancestor.style.display = null;

            } else {
               console.log('new');

               this.transformed_elem = this.drag_container.transform_callback(this.ancestor);

               this.remove_button = this.transformed_elem.querySelector('[data-drop_remove]');
               if (this.remove_button) {
                  this.remove_button.addEventListener('click', event => {

                     this.transformed_elem.remove();
                     this.remove();

                  });
               }

               drop_area.addElement(this.transformed_elem);

            }


         } else {
            console.log('123');
            this.remove();
         }

         // this.avatar.remove();


         document.onmouseup = null;

      };

      document.onscroll = () => {
         // this.move(event);
         // todo scroll
      };

   }

   move (event) {
      this.avatar.style.left = event.pageX - this.avatar.offsetWidth / 2 + 'px';
      this.avatar.style.top = event.pageY - this.avatar.offsetHeight / 2 + 'px';
   }


   findDropArea (event) {
      // Получаем самый вложенный элемент под курсором мыши
      let deepest_elem = document.elementFromPoint(event.clientX, event.clientY);
      let drop_area = deepest_elem.closest('[data-drop_area]');

      return drop_area ? DropArea.getDropArea(drop_area) : null;
   }


   remove () {

      // this.ancestor.remove();

      if (!this.drag_container.multiple) {
         this.ancestor.style.display = null;
      }

   }
/*
   removeAncestor () {

   }*/

}

class DragContainer {

   container;

   multiple;

   transform_callback;

   elements;


   constructor (drag_container) {

      this.container = drag_container;
      this.multiple = this.container.dataset.drag_multiple === 'true';

      this.transform_callback = getTransformCallback(this.container);
      this.elements = [];

   }

   initElements () {
      this.elements = Array.from(this.container.querySelectorAll('[data-drag_element]'));
      this.elements.forEach(element => this.handleElement(element));
   }


   handleElement (element) {
      element.addEventListener('mousedown', (event) => {
         if (!event.target.hasAttribute('data-drop_remove')) {
            new DragElement(element, event, this);
         }

      });
   }

   addElement (element) {
      this.container.appendChild(element);
      this.elements.push(element);
      this.handleElement(element);
   }


}


function clearDefaultDropEvents () {
   let events = ['dragenter', 'dragover', 'dragleave', 'drop'];
   events.forEach(event_name => {
      document.addEventListener(event_name, event => {
         event.preventDefault();
         event.stopPropagation();
      });
   });
}

function getTransformCallback (drag_container) {
   let callback;

   switch (drag_container.dataset.transform_callback) {
      case 'expert':
         callback = transformExpert;
         break;
      default:
         callback = defaultTransform;
         break;
   }

   return callback
}

function transformExpert (expert) {
   let new_expert = document.createElement('DIV');
   new_expert.classList.add('section__expert');
   new_expert.dataset.id = expert.dataset.id;
   new_expert.dataset.drag_element = '';
   new_expert.dataset.drag_callback = 'section_expert';

   let expert_name = document.createElement('SPAN');
   expert_name.classList.add('section__name');
   expert_name.innerHTML = expert.innerHTML;
   new_expert.appendChild(expert_name);

   let remove_btn = document.createElement('SPAN');
   remove_btn.classList.add('section__icon-remove', 'fas', 'fa-minus');
   remove_btn.dataset.drop_remove = '';
   new_expert.appendChild(remove_btn);

   return new_expert;
}

function defaultTransform (element) {
   // element.style.display = null;
   return element;
}

function getAvatarCreationCallback (draggable_element) {
   let callback;

   switch (draggable_element.dataset.drag_callback) {
      case 'expert':
         callback = expertAvatar;
         break;
      case 'section_expert':
         callback = sectionExpert;
         break;
      default:
         callback = defaultAvatar;
         break;
   }

   return callback
}

function expertAvatar (expert) {
   let expert_avatar = expert.cloneNode(true);
   expert_avatar.classList.remove('assignment__expert');
   expert_avatar.classList.add('avatar__expert');
   return expert_avatar;
}

function sectionExpert (expert) {
   let expert_avatar = document.createElement('DIV');
   expert_avatar.dataset.id = expert.dataset.id;
   expert_avatar.innerHTML = expert.querySelector('.section__name').innerHTML;
   expert_avatar.classList.add('avatar__expert');
   return expert_avatar;
}

function defaultAvatar (element) {
   let avatar = element.cloneNode(true);
   avatar.classList.add('draggable');
   avatar.style.display = 'block';
   return avatar;
}