document.addEventListener('DOMContentLoaded', () => {
   clearDefaultDropEvents();

   let drag_containers = document.querySelectorAll('[data-drag_container]');
   drag_containers.forEach(container => {
      let drag_container = new DragContainer(container);
      drag_container.initElements();
   });


});


class DropArea {
   static areas_counter = 0;
   static drop_areas = new Map();

   area;
   container;

   multiple;

   drag_container;

   result_callback;
   add_element_callback;
   elements;

   constructor (drop_area) {
      this.area = drop_area;

      this.container = this.area.querySelector('[data-drop_container]');
      this.multiple = this.container.dataset.drop_multiple === 'true';

      if (this.container.hasAttribute('data-drag_container')) {
         this.drag_container = new DragContainer(this.container);
      }

      this.add_element_callback = getAddElementCallback(this);
      this.elements = [];

      this.area.dataset.id = DropArea.areas_counter.toString();
      DropArea.drop_areas.set(DropArea.areas_counter++, this);

   }

   addElement (element) {

      if (!this.contains(element) || this.multiple) {

         if (this.drag_container) {
            this.drag_container.addElement(element);
         } else {
            this.container.appendChild(element);
         }

         this.elements.push(element);

         if (this.add_element_callback) {
            this.add_element_callback(this, element);
         }

      }

   }

   contains (element) {
      let id = parseInt(element.dataset.id);
      return !!this.container.querySelector(`[data-drag_element][data-id='${id}']`);
   }

   static getDropArea (area) {
      let id = parseInt(area.dataset.id);
      return this.drop_areas.has(id) ? this.drop_areas.get(id) : new DropArea(area);
   }

   getResult () {
      this.result_callback = this.area.dataset.result_callback;
      let get_result = getResultCallback(this);
      return get_result(this);
   }

}

function createAvatar (event) {

   let create_avatar = getAvatarCreationCallback(this.ancestor);
   this.avatar = create_avatar(this.ancestor);

   document.body.appendChild(this.avatar);
   document.body.style.userSelect = 'none';

   if (!this.drag_container.multiple) {
      this.ancestor.style.display = 'none';
   }

   console.log('567');


   document.removeEventListener('mousemove', this.create_avatar);
   // document.addEventListener('mousemove', this.move);


}

function move (event) {
   console.log('qwe');
   this.avatar.style.left = event.pageX - this.avatar.offsetWidth / 2 + 'px';
   this.avatar.style.top = event.pageY - this.avatar.offsetHeight / 2 + 'px';
}

function dropElement (event) {
   document.removeEventListener('mousemove', this.move);

   let is_added = false;

   if (this.avatar) {
      console.log('123');

      this.avatar.remove();

      document.body.style.userSelect = null;

      // this.avatar.hidden = true;

      let drop_area = findDropArea(event);
      if (drop_area) {

         this.transformed_elem = this.drag_container.transform_callback(this.ancestor);

         if (!drop_area.contains(this.transformed_elem) || drop_area.multiple) {
            drop_area.addElement(this.transformed_elem);
            this.handleRemoveButton(drop_area);
            is_added = true;
         }

      }


      if (!is_added) {
         this.remove();
      }



   }

   document.removeEventListener('mouseup', this.drop_element);


}

function findDropArea (event) {
   // Получаем самый вложенный элемент под курсором мыши
   let deepest_elem = document.elementFromPoint(event.clientX, event.clientY);
   let drop_area = deepest_elem.closest('[data-drop_area]');

   return drop_area ? DropArea.getDropArea(drop_area) : null;
}


class DragElement {

   ancestor;
   drag_container;

   create_avatar;
   move;
   drop_element;

   avatar;

   transformed_elem;

   constructor (ancestor, mouse_down_event, container) {

      this.ancestor = ancestor;
      this.drag_container = container;
      this.create_avatar = createAvatar.bind(this);

      document.addEventListener('mousemove', this.create_avatar);

      this.move = move.bind(this);
      document.addEventListener('mousemove', this.move);

      this.drop_element = dropElement.bind(this);
      document.addEventListener('mouseup', this.drop_element);
   }

   handleRemoveButton (drop_area) {
      let remove_button = this.transformed_elem.querySelector('[data-drop_remove]');

      if (remove_button) {

         let remove_callback = getRemoveElementCallback(remove_button);


         remove_button.addEventListener('click', () => {

            this.transformed_elem.remove();

            console.log(this.transformed_elem);

            remove_callback(drop_area, this.transformed_elem);

         });
      }
   }

   remove () {

      if (!this.drag_container.multiple) {
         this.ancestor.style.display = null;
      }

   }

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
   new_expert.dataset.drop_element = '';
   new_expert.dataset.drag_callback = 'section_expert';

   let expert_name = document.createElement('SPAN');
   expert_name.classList.add('section__name');
   expert_name.innerHTML = expert.innerHTML;
   new_expert.appendChild(expert_name);

   let remove_btn = document.createElement('SPAN');
   remove_btn.classList.add('section__icon-remove', 'fas', 'fa-minus');
   remove_btn.dataset.drop_remove = '';
   remove_btn.dataset.remove_callback = 'remove_expert';
   new_expert.appendChild(remove_btn);

   return new_expert;
}

function defaultTransform (element) {
   element.style.display = null;
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
   expert_avatar.classList.add('avatar');
   return expert_avatar;
}

function sectionExpert (expert) {
   let expert_avatar = document.createElement('DIV');
   expert_avatar.dataset.id = expert.dataset.id;
   expert_avatar.innerHTML = expert.querySelector('.section__name').innerHTML;
   expert_avatar.classList.add('avatar');
   return expert_avatar;
}

function defaultAvatar (element) {
   let avatar = element.cloneNode(true);
   avatar.classList.add('draggable');
   avatar.style.display = 'block';
   return avatar;
}

function getResultCallback (drop_area) {
   let callback;

   switch (drop_area.result_callback) {
      case 'experts_json':
         callback = getAssignedSectionsJSON;
         break;
      default:
   }

   return callback;
}

function getAssignedSectionsJSON (drop_area) {
   let section = { };
   if (drop_area.area.hasAttribute('data-id')) {
      section.id = drop_area.area.dataset.id;
   }
   section.experts = drop_area.elements.map(expert => expert.dataset.id);
   return section;
}

function defaultResult () {

}

function getAddElementCallback (drop_area) {
   let callback;

   switch (drop_area.area.dataset.add_element_callback) {
      case 'add_expert':
         callback = addExpert;
         break;
      default:

   }

   return callback;
}

function addExpert (drop_area, expert) {
   let assigned_experts = drop_area.area.querySelector('.section__experts');
   assigned_experts.dataset.active = 'true';
}

function getRemoveElementCallback (remove_button) {
   let callback;

   switch (remove_button.dataset.remove_callback) {
      case 'remove_expert':
         callback = removeExpert;
         break;
      default:

   }

   return callback;
}

function removeExpert (drop_area, expert) {
   let assigned_experts = drop_area.area.querySelector('.section__experts');
   if (!drop_area.container.querySelector('[data-drop_element]')) {
      assigned_experts.dataset.active = 'false';
   }
}