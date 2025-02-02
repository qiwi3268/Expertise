<?php


namespace Tables\Files\Interfaces;


/**
 * Общий интерфейс для работы с файловыми таблицами
 *
 */
interface FileTable
{

    /**
     * Предназначен для удаления записи из файловой таблицы
     *
     * @param int $id id записи
     */
    static public function deleteById(int $id): void;


    /**
     * Предназначен для проверки существования записи по ее hash'у
     *
     * @param string $hash
     * @return bool <b>true</b> запись существует<br>
     * <b>false</b> в противном случае
     */
    static public function checkExistByHash(string $hash): bool;


    /**
     * Предназначен для установки флага загрузки файла на сервер
     *
     * @param int $id id записи
     */
    static public function setUploadedById(int $id): void;


    /**
     * Предназначен для получения ассоциативного массива записи по id
     *
     * @param int $id id записи
     * @return array|null <b>array</b> ассоциативный массив, если запись существует<br>
     * <b>null</b> в противном случае
     */
    static public function getAssocById(int $id): ?array;


    /**
     * Предназначен для получения ассоциативных массивов нужных файлов по их id
     *
     * @param int[] $ids индексный массив с id файлов
     * @return array|null <b>array</b> индексный массив с ассоциативными массива внутри, если записи существуют<br>
     * <b>null</b> в противном случае
     */
    static public function getAllAssocWhereNeedsByIds(array $ids): ?array;


    /**
     * Предназначен для получения ассоциативного массива записи по её hash'у
     *
     * @param string $hash hash записи
     * @return array|null <b>array</b> ассоциативный массив, если запись существует<br>
     * <b>null</b> в противном случае
     */
    static public function getAssocByHash(string $hash): ?array;


    /**
     * Предназначен для получения ассоциативных массивов нужных файлов к главному документу по его id
     *
     * @param int $id_main_document id главного документа
     * @return array|null <b>array</b> индексный массив с ассоциативными массива внутри, если записи существуют<br>
     * <b>null</b> в противном случае
     */
    static public function getAllAssocWhereNeedsByIdMainDocument(int $id_main_document): ?array;


    /**
     * Предназначен для получения ассоциативных массивов всех ненужных файлов
     *
     * @return array|null <b>array</b> индексный массив с ассоциативными массива внутри, если записи существуют<br>
     * <b>null</b> в противном случае
     */
    static public function getAllAssocWhereNoNeeds(): ?array;


    /**
     * Предназначен для установки поля 'is_needs' в 1 по id записи
     *
     * @param int $id id созданной записи
     */
    static public function setNeedsToTrueById(int $id): void;


    /**
     * Предназначен для установки поля 'is_needs' в 0 по id записи
     *
     * @param int $id id созданной записи
     */
    static public function setNeedsToFalseById(int $id): void;


    /**
     * Предназначен для установки флага удаления крона по id
     *
     * @param int $id id созданной записи
     */
    static public function setCronDeletedFlagById(int $id): void;
}