<?php


namespace Tables\Signs\Interfaces;


/**
 * Интерфейс для работы с таблицами подписей
 *
 */
interface SignTable
{

    /**
     * Предназначен для создания записи в таблице подписей
     *
     * @param int $id_sign id подписи (и встроенной и открепленной) из файловой таблицы
     * @param int $is_external флаг открепленной подписи
     * @param int|null $id_file если is_external=1, то это id файла из файловой таблицы,
     * к которому принадлежит данная открепленная подпись
     * @param string $fio ФИО подписанта
     * @param string $certificate данные из сертификата подписанта
     * @param string $signature_result результат проверки подписи
     * @param string $signature_message сообщение из КриптоПро о результате проверки подписи
     * @param string $signature_user_message сообщение для пользователя о результате проверки подписи
     * @param string $certificate_result результат проверки сертификата
     * @param string $certificate_message сообщение из КриптоПро о результате проверки подписи (сертификата)
     * @param string $certificate_user_message сообщение для пользователя о результате проверки сертификата
     * @return int id созданной записи
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
    ): int;


    /**
     * Предназначен для получения ассоциативного массива всех подписей по id файлов
     *
     * Если открепленная подпись, то и id_sign и id_file должны присутствовать в выборке IN<br>
     * Если встроенная подпись, то id_sign должен присутствовать в выборке IN<br>
     * которые могут быть в <i>id_sign</i> или <i>id_file</i>
     *
     * @param array $ids индексный массив с id файлов, к которым будут искаться записи в таблице подписей
     * @return array|null <b>array</b> индексный массив с ассоциативными массива внутри, если записи существуют<br>
     * <b>null</b> в противном случае
     */
    static public function getAllAssocByIds(array $ids): ?array;
}