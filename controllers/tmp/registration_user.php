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

$P_last_name = '3Заявитель';
$P_first_name = '3Заявительб';
$P_middle_name = '3Заявитель';
$P_department = 1;
$P_position = 1;
$P_email = '3vam1@ge74.ru123';
$P_login = '3te1st';
$P_password = '123';
$P_users_role = [1];

// Сделать проверку, что если нет отдела,
// то не должно быть и должности,
// то должна быть выбрана только роль заявителя

// !!! Посмотреть что приходит с формы, если ничего не выбрано (подходит ли empty)?
$P_department = empty($P_department) ? 0 : (int)$P_department;
$P_position = empty($P_position) ? 0 : (int)$P_position;

$cryptPassword = password_hash($P_password, PASSWORD_DEFAULT);
$hash = bin2hex(random_bytes(25)); // Длина 50 символов

// Создание пользователя
$userId = \Tables\user::create($P_last_name, $P_first_name, $P_middle_name, $P_department, $P_position, $P_email, $P_login, $cryptPassword, $hash);

// Создание ролей пользователя
foreach ($P_users_role as $role) {

    \Tables\user_role::create($userId, $role);
}





