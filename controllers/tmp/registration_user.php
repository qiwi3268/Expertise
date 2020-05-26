<?php


// Контроллер регистрации пользователя
// Будет находится в админ-панели

// Форма регистрации будет представлять из себя:
// 1 - Фамилия
// 2 - Имя
// 3 - Отчетство
// 4 - Код отдела - ! у заявителя не будет отдела
// 5 - Код должности - ! у заявителя не будет должности
// 6 - Почта
// 7 - Логин
// 8 - Пароль
// 9 - Роли пользователя (всегда массив)

$P_last_name = 'Макаров';
$P_first_name = 'Владимир';
$P_middle_name = 'Алексеевич';
$P_department = '6';
$P_position = '1';
$P_email = 'vam@ge74.ru';
$P_login = 'vam';
$P_password = '123';
$P_users_role = [2, 9, 12];

// Сделать проверку, что если нет отдела,
// то не должно быть и должности,
// то должна быть выбрана только роль заявителя

// !!! Посмотреть что приходит с формы, если ничего не выбрано (подходит ли empty)?
$P_department = empty($P_department) ? null : (int)$P_department;
$P_position = empty($P_position) ? null : (int)$P_position;

$cryptPassword = password_hash($P_password, PASSWORD_DEFAULT);
$hash = bin2hex(random_bytes(25)); // Длина 50 символов

// Создание пользователя
$userId = UsersTable::create($P_last_name, $P_first_name, $P_middle_name, $P_department, $P_position, $P_email, $P_login, $cryptPassword, $hash);

// Создание ролей пользователя
foreach($P_users_role as $role){
    UsersRoleTable::create($userId, $role);
}





