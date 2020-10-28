<?php


namespace APIClasses\Application\Files;


use Lib\Exceptions\Transaction as TransactionEx;
use Lib\Exceptions\File as FileEx;
use ReflectionException;

use Lib\Files\Uploader;
use Lib\DataBase\Transaction;
use Lib\Files\Mappings\FilesTableMapping;


/**
 * Предназначен для загрузки файлов документации заявления
 *
 */
class DocumentationFileUploader extends Uploader
{

    private int $applicationId;
    private int $structureNodeId;


    /**
     * DocumentationFileUploader constructor.
     * @param int $applicationId
     * @param int $structureNodeId
     * @param int $mapping_level_1
     * @param int $mapping_level_2
     * @throws FileEx
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