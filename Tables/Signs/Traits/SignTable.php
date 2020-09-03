<?php


namespace Tables\Signs\Traits;

use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;


// Трейт, реализующий интерфейс Tables\Signs\Interfaces\SignTable
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство tableName с соответствующим именем таблицы
//
trait  SignTable
{

    // Предназначен для создания записи в таблице подписей
    // Принимает параметры-----------------------------------
    // id_sign                     int : id подписи (и встроенной и открепленной) из файловой таблицы
    // is_external                 int : Флаг открепленной подписи
    // id_file                    ?int : Если is_external=1, то это id файла из файловой таблицы, к которому принадлежит данная открепленная подпись
    // fio                      string : ФИО подписанта
    // certificate              string : Данные из сертификата подписанта
    // signature_result         string : Результат проверки подписи
    // signature_message        string : Сообщение из КриптоПро о результате проверки подписи
    // signature_user_message   string : Сообщение для пользователя о результате проверки подписи
    // certificate_result       string : Результат проверки сертификата
    // certificate_message      string : Сообщение из КриптоПро о результате проверки подписи (сертификата)
    // certificate_user_message string : Сообщение для пользователя о результате проверки сертификата
    // Возвращает параметры-----------------------------------
    // int : id созданной записи
    //
    static public function create(
        int $id_sign,
        int $is_external,
        ?int $id_file,
        string $fio,
        string $certificate,
        string $signature_result,
        string $signature_message,
        string $signature_user_message,
        string $certificate_result,
        string $certificate_message,
        string $certificate_user_message
    ): int {

        $table = self::$tableName;

        $bindParams = [
            $id_sign,
            $is_external,
            $id_file,
            $fio,
            $certificate,
            $signature_result,
            $signature_message,
            $signature_user_message,
            $certificate_result,
            $certificate_message,
            $certificate_user_message
        ];

        $values = TableHelper::getValuesWithoutNull($bindParams);

        $query = "INSERT INTO `{$table}`
                    (`id`,
                     `id_sign`,
                     `is_external`,
                     `id_file`,
                     `fio`,
                     `certificate`,
                     `signature_result`,
                     `signature_message`,
                     `signature_user_message`,
                     `certificate_result`,
                     `certificate_message`,
                     `certificate_user_message`)
                    VALUES (NULL, {$values})";
        return ParametrizedQuery::set($query, $bindParams);
    }


    // Предназначен для получения ассоциативных массивов всех подписей по id файлов,
    // Если открепленная подпись, то и id_sign и id_file должны присутствовать в выборке IN
    // Если встроенная подписи, то id_sign должен присутствовать в выборке IN
    // которые могут быть в id_sign или id_file
    // Принимает параметры-----------------------------------
    // ids array : индексный массив с id файлов, к которым будут искаться записи в таблице подписей
    // Возвращает параметры-----------------------------------
    // array : ассоциативные массивы, если подписи существуюты
    // null  : в противном случае
    //
    static public function getAllAssocByIds(array $ids): ?array
    {
        $table = self::$tableName;

        $in = '(' . implode(', ', $ids) . ')';

        $query = "SELECT *
                  FROM `{$table}`
                  WHERE (
                            (`is_external`=1 AND `id_file` IS NOT NULL AND (`id_sign` IN {$in} AND `id_file` IN {$in}))
                            OR
                            (`is_external`=0 AND `id_file` IS NULL AND (`id_sign` IN {$in}))
                        )";
        $result = SimpleQuery::getFetchAssoc($query);
        return $result ? $result : null;
    }
}