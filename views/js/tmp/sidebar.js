document.addEventListener('DOMContentLoaded', () => {

    let sidebar_rows = document.querySelectorAll('.sidebar-form__row');
    let selected_row;

    sidebar_rows.forEach(row => row.addEventListener('click', () => {
        //если есть выбранный элемент снимаем выделение
        selected_row = document.querySelector('.sidebar-form__row--selected');
        if (selected_row) {
            selected_row.classList.remove('sidebar-form__row--selected');
        }

        expandRelatedCard(row.dataset.card);

        //выделяем элемент и добавляем линию слева
        row.classList.add('sidebar-form__row--selected');
    }));

    function expandRelatedCard(related_card_type) {
        let related_card = document.querySelector(`.card-form[data-type='${related_card_type}']`);
        let card_body = related_card.querySelector('.body-card');
        //раскрываем блок
        if (!card_body.style.maxHeight) {
            card_body.style.maxHeight = card_body.scrollHeight + "px";
        }

    }
});