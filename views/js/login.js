document.addEventListener('DOMContentLoaded', () => {

   let form = document.getElementById('login_form');
   form.addEventListener('submit', event => {
      event.preventDefault();
      API.login(form);
   });

});