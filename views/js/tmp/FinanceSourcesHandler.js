let multiple_blocks;

document.addEventListener('DOMContentLoaded', () => {

   multiple_blocks = new Map();

   let blocks = document.querySelectorAll('.block[data-type="multiple"]');
   blocks.forEach(block => {
      multiple_blocks.set(block.dataset.block_name, new MultipleBlock(block));
   });

   //TODO поиск внутри multiple блока
   MultipleBlock.templates_container = document.querySelector('[data-block_name="templates_container"]');


   // let multiple_blocks_values = new Map();


 /*  let blocks = document.querySelectorAll('.block[data-type="multiple"]');
   blocks.forEach(block => {
      multiple_blocks_values.set(block.dataset.block_name, new MultipleBlock(block));
   });*/



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
         let part_content = MultipleBlock.createBlock(part, 'type');
         let actions = MultipleBlock.createBlock(part, 'actions');


         let save_btn = actions.querySelector('.save');
         save_btn.addEventListener('click', () => {

            let part_data = new Part(part);

            if (part_data.type) {

               let part_result = part.querySelector('.field-result');
               part_result.value = JSON.stringify(new Part(part));

               actions.dataset.inactive = 'true';
               part_content.dataset.inactive = 'true';
               part.dataset.is_saved = 'true';

               let short_part = MultipleBlock.createBlock(part, 'part_short');

               let remove_btn = part.querySelector('.body-card__part-delete');
               remove_btn.addEventListener('click', () => part.remove());

               short_part.querySelector('.body-card__part-short').addEventListener('click', () => {
                  actions.dataset.inactive = 'false';
                  part_content.dataset.inactive = 'false';
                  short_part.dataset.inactive = 'true';
                  changeParentCardMaxHeight(this.main_block);

               });

               let part_info = short_part.querySelector('.part-info');
               let part_title = part.querySelector(`[data-part_title='${part_data.type}']`);
               part_info.innerHTML = part_title.innerHTML;

               changeParentCardMaxHeight(this.main_block);

            } else {
               //todo validate block
            }

         });

         let cancel_btn = actions.querySelector('.cancel');
         cancel_btn.addEventListener('click', () => {
            if (part.dataset.is_saved !== 'true') {
               part.remove();
            } else {
               actions.dataset.inactive = 'true';
               part_content.dataset.inactive = 'true';
               let short_part = part.querySelector('[data-block_name="part_short"]');
               short_part.dataset.inactive = 'false';
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