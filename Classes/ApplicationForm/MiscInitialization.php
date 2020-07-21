<?php


// Предназначен для инициализации справочников в форме создания и редактирования анкеты
//
class MiscInitialization{
    
    // Названия имеющихся справочников
    public const MISC_NAMES = ['expertisePuproses'                => 'expertisePuproses',
                               'CORR_expertiseSubjects'           => 'CORR_expertiseSubjects',
                               'typeOfObjects'                    => 'typeOfObjects',
                               'functionalPurposes'               => 'functionalPurposes',
                               'CORR_functionalPurposeSubsectors' => 'CORR_functionalPurposeSubsectors',
                               'CORR_functionalPurposeGroups'     => 'CORR_functionalPurposeGroups',
                               'CORR_typeOfWorks'                 => 'CORR_typeOfWorks',
                               'culturalObjectTypes'              => 'culturalObjectTypes',
                               'nationalProjects'                 => 'nationalProjects',
                               'CORR_federalProjects'             => 'CORR_federalProjects',
                               'curators'                         => 'curators'
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
        $tmp[self::MISC_NAMES['expertisePuproses']] = $expertisePurposes;
    
        // Справочник "Предмет экспертизы" -> корреляция с "Цель обращения"
        $tmp[self::MISC_NAMES['CORR_expertiseSubjects']] = misc_expertiseSubjectTable::getActive_CORR_ExpertisePurpose($expertisePurposes);
    
        // Справочник "Вид объекта"
        $tmp[self::MISC_NAMES['typeOfObjects']] = misc_typeOfObjectTable::getAllActive();
    
        // Справочник "Функциональное назначение"
        $functionalPurposes = misc_functionalPurposeTable::getAllActive();
        $tmp[self::MISC_NAMES['functionalPurposes']] = $functionalPurposes;
    
        // Справочник "Функциональное назначение. Подотрасль" -> корреляция с "Функциональное назначение"
        $tmp[self::MISC_NAMES['CORR_functionalPurposeSubsectors']] = misc_functionalPurposeSubsectorTable::getActive_CORR_FunctionalPurpose($functionalPurposes);
    
        // Справочник "Функциональное назначение. Группа" -> корреляция с "Функциональное назначение. Подотрасль"
        $tmp[self::MISC_NAMES['CORR_functionalPurposeGroups']] = misc_functionalPurposeGroupTable::getActive_CORR_FunctionalPurposeSubsector(misc_functionalPurposeSubsectorTable::getAllActive());
    
        // Справочник "Вид работ" -> корреляция с "Цель обращения"
        $tmp[self::MISC_NAMES['CORR_typeOfWorks']] = misc_typeOfWorkTable::getActive_CORR_ExpertisePurpose($expertisePurposes);
    
        // Справочник "Тип объекта культурного наследия"
        $tmp[self::MISC_NAMES['culturalObjectTypes']] = misc_culturalObjectTypeTable::getAllActive();
    
        // Справочник "Национальный проект"
        $nationalProjects = misc_nationalProjectTable::getAllActive();
        $tmp[self::MISC_NAMES['nationalProjects']] = $nationalProjects;
    
        // Справочник "Федеральный проект" -> корреляция с "Национальный проект"
        $tmp[self::MISC_NAMES['CORR_federalProjects']] = misc_federalProjectTable::getActive_CORR_NationalProject($nationalProjects);
    
        // Справочник "Куратор"
        $tmp[self::MISC_NAMES['curators']] = misc_curatorTable::getAllActive();
        
        // Проверка на обработку всех объявленных справочников
        if(count(self::MISC_NAMES) != count($tmp)){
            
            //todo
            var_dump('Ошибка');
        }
        
        // Записываем одиночные и зависимые справочники в разные места
        foreach($tmp as $miscName => $value){
            
            if(mb_strpos($miscName, self::CORR_MISC_PREFIX) !== false){
                $this->dependentMiscs[$miscName] = $value;
            }else{
                $this->singleMiscs[$miscName] = $value;
            }
        }
    }
    
    public function getPaginationSingleMisc():array {
        
        $result = [];
        
        // Цикл по имеющимся справочникам
        foreach($this->singleMiscs as $miscName => $value){
            
            $result[$miscName] = array_chunk($value, self::PAGINATION_SIZE);
        }
        return $result;
    }
    
    public function getPaginationDependentMisc():array {
    
        $result = [];
        
        // Цикл по имеющимся справочникам
        foreach($this->dependentMiscs as $miscName => $value){
            
            // Цикл по справочнику в зависимоти от id-главного справочника
            foreach($value as &$misc){
                
                $misc = array_chunk($misc, self::PAGINATION_SIZE);
            }
            
            unset($misc);
            $result[$miscName] = $value;
        }
        return $result;
    }
}


class Edit extends MiscInitialization{
    
    // Ассоциативный массив заявления (с сохраненными данными)
    private array $applicationAssoc;
    
    function __construct(array $applicationAssoc){
        
        // Вызов родительского конструктора для инициализации справочников
        parent::__construct();
        
        
        
    }
}