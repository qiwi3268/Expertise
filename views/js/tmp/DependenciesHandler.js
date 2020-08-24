document.addEventListener('DOMContentLoaded', () => {
   DependenciesHandler.radio_dependency = document.querySelector('.radio__content-change-logic');
   DependenciesHandler.block_dependencies = JSON.parse(document.getElementById('block_dependencies').value);
   DependenciesHandler.require_dependencies = JSON.parse(document.getElementById('require_dependencies').value);

});
class DependenciesHandler {
   static radio_dependency;
   static block_dependencies;
   static require_dependencies;

   static handleDependencies(result_field) {
      let field_name = result_field.name;


      let block_dependencies = this.block_dependencies[field_name];
      if (block_dependencies) {
         this.handleBlockDependencies(block_dependencies, result_field)
      }


      // let radio_dependencies = this.radio_dependency[field_name];
      // if (radio_dependencies) {
         handleDependentRadios(result_field);
      // }


      let require_dependencies = this.require_dependencies[field_name];

   }

   static handleBlockDependencies(dependencies, result_field) {

      let dependent_values = new Map();

      Object.keys(dependencies).forEach(key => {
         dependent_values.set(key, block_dependencies[result_field.name][key]);
      });

      let setBlockState;

      dependent_values.forEach((block_states, dependency_key) => {



         if (!isNaN(parseInt(dependency_key))) {

            setBlockState = function(block_state) {
               return !block_state;
            }

         } else if (dependency_key.includes('JSON_includes')) {

            setBlockState = function() {
               let field_value = JSON.parse(result_field.value);
               let includes = dependency_key.replace('JSON_includes:', '').split('#');
               return !field_value.find(field_value => includes.includes(field_value));
            };

         } else if (dependency_key.includes('JSON_excludes')) {

            setBlockState = function() {
               let field_value = JSON.parse(result_field.value);
               let excludes = dependency_key.replace('JSON_excludes:', '').split('#');
               return field_value.find(field_value => excludes.includes(field_value));
            };

         } else {
            console.error('Ошибка в DependenciesHandler');
         }


         Object.keys(block_states).forEach(block_name => {


            let dependent_blocks = document.querySelectorAll(`[data-block_name="${block_name}"]`);
            dependent_blocks.forEach(block => {

               block.dataset.inactive = setBlockState(block_states[block_name]);
               clearBlock(block);

            });

         });


      });


   }




   static numberCallback(block_state) {
      return !block_state;
   }

   static includesCallback(block_state) {



   }

   static handleRadioDependencies(dependencies) {

   }

   static handleRequireDependencies(dependencies) {

   }

}