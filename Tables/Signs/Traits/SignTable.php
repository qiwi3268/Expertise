<?php


namespace Tables\Signs\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;


/**
 * Реализует интерфейс {@see \Tables\Signs\Interfaces\SignTable}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait  SignTable
{

    /**
     * Реализация метода интерфейса
     * {@see \Tables\Signs\Interfaces\SignTable::create()}
     *
     * @param int $id_sign
     * @param int $is_external
     * @param int|null $id_file
     * @param string $fio
     * @param string $certificate
     * @param string $signature_result
     * @param string $signature_message
     * @param string $signature_user_message
     * @param string $certificate_result
     * @param string $certificate_message
     * @param string $certificate_user_message
     * @return int
     * @throws DataBaseEx
     */
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

        $values = TableHelper::getValuesWithoutNullForInsert($bindParams);

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


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Signs\Interfaces\SignTable::getAllAssocByIds()}
     *
     * @param array $ids
     * @return array|null
     * @throws DataBaseEx
     */
    static public function getAllAssocByIds(array $ids): ?array
    {
        $table = self::$tableName;

        $in = '(' . implode(', ', $ids) . ')';

        $query = "SELECT *
                  FROM `{$table}`
                  WHERE (
                            (`is_external`=1 AND `id_file` IS NOT NULL AND (`id_file` IN {$in}))
                            OR
                            (`is_external`=0 AND `id_file` IS NULL AND (`id_sign` IN {$in}))
                        )";
        $result = SimpleQuery::getFetchAssoc($query);
        return $result ? $result : null;
    }
}