
document.addEventListener('DOMContentLoaded', () => {

   let targets = document.querySelectorAll('[data-tooltip]');
   targets.forEach(target => {

      target.addEventListener('mouseover', () => new Tooltip(target), {once: true});

   });
   
});

class Tooltip {

   element;
   target;
   content;

   constructor (target) {
      this.element = document.createElement('DIV');
      this.target = target;

      this.setContent(this.target);
      this.target.appendChild(this.element);
      this.element.classList.add('tooltip', 'active');
      this.element.style.top = this.target.offsetHeight / 2 - this.element.offsetHeight / 2 + 'px';
      this.element.style.left = this.target.offsetWidth + 10 + 'px';
      this.target.addEventListener('mouseleave', () => this.close(), { once: true });
   }


   close () {
      this.element.remove();
      this.target.addEventListener('mouseover', () => new Tooltip(this.target), { once: true });
   }

   setContent (target) {
      let content = target.querySelector('[data-tooltip_content]');
      this.content = content.cloneNode(true);
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