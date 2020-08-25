<?php


// Справочник "Уровень бюджета"
//
final class misc_budgetLevelTable implements Interface_singleMiscTableValidate{
    
    static private string $tableName = 'misc_budget_level';
    
    use Trait_singleMiscTableValidate;
    // checkExistById(int $id):bool
    
    use Trait_singleMiscTable;
    // getAllActive():array
}
