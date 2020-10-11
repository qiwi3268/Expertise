document.addEventListener('DOMContentLoaded', () => {
   let add_comment_btn = document.querySelector('[data-add_comment]');
   add_comment_btn.addEventListener('click', () => CommentCreator.getInstance().open());
});

class CommentCreator {

   modal;
   overlay;

   comments;
   // comments_counter = 0;

   marked_files;
   comment_id_input;


   comment_hash;


   text;
   normative_document;
   no_files_checkbox;
   criticality_name;
   criticality_value;
   note;


   static get instance() {
      return this._instance;
   }

   static set instance(instance) {
      this._instance = instance;
   }

   static getInstance () {

      if (!this.instance) {
         this.instance = new CommentCreator();
      }

      return this.instance;
   }

   constructor () {
      this.modal = document.querySelector('.comment-modal');

      this.id_input = document.getElementById('comment_id');

      this.text = document.getElementById('comment_text');
      this.normative_document = document.getElementById('normative_document');
      this.no_files_checkbox = document.getElementById('no_files');

      this.criticality_name = document.getElementById('comment_criticality_name');
      this.criticality_value = document.getElementById('comment_criticality_value');
      this.note = document.getElementById('comment_note');


      this.comments = new Map();

      this.no_files_checkbox.addEventListener('click', () => {
         this.marked_files.forEach(this.toggleFileCheckbox);
         this.marked_files.clear();
      });


      let save_button = this.modal.querySelector('[data-save_comment]');
      save_button.addEventListener('click', () => {

         let comment = {};

         let field_inputs = this.modal.querySelectorAll('.field-result');
         field_inputs.forEach(input => comment[input.name] = input.value || null);
         comment.files = Array.from(this.marked_files.keys());
         comment.criticality_name = this.criticality_name.innerHTML;

         this.validateComment(comment);

      });

      this.handleOverlay();
      this.handleFiles();
   }

   validateComment (comment) {

      validateBlock(this.modal);

      console.log(comment);

      if (!comment.text || !comment.normative_document || !comment.comment_criticality) {
         ErrorModal.open('Ошибка при сохранении замечания', 'Не заполнены обязательные поля');
      } else if (comment.files.length === 0 && comment.no_files === null) {
         ErrorModal.open('Ошибка при сохранении замечания', 'Не отмечены файлы к замечанию');
      } else {
         this.saveComment(comment);
      }

   }

   saveComment (comment) {
      let comments_container = document.querySelector('.descriptive-part__comments');
      let comment_element;
      let hash;

      if (this.comment_hash === null) {
         console.log('save_comment');
         comment_element = document.createElement('DIV');
         comment_element.classList.add('descriptive-part__comment');
         hash = Date.now();
         comment_element.dataset.hash = hash;

         comment_element.innerHTML = comment.text;
         comment_element.addEventListener('click', () => CommentCreator.getInstance().open(comment_element));

         comments_container.appendChild(comment_element);

         // resizeCard(comments_container);
      } else {
         console.log('edit comment');
         comment_element = comments_container.querySelector(`[data-hash="${this.comment_hash}"]`);
         comment_element.innerHTML = comment.text;
         hash = this.comment_hash;
      }

      this.comments.set(hash, comment);
      this.modal.classList.remove('active');
      this.overlay.classList.remove('active');

   }

   handleFiles () {
      let files = this.modal.querySelectorAll('.files__item');
      files.forEach(file => {

         let file_info = file.querySelector('.files__info');
         file_info.addEventListener('click', () => {
            let file_id = parseInt(file.dataset.id);

            if (this.no_files_checkbox.dataset.selected !== "true") {

               this.marked_files.has(file_id)
                  ? this.marked_files.delete(file_id)
                  : this.marked_files.set(file_id, file);

               this.toggleFileCheckbox(file);

            } else {
               ErrorModal.open(
                  'Ошибка при отметке файла',
                  'Выбрана опция: "Отметка файла не требуется"'
               );
            }

         });
      });
   }

   toggleFileCheckbox (file_element) {
      let checkbox = file_element.querySelector('.files__checkbox');
      checkbox.classList.toggle('fa-check-square');
      checkbox.classList.toggle('far');
      checkbox.classList.toggle('fa-square');
      checkbox.classList.toggle('fas');
   }

   removeFileCheckbox (file_element) {
      let checkbox = file_element.querySelector('.files__checkbox');
      checkbox.classList.add('fa-square', 'far');
      checkbox.classList.remove('fa-check-square', 'fas');
   }

   setFileCheckbox (file_element) {
      let checkbox = file_element.querySelector('.files__checkbox');
      checkbox.classList.add('fa-check-square', 'fas');
      checkbox.classList.remove('fa-square', 'far');
   }

   handleOverlay () {
      this.overlay = document.querySelector('.comment-overlay');
      this.overlay.addEventListener('click', () => {
         this.modal.classList.remove('active');
         this.overlay.classList.remove('active');
      });
   }

   open (comment = null) {
      this.modal.classList.add('active');
      this.overlay.classList.add('active');

      // this.marked_files = new Map();

      this.initFields(comment);
   }

   initFields (comment_element) {
      let checkbox_icon = this.no_files_checkbox.querySelector('.radio__icon');
      let checkbox_field = this.no_files_checkbox.closest('.field[data-name="no_files"]');
      let checkbox_input = checkbox_field.querySelector('.field-result');
      let criticality_field = this.criticality_name.closest('.field');


      this.marked_files = new Map();
      let files = this.modal.querySelectorAll('.files__item');
      files.forEach(file => this.removeFileCheckbox(file));


      if (comment_element) {
         this.comment_hash = parseInt(comment_element.dataset.hash);
         let comment = this.comments.get(parseInt(this.comment_hash));

         this.id_input.value = comment.id;
         this.text.value = comment.text;
         this.normative_document.value = comment.normative_document;

         if (comment.no_files === null) {
            this.no_files_checkbox.dataset.selected = 'false';
            checkbox_icon.classList.add('fa-square');
            checkbox_icon.classList.remove('fa-check-square');
            checkbox_input.value = null;
         } else {
            this.no_files_checkbox.dataset.selected = 'true';
            checkbox_icon.classList.add('fa-check-square');
            checkbox_icon.classList.remove('fa-square');
            checkbox_input.value = "1";
         }

         this.criticality_name.innerHTML = comment.criticality_name;
         this.criticality_value.value = comment.comment_criticality;
         criticality_field.classList.add('filled');

         let marked_files = comment.files;
         marked_files.forEach(file_id => {
            let file = this.modal.querySelector(`.files__item[data-id="${file_id}"]`);
            this.setFileCheckbox(file);
            this.marked_files.set(file_id, file);
         });

         this.note.value = comment.note;

      } else {
         this.comment_hash = null;
         this.no_files_checkbox.dataset.selected = 'false';
         this.text.value = '';
         this.normative_document.value = '';

         this.criticality_name.innerHTML = 'Выберите критичность';
         this.criticality_value.value = '';
         criticality_field.classList.remove('filled');

         this.no_files_checkbox.dataset.selected = 'false';
         checkbox_icon.classList.add('fa-square');
         checkbox_icon.classList.remove('fa-check-square');
         checkbox_input.value = null;

         this.note.value = '';
      }


   }

}