document.addEventListener('DOMContentLoaded', () => {
   let add_comment_btn = document.querySelector('[data-add_comment]');
   add_comment_btn.addEventListener('click', () => CommentCreator.getInstance().open());
});

class CommentCreator {

   modal;
   overlay;

   marked_files;

   id_input;
   text_input;
   normative_document_input;

   no_files_checkbox;
   criticality_name_input;
   criticality_value_input;
   note_input;


   editable_comment;
   is_editing;

   static get hash() {
      return this._hash;
   }

   static set hash(hash) {
      this._hash = hash;
   }

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
      this.modal = document.getElementById('comment_modal');
      this.overlay = document.getElementById('comment_overlay')

      this.id_input = document.getElementById('comment_id');

      this.text_input = document.getElementById('comment_text');
      this.normative_document_input = document.getElementById('normative_document');
      this.no_files_checkbox = document.getElementById('no_files');

      this.criticality_name_input = document.getElementById('comment_criticality_name');
      this.criticality_value_input = document.getElementById('comment_criticality_value');
      this.note_input = document.getElementById('comment_note');

      // this.comments = new Map();
      CommentCreator.hash = Date.now();

      this.no_files_checkbox.addEventListener('click', () => {
         this.marked_files.forEach(this.toggleFileCheckbox);
         this.marked_files.clear();
      });


      // this.comments_table = document.getElementById('comments_table');
      // this.table_body = document.getElementById('comments_table_body');
      // this.files_container = document.getElementById('documentation');


      let save_button = this.modal.querySelector('[data-save_comment]');
      save_button.addEventListener('click', () => {
         this.save();
      });

      let cancel_button = this.modal.querySelector('[data-delete_comment]');
      cancel_button.addEventListener('click', () => {
         this.modal.classList.remove('active');
         this.overlay.classList.remove('active');
      });

      this.handleFiles();
   }

   save () {
      this.comment_data = new CommentData(this);

      let is_valid = this.validate();

      if (is_valid) {
         this.saveComment();
      }

   }

   validate () {
      let result = false;

      validateBlock(this.modal);

      if (
         !this.comment_data.text
         || !this.comment_data.comment_criticality
         || (!this.comment_data.normative_document && this.comment_data.comment_criticality !== '1')
      ) {
         ErrorModal.open('Ошибка при сохранении замечания', 'Не заполнены обязательные поля');
      } else if (this.marked_files.size === 0 && this.comment_data.no_files === null) {
         ErrorModal.open('Ошибка при сохранении замечания', 'Не отмечены файлы к замечанию');
      } else {
         result = true;
      }

      return result;
   }


   saveComment () {

      !this.is_editing ? GeComment.create(this) : GeComment.edit(this);

      resizeCard(CommentsTable.getInstance().element);

      this.modal.classList.remove('active');
      this.overlay.classList.remove('active');
   }


   handleFiles () {
      let files = this.modal.querySelectorAll('.files__item');
      files.forEach(file => {

         let file_info = file.querySelector('.files__info');
         file_info.addEventListener('click', () => this.markFile(file));

      });
   }

   markFile (file) {
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

   open (comment = null) {
      this.modal.classList.add('active');
      this.overlay.classList.add('active');

      this.init(comment);
   }

   init (comment) {

      this.clearModal();

      this.setFieldValues(comment);

      let criticality_field = this.criticality_name_input.closest('.field');
      if (comment !== null) {

         this.editable_comment = comment;
         this.is_editing = true;

         let normative_block = this.normative_document_input.closest('[data-block]');
         normative_block.dataset.active = this.criticality_value_input.value !== '1' ? 'true' : 'false';

         criticality_field.classList.add('filled');
         if (this.editable_comment.attached_file) {
            let file = this.modal.querySelector(`.files__item[data-id="${this.editable_comment.attached_file}"]`);
            this.setFileCheckbox(file);
            this.marked_files.set(this.editable_comment.attached_file, file);
         }

      } else {
         criticality_field.classList.remove('filled');
         this.is_editing = false;
      }
   }

   clearModal() {
      this.marked_files = new Map();
      let files = this.modal.querySelectorAll('.files__item');
      files.forEach(file => this.removeFileCheckbox(file));

      let fields = this.modal.querySelectorAll('.field');
      fields.forEach(field => field.classList.remove('invalid'));
   }

   setFieldValues (comment) {
      this.id_input.value = comment ? comment.id : null;
      this.text_input.value = comment ? comment.text : null;
      this.normative_document_input.value = comment ? comment.normative_document : null;
      this.criticality_name_input.innerHTML = comment ? comment.criticality_name : 'Выберите критичность';
      this.criticality_value_input.value = comment ? comment.comment_criticality : null;

      let checkbox_icon = this.no_files_checkbox.querySelector('.radio__icon');
      let checkbox_field = this.no_files_checkbox.closest('.field[data-name="no_files"]');
      let checkbox_input = checkbox_field.querySelector('[data-field_result]');
      if (comment === null || comment.no_files === null) {
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

      this.note_input.value = comment ? comment.note : null;
   }

   // todo убрать в отдельный модуль
   showAlert(comment_hash) {
      let alert_modal = document.getElementById('alert_modal');
      let alert_overlay = document.getElementById('alert_overlay');

      alert_modal.classList.add('active');
      alert_overlay.classList.add('active');

      let delete_comment = () => {

         GeComment.comments.delete(comment_hash);
         // this.comments.delete(comment_hash);
         CommentsTable.getInstance().removeComment(comment_hash);

         alert_modal.classList.remove('active');
         alert_overlay.classList.remove('active');
      };

      let confirm_button = document.getElementById('alert_confirm');
      confirm_button.addEventListener('click', delete_comment, {once: true});

      let cancel_button = document.getElementById('alert_cancel');
      cancel_button.addEventListener('click', () => {
         alert_modal.classList.remove('active');
         alert_overlay.classList.remove('active');
         confirm_button.removeEventListener('click', delete_comment);

      }, {once: true});

   }

}


function CommentData(comment_creator) {
   let field_inputs = comment_creator.modal.querySelectorAll('[data-field_result]');
   field_inputs.forEach(input => this[input.name] = input.value || null);
   this.criticality_name = comment_creator.criticality_name_input.innerHTML;
   return this;
}

