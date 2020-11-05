document.addEventListener('DOMContentLoaded', () => {

   let drag_containers = document.querySelectorAll('[data-drag_container]');
   drag_containers.forEach(container => new DragContainer(container));

});

/**
 * Представляет собой контейнер с элементами для переноса
 */
class DragContainer {

   /**
    * Блок с элементами для переноса
    *
    * @type {HTMLElement}
    */
   container;

   /**
    * Флаг, указывающий можно ли вытащить несколько копий одного элемента
    *
    * @type {boolean}
    */
   multiple;

   /**
    * callback преобразования элемента, перенесенного в область для переноса
    *
    * @type {HTMLElement}
    */
   transform_callback;

   /**
    * Массив с перетскиваемыми элементами
    *
    * @type {HTMLElement[]}
    */
   elements;

   /**
    * Создает объект контейнера с перетаскиваемыми элементами
    *
    * @param {HTMLElement} drag_container - элемент контейнера
    */
   constructor (drag_container) {

      this.container = drag_container;
      this.multiple = this.container.dataset.drag_multiple === 'true';

      this.transform_callback = this.getTransformCallback();

      this.initElements();
   }

   /**
    * Получает функцию преобразования элемента, перенесенного в область для переноса
    *
    * @return {Function}
    */
   getTransformCallback () {
      let callback;

      switch (this.container.dataset.transform_callback) {
         case 'expert':
            callback = transformExpert;
            break;
         case 'section_expert':
            callback = transformSectionExpert;
            break;
      }

      return callback
   }

   /**
    * Инициализирует перетаскиваемые элементы
    */
   initElements () {
      this.elements = Array.from(this.container.querySelectorAll('[data-drag_element]'));
      this.elements.forEach(element => this.handleElement(element));
   }

   /**
    *
    *
    * @param element
    */
   handleElement (element) {
      element.addEventListener('mousedown', (event) => {
         if (
            !event.target.hasAttribute('data-drag_inactive')
            && event.button === 0
         ) {
            console.log('tut');
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




function getAvatarCreationCallback (draggable_element) {
   let callback;

   //todo обработать случай, когда не найден callback
   switch (draggable_element.dataset.drag_callback) {
      case 'expert':
         callback = createExpertAvatar;
         break;
      case 'section_expert':
         callback = createSectionExpertAvatar;
         break;
   }

   return callback
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






/**
 * Представляет собой область на странице, в которую можно перенести элемент
 */
class DropArea {

   /**
    * Счетчик областей переноса, используется как инкрементируемый идентификатор
    *
    * @type {number}
    */
   static areas_counter = 0;

   /**
    * Контейней, хранящий объекты областей переноса
    *
    * @type {Map<number, DropArea>}
    */
   static drop_areas = new Map();

   /**
    * Элемент области переноса
    *
    * @type {HTMLElement}
    */
   element;

   /**
    * Блок, в который добавляются перенесенные элементы
    *
    * @type {HTMLElement}
    */
   container;

   /**
    * Флаг, указывающий могут ли переносится одинаковые элементы
    *
    * @type {boolean}
    */
   multiple;

   /**
    * Область, из которой можно перенести элементы
    *
    * @type {DragContainer}
    */
   drag_container;

   /**
    * callback для обработки добавления элемента в область
    *
    * @type {Function}
    */
   add_element_callback;

   /**
    * Создает объект области для переноса
    *
    * @param {HTMLElement} drop_area - элемент области
    */
   constructor (drop_area) {
      this.element = drop_area;

      this.container = this.element.querySelector('[data-drop_container]');
      this.multiple = this.container.dataset.drop_multiple === 'true';

      if (this.container.hasAttribute('data-drag_container')) {
         this.drag_container = new DragContainer(this.container);
      }

      this.add_element_callback = this.getAddElementCallback();

      this.element.dataset.id_area = DropArea.areas_counter.toString();
      DropArea.drop_areas.set(DropArea.areas_counter++, this);

   }

   /**
    * Получает функцию обработки добавления элемента в область переноса
    *
    * @return {Function}
    */
   getAddElementCallback () {
      let callback;

      switch (this.element.dataset.add_element_callback) {
         case 'add_expert':
            callback = showExpertsBlock;
            break;
         default:

      }

      return callback;
   }

   addElement (element) {

      if (!this.contains(element) || this.multiple) {

         if (this.drag_container) {
            this.drag_container.addElement(element);
         } else {
            this.container.appendChild(element);
         }

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
      let id = parseInt(area.dataset.id_area);
      return this.drop_areas.has(id) ? this.drop_areas.get(id) : new DropArea(area);
   }

   static findByDropEvent (event) {
      // Получаем самый вложенный элемент под курсором мыши
      let deepest_elem = document.elementFromPoint(event.clientX, event.clientY);


      let drop_area = deepest_elem.closest('[data-drop_area]');


      return drop_area ? DropArea.getDropArea(drop_area) : null;
   }

}

function createAvatar () {

   if (this.is_moving) {
      let create_avatar = getAvatarCreationCallback(this.ancestor);
      this.avatar = create_avatar(this.ancestor);

      document.body.appendChild(this.avatar);
      document.body.style.userSelect = 'none';

      if (!this.drag_container.multiple) {
         this.ancestor.style.display = 'none';
      }

      document.removeEventListener('mousemove', this.create_avatar);
   }

}

function move (event) {
   this.avatar.style.left = event.pageX - this.avatar.offsetWidth / 2 + 'px';
   this.avatar.style.top = event.pageY - this.avatar.offsetHeight / 2 + 'px';
}

function dropElement (event) {
   this.is_moving = false;
   document.removeEventListener('mousemove', this.move);

   let is_added = false;


   if (this.avatar) {

      this.avatar.remove();

      document.body.style.userSelect = null;

      let drop_area = DropArea.findByDropEvent(event);
      if (drop_area) {

         this.transformed_elem = this.drag_container.transform_callback(this.ancestor);

         if (!drop_area.contains(this.transformed_elem) || drop_area.multiple) {
            drop_area.addElement(this.transformed_elem);
            this.handleRemoveButton(drop_area);
            is_added = true;
         }

      }

   }

   if (!is_added && !this.drag_container.multiple) {
      this.ancestor.style.display = null;
   }

   document.removeEventListener('mouseup', this.drop_element);

}




class DragElement {

   ancestor;
   drag_container;

   create_avatar;
   move;
   drop_element;

   is_moving;
   avatar;

   transformed_elem;

   constructor (ancestor, mouse_down_event, container) {

      this.ancestor = ancestor;
      this.drag_container = container;
      this.create_avatar = createAvatar.bind(this);
      this.is_moving = true;

      document.addEventListener('mousemove', this.create_avatar);

      this.move = move.bind(this);
      document.addEventListener('mousemove', this.move);

      this.drop_element = dropElement.bind(this);
      document.addEventListener('mouseup', this.drop_element);
   }

   handleRemoveButton (drop_area) {
      let remove_button = this.transformed_elem.querySelector('[data-drop_remove]');
      if (remove_button) {
         remove_button.addEventListener('click', () => {
            let remove_callback = getRemoveElementCallback(remove_button);
            this.transformed_elem.remove();
            remove_callback(drop_area, this);

            if (!this.drag_container.multiple) {
               this.ancestor.style.display = null;
            }

         });
      }
   }

}

