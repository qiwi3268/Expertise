document.addEventListener('DOMContentLoaded', () => {
   Calendar.handleFields();
});

/**
 * Класс представляет собой модальное окно календаря
 */
class Calendar {

   /**
    * Объект модального окна календаря
    *
    * @return {Calendar}
    */
   static get instance() {
      return this._instance;
   }

   static set instance(instance) {
      this._instance = instance;
   }

   /**
    * Фон модального окна
    *
    * @type {HTMLElement}
    */
   overlay;

   /**
    * Поле для выбора даты
    *
    * @type {HTMLElement}
    */
   select;

   /**
    * Блок, в который подставляется выбранная дата
    *
    * @type {HTMLElement}
    */
   field_value;

   /**
    * Элемент модального окна
    *
    * @type {HTMLElement}
    */
   element;

   /**
    * Блок, в который помещается содержимое календаря
    *
    * @type {HTMLElement}
    */
   body;

   /**
    * Блок с днями недели
    *
    * @type {HTMLElement}
    */
   title;

   /**
    * Дата в заголовке
    *
    * @type {HTMLElement}
    */
   selected_date_label;

   /**
    * Выбранный день
    *
    * @type {number}
    */
   selected_day;

   /**
    * Выбранный месяц
    *
    * @type {number}
    */
   selected_month;

   /**
    * Выбранный год
    *
    * @type {number}
    */
   selected_year;

   /**
    * Отображаемая дата
    *
    * @type {Date}
    */
   current_date;

   /**
    * Отображаемый месяц
    *
    * @type {number}
    */
   current_month;

   /**
    * Отображаемый год
    *
    * @type {number}
    */
   current_year;

   /**
    * Строка с элементами календаря
    *
    * @type {HTMLElement}
    */
   current_row;

   /**
    * Отображаемый уровень календаря
    * 3 - дни
    * 2 - месяцы
    * 1 - годы
    *
    * @type {number}
    */
   level;

   /**
    * Скрытый инпут поля с датой, в который записывается выбранная дата
    *
    * @type {HTMLElement}
    */
   result_input;

   /**
    * Создает объект модального окна календаря
    *
    * @param {HTMLElement} select - файловое поле
    */
   constructor (select) {
      this.select = select;

      this.field_value = mQS(this.select, '[data-field_value]', 4);
      this.parent_field = mClosest(this.select, '.field', 5);
      this.element = document.getElementById('calendar');
      this.body = document.getElementById('calendar_body');
      this.title = document.getElementById('calendar_title');
      this.selected_date_label = document.getElementById('calendar_label');
      this.result_input = mQS(this.parent_field, '[data-field_result]', 10);

      this.current_date = new Date();

      this.init();
      this.handleDateLabel();
      this.handleArrows();
      this.handleOverlay();
   }

   /**
    * Инициализирует календарь
    */
   init () {
      this.selected_day = this.current_date.getDate();
      this.selected_month = this.current_date.getMonth();
      this.current_month = this.current_date.getMonth();
      this.selected_year = this.current_date.getFullYear();
      this.current_year = this.current_date.getFullYear();

      this.level = 3;
      this.putItems();
   }

   /**
    * Добавляет элементы в календарь в зависимости от выбранного уровня
    */
   putItems () {
      this.body.innerHTML = '';
      this.createRow();

      // В зависимости от уровня добавляем элементы в календарь, строку с днями недели и
      // отображаем текущую дату в заголовке
      switch (this.level) {
         case 3 :
            this.putDays();
            this.title.classList.remove('hidden');
            this.selected_date_label.innerHTML = `${this.getFullMonthString()} ${this.current_year}`;
            break;
         case 2 :
            this.putMonths();
            this.title.classList.add('hidden');
            this.selected_date_label.innerHTML = this.current_year;
            break;
         case 1 :
            this.putYears();
            this.title.classList.add('hidden');
            this.selected_date_label.innerHTML = `${this.current_year - 5} - ${this.current_year + 6}`
      }
   }

   /**
    * Создает строку для элементов календаря
    */
   createRow () {
      this.current_row = document.createElement('DIV');
      this.current_row.classList.add('calendar__row');
      this.body.appendChild(this.current_row);
   }

