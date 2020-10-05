document.addEventListener('DOMContentLoaded', () => {

   MultipleBlock.multiple_blocks = new Map();

   let blocks = document.querySelectorAll('[data-block][data-type="multiple"]');
   blocks.forEach(block => {
      new MultipleBlock(block);
   });

   // console.log(MultipleBlock.multiple_blocks);

});

class MultipleBlock {
   static multiple_blocks;

   element;

   parts;

   templates_container;
   // is_changed = false;

   constructor (main_block) {

      this.element = main_block;
      MultipleBlock.multiple_blocks.set(this.element.dataset.name, this);

      this.templates_container = this.element.querySelector('[data-block][data-name="templates_container"]');
      this.parts = new Map();

      this.handleAddPartButton();
      this.initParts();
   }

   handleAddPartButton () {
      this.add_btn = this.element.querySelector('[data-multiple_add]');
      this.add_btn.addEventListener('click', () => {

         // let part = new PartBlock(this);
         let part = PartBlock.create(this);
         this.element.appendChild(part.element);

      });
   }

   initParts () {

      let parts = this.element.querySelectorAll('[data-block][data-name="multiple_block_part"][data-active="true"]');
      parts.forEach(part_elem => {

         let part = PartBlock.createFromElement(this, part_elem);
         this.parts.set(part.id, part);
         this.element.appendChild(part.element);

         /*  console.log(part_elem);
           let body = part_elem.querySelector('[data-block][data-name="type"]');
           let actions = part_elem.querySelector('[data-block][data-name="actions"]');
           let part = new PartBlock(this, part_elem, body, actions);
           part.short_block = part_elem.querySelector('[data-block][data-name="part_short"]');
           part.handleExpandButton();
           part.handleDeleteButton();
           this.element.appendChild(part.element);

           console.log(part);*/
      });
   }

   static getBlockByName (name) {
      return MultipleBlock.multiple_blocks.get(name);
   }

   createBlock (main_block, dependent_name) {

      let template = this.templates_container.querySelector(`[data-block][data-name='${dependent_name}']`);
      let new_block = template.cloneNode(true);

      main_block.appendChild(new_block);

      new_block.dataset.active = 'true';

      this.addEventListeners(new_block);

      resizeCard(main_block);

      return new_block;
   }

   addEventListeners (new_block) {
      Misc.initializeMiscSelects(new_block);
      initializeRadio(new_block);
   }

   getPartsDataJSON () {
      let parts = [];
      this.parts.forEach(part => parts.push(part.data));
      return JSON.stringify(parts);
   }

}
