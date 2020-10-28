/*
document.addEventListener('DOMContentLoaded', () => {
   let add_comment_btn = document.querySelector('[data-add_comment]');
   add_comment_btn.addEventListener('click', () => CommentCreator.getInstance().open());
});

class CommentCreator {

   modal;
   overlay;

   comments;

   marked_files;

   comment_hash;

   id_input;
   text;
   normative_document;
   no_files_checkbox;
   criticality_name;
   criticality_value;
   note;

   comments_table;
   table_body;
   files_container;

   saved_file;





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
      this.modal = document.querySelector('.comment-modal');
      this.overlay = document.querySelector('.comment-overlay');

      this.id_input = document.getElementById('comment_id');

      this.text = document.getElementById('comment_text');
      this.normative_document = document.getElementById('normative_document');
      this.no_files_checkbox = document.getElementById('no_files');

      this.criticality_name = document.getElementById('comment_criticality_name');
      this.criticality_value = document.getElementById('comment_criticality_value');
      this.note = document.getElementById('comment_note');

      this.comments = new Map();
      CommentCreator.hash = Date.now();

      this.no_files_checkbox.addEventListener('click', () => {
         this.marked_files.forEach(this.toggleFileCheckbox);
         this.marked_files.clear();
      });


      this.comments_table = document.getElementById('comments_table');
      this.table_body = document.getElementById('comments_table_body');
      this.files_container = document.getElementById('documentation');


      let save_button = this.modal.querySelector('[data-save_comment]');
      save_button.addEventListener('click', () => {

         let comment = new Comment();

         let field_inputs = this.modal.querySelectorAll('[data-field_result]');
         field_inputs.forEach(input => comment[input.name] = input.value || null);

         this.validateComment(comment);

      });

      let cancel_button = this.modal.querySelector('[data-delete_comment]');
      cancel_button.addEventListener('click', () => {
         this.modal.classList.remove('active');
         this.overlay.classList.remove('active');
      });

      this.handleFiles();
   }

   validateComment (comment) {

      validateBlock(this.modal);

      comment.criticality_name = this.criticality_name.innerHTML;

      // console.log(this.marked_files.size === 0);
      // console.log(comment.no_files === null);

      if (
         !comment.text
         || !comment.comment_criticality
         || (!comment.normative_document && comment.comment_criticality !== '1')
      ) {
         ErrorModal.open('Ошибка при сохранении замечания', 'Не заполнены обязательные поля');
      } else if (this.marked_files.size === 0 && comment.no_files === null) {
         ErrorModal.open('Ошибка при сохранении замечания', 'Не отмечены файлы к замечанию');
      } else {
         this.saveComment(comment);
      }

   }

   saveComment (comment) {
      // let counter = 0;
      // let hash;

      if (this.comment_hash === null) {

         if (this.marked_files.size > 0) {

            this.marked_files.forEach(file => {
               let comment_copy = Object.assign({}, comment, {id: null});
               comment_copy.attached_file = parseInt(file.dataset.id);
               // this.addCommentToTable(comment_copy, hash + counter++, file);
               this.addCommentToTable(comment_copy, CommentCreator.hash++, file);
            });

         } else {
            comment.attached_file = null;
            this.addCommentToTable(comment, CommentCreator.hash++);

            // console.log(comment);
         }

      } else {

         if (this.marked_files.size > 0) {
            // console.log('edit_with_file_old_comment');

            let iterator = this.marked_files.entries();
            let first_file = iterator.next().value;
            comment.attached_file = first_file[0];
            this.editTableComment(comment, this.comment_hash, first_file[1]);

            this.marked_files.delete(first_file[0]);

         } else {
            // console.log('edit_old_comment');
            comment.attached_file = null;
            this.editTableComment(comment, this.comment_hash);

         }

         this.marked_files.forEach(file => {
            // console.log('copy');
            let comment_copy = Object.assign({}, comment, {file: undefined, id:null});
            comment_copy.attached_file = parseInt(file.dataset.id);
            this.addCommentToTable(comment_copy, CommentCreator.hash++, file);
         });
      }

      resizeCard(this.comments_table);

      this.modal.classList.remove('active');
      this.overlay.classList.remove('active');
   }

   addCommentToTable (comment, hash, file = null) {
      this.comments_table.dataset.active = 'true';

      comment.hash = hash;
      this.comments.set(hash, comment);

      let actions = document.createElement('DIV');
      actions.classList.add('comments-table__actions');
      actions.setAttribute('data-comment_hash', hash);
      // data_row.appendChild(actions);
      this.table_body.appendChild(actions);

      let edit_button = this.createActionButton('edit');
      edit_button.addEventListener('click', () => CommentCreator.getInstance().open(hash));
      actions.appendChild(edit_button);

      let delete_button = this.createActionButton('delete');
      delete_button.addEventListener('click', () => this.showAlert(hash));
      actions.appendChild(delete_button);

      let text_column = document.createElement('DIV');
      text_column.classList.add('comments-table__column');
      text_column.setAttribute('data-comment_text', '');
      text_column.setAttribute('data-comment_hash', hash);
      text_column.innerHTML = comment.text;
      // data_row.appendChild(text_column);
      this.table_body.appendChild(text_column);


      let normative_column = document.createElement('DIV');
      normative_column.classList.add('comments-table__column');
      normative_column.setAttribute('data-comment_normative_document', '');
      normative_column.setAttribute('data-comment_hash', hash);
      normative_column.innerHTML = comment.normative_document;
      // data_row.appendChild(normative_column);
      this.table_body.appendChild(normative_column);


      let criticality_column = document.createElement('DIV');
      criticality_column.classList.add('comments-table__column', 'comments-table__criticality');
      criticality_column.setAttribute('data-comment_criticality', '');
      criticality_column.setAttribute('data-comment_hash', hash);
      criticality_column.innerHTML = comment.criticality_name;
      // data_row.appendChild(criticality_column);
      this.table_body.appendChild(criticality_column);


      let files_column = document.createElement('DIV');
      files_column.classList.add('comments-table__column');
      files_column.setAttribute('data-comment_hash', hash);
      files_column.setAttribute('data-comment_files', '');

      // data_row.appendChild(files_column);
      this.table_body.appendChild(files_column);

      // todo добавлять, если появляются файлы на редактировании
      let files_block = document.createElement('DIV');
      files_block.classList.add('documentation__files', 'files', 'filled');
      files_block.setAttribute('data-id_file_field', '');
      files_block.setAttribute('data-mapping_level_1', this.files_container.dataset.mapping_level_1);
      files_block.setAttribute('data-mapping_level_2', this.files_container.dataset.mapping_level_2);
      files_column.appendChild(files_block);

      if (file !== null) {

         files_column.appendChild(files_block);

         //=== save file
         let file_copy = file.cloneNode(true);
         file_copy.removeAttribute('style');
         let checkbox = file_copy.querySelector('.files__checkbox');
         checkbox.remove();

         let ge_file = new GeFile(file_copy, files_block);
         ge_file.handleActionButtons();

         files_block.appendChild(file_copy);
         //=== save file

      }

   }

   addFilesToTable (comment, files_block) {
      this.marked_files.forEach(file_id => {
         let file_element = this.files_container.querySelector(`.files__item[data-id="${file_id}"]`);
         let file_copy = file_element.cloneNode(true);
         file_copy.removeAttribute('style');
         let checkbox = file_copy.querySelector('.files__checkbox');
         checkbox.remove();

         let ge_file = new GeFile(file_copy, files_block);
         ge_file.handleActionButtons();

         files_block.appendChild(file_copy);
      });
   }


   editTableComment (comment, hash, file = null) {

      comment.hash = hash;
      this.comments.set(hash, comment);

      // let table_row = this.comments_table.querySelector(`[data-comment_hash="${hash}"]`);
      let text_column = this.comments_table.querySelector(`[data-comment_hash="${hash}"][data-comment_text]`);
      text_column.innerHTML = comment.text;

      let normative_column = this.comments_table.querySelector(`[data-comment_hash="${hash}"][data-comment_normative_document]`);
      normative_column.innerHTML = comment.normative_document;

      let criticality_column = this.comments_table.querySelector(`[data-comment_hash="${hash}"][data-comment_criticality]`);
      criticality_column.innerHTML = comment.criticality_name;

      // todo сделать проверку на совпадение с уже добавленными файлами
      let files_column = this.comments_table.querySelector(`[data-comment_hash="${hash}"][data-comment_files]`);
      let files_block = files_column.querySelector('.files');
      if (files_block) {
         files_block.innerHTML = '';
      }

      //=== save file
      if (file !== null) {
         let file_copy = file.cloneNode(true);
         file_copy.removeAttribute('style');
         let checkbox = file_copy.querySelector('.files__checkbox');
         checkbox.remove();

         let ge_file = new GeFile(file_copy, files_block);
         ge_file.handleActionButtons();

         files_block.appendChild(file_copy);
      }
      //=== save file

   }

   // todo убрать
   showAlert(comment_hash) {
      let alert_modal = document.getElementById('alert_modal');
      let alert_overlay = document.getElementById('alert_overlay');

      alert_modal.classList.add('active');
      alert_overlay.classList.add('active');

      let delete_comment = () => {
         this.comments.delete(comment_hash);


         let comment_columns = this.comments_table.querySelectorAll(`[data-comment_hash="${comment_hash}"]`);
         comment_columns.forEach(column => column.remove());

         if (!this.table_body.querySelector('[data-comment_hash]')) {
            this.comments_table.dataset.active = 'false';
         }

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



   createActionButton (action_class) {
      let action = document.createElement('DIV');
      action.classList.add('comments-table__action', action_class);

      let action_icon = document.createElement('I');
      let icon_class = action_class === 'edit' ? 'fa-pen-alt' : 'fa-times';
      action_icon.classList.add('comments-table__icon-action', 'fas', icon_class);
      action.appendChild(action_icon);

      let action_text = document.createElement('SPAN');
      action_text.classList.add('comments-table__label-action');
      action_text.innerHTML = action_class === 'edit' ? 'Изменить' : 'Удалить';
      action.appendChild(action_text);

      return action;
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

   open (hash = null) {
      this.modal.classList.add('active');
      this.overlay.classList.add('active');

      this.initFields(hash);
   }

   initFields (hash) {
      let checkbox_icon = this.no_files_checkbox.querySelector('.radio__icon');
      let checkbox_field = this.no_files_checkbox.closest('.field[data-name="no_files"]');
      let checkbox_input = checkbox_field.querySelector('[data-field_result]');
      let criticality_field = this.criticality_name.closest('.field');


      this.marked_files = new Map();
      let files = this.modal.querySelectorAll('.files__item');
      files.forEach(file => this.removeFileCheckbox(file));

      let fields = this.modal.querySelectorAll('.field');
      fields.forEach(field => field.classList.remove('invalid'));

      let normative_block = this.normative_document.closest('[data-block]');

      if (hash) {


         this.comment_hash = hash;
         let comment = this.comments.get(this.comment_hash);

         // console.log(comment);
         // console.log(this.comment_hash);

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

         if (this.criticality_value.value !== '1') {
            normative_block.dataset.active = 'true';
         } else {
            normative_block.dataset.active = 'false';
         }

         // let marked_files = comment.file;
         if (comment.attached_file) {
            let file = this.modal.querySelector(`.files__item[data-id="${comment.attached_file}"]`);
            this.setFileCheckbox(file);
            this.marked_files.set(comment.attached_file, file);
            this.saved_file = file;
         }

         // console.log(comment);

         this.note.value = comment.note;

      } else {
         this.comment_hash = null;

         this.id_input.value = null;
         this.text.value = '';
         this.normative_document.value = '';
         normative_block.dataset.active = 'false';

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

}*/
