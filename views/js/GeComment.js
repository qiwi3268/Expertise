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

      Object.assign(this, data.comment_data, {id: null});
      this.attached_file = file ? parseInt(file.dataset.id) : null;

      this.hash = CommentCreator.hash++;
      GeComment.comments.set(this.hash, this);

      CommentsTable.getInstance().addComment(this, file);
   }

   static create (comment_creator) {
      let comments = [];

      if (comment_creator.marked_files.size > 0) {

         comment_creator.marked_files.marked_files.forEach(file => {
            new GeComment(comment_creator.comment_data, file);
         });

      } else {
         new GeComment(comment_creator.comment_data, null);
      }

      return comments;
   }


   static edit (comment_creator) {


      if (comment_creator.marked_files.size > 0) {

         let iterator = comment_creator.marked_files.entries();
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




}