   /**
    * Добавляет дни в календарь
    */
   putDays () {
      let current_date = new Date(this.current_year, this.current_month);

      // добавляем в начало календаря дни из пердыдущего месяца и берем их количество
      let previous_month_days_count = this.putPreviousMonthDaysAndGetCount(current_date);

      for (let i = 0; i < 42 - previous_month_days_count; i++) {
         this.putDay(current_date.getDate(), current_date.getMonth(), current_date.getFullYear());
         current_date.setDate(current_date.getDate() + 1);
      }
   }

   /**
    * Добавляет в текущий месяц в каледаре дни из прошлого месяца
    *
    * @param {Date} current_date - текущая дата
    * @return {number} количество добавленных дней из прошлого месяца
    */
   putPreviousMonthDaysAndGetCount (current_date) {
      let days_counter = 0;

      // Номер дня недели первого дня текущего месяца
      let first_week_day = current_date.getDay();

      // От первого дня текущего месяца отнимаем количество дней
      // в первой неделе из прошлого месяца
      let previous_month_last_day = current_date.getDate() - first_week_day + 1;

      // День из прошлого месяца, с которого начинается первая неделя
      let previous_month = new Date(current_date.getFullYear(), current_date.getMonth(), previous_month_last_day);

      for (let j = first_week_day - 1; j > 0; j--) {
         this.putDay(previous_month.getDate(), previous_month.getMonth(), previous_month.getFullYear());
         previous_month.setDate(previous_month.getDate() + 1);
         days_counter++;
      }

      return days_counter;
   }

   /**
    * Добавляет элемент дня в календарь
    *
    * @param {number} current_day - отображаемый день
    * @param {number} current_month - отображаемый месяц
    * @param {number} current_year - отображаемый год
    */
   putDay (current_day, current_month, current_year) {
      let day_element = this.createDay(current_day, current_month, current_year);
      day_element.addEventListener('click', () => {

         this.current_date.setFullYear(parseInt(day_element.dataset.year));

         if (day_element.classList.contains('previous') || day_element.classList.contains('next')) {
            this.changeCurrentMonth(day_element.dataset.month);
         }

         this.parent_field.classList.add('filled');

         this.setSelectedDate(day_element);
         this.removeSelectedItem();
         this.changeSelectedDay();
         this.close();
      });
   }

   /**
    * Создает элемент дня
    *
    * @param {number} current_day - отображаемый день
    * @param {number} current_month - отображаемый месяц
    * @param {number} current_year - отображаемый год
    * @return {HTMLElement} элемент дня для календаря
    */
   createDay (current_day, current_month, current_year) {
      let day_element = this.createItem('day', 7);

      // записывает месяц и год, к которому относится день
      day_element.dataset.year = current_year;
      day_element.dataset.month = current_month;

      // если день из прошлого или следующего месяца
      if (current_month > this.current_month) {
         day_element.classList.add('next');
      } else if (current_month < this.current_month) {
         day_element.classList.add('previous');
      }

      day_element.innerHTML = current_day;

      // Если день сопадает с выбранной датой, отображаем его как выбранный
      if (parseInt(day_element.innerHTML) === this.selected_day &&
         parseInt(day_element.dataset.month) === this.selected_month &&
         parseInt(day_element.dataset.year) === this.selected_year
      ) {
         day_element.classList.add('selected');
      }

      return day_element;
   }

   /**
    * Создает и добавляет элемент календаря в строку
    *
    * @param {string} type - тип элемента (день, месяц, год)
    * @param {number} row_size - размер строки с элементами
    * @return {HTMLElement}
    */
   createItem (type, row_size) {
      if (this.current_row.childElementCount === row_size) {
         this.createRow();
      }

      let item = document.createElement('DIV');
      item.classList.add('calendar__item');
      item.dataset.type = type;

      this.current_row.appendChild(item);

      return item;
   }

   /**
    * Добавляет месяцы в календарь
    */
   putMonths () {
      for (let i = 0; i < 12; i++) {
         this.putMonth(i);
      }
   }

