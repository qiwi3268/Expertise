document.addEventListener('DOMContentLoaded', () => {

   let targets = document.querySelectorAll('[data-tooltip]');

   targets.forEach(target => {

      target.addEventListener('mouseover', () => Tooltip.open(target), { once: true });

   });
   
});

class Tooltip {

   static instance;
   element;

   target;
   content;

   static open (target) {

      if (!this.instance) {
         this.instance = new Tooltip();
      }

      this.instance.setContent(target);
      this.instance.target = target;
      this.instance.target.appendChild(this.instance.element);
      this.instance.element.classList.add('active');


      console.log(this.instance.target.offsetHeight);
      console.log(this.instance.content.offsetHeight);

      this.instance.element.style.top = -this.instance.target.offsetHeight / 2 + 'px';

      this.instance.target.addEventListener('mouseleave', () => this.instance.close(), { once: true });
   }

   constructor () {
      this.element = document.createElement('DIV');
      this.element.classList.add('tooltip');
   }

   close () {
      this.element.classList.remove('active');
      this.element.innerHTML = '';
      this.target.addEventListener('mouseover', () => Tooltip.open(this.target), { once: true });
   }

   setContent (target) {

      let content = target.querySelector('[data-tooltip_content]');
      let copy_to_view = content.cloneNode(true);

      // this.element.style.top = target.clientHeight / 2 + 'px';

      this.content = copy_to_view;

      this.element.appendChild(this.content);
      this.content.removeAttribute('hidden');




   }

}

function getTooltipContent (target) {
    let content;

   switch (target.dataset.tooltip) {
      case 'responsible':
         content = getResponsibleBlock();
         break;

   }

   return content;
}

function getResponsibleBlock () {
   let responsible = document.createElement('DIV');
   responsible.innerHTML = 'Ответственных мне запили';
   responsible.classList.add('tooltip__responsible');
   return responsible;
}