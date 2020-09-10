document.addEventListener('DOMContentLoaded', () => {

   MultipleBlock.multiple_blocks = new Map();

   let blocks = document.querySelectorAll('.block[data-type="multiple"]');
   blocks.forEach(block => {
      new MultipleBlock(block);
   });

});

class MultipleBlock {
   static multiple_blocks;

   element;

   parts = [];

   templates_container;
   is_changed = false;

   constructor (main_block) {

      this.element = main_block;
      MultipleBlock.multiple_blocks.set(this.element.dataset.name, this);

      this.templates_container = this.element.querySelector('.block[data-name="templates_container"]');

      this.add_btn = this.element.querySelector('.field-add');
      this.add_btn.addEventListener('click', () => {

         let part = new PartBlock(this);
         this.parts.push(part);
         this.element.appendChild(part.element);

      });

   }

   static getBlockByName (name) {
      return MultipleBlock.multiple_blocks.get(name);
   }

   createBlock (main_block, dependent_name) {

      let template = this.templates_container.querySelector(`.block[data-name='${dependent_name}']`);
      let new_block = template.cloneNode(true);

      main_block.appendChild(new_block);

      new_block.dataset.active = 'true';

      this.addEventListeners(new_block);

      resizeCard(main_block);

      return new_block;
   }

   addEventListeners (new_block) {
      initializeModalSelects(new_block);
      initializeRadio(new_block);
   }

   getPartsDataJSON () {
      return JSON.stringify(this.parts.map(part => part.data));
   }

}
