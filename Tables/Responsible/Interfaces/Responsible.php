<?php


namespace Tables\Responsible\Interfaces;


/**
 * Общий интерфейс для работы с таблицами ответственных
 *
 */
interface Responsible
{
    //todo важное удалить одного ответственного

    /**
     * Предназначен для удаления всех ответственных
     *
     * @param int $id_main_document
     */
    static public function deleteResponsibleByIdMainDocument(int $id_main_document): void;


    /**
     * Предназначен для получения ассоциативных массивов с ответственными пользователями
     *
     * Ассоциативный массив пользователя должен включать в себя поля:<br>
     * id, last_name, first_name, middle_name
     *
     * @param int $id_main_document
     * @return array|null
     */
    static public function getResponsibleByIdMainDocument(int $id_main_document): ?array;
}