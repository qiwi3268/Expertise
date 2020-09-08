document.addEventListener('DOMContentLoaded', () => {
   clearDefaultDropEvents();

   let drag_containers = document.querySelectorAll('[data-drag_container]');

   drag_containers.forEach(container => {
      new DragContainer(container);
   });


});


class DropArea {



}

class DragContainer {

   container;
   elements;
   multiple;

   draggable_elem;

   constructor (drag_container) {


      this.container = drag_container;
      this.multiple = this.container.dataset.drag_multiple;

      this.elements = Array.from(this.container.querySelectorAll('[data-drag_element]'));

      this.elements.forEach(element => {

         element.addEventListener('mousedown', (event) => {


            if (this.multiple === 'true') {
               this.draggable_elem = element.cloneNode(true);
            }

            this.draggable_elem.classList.add('drag');
            this.draggable_elem.style.position = 'absolute';
            this.draggable_elem.style.zIndex = 1000;

            document.body.appendChild(this.draggable_elem);

            move(event, this.draggable_elem);


            document.addEventListener('mousemove', this.mouseMove.bind(this, event));

            document.onmousemove = function (event) {
               move(event, element);
            };

            document.onscroll = function (event) {
               // todo get mouse position
               move(event, element);
            };


            element.onmouseup = function () {
               document.onmousemove = null;


               element.remove();

            };



         });

      });

   }

   mouseMove (event) {
      this.draggable_elem.style.left = event.pageX - this.draggable_elem.offsetWidth / 2 + 'px';
      this.draggable_elem.style.top = event.pageY - this.draggable_elem.offsetHeight / 2 + 'px';
   }


}

function move (event, element) {
   element.style.left = event.pageX - element.offsetWidth / 2 + 'px';
   element.style.top = event.pageY - element.offsetHeight / 2 + 'px';
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