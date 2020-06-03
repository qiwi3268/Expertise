<?php


//API result:
//	1 - Нет обязательных параметров POST запроса
//      {result}
//	2 - Не подходит логин/пароль
//      {result}
//  3 - Учетная запись забанена
//      {result}
//  4 - Пользователь не имеет роль в системе
//      {result}
//	5 - Авторизация и создание сессии прошло успешно
//      {result, ref : ссылка на домашнюю страницу}
//	6 - Ошибка при запросе в БД
//      {result, message : текст ошибки, code: код ошибки}
//	7 - Непредвиденная ошибка
//      {result, message : текст ошибки, code: код ошибки}
//

if(checkParamsPOST('login', 'password')){

    try{

        extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');

        $userAssoc = UsersTable::getAssocByLogin($P_login);

        // Пользователь сущесвует
        if(!is_null($userAssoc)){

            // Пароль введен верно
            if(password_verify($P_password, $userAssoc['password'])){

                // Учетная запись пользователя забанена
                if($userAssoc['is_banned']){

                    exit(json_encode(['result' => 3]));
                }else{

                    // Обнуляем счетчик неверно введенных паролей
                    UsersTable::zeroingIncorrectPasswordInputById($userAssoc['id']);
                }

                $userRole = UsersTable::getRolesById($userAssoc['id']);

                // Пользователь не имеет ролей
                if(is_null($userRole)){

                    exit(json_encode(['result' => 4]));
                }

                // Создание сессии пользователя
                Session::createUser($userAssoc, $userRole);

                if(Session::isApplicant()){

                    $applicationsIds = ApplicationsTable::getIdsWhereAuthorById(Session::getUserId());

                    if(!is_null($applicationsIds)){
                        Session::createAuthorRoleApplicationIds($applicationsIds);
                    }
                }


                // Определяем, на какую страницу перенаправлять пользователя
                $roles = Session::getUserRole();

                $ref = '';

                if(in_array(_ROLE['APP'], $roles, true))         $ref = '/home/applicant';
                elseif(in_array(_ROLE['EXP'], $roles, true))     $ref = '/home/experts';
                elseif(in_array(_ROLE['EMP_PTO'], $roles, true)) $ref = '/home/pto';
                elseif(in_array(_ROLE['EMP_BUH'], $roles, true)) $ref = '/home/buh';
                elseif(in_array(_ROLE['EMP_PKR'], $roles, true)) $ref = '/home/pkr';
                elseif(in_array(_ROLE['BOSS'], $roles, true))    $ref = '/home/boss';
                elseif(in_array(_ROLE['ADM'], $roles, true))     $ref = '/home/admin';

                $ref = '/home/create_application';

                // Авторизация прошла успешно
                exit(json_encode(['result' => 5,
                                  'ref'    => $ref
                                 ]));
            }

            // Инкрементируем счетчик неверно введенных паролей
            UsersTable::incrementIncorrectPasswordInputById($userAssoc['id']);

            // Максимально допустимое количество неверно введенных паролей
            $maxCountIncorrectPassword = 4;

            if($userAssoc['num_incorrect_password_input'] + 1 > $maxCountIncorrectPassword){

                // Баним пользователя
                UsersTable::banById($userAssoc['id']);
            }
        }

        exit(json_encode(['result' => 2]));

    }catch(DataBaseException $e){

        exit(json_encode(['result'  => 6,
                          'message' => $e->getMessage(),
                          'code'	=> $e->getCode()
                         ]));
    }catch(Exception $e){

        exit(json_encode(['result'  => 7,
                          'message' => $e->getMessage(),
                          'code'	=> $e->getCode()
                         ]));
    }
}
exit(json_encode(['result' => 1]));
