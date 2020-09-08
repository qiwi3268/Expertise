document.addEventListener('DOMContentLoaded', () => {
   clearDefaultDropEvents();

   let drag_containers = document.querySelectorAll('[data-drag_container]');
   drag_containers.forEach(container => {
      new DragContainer(container);
   });

   let drop_areas = document.querySelectorAll('[data-drop_area]');
   drop_areas.forEach(area => {
      new DropArea(area);
   });

});


class DropArea {

   area;
   container;
   elements;


   constructor (drop_area, element) {
      this.area = drop_area;
      this.elements = [element];


   }


}

class DragContainer {

   container;
   elements;
   multiple;

   draggable_elem;

   constructor (drag_container) {

      this.container = drag_container;
      this.multiple = this.container.dataset.drag_multiple === 'true';

      this.elements = Array.from(this.container.querySelectorAll('[data-drag_element]'));

      this.elements.forEach(element => {

         element.addEventListener('mousedown', (event) => {

            this.draggable_elem = this.multiple ? element.cloneNode(true) : element;
            this.draggable_elem.classList.add('drag');

            document.body.appendChild(this.draggable_elem);
            this.move(event);


            document.onmousemove = event => {
               this.move(event);
            };

            document.onmouseup = event => {

               document.onmousemove = null;

               if (this.multiple) {
                  this.draggable_elem.remove();
               } else {
                  this.container.appendChild(this.draggable_elem);
                  this.draggable_elem.classList.remove('drag');
               }

               let drop_area = this.findDropArea(event);
               if (drop_area) {
                  console.log('123');
                  new DropArea(drop_area, this.draggable_elem);
               }


               document.onmouseup = null;


            };

         /*   this.draggable_elem.addEventListener('mouseup', event => {
               document.onmousemove = null;


               if (this.multiple) {
                  this.draggable_elem.remove();
               } else {
                  this.container.appendChild(this.draggable_elem);
                  this.draggable_elem.classList.remove('drag');
               }

               let drop_container = this.findDropContainer(event);
               console.log('123');
               console.log(drop_container);


            });
         */



          /*  document.onmousemove = function (event) {
               move(event, element);
            };*/

            document.onscroll = function (event) {
               // todo get mouse position
               // move(event, element);
            };


            /*element.onmouseup = function () {
               document.onmousemove = null;


               element.remove();

            };*/



         });

      });

   }

   move (event) {
      this.draggable_elem.style.left = event.pageX - this.draggable_elem.offsetWidth / 2 + 'px';
      this.draggable_elem.style.top = event.pageY - this.draggable_elem.offsetHeight / 2 + 'px';
   }

   findDropArea (event) {
      // Получаем самый вложенный элемент под курсором мыши
      let deepest_elem = document.elementFromPoint(event.clientX, event.clientY);
      return deepest_elem ? deepest_elem.closest('[data-drop_area]') : null;
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

function mouseUp () {

}

function mouseMove (event, element) {

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