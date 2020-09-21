<?php


namespace Tables\Actions\Interfaces;


/**
 * Интерфейс для работы с таблицами действий
 *
 */
interface ActionTable
{

    /**
     * Предназначен для получения ассоциативных массивов активных действий
     *
     * @return array индексный массив с ассоциативными массивами
     */
    static public function getAllAssocWhereActive(): array;


    /**
     * Предназначен для получения ассоциативного массива активного действия по имени страницы
     *
     * @param string $pageName
     * @return array|null <b>array</b> ассоциативный массив, если запись существует<br>
     * <b>null</b> в противном случае
     */
    static public function getAssocWhereActiveByPageName(string $pageName): ?array;


    /**
     * Предназначен для получения ассоциативного массива действия по имени страницы
     *
     * @param string $pageName имя страницы
     * @return array|null <b>array</b> ассоциативный массив, если запись существует<br>
     * <b>null</b> в противном случае
     */
    static public function getAssocByPageName(string $pageName): ?array;


    /**
     * Предназначен для получения ассоциативного массива данных бизнесс-процесса,
     * необходимых для работы callback-методов
     *
     * @param int $id_document
     * @return array ассоциативный массив
     */
    static public function getAssocBusinessProcessById(int $id_document): array;
}