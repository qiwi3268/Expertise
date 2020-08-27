let multiple_blocks;

document.addEventListener('DOMContentLoaded', () => {

   multiple_blocks = new Map();

   let blocks = document.querySelectorAll('.block[data-type="multiple"]');
   blocks.forEach(block => {
      multiple_blocks.set(block.dataset.block_name, new MultipleBlock(block));
   });

});

class MultipleBlock {
   element;

   //todo добавить перебор результатов
   parts;

   templates_container;
   is_changed = false;

   constructor(main_block) {
      this.element = main_block;
      this.templates_container = this.element.querySelector('[data-block_name="templates_container"]');



      this.add_btn = this.element.querySelector('.field-add');
      this.add_btn.addEventListener('click', () => {
         let part = new Part(this);
      });

   }


   createBlock(main_block, dependent_block_name) {

      let template = this.templates_container.querySelector(`[data-block_name='${dependent_block_name}']`);
      let new_block = template.cloneNode(true);
      main_block.appendChild(new_block);
      new_block.dataset.inactive = 'false';

      this.addEventListeners(new_block);

      changeParentCardMaxHeight(main_block);

      return new_block;
   }

   addEventListeners(new_block) {
      initializeModalSelects(new_block);
      initializeRadio(new_block);
   }


}

class Part {
   parent;

   element;
   body;
   actions;

   is_saved;

   short_block;

   data;

   copy;

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
            this.parent.is_changed = 'true';
            this.data = part_data;
            // this.copy = this.element.cloneNode(true);

            let result_input = this.element.querySelector('.field-result');
            result_input.value = JSON.stringify(this.data);

            this.actions.dataset.inactive = 'true';
            this.body.dataset.inactive = 'true';

            this.is_saved = 'true';

            if (this.short_block) {
               this.short_block.dataset.inactive = 'false';
            } else {
               this.createShortElement();
            }

         } else {
            //todo validate block
         }

      });
   }

   handleCancelButton() {
      let cancel_btn = this.actions.querySelector('.cancel');
      cancel_btn.addEventListener('click', () => {
         if (this.is_saved !== 'true') {
            this.element.remove();
         } else {
            // this.parent.element.replaceChild(this.copy, this.element);
            // this.element = this.copy;
            // this.element.remove();

            this.actions.dataset.inactive = 'true';
            this.body.dataset.inactive = 'true';
            this.short_block.dataset.inactive = 'false';
         }
      });
   }

   createShortElement() {
      this.short_block = this.parent.createBlock(this.element, 'part_short');

      let delete_btn = this.short_block.querySelector('.body-card__part-delete');
      delete_btn.addEventListener('click', () => this.element.remove());

      let expand_btn = this.short_block.querySelector('.body-card__part-short');
      expand_btn.addEventListener('click', () => {
         this.actions.dataset.inactive = 'false';
         this.body.dataset.inactive = 'false';
         this.short_block.dataset.inactive = 'true';
         changeParentCardMaxHeight(this.parent.element);
      });

      let part_info = this.short_block.querySelector('.part-info');
      let part_title = this.element.querySelector(`[data-part_title='${this.data.type}']`);
      part_info.innerHTML = part_title.innerHTML;

      changeParentCardMaxHeight(this.parent.element);
   }

   PartData(part_block) {
      let dependent_blocks = part_block.querySelectorAll('.block[data-type="part"][data-inactive="false"]');

      dependent_blocks.forEach(block => {
         block.querySelectorAll('.field-result[data-field]').forEach(input => {

            this[input.dataset.field] = input.value ? input.value : null;

         });

      });
   }

}

function PartData(part_block) {
   let dependent_blocks = part_block.querySelectorAll('.block[data-type="part"][data-inactive="false"]');

   dependent_blocks.forEach(block => {
      block.querySelectorAll('.field-result[data-field]').forEach(input => {

         this[input.dataset.field] = input.value ? input.value : null;

      });

   });
}