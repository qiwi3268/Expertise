document.addEventListener('DOMContentLoaded', () => {
   GeComment.comments = new Map();
});

class GeComment {

   static get comments() {
      return this._comments;
   }

   static set comments(comments) {
      this._comments = comments;
   }

   static getByHash(hash) {
      return this.comments.get(parseInt(hash));
   }

   hash;

   text;
   normative_document;
   criticality_name;
   comment_criticality;
   note;

   no_files;
   attached_file;

   constructor() {

   }

/*   static createFromForm(modal) {
      let comment = new GeComment();

      let field_inputs = modal.querySelectorAll('[data-field_result]');
      field_inputs.forEach(input => comment[input.name] = input.value || null);

      return comment;
   }*/

}

