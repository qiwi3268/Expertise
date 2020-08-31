document.addEventListener('DOMContentLoaded', () => {
   let file_blocks = document.querySelectorAll('.files');

   file_blocks.forEach(block => {
      let file_block = new FileBlock(block);
      file_block.initFiles();
   });

});


class FileBlock {
   element;
   actions;

   document_id;
   mapping_1;
   mapping_2;
   id_structure_node;

   constructor(block) {

      this.element = block;
      this.initFieldData();

   }

   initFieldData() {
      let parent_field = this.element.closest('[data-mapping_level_1]');
      let parent_node = this.element.closest('[data-id_structure_node]');

      this.document_id = getIdApplication();

      this.mapping_1 = parent_field.dataset.mapping_level_1;
      this.mapping_2 = parent_field.dataset.mapping_level_1;

      if (parent_node) {
         this.id_structure_node = parent_field.dataset.id_structure_node;
      }

   }


   initFiles() {
      let files = this.element.querySelectorAll('.files__item');

      if (files.length > 0) {
         this.element.classList.add('filled');
      }

      files.forEach(file_element => {
         let file = new GeFile(file_element);
         file.handleActionButtons();
      });

   }


}
























