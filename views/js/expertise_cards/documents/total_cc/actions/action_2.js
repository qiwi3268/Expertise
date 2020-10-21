document.addEventListener('DOMContentLoaded', () => {

   let groups = document.querySelectorAll('.comment-group');
   groups.forEach(group => {

      group.addEventListener('click', () => {
         CommentGroup.getInstance().open();
      });

   });

});