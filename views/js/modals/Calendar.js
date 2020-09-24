document.addEventListener('DOMContentLoaded', () => {
   Calendar.handleOverlay();

   Calendar.handleFields();
});

class Calendar {
   // Объект календаря
   static instance;

   // Фон календаря
   static overlay;

   // Родительское поле
   select;

   // Элемент, в который записывается выбранная дата
   field_value;

   // Element календаря
   element;

   // Блок, в который помещается содержимое календаря
   body;

   // Строка с днями недели
   title;

   // Отображаемая дата в заголовке
   selected_date_label;

   // Выбранные даты
   selected_day;
   selected_month;
   selected_year;

   // Отображаемые даты
   current_date;
   current_month;
   current_year;
   current_row;

   // Уровень календаря
   // 3 - дни
   // 2 - месяцы
   // 1 - годы
   level;

   // Скрытый инпут, в который записывается выбранная дата
   result_input;

   constructor (select) {
      this.select = select;

      this.field_value = mQS(this.select, '.field-value', 4);
      this.parent_field = mClosest(this.select, '.field', 5);
      this.element = mQS(document, '.calendar', 6);
      this.body = mQS(this.element, '.calendar__body', 7);
      this.title = mQS(this.element, '.calendar__title', 8);
      this.selected_date_label = mQS(this.element, '.calendar__selected_label', 9);
      this.result_input = mQS(this.parent_field, '.field-result', 10);

      this.current_date = new Date();

      this.init();
      this.handleDateLabel();
      this.handleArrows();
   }

   // Предназначен для инициализации календаря
   init () {
      this.selected_day = this.current_date.getDate();
      this.selected_month = this.current_date.getMonth();
      this.current_month = this.current_date.getMonth();
      this.selected_year = this.current_date.getFullYear();
      this.current_year = this.current_date.getFullYear();

      this.level = 3;
      this.putItems();
   }

   // Предназначен для добавления и отображения элементов в календарь
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

   // Предназначен для создания строки для элементов календаря
   createRow () {
      this.current_row = document.createElement('DIV');
      this.current_row.classList.add('calendar__row');
      this.body.appendChild(this.current_row);
   }

   // Предназначен для добавления и отображения дней в календаре
   putDays () {
      let current_date = new Date(this.current_year, this.current_month);

      // добавляем в начало календаря дни из пердыдущего месяца и берем их количество
      let previous_month_days_count = this.putPreviousMonthDaysAndGetCount(current_date);

      for (let i = 0; i < 42 - previous_month_days_count; i++) {
         this.putDay(current_date.getDate(), current_date.getMonth(), current_date.getFullYear());
         current_date.setDate(current_date.getDate() + 1);
      }
   }

   // Предназначен для добавления в текущий месяц в календаре дней из прошлого месяца
   // Принимает параметры-------------------------------------------
   // current_date         Date : объект даты текущего месяца
   // Возвращает параметры------------------------------------------
   // days_counter         number : количество дней из предыдущего месяца добавленных в текущий месяц
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

   // Предназначен для создания и добавления дня в календарь
   // Принимает параметры-------------------------------
   // current_day         number : текущий день
   // current_month       number : текущий месяц
   // current_year        number : текущий год
   putDay (current_day, current_month, current_year) {
      let day_element = this.createDay(current_day, current_month, current_year);

      day_element.addEventListener('click', () => {

         this.current_date.setFullYear(parseInt(day_element.dataset.year));

         if (day_element.classList.contains('previous') || day_element.classList.contains('next')) {
            this.changeCurrentMonth(day_element.dataset.month);
         }

         this.select.classList.add('filled');

         this.setSelectedDate(day_element);
         this.removeSelectedItem();
         this.changeSelectedDay();
         this.close();
      });
   }

   // Предназначен для создания элемента дня
   // Принимает параметры-------------------------------
   // current_day         number : текущий день
   // current_month       number : текущий месяц
   // current_year        number : текущий год
   // Возвращает параметры------------------------------
   // day_element        Element : созданный элемент дня
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

   // Предназначен для добавления и отображения месяцев в календаре
   putMonths () {
      for (let i = 0; i < 12; i++) {
         this.putMonth(i);
      }
   }

   // Предназначен для создания и добавления месяца в календарь
   // Принимает параметры-------------------------------
   // month_num         number : номер месяца
   putMonth (month_num) {
      let month_element = this.createMonth(month_num, this.current_year);

      month_element.addEventListener('click', () => {
         this.level++;
         this.changeCurrentMonth(month_num);
         this.putItems();
      });
   }

   // Предназначен для создания элемента месяца
   // Принимает параметры-------------------------------
   // month           number : номер месяца
   // year            number : год
   // Возвращает параметры------------------------------
   // month_element  Element : созданный элемент месяца
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

   // Предназначен для создания и добавления годов в календарь
   putYears () {
      let year_shift = 5;

      for (let i = 0; i < 12; i++) {
         this.putYear(this.current_year - year_shift--);
      }
   }

   // Предназначен для создания и добавления года в календарь
   // Принимает параметры-------------------------------
   // year         number : год
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
      let field_value = mQS(select, '.field-value', 11);

      Calendar.instance.current_date = result_input.value ? getDateFromString(result_input.value) : new Date();

      Calendar.instance.select = select;
      Calendar.instance.result_input = result_input;
      Calendar.instance.field_value = field_value;

      Calendar.instance.init();
   }

   // Предназначен для создания и добавления элемента календаря
   // Принимает параметры-------------------------------
   // type          string : тип элемента
   // row_size      number : количество элементов в строке календаря
   // Возвращает параметры------------------------------
   // item         Element : созданный элемент календаря
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
      Calendar.overlay.classList.add('active');
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
      Calendar.overlay.classList.remove('active');
   }

   //========================================= Static methods

   // Предназначен для добавления обработки клика по полям, в которые записывается дата
   static handleFields () {
      // Поля, в которые записываются даты
      let calendar_fields = document.querySelectorAll('.modal-calendar');

      calendar_fields.forEach(field => {
         field.addEventListener('click', () => {
            let calendar_field = mClosest(field, '.field', 1);
            let result_input = mQS(calendar_field, '.field-result', 2);

            let calendar = Calendar.getInstance(field);
            calendar.clear(field, result_input);
            calendar.setPosition();
            calendar.show();
         });
      });
   }

   // Предназначен для добавления события клика по фону календаря
   static handleOverlay () {
      // Фон модального окна
      Calendar.overlay = mQS(document, '.calendar-overlay', 3);
      Calendar.overlay.addEventListener('click', () => {
         Calendar.getInstance().close();
      });
   }

   // Предназначен для получения объекта календаря по родительскому полю
   // Принимает параметры-------------------------------------------
   // field      Element : поле для выбора даты
   // Возвращает параметры------------------------------------------
   // calendar   Calendar : объект календаря
   static getInstance (field) {

      if (!Calendar.instance) {
         Calendar.instance = new Calendar(field);
      }

      return Calendar.instance;
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


