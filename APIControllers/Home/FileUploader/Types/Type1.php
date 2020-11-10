<?php


namespace APIControllers\Home\FileUploader\Types;

use Lib\Exceptions\Transaction as TransactionEx;
use ReflectionException;

use APIControllers\Home\FileUploader\Uploader;
use Lib\DataBase\Transaction;


/**
 * Файловый загрузчик для таблиц типа
 * {@see \Tables\Files\Interfaces\FileTableType1}
 *
 */
class Type1 extends Uploader
{

    /**
     * Реализация абстрактного метода
     *
     */
    function getRequiredParams(): array
    {
        return [];
    }


    /**
     * Реализация абстрактного метода
     *
     */
    function initializeProperties(): void
    {
        return;
    }


    /**
     * Реализация абстрактного метода
     *
     * @param array $filesName
     * @param array $filesSize
     * @param array $hashes
     * @return Transaction
     * @throws ReflectionException
     * @throws TransactionEx
     */
    protected function getCreateTransaction(array $filesName, array $filesSize, array $hashes): Transaction
    {
        $transaction = new Transaction();

        $class = $this->fileTableClass;
        $mainDocumentId = $this->mainDocumentId;

        for ($l = 0; $l < count($filesName); $l++) {

            $transaction->add(
                $class,
                'create',
                [
                    $mainDocumentId,
                    $filesName[$l],
                    $filesSize[$l],
                    $hashes[$l]
                ]
            );
        }
        return $transaction;
    }
}