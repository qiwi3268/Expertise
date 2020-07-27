

document.addEventListener('DOMContentLoaded', () => {

});

function addDeleteButton(file) {
   let delete_button = file.querySelector('.file-delete');
   delete_button.addEventListener('click', () => {


      file.remove();
   });

}


function addUnloadButton() {

}