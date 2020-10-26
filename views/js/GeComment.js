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

   constructor (data, file = null) {

      Object.assign(this, data, {id: null});
      this.attached_file = file ? parseInt(file.dataset.id) : null;

      this.hash = CommentCreator.hash++;
      GeComment.comments.set(this.hash, this);

      CommentsTable.getInstance().addComment(this, file);
   }

   static create (comment_creator) {
      if (comment_creator.marked_files.size > 0) {

         comment_creator.marked_files.forEach(file => {
            new GeComment(comment_creator.comment_data, file);
         });

      } else {
         new GeComment(comment_creator.comment_data, null);
      }
   }


   static edit (comment_creator) {

      delete comment_creator.editable_comment.normative_document;
      let comment = Object.assign(comment_creator.editable_comment, comment_creator.comment_data);

      let comment_table = CommentsTable.getInstance();

      if (comment_creator.marked_files.size > 0) {

         let iterator = comment_creator.marked_files.entries();
         let first_file = iterator.next().value;
         comment.attached_file = first_file[0];
         comment_table.editComment(comment, first_file[1]);

         comment_creator.marked_files.delete(first_file[0]);

      } else {
         comment.attached_file = null;
         comment_table.editComment(comment);
      }

      GeComment.comments.set(comment.hash, comment);

      comment_creator.marked_files.forEach(file => {
         let comment_copy = Object.assign({}, comment, {file: undefined, id:null});
         comment_copy.attached_file = parseInt(file.dataset.id);
         new GeComment(comment_creator.comment_data, file);
      });

   }




}

