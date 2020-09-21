class PartBlock {
   static parts_counter = 0;

   id;

   parent;

   element;
   body;
   actions;
   cancel_btn;

   short_block;

   data;

   is_saved = false;

   constructor (multiple_block) {
      this.parent = multiple_block;

      this.element = this.parent.createBlock(this.parent.element, 'part');
      this.body = this.parent.createBlock(this.element, 'type');
      this.actions = this.parent.createBlock(this.element, 'actions');


      this.handleSaveButton();
      this.handleCancelButton();
   }

   handleSaveButton () {
      let save_btn = this.actions.querySelector('.save');
      save_btn.addEventListener('click', () => {
         let part_data = new PartData(this.element);

         if (part_data.type) {
            this.savePart(part_data);

            console.log(this.parent.parts);
         } else {
            //todo validate block
         }

      });
   }

   savePart (part_data) {
      this.parent.is_changed = true;

      if (!this.is_saved) {
         this.id = PartBlock.parts_counter++;
         this.parent.parts.set(this.id, this);
         this.is_saved = true;
      }

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

   createShortElement () {
      this.short_block = this.parent.createBlock(this.element, 'part_short');

      let delete_btn = this.short_block.querySelector('.delete');
      delete_btn.addEventListener('click', () => {
         this.parent.is_changed = true;
         this.parent.parts.delete(this.id);
         this.element.remove();
      });

      let expand_btn = this.short_block.querySelector('.part-short');
      expand_btn.addEventListener('click', () => {
         this.actions.dataset.active = 'true';
         this.body.dataset.active = 'true';
         this.short_block.dataset.active = 'false';
         resizeCard(this.parent.element);
      });

      let part_info = this.short_block.querySelector('.part-info');
      let part_title = this.element.querySelector(`[data-part_title='${this.data.type}']`);
      part_info.innerHTML = part_title.innerHTML;

      resizeCard(this.parent.element);
   }

   handleCancelButton () {
      this.cancel_btn = this.actions.querySelector('.cancel');
      this.cancel_btn.addEventListener('click', () => {
         this.element.remove();
         this.parent.parts.delete(this.id);
         resizeCard(this.parent.element);
      });
   }

}

function PartData (part_block) {
   let dependent_blocks = part_block.querySelectorAll('.block[data-active="true"]');

   dependent_blocks.forEach(block => {
      let field_inputs = block.querySelectorAll('.field-result[data-field]');

      field_inputs.forEach(input => this[input.dataset.field] = input.value || null);
   });
}