document.addEventListener('DOMContentLoaded', () => {

   MultipleBlock.multiple_blocks = new Map();

   let blocks = document.querySelectorAll('[data-block][data-type="multiple"]');
   blocks.forEach(block => {

      // if (block.dataset.readonly)

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

         let create_part = getPartCreationCallback(this.add_btn.dataset.multiple_add);

         if (create_part !== null) {
            let part = create_part(this);
            this.element.appendChild(part.element);
         } else {
            ErrorModal.open('Ошибка создания блока', 'Не определен callback для создания блока');
         }



      });
   }

   initParts () {

      let parts = this.element.querySelectorAll('[data-block][data-name="multiple_block_part"][data-active="true"]');
      parts.forEach(part_elem => {

         let part = PartBlock.createFromElement(this, part_elem);
         this.parts.set(part.id, part);
         this.element.appendChild(part.element);

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
      // todo придумать что-то с этим
      // вынести в callback создания части
      if (new_block.querySelector('[data-modal_select="misc"]')) {
         Misc.initializeMiscSelects(new_block);
      }

      if (new_block.querySelector('.radio')) {
         initializeRadio(new_block);
      }
   }

   getPartsDataJSON () {
      let parts = [];
      this.parts.forEach(part => parts.push(part.data));
      return JSON.stringify(parts);
   }

   static appendMultipleBlocks (form_data) {

      let multiple_blocks_to_save = [];

      let multiple_blocks = document.querySelectorAll('[data-block][data-type="multiple"]');
      multiple_blocks.forEach(block => {

         let multiple_block = MultipleBlock.getBlockByName(block.dataset.name);

         if (multiple_block.element.dataset.saved !== 'true') {

            // multiple_block.element.dataset.saved = 'true';
            multiple_blocks_to_save.push(multiple_block);

            form_data.append(`${block.dataset.name}_exist_flag`, '1');
            form_data.append(block.dataset.name, multiple_block.getPartsDataJSON());
         } else {
            form_data.append(`${block.dataset.name}_exist_flag`, '0');
            form_data.append(block.dataset.name, '');
         }

      });

      return multiple_blocks_to_save;
   }

   static saveMultipleBlocks (multiple_blocks) {
      multiple_blocks.forEach(block => block.element.dataset.saved = 'true')
   }

}

function getPartCreationCallback (callback_name) {
   let callback = null;

   switch (callback_name) {
      case 'add_financing_source':
         callback = createFinancingSource;
         break;
      case 'add_tep':
         callback = createTEP;
         break;
   }

   return callback;
}

