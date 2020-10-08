document.addEventListener('DOMContentLoaded', () => {


   let submit_btn = document.querySelector('[data-action_submit]');
   submit_btn.addEventListener('click', saveSection);

});

function saveSection () {
   let form_data = new FormData();

   MultipleBlock.saveMultipleBlocks(form_data);

   form_data.append('descriptive_part', tinymce.get('descriptive_part').getContent());

   API.executeAction(form_data)
      .then((response) => {


      })
      .catch((exc) => {
         ErrorModal.open('Ошибка при сохранении общей части', exc);
      });


   console.log(new Map(form_data));
}

tinymce.init({
   selector: "textarea#descriptive_part",
   min_height: 400,
   max_height: 700,
   table_default_styles: {
      width: '50%'
   },
   elementpath: false,
   statusbar: false,
   language: 'ru',
   placeholder: 'Введите текст описательной части',
   plugins: [
      "advlist autolink lists link image charmap print preview anchor",
      "searchreplace visualblocks code fullscreen",
      "insertdatetime media table paste code help wordcount autoresize"
   ],
   toolbar:
      "undo redo | " +
      "bold italic underline | alignleft aligncenter " +
      "alignright alignjustify | bullist numlist outdent indent | " +
      "table",
   menubar: 'file edit format insert view help',
   menu: {
      file: { title: 'File', items: 'newdocument | preview | print ' },
      edit: { title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace' },
      view: { title: 'View', items: 'visualblocks' },
      insert: { title: 'Insert', items: 'inserttable charmap insertdatetime' },
      format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript | removeformat' },
      help: { title: 'Дополнительно', items: 'wordcount help' }
   },
   skin: "CUSTOM",
   content_css: "CUSTOM"
});