   /**
    * Добавляет элемент месяца в календарь
    *
    * @param {number} month_num - номер месяца
    */
   putMonth (month_num) {
      let month_element = this.createMonth(month_num, this.current_year);
      month_element.addEventListener('click', () => {
         this.level++;
         this.changeCurrentMonth(month_num);
         this.putItems();
      });
   }

   /**
    * Создает элемент месяца
    *
    * @param {number} month - номер месяца
    * @param {number} year - отображаемый год
    * @return {HTMLElement} элемент месяца для календаря
    */
   createMonth (month, year) {
      let month_element = this.createItem('month', 4);
      month_element.innerHTML = this.getMonthString(month);
      month_element.dataset.year = year;
      month_element.dataset.month = month;

      // Если месяц совпадает с выбранной датой, отображаем как выбранный
      if (month === this.selected_month && parseInt(month_element.dataset.year) === this.selected_year) {
         month_element.classList.add('selected');
      }

      return month_element;
   }

   /**
    * Добавляет года в календарь
    */
   putYears () {
      let year_shift = 5;

      for (let i = 0; i < 12; i++) {
         this.putYear(this.current_year - year_shift--);
      }
   }

   /**
    * Добавляет элемент года в календарь
    *
    * @param {number} year - номер года
    */
   putYear (year) {
      let year_element = this.createItem('year', 4);
      year_element.innerHTML = year;

      year_element.addEventListener('click', () => {
         this.level++;
         this.changeCurrentYear(parseInt(year_element.innerHTML));
      });

      // Если год сопадает с выбранной датой, отображаем как выбранный
      if (year === this.selected_year) {
         year_element.classList.add('selected');
      }
   }

   // Предназначен для предназначен для смены выбранной даты
   // Принимает параметры-------------------------------
   // day         number : номер дня
   setSelectedDate (day_element) {
      this.current_date.setDate(+day_element.innerHTML);
      this.selected_day = this.current_date.getDate();

      this.selected_month = this.current_month;
      this.selected_year = parseInt(day_element.dataset.year);
      this.current_month = this.selected_month;
      this.current_year = this.selected_year;

      // Записываем значение в родительское поле и скрытый инпут
      this.result_input.value = this.getDateString();
      this.field_value.innerHTML = this.result_input.value;

      this.putItems();
   }

   // Предназначен для получения форматированной строки с выбранной датой
   getDateString () {
      let date_string;
      let day = this.selected_day < 10 ? `0${this.selected_day}` : this.selected_day;

      let month = this.selected_month + 1;
      let month_string = month < 10 ? `0${month}` : month;

      date_string = `${day}.${month_string}.${this.selected_year}`;

      return date_string;
   }

   // Предназначен для удаления выбранной даты
   removeSelectedItem () {
      let selected_item = this.body.querySelector('.calendar__item.selected');
      if (selected_item) {
         selected_item.classList.remove('selected');
      }
   }

   // Предназначен для смены выбранного элемента дня
   changeSelectedDay () {
      let days = Array.from(this.body.querySelectorAll('.calendar__item'));

      let selected_day = days.find(day => {
         return parseInt(day.innerHTML) === this.selected_day && parseInt(day.dataset.month) === this.selected_month
      });

      if (selected_day) {
         selected_day.classList.add('selected');
      }
   }

   // Предназначен для добавления обработчика для заголовка с выбранной датой
   handleDateLabel () {
      this.selected_date_label.addEventListener('click', () => {
         if (this.level > 1) {
            this.level--;
            this.putItems();
         }
      });
   }

   // Предназначен для добавления обработчика стрелок для смены страниц в каледаре
   handleArrows () {
      let arrow_left = mQS(this.element, '.calendar__arrow.left');
      let arrow_right = mQS(this.element, '.calendar__arrow.right');

      arrow_left.addEventListener('click', () => this.arrowClickListener(-1));
      arrow_right.addEventListener('click', () => this.arrowClickListener(1));
   }

