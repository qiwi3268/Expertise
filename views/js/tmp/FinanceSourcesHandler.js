document.addEventListener('DOMContentLoaded', () => {
   // FinanceSourcesHandler.sources = new Map();

   // new FinanceSourcesHandler();

   //TODO поиск внутри multiple блока
   MultipleBlock.templates_container = document.querySelector('[data-block_name="templates_container"]');


   let multiple_blocks_values = new Map();


   let blocks = document.querySelectorAll('.block[data-type="multiple"]');
   blocks.forEach(block => {
      multiple_blocks_values.set(block.dataset.block_name, new MultipleBlock(block));
   });



});

class FinanceSourcesHandler {
   // static sources;
   multiple_blocks_values;
   // sources_block;

   constructor() {
      // this.sources = new Map();
      this.multiple_blocks_values = new Map();
      // this.sources_block = document.querySelector('[data-block_name="finance_sources"]');


      let add_block_buttons = document.querySelectorAll('.field-add');

      add_block_buttons.forEach(add_button => {

         let multiple_block = add_button.closest('.multiple-block');
         let values = new Map();

         this.multiple_blocks_values.set(multiple_block.dataset.block_name, new MultipleBlock(multiple_block));

      });
   }
}

class MultipleBlock {
   main_block;

   name;
   parts;
   add_btn;

   part;
   save_btn;
   cancel_btn;

   static templates_container;

   constructor(main_block) {
      this.main_block = main_block;

      this.add_btn = this.main_block.querySelector('.field-add');
      this.add_btn.addEventListener('click', () => {

         let part = MultipleBlock.createBlock(this.main_block, 'part');
         let part_info = MultipleBlock.createBlock(part, 'type');
         let actions = MultipleBlock.createBlock(part, 'actions');
         
         let save_btn = actions.querySelector('.save');
         save_btn.addEventListener('click', () => {

            let part_data = new Part(part);

            if (part_data.type) {

               let part_result = part.querySelector('.field-result');
               part_result.value = JSON.stringify(new Part(part));

               actions.dataset.inactive = 'true';
               part_info.dataset.inactive = 'true';
            } else {
               //todo alert
            }

         });
      });


   }

   static createBlock(main_block, dependent_block_name) {

      let template = MultipleBlock.templates_container.querySelector(`[data-block_name='${dependent_block_name}']`);
      let new_block = template.cloneNode(true);
      main_block.appendChild(new_block);
      new_block.dataset.inactive = 'false';

      this.addEventListeners(new_block);

      changeParentCardMaxHeight(main_block);

      return new_block;
   }

   static addEventListeners(new_block) {
      initializeModalSelects(new_block);
      initializeRadio(new_block);
   }



}

function Part(part) {

   let dependent_blocks = part.querySelectorAll('.block[data-type="part"][data-inactive="false"]');

   dependent_blocks.forEach(block => {
      block.querySelectorAll('.field-result[data-field]').forEach(input => {

         this[input.dataset.field] = input.value ? input.value : null;

      });

   });

   /*if (this.type) {
      this.is_changed = true;
   }*/

}