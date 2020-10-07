<?php


namespace Tables\LoggingActions\Interfaces;


/**
 * Общий интерфейс для работы с таблицами логирования действий к документу
 *
 */
interface LogActionTable
{

    /**
     * Предназначен для создания записи в таблице логирования действий к документу
     *
     * @param int $id_main_document id главного документа
     * @param int $id_action id действия
     * @param int $id_author id автора
     * @return int
     */
    static public function create(
        int $id_main_document,
        int $id_action,
        int $id_author
    ): int;


    /**
     * Предназначен для проверки существования записи по id главного документа и id действия
     *
     * @param int $id_main_document id главного документа
     * @param int $id_action id действия
     * @return bool <b>true</b> запись существует<br>
     * <b>false</b> в противном случае
     */
    static public function checkExistByIdMainDocumentAndIdAction(int $id_main_document, int $id_action): bool;


    /**
     * Предназначен для проверки существования записи по id главного документа и
     * названии callback'a из таблицы действий
     *
     * @param int $id_main_document id главного документа
     * @param string $callback_name название callback'а из таблицы действий
     * @return bool <b>true</b> запись существует<br>
     * <b>false</b> в противном случае
     */
    static public function checkExistByIdMainDocumentAndActionCallbackName(int $id_main_document, string $callback_name): bool;
}