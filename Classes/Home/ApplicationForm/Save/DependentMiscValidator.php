<?php


<<<<<<< HEAD
class DependentMiscValidator extends \Classes\Miscs\Validator\DependentMisc{
=======
class DependentMiscValidator extends \Classes\Miscs\Validation\DependentMisc{
>>>>>>> 346c3228d8d85e51138fbddaff8753f22b7e3ce0
    
    private ?string $columnName; // Имя столбца справочника в БД
    
    
<<<<<<< HEAD
   public function __construct(\Classes\Miscs\Validator\Validator $MainValidator, string $form_value, string $class, ?string $columnName = null){
=======
   public function __construct(\Classes\Miscs\Validation\Validator $MainValidator, string $form_value, string $class, ?string $columnName = null){
>>>>>>> 346c3228d8d85e51138fbddaff8753f22b7e3ce0
       
       parent::__construct($MainValidator, $form_value, $class);
       
       $this->columnName = $columnName;
   }
   
    
    public function validate():self {
        
        try{
            
            parent::validate();
        }catch(MiscValidatorException $e){
            
            $msg = $e->getMessage();
            $code = $e->getCode();
            // Конвертируем значения MiscValidatorException к значениям API_save_form result
            switch($code){
                case 1: throw new ApplicationFormMiscValidatorException($msg, 4);
                case 4: throw new ApplicationFormMiscValidatorException($msg, 5);
                case 5: throw new ApplicationFormMiscValidatorException($msg, 7);
                case 2:
                case 3: throw new MiscValidatorException($msg, $code);
            }
        }
        return $this;
    }
    
    
    // Предназначен для добавления значения справочника к массиву обновлений
    //
    public function addToUpdate():void {
    
        if(is_null($this->columnName)){
            throw new LogicException("Попытка вызвать метод SingleMiscValidator::addToUpdate при неуказанном в конструкторе columnName. Название класса справочника: '{$this->class}'");
        }
        
        DataToUpdate::addInt($this->form_value, $this->columnName);
    }
}