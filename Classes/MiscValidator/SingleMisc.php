<?php


namespace MiscValidator;


// Предназначен для валидации одиночных справочников
class SingleMisc extends Validator{
    
    private const INTERFACE = 'Interface_singleMiscTableValidate';
    private const METHOD = 'checkExistById';
    
    
    // Принимает параметры-----------------------------------
    // form_value string : значение из формы
    // class      string : название класса справочника
    public function __construct(string $form_value, string $class){
        
        $this->form_value = $form_value;
        $this->class = $class;
    }
    
    
    // Предназначен для комплексной проверки справочника
    //
    public function validate(){
    
        if($this->form_value !== ''){
            
            $int = $this->int_value = $this->getValidatedInt($this->form_value);
    
            $this->checkClass($this->class, self::INTERFACE);
    
            $this->checkMiscExist($this->class, self::METHOD, [$int]);
    
            $this->isExist = true;
        }else{
    
            $this->isExist = false;
        }
    }

}
