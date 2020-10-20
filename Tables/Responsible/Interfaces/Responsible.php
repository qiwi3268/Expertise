<?php


namespace Tables\Responsible\Interfaces;


/**
 * Общий интерфейс для работы с таблицами ответственных
 *
 */
interface Responsible
{

    /**
     * Предназначен для получения ассоциативных массивов с ответственными пользователями
     *
     * Ассоциативный массив пользователя должен включать в себя поля:
     * - user_id
     * - last_name
     * - first_name
     * - middle_name
     *
     * @param int $id_main_document id главного документа
     * @return array|null <b>array</b> индексный массив с ассоциативными массива внутри, если записи существуют<br>
     * <b>null</b> в противном случае
     */
    static public function getResponsibleByIdMainDocument(int $id_main_document): ?array;


    /**
     * Предназначен для удаления всех ответственных с документа
     *
     * @param int $id_main_document id главного документа
     */
    static public function deleteResponsibleByIdMainDocument(int $id_main_document): void;
}