   // Обработчик для стрелок смены страниц в календаре
   // Принимает параметры-------------------------------
   // offset         number : 1 - следующая страница
   //                        -1 - предыдущая страница
   arrowClickListener (offset) {
      switch (this.level) {
         case 3 :
            this.changeCurrentMonth(this.current_month + 1 * offset);
            break;
         case 2 :
            this.changeCurrentYear(this.current_year + 1 * offset);
            break;
         case 1 :
            this.changeCurrentYear(this.current_year + 10 * offset);
      }

      this.putItems();
   }

   // Предназначен для смены родительского поля календаря
   // Принимает параметры-------------------------------
   // select         Element : родительское поле
   // result_input   Element : поле с выбранной датой
   clear (select, result_input) {
      let field_value = mQS(select, '[data-field_value]', 11);

      this.current_date = result_input.value ? getDateFromString(result_input.value) : new Date();

      this.select = select;
      this.result_input = result_input;
      this.field_value = field_value;

      this.init();
   }

   // Предназначен для смены отображаемого месяца и добавления новых элементов
   // Принимает параметры------------------------------
   // month_num         number : номер нового месяца
   changeCurrentMonth (month_num) {
      this.current_date.setMonth(month_num);
      this.current_date.setDate(this.selected_day);

      this.current_month = this.current_date.getMonth();
      this.current_year = this.current_date.getFullYear();
   }

   // Предназначен для смены отображаемого года и добавления новых элементов
   // Принимает параметры-------------------------------
   // year_num         number : новый год
   changeCurrentYear (year_num) {
      this.current_date.setFullYear(year_num);
      this.current_year = this.current_date.getFullYear();

      this.putItems();
   }

   // Предназначен для получения короткого названия месяца по числовому значению
   // Принимает параметры-------------------------------
   // month_num           number : номер месяца
   // Возвращает параметры------------------------------
   // months[month_num]   string : короткое название месяца
   getMonthString (month_num) {
      let months = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];
      return months[month_num];
   }

   // Предназначен для получния полного названия отображаемого месяца
   // Возвращает параметры------------------------------
   // months[this.current_month]      string : полное название отображаемого месяца
   getFullMonthString () {
      let months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль',
         'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
      return months[this.current_month];
   }

   // Предназначен для отображения модального окна календаря
   show () {
      this.element.classList.add('active');
      this.overlay.classList.add('active');
   }

   // Предназначен для позиционирования календаря возле родительского поля
   setPosition () {
      let coordinates = this.select.getBoundingClientRect();
      let coordinates_with_offset = Calendar.getCoords(this.select);
      this.element.style.top = coordinates_with_offset.top - this.element.offsetHeight + 'px';
      this.element.style.left = coordinates.left + 'px';
   }

   // Предназначен для закрытия модального окна календаря
   close () {
      this.element.classList.remove('active');
      this.overlay.classList.remove('active');
   }



   // Предназначен для добавления события клика по фону календаря
   handleOverlay () {
      this.overlay = document.getElementById('calendar_overlay');
      this.overlay.addEventListener('click', () => this.close());
   }

   /**
    * Обрабатывает нажатие на поля с датой
    */
   static handleFields () {
      let calendar_fields = document.querySelectorAll('[data-modal_select="calendar"]');
      calendar_fields.forEach(field => {
         field.addEventListener('click', () => {
            let calendar_field = mClosest(field, '.field', 1);
            let result_input = mQS(calendar_field, '[data-field_result]', 2);

            let calendar = Calendar.getInstance(field);
            calendar.clear(field, result_input);
            calendar.setPosition();
            calendar.show();
         });
      });
   }

   /**
    * Получает объект модального поля календаря
    *
    * @param {HTMLElement} field - файловое поле
    * @return {Calendar}
    */
   static getInstance (field) {

      if (!this.instance) {
         this.instance = new Calendar(field);
      }

      return this.instance;
   }

   // Предназначен для получения координат элемента относительно документа
   // Принимает параметры-------------------------------
   // elem           Element : элемент, для которого нужно получить координаты
   // Возвращает параметры------------------------------
   // coordinates     Object : координаты элемента
   static getCoords (elem) {
      let box = elem.getBoundingClientRect();

      return {
         top: box.top + pageYOffset,
         left: box.left + pageXOffset
      };
   }

}


