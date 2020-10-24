<?php


namespace test;

use Lib\Exceptions\Transaction as TransactionEx;
use ReflectionException;

use Lib\DataBase\Transaction;
use Lib\Files\Mappings\FilesTableMapping;


/**
 *
 *
 * Наследующий класс должен инициализировать свойства:
 * - inputName
 */
class DocumentationFileUploader extends FileUploader
{

    private int $applicationId;
    private int $structureNodeId;


    /**
     * DocumentationFileUploader constructor.
     * @param int $applicationId
     * @param int $structureNodeId
     * @param int $mapping_level_1
     * @param int $mapping_level_2
     * @throws \Exception //todo parent exception
     */
    public function __construct(
        int $applicationId,
        int $structureNodeId,
        int $mapping_level_1,
        int $mapping_level_2
    ) {
        $this->filesTableMapping = new FilesTableMapping($mapping_level_1, $mapping_level_2);

        $this->inputName = 'download_files';

        $this->directory = APPLICATIONS_FILES . "/{$applicationId}";
        $this->allowedFormats = ['.docx', '.doc', '.odt', '.pdf', '.xlsx', '.xls', '.ods', '.xml'];
        $this->forbiddenSymbols = [','];
        $this->maxFileSize = 80;

        $this->applicationId = $applicationId;
        $this->structureNodeId = $structureNodeId;

        parent::__construct();
    }


    /**
     * Реализация абстрактного метода
     *
     * @param array $filesName
     * @param array $filesSize
     * @param array $hashes
     * @return Transaction
     * @throws TransactionEx
     * @throws ReflectionException
     */
    protected function getCreateTransaction(array $filesName, array $filesSize, array $hashes): Transaction
    {
        $transaction = new Transaction();

        $class = $this->filesTableMapping->getClassName();

        for ($l = 0; $l < count($filesName); $l++) {

            $transaction->add(
                $class,
                'create',
                [
                    $this->applicationId,
                    $this->structureNodeId,
                    $filesName[$l],
                    $filesSize[$l],
                    $hashes[$l]
                ]
            );
        }

        return $transaction;
    }
}