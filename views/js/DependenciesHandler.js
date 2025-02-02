document.addEventListener('DOMContentLoaded', () => {
   DependenciesHandler.radio_dependency = document.querySelector('.radio__content-change-logic');

   let block_dependencies = document.getElementById('block_dependencies');
   if (block_dependencies) {
      DependenciesHandler.block_dependencies = JSON.parse(block_dependencies.value);
   }

   let require_dependencies = document.getElementById('require_dependencies');
   if (require_dependencies) {
      DependenciesHandler.require_dependencies = JSON.parse(require_dependencies.value);
   }

   DependenciesHandler.handleClearFieldButtons();
});


class DependenciesHandler {
   static radio_dependency;
   static block_dependencies;
   static require_dependencies;

   static result_input;
   static is_multiple_block;
   static blocks_container;
   static multiple_block;

   static initialize (result_input) {
      this.result_input = result_input;

      let parent_block = this.result_input.closest('[data-block]');
      if (parent_block && parent_block.dataset.type === 'template') {
         this.blocks_container = parent_block;
         this.is_multiple_block = true;

         let multiple_name = parent_block.closest('[data-block][data-type="multiple"]').dataset.name;
         this.multiple_block = MultipleBlock.getBlockByName(multiple_name);
      } else {
         this.blocks_container = document;
         this.is_multiple_block = false;
      }
   }

   //todo разбить на несколько классов
   static handleDependencies (result_input) {
      this.initialize(result_input);

      let field_name = this.result_input.name;

      let block_dependencies = this.block_dependencies[field_name];

      if (block_dependencies) {
         this.handleBlockDependencies(block_dependencies)
      }

      if (this.radio_dependency) {
         this.handleRadioDependencies();
      }

      if (this.require_dependencies) {
         let require_dependencies = this.require_dependencies[field_name];
         if (require_dependencies) {
            this.handleRequireDependencies(require_dependencies);
         }
      }

      resizeCard(this.result_input);
   }

   static handleBlockDependencies (dependencies) {
      let field_name = this.result_input.name;
      let field_value = this.result_input.value;
      let dependent_values = new Map();

      if (this.block_dependencies[field_name].hasOwnProperty(field_value)) {
         dependent_values.set(field_value, this.block_dependencies[field_name][field_value]);
      } else {
         Object.keys(dependencies).forEach(key => {
            dependent_values.set(key, this.block_dependencies[field_name][key]);
         });
      }

      dependent_values.forEach((block_states, dependency_key) => {


         let setBlockState = this.getBlockStateSetter(dependency_key);

         Object.keys(block_states).forEach(name => {
            let is_active = setBlockState(block_states[name]);

            if (this.is_multiple_block) {

               let dependent_blocks = this.blocks_container.querySelectorAll(`[data-block][data-name="${name}"]`);
               if (dependent_blocks.length === 0 && is_active) {

                  let new_block = this.multiple_block.createBlock(this.blocks_container, name);
                  new_block.dataset.active = is_active;
                  resizeCard(new_block);

               } else {
                  dependent_blocks.forEach(block => {
                     block.dataset.active = is_active;
                     resizeCard(block);
                  });
               }

            } else {

               let dependent_blocks = document.querySelectorAll(`[data-block][data-name="${name}"]`);
               dependent_blocks.forEach(block => {

                  block.dataset.active = is_active;
                  resizeCard(block);
               });

            }

         });

      });
   }

   static getBlockStateSetter (dependency_key) {
      let setBlockState;

      if (!isNaN(parseInt(dependency_key)) || dependency_key === '') {

         setBlockState = function (block_state) {
            return block_state;
         };

      } else if (dependency_key.includes('JSON_TRUE_OR')) {

         let field_value = JSON.parse(this.result_input.value);
         let includes = dependency_key.replace('JSON_TRUE_OR:', '').split('#');

         setBlockState = function (block_state) {

            if (field_value.find(field_value => includes.includes(field_value))) {
               return block_state;
            } else {
               return false;
            }

         };

      } else if (dependency_key.includes('JSON_FALSE_AND')) {

         let field_value = JSON.parse(this.result_input.value);
         let excludes = dependency_key.replace('JSON_FALSE_AND:', '').split('#');

         setBlockState = function (block_state) {

            if (!field_value.find(field_value => excludes.includes(field_value))) {
               return block_state;
            }

         };
      }

      return setBlockState;
   }

   static handleRadioDependencies () {
      let dependency_inputs = this.radio_dependency.querySelectorAll(`input[data-when_change=${this.result_input.name}]`);

      dependency_inputs.forEach(input => {

         // Все возможные значения для блока с переключателями
         let values = JSON.parse(input.value);

         // Берем нужные значения, по значению родительского поля
         let radio_values = values[this.result_input.value][0];

         let dependent_row = this.blocks_container.querySelector(`.field[data-name=${input.dataset.target_change}]`);
         let dependent_radio = dependent_row.querySelector('.radio');

         if (dependent_radio) {
            let radio_body = dependent_radio.querySelector('.radio__body');
            let result_input = dependent_row.querySelector('[data-field_result]');

            result_input.value = '';
            radio_body.innerHTML = '';

            this.handleDependencies(result_input);

            // Для каждого значения создаем элемент переключателя
            radio_values.forEach(value => {
               let radio_item = createRadioItem(value);
               radio_body.appendChild(radio_item);
            });

            // Добавляем обработчики для переключателей
            initRadioItems(dependent_radio);
         }

      });
   }

   static handleRequireDependencies (dependencies) {
      let dependent_field_names = dependencies[this.result_input.value];

      if (dependent_field_names) {
         Object.keys(dependent_field_names).forEach(field_name => {
            let dependent_rows = this.blocks_container.querySelectorAll(`.field[data-name="${field_name}"]`);
            dependent_rows.forEach(row => row.dataset.required = dependent_field_names[field_name]);
         });
      }
   }

   // Предназначен для обработки кнопок удаления значений полей
   static handleClearFieldButtons () {
      let clear_buttons = document.querySelectorAll('.form-field__icon-clear');

      clear_buttons.forEach(button => {
         button.addEventListener('click', () => {

            let parent_field = button.closest('.field');

            let field_result = parent_field.querySelector('[data-field_result]');
            this.handleDependencies(field_result);

            if (field_result.value) {
               let parent_select = parent_field.querySelector('[data-modal_select]');

               this.removeRowValue(parent_field, parent_select);


               let related_modal = parent_field.querySelector('[data-misc_modal]');
               if (related_modal) {
                  let misc = Misc.getMiscBySelect(parent_select);
                  misc.clearRelatedMiscs();
                  validateMisc(misc);
               }


               validateCard(parent_field.closest('.card-form'));
            }
         });
      });
   }

   static removeRowValue (field, select) {
      // Удаляем записанное значение в зависимом поле
      field.querySelector('[data-field_result]').value = '';
      field.classList.remove('filled');

      // let select = field.querySelector('.field-select');
      if (select) {
         // select.classList.remove('filled');

         let value = field.querySelector('[data-field_value]');
         // Если зависимое поле - дата, удаляем отображаемую дату
         if (select.dataset.modal_select === 'calendar') {
            value.innerHTML = 'Выберите дату';
         } else if (value) {
            value.innerHTML = 'Выберите значение';
         }
      }
   }

}