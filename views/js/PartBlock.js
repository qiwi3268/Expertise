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

   static create(multiple_block) {

      let element = multiple_block.createBlock(multiple_block.element, 'multiple_block_part');
      let body = multiple_block.createBlock(element, 'type');
      let actions = multiple_block.createBlock(element, 'actions');

      return new PartBlock(multiple_block, element, body, actions);

   }

   static createFromElement (multiple_block, part_elem) {

      let body = part_elem.querySelector('[data-block][data-name="type"]');
      let actions = part_elem.querySelector('[data-block][data-name="actions"]');
      let part = new PartBlock(multiple_block, part_elem, body, actions);

      part.short_block = part_elem.querySelector('[data-block][data-name="part_short"]');

      // console.log(part);
      // console.log(new PartData(part.element));

      part.data = new PartData(part.element, true);

      console.log(part.data);

      part.id = PartBlock.parts_counter++;
      part.handleDeleteButton();
      part.handleExpandButton();

      return part;
   }

   constructor (multiple_block, element, body, actions) {
      this.parent = multiple_block;

      this.element = element;
      this.body = body;
      this.actions = actions;

      this.handleSaveButton();
      this.handleCancelButton();

   }

   handleSaveButton () {

      let save_btn = this.actions.querySelector('.save');
      save_btn.addEventListener('click', () => {

         let part_data = new PartData(this.element);
         if (part_data.type) {
            this.savePart(part_data);
         } else {

            ErrorModal.open(
               'Ошибка при сохранении источника финансирования',
               'Не выбран вид финансирования'
            );

         }

      });
   }

   savePart (part_data) {
      this.element.classList.add('short');
      // this.parent.is_changed = true;
      this.parent.element.dataset.saved = 'false';

      if (!this.is_saved) {
         this.id = PartBlock.parts_counter++;
         this.parent.parts.set(this.id, this);
         this.is_saved = true;
      }

      this.data = part_data;

      if (this.cancel_btn) {
         this.cancel_btn.remove();
      }

      this.actions.dataset.active = 'false';
      this.body.dataset.active = 'false';

      if (this.short_block) {
         this.short_block.dataset.active = 'true';
      } else {
         this.createShortElement();
      }

      let part_info = this.short_block.querySelector('.part-info');
      let part_title = this.element.querySelector(`[data-part_title='${this.data.type}']`);
      part_info.innerHTML = part_title.innerHTML;
      resizeCard(this.parent.element);

   }

   createShortElement () {
      this.short_block = this.parent.createBlock(this.element, 'part_short');

      this.handleDeleteButton();
      this.handleExpandButton();
   }

   handleDeleteButton() {
      let delete_btn = this.short_block.querySelector('.delete');
      delete_btn.addEventListener('click', () => {
         // this.parent.is_changed = true;
         this.parent.element.dataset.saved = 'false';
         this.parent.parts.delete(this.id);
         this.element.remove();
      });
   }

   handleExpandButton() {
      let expand_btn = this.short_block.querySelector('.part-short');
      expand_btn.addEventListener('click', () => {
         this.element.classList.remove('short');
         this.actions.dataset.active = 'true';
         this.body.dataset.active = 'true';
         this.short_block.dataset.active = 'false';
         resizeCard(this.parent.element);
      });
   }

   handleCancelButton () {
      this.cancel_btn = this.actions.querySelector('.cancel');
      if (this.cancel_btn) {
         this.cancel_btn.addEventListener('click', () => {
            this.element.remove();
            this.parent.parts.delete(this.id);
            resizeCard(this.parent.element);
         });
      }

   }

}

function PartData (part_block, is_initialization) {
   let field_inputs = part_block.querySelectorAll('.field-result[data-multiple_block_field]');
   field_inputs.forEach(input => {

      if (!input.closest('[data-block][data-active="false"]') || is_initialization) {
         this[input.dataset.multiple_block_field] = input.value || null;
      } else {
         this[input.dataset.multiple_block_field] = null;
      }

   });

 /*  let dependent_blocks = part_block.querySelectorAll('[data-block][data-active="true"]');
   dependent_blocks.forEach(block => {
      let field_inputs = block.querySelectorAll('.field-result[data-multiple_block_field]');
      field_inputs.forEach(input => {

         if (!input.closest('[data-block][data-active="false"]')) {
            this[input.dataset.multiple_block_field] = input.value || null;
         } else {
            this[input.dataset.multiple_block_field] = null;
         }

      });
   });*/
}