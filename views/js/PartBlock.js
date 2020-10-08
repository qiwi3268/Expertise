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

   // todo определить callback в дата атрибуте
   static createFromElement (multiple_block, part_elem) {

      let body = part_elem.querySelector('[data-block][data-name="type"]');
      let actions = part_elem.querySelector('[data-block][data-name="actions"]');
      let part = new PartBlock(multiple_block, part_elem, body, actions);

      part.short_block = part_elem.querySelector('[data-block][data-name="part_short"]');

      // console.log(part);
      // console.log(new PartData(part.element));

      part.data = new PartData(part.element, true);

      // console.log(part.data);

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

         this.data = new PartData(this.element);

         let save_part = getSavePartCallback(this.parent);

         if (save_part !== null) {
            save_part(this);
         } else {

            this.data = null;
            ErrorModal.open(
               'Ошибка при создании блока',
               'Не указан callback для сохранения данного вида блока'
            );
         }

      });
   }

   save (part_title) {
      this.element.classList.add('short');
      // this.parent.is_changed = true;
      this.parent.element.dataset.saved = 'false';

      if (!this.is_saved) {
         this.id = PartBlock.parts_counter++;
         this.parent.parts.set(this.id, this);
         this.is_saved = true;
      }

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

      let part_info = this.short_block.querySelector('[data-part_info]');
      part_info.innerHTML = part_title;
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

function createFinancingSource (multiple_block) {
   let element = multiple_block.createBlock(multiple_block.element, 'multiple_block_part');
   let body = multiple_block.createBlock(element, 'type');
   let actions = multiple_block.createBlock(element, 'actions');

   return new PartBlock(multiple_block, element, body, actions);
}

function createTEP (multiple_block) {
   let element = multiple_block.createBlock(multiple_block.element, 'multiple_block_part');
   let actions = element.querySelector('[data-block][data-name="actions"]');
   let body = element.querySelector('[data-multiple_body]');

   return new PartBlock(multiple_block, element, body, actions);
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

}

function getSavePartCallback (multiple_block) {
   let callback = null;

   switch (multiple_block.element.dataset.name) {

      case 'financing_sources':
         callback = saveFinancingSource;
         break;
      case 'TEP':
         callback = saveTEP;
         break;
      default:

   }

   return callback;
}

function saveFinancingSource (part) {

   if (part.data.type) {

      let part_title = part.element.querySelector(`[data-part_title='${part.data.type}']`);
      part.save(part_title.innerHTML);

   } else {

      this.data = null;
      ErrorModal.open(
         'Ошибка при сохранении источника финансирования',
         'Не выбран вид финансирования'
      );

   }
}

function saveTEP (part) {

   if (part.data.indicator && part.data.measure && part.data.value) {

      let title_string = `${part.data.indicator} - ${part.data.value} ${part.data.measure}`;
      let part_title = part.element.querySelector('[data-multiple_title]');
      part_title.innerHTML = title_string;
      part.save(title_string);

   } else {

      this.data = null;
      ErrorModal.open(
         'Ошибка при сохранении технико-экономического показателя',
         'Не заполнены обязательные поля'
      );

   }
}

