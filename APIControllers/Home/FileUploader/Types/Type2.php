<?php


namespace APIControllers\Home\FileUploader\Types;

use Lib\Exceptions\Transaction as TransactionEx;
use ReflectionException;

use APIControllers\Home\FileUploader\Uploader;
use Lib\DataBase\Transaction;


class Type2 extends Uploader
{

    /**
     * Реализация абстрактного метода
     *
     */
    function getRequiredParams(): array
    {
        return ['id_structure_node'];
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
     * @throws \Lib\Exceptions\Transaction
     * @throws \ReflectionException
     */
    protected function getCreateTransaction(array $filesName, array $filesSize, array $hashes): Transaction
    {
        $transaction = new Transaction();

        $class = $this->fileTableClass;
        $mainDocumentId = $this->mainDocumentId;
        $structureNodeId = $this->request->id_structure_node;

        for ($l = 0; $l < count($filesName); $l++) {

            $transaction->add(
                $class,
                'create',
                [
                    $mainDocumentId,
                    $structureNodeId,
                    $filesName[$l],
                    $filesSize[$l],
                    $hashes[$l]
                ]
            );
        }
        return $transaction;
    }
}