document.addEventListener('DOMContentLoaded', () => {

   let groups = document.querySelectorAll('.comment-group');
   groups.forEach(group => {

      group.addEventListener('click', () => {
         CommentGroup.getInstance().open();
      });

   });

   let tmp_comment_1 = document.getElementById('tmp_comment_1');
   let tmp_comment_2 = document.getElementById('tmp_comment_2');
   let tmp_messages_1 = document.getElementById('tmp_messages_1');
   let tmp_messages_2 = document.getElementById('tmp_messages_2');

   tmp_comment_1.addEventListener('click', () => {
      tmp_messages_1.dataset.active = 'true';
      tmp_messages_2.dataset.active = 'false';
   });

   tmp_comment_2.addEventListener('click', () => {
      tmp_messages_2.dataset.active = 'true';
      tmp_messages_1.dataset.active = 'false';
   });
});
