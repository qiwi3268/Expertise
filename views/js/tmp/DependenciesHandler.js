document.addEventListener('DOMContentLoaded', () => {
   DependenciesHandler.radio_dependency = document.querySelector('.radio__content-change-logic');
   DependenciesHandler.block_dependencies = JSON.parse(document.getElementById('block_dependencies').value);
   DependenciesHandler.require_dependencies = JSON.parse(document.getElementById('require_dependencies').value);

});


class DependenciesHandler {
   static radio_dependency;
   static block_dependencies;
   static require_dependencies;

   static is_multiple_block;
   static blocks_container;
   static multiple_block;

   static handleDependencies(result_field) {
      //TODO вынести в отдельный listener
      let parent_block = result_field.closest('.block');
      if (parent_block && parent_block.dataset.type === 'part') {
         this.blocks_container = parent_block;
         this.is_multiple_block = true;
         let multiple_block_name = parent_block.closest('.block[data-type="multiple"]').dataset.block_name;
         this.multiple_block = MultipleBlock.getBlockByName(multiple_block_name);
      } else {
         this.blocks_container = document;
         this.is_multiple_block = false;
      }

      let field_name = result_field.name;

      let block_dependencies = this.block_dependencies[field_name];
      if (block_dependencies) {
         this.handleBlockDependencies(block_dependencies, result_field)
      }

      this.handleRadioDependencies(result_field);

      let require_dependencies = this.require_dependencies[field_name];
      if (require_dependencies) {
         this.handleRequireDependencies(require_dependencies, result_field);
      }

      changeParentCardMaxHeight(result_field);
   }

   static handleBlockDependencies(dependencies, result_field) {

      let dependent_values = new Map();

      if (!isNaN(parseInt(result_field.value))) {
         dependent_values.set(result_field.value, block_dependencies[result_field.name][result_field.value]);
      } else {
         Object.keys(dependencies).forEach(key => {
            dependent_values.set(key, block_dependencies[result_field.name][key]);
         });
      }

      let setBlockState;

      dependent_values.forEach((block_states, dependency_key) => {

         if (result_field.value === undefined) {

            setBlockState = function () {
               return 'true';
            };

         } else if (!isNaN(parseInt(dependency_key))) {

            setBlockState = function(block_state) {
               return block_state;
            };

         } else if (dependency_key.includes('JSON_TRUE_OR')) {
            let field_value = JSON.parse(result_field.value);
            let includes = dependency_key.replace('JSON_TRUE_OR:', '').split('#');

            setBlockState = function(block_state) {

               if (field_value.find(field_value => includes.includes(field_value))) {
                  return !block_state;
               } else {
                  return true;
               }

            };

         } else if (dependency_key.includes('JSON_FALSE_AND')) {
            let field_value = JSON.parse(result_field.value);
            let excludes = dependency_key.replace('JSON_FALSE_AND:', '').split('#');

            setBlockState = function(block_state) {

               if (!field_value.find(field_value => excludes.includes(field_value))) {
                  return !block_state;
               }

            };
         }


         Object.keys(block_states).forEach(block_name => {

            let inactive = setBlockState(block_states[block_name]);

            if (this.is_multiple_block) {

               let dependent_blocks = this.blocks_container.querySelectorAll(`[data-block_name="${block_name}"]`);
               if (dependent_blocks.length === 0 && !inactive) {
                  let new_block = this.multiple_block.createBlock(this.blocks_container, block_name);
                  new_block.dataset.inactive = inactive;
               } else {
                  dependent_blocks.forEach(block => block.dataset.inactive = inactive);
               }

            } else {

               let dependent_blocks = document.querySelectorAll(`[data-block_name="${block_name}"]`);
               dependent_blocks.forEach(block => {

                  let inactive = setBlockState(block_states[block_name]);

                  block.dataset.inactive = inactive;

                  if (inactive) {
                     clearBlock(block);
                  }

               });
            }


         });


      });


   }



   static handleRadioDependencies(result_field) {
      let dependency_inputs = radio_dependency.querySelectorAll(`input[data-when_change=${result_field.name}]`);

      dependency_inputs.forEach(input => {
         // Все возможные значения для блока с переключателями
         let values = JSON.parse(input.value);

         // Берем нужные значения, по значению родительского поля
         let radio_values = values[result_field.value][0];

         let dependent_row = this.blocks_container.querySelector(`[data-row_name=${input.dataset.target_change}]`);
         let dependent_radio = dependent_row.querySelector('.radio');

         if (dependent_radio) {
            let radio_body = dependent_radio.querySelector('.radio__body');
            let result_input = dependent_row.querySelector('.field-result');

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

   static handleRequireDependencies(dependencies, result_field) {
      let dependent_row_names = dependencies[result_field.value];

      if (dependent_row_names) {
         Object.keys(dependent_row_names).forEach(row_name => {
            let dependent_rows = this.blocks_container.querySelectorAll(`[data-row-name="${row_name}"]`);

            dependent_rows.forEach(row => {

               row.dataset.required = dependent_row_names[row_name];

            });
         });
      }
   }

}