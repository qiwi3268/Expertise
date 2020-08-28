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

   constructor(main_block) {

      this.element = main_block;
      MultipleBlock.multiple_blocks.set(this.element.dataset.block_name, this);

      this.templates_container = this.element.querySelector('[data-block_name="templates_container"]');

      this.add_btn = this.element.querySelector('.field-add');
      this.add_btn.addEventListener('click', () => {


         let part = new Part(this);

         this.parts.push(part);

         this.element.appendChild(part.element);
         console.log(this.element);


      });

   }

   static getBlockByName(name) {
      return MultipleBlock.multiple_blocks.get(name);
   }

   createBlock(main_block, dependent_block_name) {

      let template = this.templates_container.querySelector(`[data-block_name='${dependent_block_name}']`);
      let new_block = template.cloneNode(true);


      main_block.appendChild(new_block);


      new_block.dataset.active = 'true';




      this.addEventListeners(new_block);

      changeParentCardMaxHeight(main_block);

      return new_block;
   }

   addEventListeners(new_block) {
      initializeModalSelects(new_block);
      initializeRadio(new_block);
   }

   getPartsDataJSON() {
      return JSON.stringify(this.parts.map(part => part.data));
   }

}

class Part {
   parent;

   element;
   body;
   actions;
   cancel_btn;

   short_block;

   data;

   constructor(multiple_block) {
      this.parent = multiple_block;

      this.element = this.parent.createBlock(this.parent.element, 'part');
      this.body = this.parent.createBlock(this.element, 'type');
      this.actions = this.parent.createBlock(this.element, 'actions');


      this.handleSaveButton();
      this.handleCancelButton();
   }

   handleSaveButton() {
      let save_btn = this.actions.querySelector('.save');
      save_btn.addEventListener('click', () => {
         let part_data = new PartData(this.element);

         if (part_data.type) {
            this.savePart(part_data);
         } else {
            //todo validate block
         }

      });
   }

   savePart(part_data) {
      this.parent.is_changed = 'true';
      this.data = part_data;

      this.cancel_btn.remove();
      this.actions.dataset.active = 'false';
      this.body.dataset.active = 'false';

      if (this.short_block) {
         this.short_block.dataset.active = 'true';
      } else {
         this.createShortElement();
      }

   }

   createShortElement() {
      this.short_block = this.parent.createBlock(this.element, 'part_short');

      let delete_btn = this.short_block.querySelector('.body-card__part-delete');
      delete_btn.addEventListener('click', () => {
         this.parent.is_changed = true;
         this.element.remove();
      });

      let expand_btn = this.short_block.querySelector('.body-card__part-short');
      expand_btn.addEventListener('click', () => {
         this.actions.dataset.active = 'true';
         this.body.dataset.active = 'true';
         this.short_block.dataset.active = 'false';
         changeParentCardMaxHeight(this.parent.element);
      });

      let part_info = this.short_block.querySelector('.part-info');
      let part_title = this.element.querySelector(`[data-part_title='${this.data.type}']`);
      part_info.innerHTML = part_title.innerHTML;

      changeParentCardMaxHeight(this.parent.element);
   }

   handleCancelButton() {
      this.cancel_btn = this.actions.querySelector('.cancel');
      this.cancel_btn.addEventListener('click', this.element.remove());
   }

}

function PartData(part_block) {
   let dependent_blocks = part_block.querySelectorAll('.block[data-active="true"]');

   dependent_blocks.forEach(block => {
      let field_inputs = block.querySelectorAll('.field-result[data-field]');
      field_inputs.forEach(input => this[input.dataset.field] = input.value ? input.value : null);
   });
}