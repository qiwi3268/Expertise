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
    * callback преобразования элемента во время переноса
    *
    * @type {Function}
    */
   create_avatar;

   /**
    * callback преобразования элемента, перенесенного в область для переноса
    *
    * @type {Function}
    */
   transform;

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

      this.transform = this.getTransformCallback();
      this.create_avatar = this.getAvatarCreationCallback();

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
    * Получает фунцию преобразования элемента для переноса
    *
    * @return {Function}
    */
   getAvatarCreationCallback () {
      let callback;

      //todo обработать случай, когда не найден callback
      switch (this.container.dataset.drag_callback) {
         case 'expert':
            callback = createExpertAvatar;
            break;
         case 'section_expert':
            callback = createSectionExpertAvatar;
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
    * Обрабатывает нажатие на перетаскиваемый элемент
    *
    * @param {HTMLElement} element - перетаскиваемый элемент
    */
   handleElement (element) {

      element.addEventListener('mousedown', (event) => {

         // Проверяем, что не нажата кнопка внутри перетаскиваемого элемента
         // и нажата левая кнопка мыши
         if (
            event.button === 0
            && !event.target.hasAttribute('data-drag_inactive')
         ) {

            let drag_container = this;
            let create_drag_element = function () {
               new DragElement(element, drag_container);
            };

            document.addEventListener('mousemove', create_drag_element, {once: true});

            // Удаляем обработку создания перетаскиваемого элемента, если просто нажали на элемент
            document.addEventListener('mouseup', () => {
               document.removeEventListener('mousemove', create_drag_element);
            }, {once: true});

         }

      });
   }

   /**
    * Добавляет в контейнер перетаскиваемый элемент
    *
    * @param {HTMLElement} element - добавляемый элемент
    */
   addElement (element) {
      this.container.appendChild(element);
      this.elements.push(element);
      this.handleElement(element);
   }

}

/**
 * Представляет собой перетаскиваемый элемент
 */
class DragElement {

   /**
    * Родительский элемент
    *
    * @type {HTMLElement}
    */
   ancestor;

   /**
    * Блок, из которого перетаскивается элемент
    *
    * @type {DragContainer}
    */
   drag_container;

   /**
    * Функция изменения позиции элемента во время переноса
    *
    * @type {Function}
    */
   move;

   /**
    * Элемент для отображения во время переноса
    *
    * @type {HTMLElement}
    */
   avatar;

   /**
    * Преобразованный элемент для добавления в область переноса
    *
    * @type {HTMLElement}
    */
   transformed_elem;

   /**
    * Создает объект перетаскиваемого элемента
    *
    * @param {HTMLElement} ancestor - родительский элемент
    * @param {DragContainer} container - блок, из которого перетаскивается элемент
    */
   constructor (ancestor, container) {

      this.ancestor = ancestor;
      this.drag_container = container;
      this.createAvatar();

      this.move = this.moveAvatar.bind(this);
      document.addEventListener('mousemove', this.move);

      document.addEventListener('mouseup', event => this.dropElement(event), {once: true});

   }

   /**
    * Создает элемент для отображения при переносе
    */
   createAvatar () {

      this.avatar = this.drag_container.create_avatar(this.ancestor);

      document.body.appendChild(this.avatar);
      document.body.style.userSelect = 'none';

      // Прячем исходный элемент, если не разрешено копирование
      if (!this.drag_container.multiple) {
         this.ancestor.style.display = 'none';
      }

   }

   /**
    * Функция изменения позиции элемента во время переноса
    *
    * @param {MouseEvent} event - событие перемещения курсора
    */
   moveAvatar (event) {
      this.avatar.style.left = event.pageX - this.avatar.offsetWidth / 2 + 'px';
      this.avatar.style.top = event.pageY - this.avatar.offsetHeight / 2 + 'px';
   }

   /**
    * Добавляет перетаскиваемый элемент в облать переноса
    *
    * @param {MouseEvent} event - событие отпускания кнопки мыши
    */
   dropElement (event) {

      document.removeEventListener('mousemove', this.move);
      let is_added = false;
      document.body.style.userSelect = null;
      this.avatar.remove();

      let drop_area = DropArea.findByDropEvent(event);
      if (drop_area) {

         this.transformed_elem = this.drag_container.transform(this.ancestor);
         if (!drop_area.contains(this.transformed_elem) || drop_area.multiple) {
            is_added = true;
            drop_area.addElement(this.transformed_elem);
            this.handleRemoveButton(drop_area);
         }

      }

      // Если не удалось перенести элемент и исходный элемент скрыт, показываем его
      if (!is_added && !this.drag_container.multiple) {
         this.ancestor.style.display = null;
      }

   }

   /**
    * Обрабатывает кнопку удаления элемента из области переноса
    *
    * @param {DropArea} drop_area - область из которой удаляется элемент
    */
   handleRemoveButton (drop_area) {
      let remove_button = this.transformed_elem.querySelector('[data-drop_remove]');
      if (remove_button) {
         remove_button.addEventListener('click', () => {

            let remove_callback = this.getRemoveElementCallback(remove_button);
            remove_callback(drop_area, this);
            this.transformed_elem.remove();

            // Показываем исходный элемент, если он скрыт
            if (!this.drag_container.multiple) {
               this.ancestor.style.display = null;
            }

         });
      }
   }

   /**
    * Получает функцию удаления элемента из области для переноса
    *
    * @param {HTMLElement} remove_button - кнопка для удаления
    * @return {Function}
    */
   getRemoveElementCallback (remove_button) {
      let callback;

      switch (remove_button.dataset.remove_callback) {
         case 'remove_expert':
            callback = removeExpert;
            break;
         default:

      }

      return callback;
   }

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

   /**
    * Добавляет перенесенный элемент в контейнер
    *
    * @param {HTMLElement} element - перенесенный элемент
    */
   addElement (element) {

      if (this.drag_container) {
         this.drag_container.addElement(element);
      } else {
         this.container.appendChild(element);
      }

      if (this.add_element_callback) {
         this.add_element_callback(this, element);
      }

   }

   /**
    * Определяет, содержится ли элемент в области
    *
    * @param {HTMLElement} element - элемент, который проверяется
    * @return {boolean}
    */
   contains (element) {
      let id = parseInt(element.dataset.id);
      return !!this.container.querySelector(`[data-drag_element][data-id='${id}']`);
   }

   /**
    * Получает объект области переноса по блоку страницы
    *
    * @param {HTMLElement} area - блок области
    * @return {DropArea}
    */
   static getDropArea (area) {
      let id = parseInt(area.dataset.id_area);
      return this.drop_areas.has(id) ? this.drop_areas.get(id) : new DropArea(area);
   }

   /**
    * Получает объект области переноса по позиции курсора при отпускании мыши
    * во время переноса элемента
    *
    * @param {MouseEvent} event - событие отпускания кнопки мыши
    * @return {DropArea | null}
    */
   static findByDropEvent (event) {
      // Получаем самый вложенный элемент под курсором мыши
      let deepest_elem = document.elementFromPoint(event.clientX, event.clientY);

      let drop_area = deepest_elem && deepest_elem.closest('[data-drop_area]');

      return drop_area ? DropArea.getDropArea(drop_area) : null;
   }
}
