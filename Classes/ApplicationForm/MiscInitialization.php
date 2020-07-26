<?php


// Предназначен для инициализации справочников в форме создания и редактирования анкеты
//
class MiscInitialization{
    
    // Названия имеющихся справочников
    private const MISC_NAMES = ['expertise_purpose'                 => 'expertise_purpose',
                               'CORR_expertise_subjects'           => 'CORR_expertise_subjects',
                               'type_of_object'                    => 'type_of_object',
                               'functional_purpose'                => 'functional_purpose',
                               'CORR_functional_purpose_subsector' => 'CORR_functional_purpose_subsector',
                               'CORR_functional_purpose_group'     => 'CORR_functional_purpose_group',
                               'CORR_type_of_work'                 => 'CORR_type_of_work',
                               'cultural_object_type'              => 'cultural_object_type',
                               'national_project'                  => 'national_project',
                               'CORR_federal_project'              => 'CORR_federal_project',
                               'curator'                           => 'curator'
    ];
    
    // Названия главных справочников для зависимых:
    // Ключ - зависимый справочник, значение - главный справочник
    protected const MISC_MAIN_NAMES = ['expertise_subjects'           => 'expertise_purpose',
                                       'functional_purpose_subsector' => 'functional_purpose',
                                       'functional_purpose_group'     => 'functional_purpose_subsector',
                                       'type_of_work'                 => 'expertise_purpose',
                                       'federal_project'              => 'national_project'
    ];
    
 
    // Префикс в именах зависимых справочников
    private const CORR_MISC_PREFIX = 'CORR_';
    // Количество справочных элементов на странице при пагинации
    private const PAGINATION_SIZE = 8;
    
    // Массив одиночных справочников
    protected array $singleMiscs = [];
    // Массив зависимых справочников
    protected array $dependentMiscs = [];
    
    
    // Инициализация имеющихся справочников
    function __construct(){
     
        $tmp = [];
        
        // Справочник "Цель обращения"
        $expertisePurposes = misc_expertisePurposeTable::getAllActive();
        $tmp[self::MISC_NAMES['expertise_purpose']] = $expertisePurposes;
    
        // Справочник "Предмет экспертизы" -> корреляция с "Цель обращения"
        $tmp[self::MISC_NAMES['CORR_expertise_subjects']] = misc_expertiseSubjectTable::getActive_CORR_ExpertisePurpose($expertisePurposes);
    
        // Справочник "Вид объекта"
        $tmp[self::MISC_NAMES['type_of_object']] = misc_typeOfObjectTable::getAllActive();
    
        // Справочник "Функциональное назначение"
        $functionalPurposes = misc_functionalPurposeTable::getAllActive();
        $tmp[self::MISC_NAMES['functional_purpose']] = $functionalPurposes;
    
        // Справочник "Функциональное назначение. Подотрасль" -> корреляция с "Функциональное назначение"
        $tmp[self::MISC_NAMES['CORR_functional_purpose_subsector']] = misc_functionalPurposeSubsectorTable::getActive_CORR_FunctionalPurpose($functionalPurposes);
    
        // Справочник "Функциональное назначение. Группа" -> корреляция с "Функциональное назначение. Подотрасль"
        $tmp[self::MISC_NAMES['CORR_functional_purpose_group']] = misc_functionalPurposeGroupTable::getActive_CORR_FunctionalPurposeSubsector(misc_functionalPurposeSubsectorTable::getAllActive());
    
        // Справочник "Вид работ" -> корреляция с "Цель обращения"
        $tmp[self::MISC_NAMES['CORR_type_of_work']] = misc_typeOfWorkTable::getActive_CORR_ExpertisePurpose($expertisePurposes);
    
        // Справочник "Тип объекта культурного наследия"
        $tmp[self::MISC_NAMES['cultural_object_type']] = misc_culturalObjectTypeTable::getAllActive();
    
        // Справочник "Национальный проект"
        $nationalProjects = misc_nationalProjectTable::getAllActive();
        $tmp[self::MISC_NAMES['national_project']] = $nationalProjects;
    
        // Справочник "Федеральный проект" -> корреляция с "Национальный проект"
        $tmp[self::MISC_NAMES['CORR_federal_project']] = misc_federalProjectTable::getActive_CORR_NationalProject($nationalProjects);
    
        // Справочник "Куратор"
        $tmp[self::MISC_NAMES['curator']] = misc_curatorTable::getAllActive();
        
        
        // Проверка на обработку всех объявленных справочников
        if(count(self::MISC_NAMES) != count($tmp)){
            
            throw new Exception('Количество определенных справочников в MISC_NAMES не соответствует количеству инициализированных');
        }
        
        // Записываем одиночные и зависимые справочники в разные места
        // value: массивы одиночных справочников, либо
        //        массивы, где ключ = id главного справочника, а значение - массивы зависимых справочников
        foreach($tmp as $miscName => $value){
    
            // Удаляем префикс у зависимых справочников
            if(mb_strpos($miscName, self::CORR_MISC_PREFIX) !== false){
                $this->dependentMiscs[str_replace(self::CORR_MISC_PREFIX, '', $miscName)] = $value;
            }else{
                $this->singleMiscs[$miscName] = $value;
            }
        }
    }
    
    
    // Предназначен для разбивки одиночных справочников на страницы
    // Принимает параметры-----------------------------------
    // singleMiscs ?array : одиночные справочники, которые необходимо разбить на страницу
    // Возвращает параметры----------------------------------
    // array : разбитые постранично справочники
    //
    public function getPaginationSingleMiscs(?array $singleMiscs = null):array {
        
        if(is_null($singleMiscs)){
            $singleMiscs = $this->singleMiscs;
        }
        
        $result = [];
        
        foreach($singleMiscs as $miscName => $misc){
            $result[$miscName] = array_chunk($misc, self::PAGINATION_SIZE, false);
        }
        return $result;
    }
    
    public function getPaginationDependentMiscs(?array $dependentMiscs = null):array {
        
        if(is_null($dependentMiscs)){
            $dependentMiscs = $this->dependentMiscs;
        }
    
        $result = [];
        
        foreach($dependentMiscs as $miscName => $mainMiscIds){
            
            // Цикл по справочнику в зависимоти от id-главного справочника
            foreach($mainMiscIds as $mainMiscId => $misc){
                $result[$miscName][$mainMiscId] = array_chunk($misc, self::PAGINATION_SIZE, false);
            }
        }
        return $result;
    }
    
    
    public static function getMiscNames():array{
        
        $names = array_keys(self::MISC_NAMES);
        
        foreach($names as &$name){
            if(mb_strpos($name, self::CORR_MISC_PREFIX) !== false){
                $name = str_replace(self::CORR_MISC_PREFIX, '', $name);
            }
        }
        unset($name);
        
        return $names;
    }
}