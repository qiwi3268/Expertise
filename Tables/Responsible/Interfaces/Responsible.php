<?php


namespace Tables\Responsible\Interfaces;


/**
 * Общий интерфейс для работы с таблицами ответственных
 *
 */
interface Responsible
{
    //todo ??? удалить одного ответственного

    /**
     * Предназначен для удаления всех ответственных с документа
     *
     * @param int $id_main_document id главного документа
     */
    static public function deleteResponsibleByIdMainDocument(int $id_main_document): void;


    /**
     * Предназначен для получения ассоциативных массивов с ответственными пользователями
     *
     * Ассоциативный массив пользователя должен включать в себя поля:<br>
     * user_id,<br>
     * last_name,<br>
     * first_name,<br>
     * middle_name
     *
     * @param int $id_main_document id главного документа
     * @return array|null <b>array</b> ассоциатиные массивы ответственных, если записи существуют<br>
     * <b>null</b> в противном случае
     */
    static public function getResponsibleByIdMainDocument(int $id_main_document): ?array;
}