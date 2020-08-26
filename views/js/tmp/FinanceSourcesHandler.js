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

   static templates_container;
   static part;

   constructor(main_block) {
      this.parts = new Map();
      this.main_block = main_block;


      this.add_btn = this.main_block.querySelector('.field-add');
      this.add_btn.addEventListener('click', () => {
         MultipleBlock.part = MultipleBlock.createBlock(this.main_block, 'template');

      });


   }

   static createBlock(main_block, dependent_block_name) {
      // console.log(main_block);
      // console.log(dependent_block_name);

      let template = MultipleBlock.templates_container.querySelector(`[data-block_name='${dependent_block_name}']`);

    /*  if (!template) {
         let parent = document.querySelector('.block[data-type="multiple"]');
         template = parent.querySelector(`[data-block_name='${dependent_block_name}']`);
      }
*/

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




class FinanceSource {

   constructor() {
   }


}