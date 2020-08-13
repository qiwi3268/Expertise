<?php

// API предназначен
//
// API result:
//	1  - Нет обязательных параметров POST запроса
//       {result, error_message : текст ошибки}




// Проверка наличия обязательных параметров
if(!checkParamsPOST('id_application', 'id_file', 'mapping_level_1', 'mapping_level_2')){
    exit(json_encode(['result'        => 1,
                      'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}

try{
    
    /** @var string $P_id_application  */
    /** @var string $P_id_file         */
    /** @var string $P_mapping_level_1 */
    /** @var string $P_mapping_level_2 */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');
    
    
    // Проверка заявителя на доступ к заявлению - НЕ НУЖНА Т,К, В ЧЕКЕРЕ
    // Блок проверки маппинга - НЕ НУЖНЫ Т,К, В ЧЕКЕРЕ
    
    // проверка маппинга на интерефейс подписания
    
    
    
    
        //todo добавить интерфейсы для работы с таблицей подписей
    //
    
    
    
    
    
}catch(Exception $e){

}