document.addEventListener('DOMContentLoaded', () => {
   clearDefaultDropEvents();



   let drag_containers = document.querySelectorAll('[data-drag_container]');
   drag_containers.forEach(container => {
      let drag_container = new DragContainer(container, transformExpert);
      drag_container.initElements();
   });

   /*  let drop_areas = document.querySelectorAll('[data-drop_area]');
     drop_areas.forEach(area => {
        new DropArea(area);
     });*/



});


class DropArea {

   static drop_areas = new Map();

   area;
   container;
   elements;

   multiple;

   drag_container;

   constructor (drop_area, element) {
      this.area = drop_area;

      this.container = this.area.querySelector('[data-drop_container]');
      this.multiple = this.container.dataset.drop_multiple === 'true';


      console.log(element);
      // let transformed_elem = transform_callback ? transform_callback(element) : element;
      // console.log(transformed_elem);

      console.log(this.container);
      if (this.container.hasAttribute('data-drag_container')) {
         console.log('asd');
         this.drag_container = new DragContainer(this.container, null);
         // this.container.addElement(element);
         // this.container.appendChild(element);
         this.drag_container.addElement(element);
      } else {
         console.log('qwe');
         this.container.appendChild(element);

      }




      this.elements = [element];
   }

   addElement (element) {


   }

}

class DragElement {

   ancestor;
   container;

   createAvatar;

   avatar;

   constructor (ancestor, mouse_down_event, container) {
      this.ancestor = ancestor;
      this.container = container;

      this.createAvatar = getAvatarCreationCallback(this.ancestor);

      this.avatar = this.createAvatar(this.ancestor);


      if (!this.ancestor.multiple) {
         this.ancestor.style.display = 'none';
      }

      document.body.appendChild(this.avatar);
      this.move(mouse_down_event);

      document.onmousemove = event => {
         this.move(event);
      };

      document.onmouseup = event => {

         document.onmousemove = null;


         let drop_area = this.findDropArea(event);
         if (drop_area) {
            console.log('123');
            let transformed_elem = this.transform_callback(this.draggable_elem);
            new DropArea(drop_area, transformed_elem);
         }

         this.draggable_elem.remove();

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
      return deepest_elem ? deepest_elem.closest('[data-drop_area]') : null;
   }


   remove () {

      if (!this.ancestor.multiple) {
         this.ancestor.style.display = null;
      }

   }

}

class DragContainer {

   container;
   elements;
   multiple;

   draggable_elem;

   transform_callback;


   constructor (drag_container, drug_callback = null) {

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

         new DragElement(element, event, this);
/*

         let createAvatar = getAvatarCreationCallback(element);

         this.draggable_elem = createAvatar(element);

         console.log(this.draggable_elem);

         if (!this.multiple) {
            element.style.display = 'none';
         }

         // this.draggable_elem = this.multiple ? element.cloneNode(true) : element;
         // this.draggable_elem.classList.add('drag');

         console.log(this.draggable_elem);

         document.body.appendChild(this.draggable_elem);
         this.move(event);


         document.onmousemove = event => {
            this.move(event);
         };

         document.onmouseup = event => {

            document.onmousemove = null;

            if (!this.multiple) {
               // this.draggable_elem.remove();
               element.style.display = null;

            } else {
               // this.container.appendChild(this.draggable_elem);
               // this.draggable_elem.classList.remove('drag');
            }



            let drop_area = this.findDropArea(event);
            if (drop_area) {
               console.log('123');
               let transformed_elem = this.transform_callback(this.draggable_elem);
               new DropArea(drop_area, transformed_elem);
            }

            this.draggable_elem.remove();

            document.onmouseup = null;

         };

         document.onscroll = () => {
            // this.move(event);
            // todo scroll
         };
*/


      });
   }

   addElement (element) {
      console.log('123');
      this.container.appendChild(element);
      this.elements.push(element);
      this.handleElement(element);
   }




   mouseMove (event) {
      this.draggable_elem.style.left = event.pageX - this.draggable_elem.offsetWidth / 2 + 'px';
      this.draggable_elem.style.top = event.pageY - this.draggable_elem.offsetHeight / 2 + 'px';
   }

   mouseUp () {
      document.onmousemove = null;

      this.draggable_elem.remove();
   }
}

function move (event, element) {
   // element.style.left = event.pageX - element.offsetWidth / 2 + 'px';
   // element.style.top = event.pageY - element.offsetHeight / 2 + 'px';
   this.draggable_elem.style.left = event.pageX - this.draggable_elem.offsetWidth / 2 + 'px';
   this.draggable_elem.style.top = event.pageY - this.draggable_elem.offsetHeight / 2 + 'px';
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

function defaultTransform (element) {
   return element;
}

function getAvatarCreationCallback (draggable_element) {
   let callback;

   switch (draggable_element.dataset.drag_callback) {
      case 'expert':
         callback = expertAvatar;
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

function defaultAvatar (element) {
   let avatar = element.cloneNode(true);
   avatar.classList.add('.draggable');
   return avatar;
}