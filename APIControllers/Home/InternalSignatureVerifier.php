<?php


namespace APIControllers\Home;

use core\Classes\Exceptions\Request as RequestEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Transaction as TransactionEx;
use Lib\Exceptions\CSPValidator as CSPValidatorEx;
use Lib\Exceptions\TableMappings as TableMappingsEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use functions\Exceptions\Functions as FunctionsEx;
use ReflectionException;
use Exception;

use core\Classes\Request\HttpRequest;
use Lib\DataBase\Transaction;
use core\Classes\ControllersInterface\APIController;
use Lib\CSP\MessageParser;
use Lib\CSP\InternalSignature;
use Lib\CSP\Validator;
use Lib\TableMappings\TableMappingsXMLHandler;
use Lib\Singles\Logger;
use Classes\Application\Helpers\Helper as ApplicationHelper;



/**
 * API предназначен для валидации встроенной подписи к файлу
 * В случае корректной подписи создаются соответствующие записи в таблице подписей
 *
 * <b>***</b> Предполагается, что перед использованием данного API был вызов FileChecker для проверяемого файла
 *
 * API result:
 * - ok     - ['validate_results']
 * - finisr - Проверяемый файл не является встроенной подписью
 * - fiesr  - Загружен файл открепленной подписи
 * - 1      - Ошибка при обработке XML схемы table_mappings
 * - 2      - Ошибка при валидации XML схемы table_mappings
 * - 3      - Ошибка при разборе 'fs_name_sign'
 * - 4      - Передан пустой файл
 * - 5      - Произошла внутренняя ошибка
 * - 6      - Произошла ошибка при добавлении записей в таблицу подписей
 */
class InternalSignatureVerifier extends APIController
{

    /**
     * Код выхода, соответствующий случаю, когда проверяемый файл не является встроенной подписью
     *
     */
    private const FILE_IS_NOT_INTERNAL_SIGN_RESULT = 'finisr';

    /**
     * Код выхода, соответствующий случаю, когда проверяемый файл является открепленной подписью
     *
     */
    private const FILE_IS_EXTERNAL_SIGN_RESULT = 'fiesr';


    /**
     * Реализация абстрактного метода
     *
     * @throws DataBaseEx
     * @throws ReflectionException
     * @throws RequestEx
     * @throws TransactionEx
     */
    public function doExecute(): void
    {

        list(
            'fs_name_sign'    => $fs_sign,
            'mapping_level_1' => $ml_1,
            'mapping_level_2' => $ml_2
            ) = $this->getCheckedRequiredParams(HttpRequest::POST, ['fs_name_sign', 'mapping_level_1', 'mapping_level_2']);

        try {

            $XMLHandler = new TableMappingsXMLHandler();
            $level_2 = $XMLHandler->getLevel2($ml_1, $ml_2);

            list(
                'file_table_class'   => $fileTable,
                'sign_table_class'   => $signTable
                ) = $XMLHandler->validateLevel2Structure($level_2)->getHandledLevel2Value($level_2);
        } catch (TableMappingsEx $e) {

            $this->logAndExceptionExit(1, $e, 'Ошибка при обработке XML схемы table_mappings');
        } catch (XMLValidatorEx $e) {

            $this->logAndExceptionExit(2, $e, 'Ошибка при валидации XML схемы table_mappings');
        }

        try {

            // Получение id файла
            list(
                'application_id' => $application_id,
                'file_name'      => $hash_sign
                ) = ApplicationHelper::parseApplicationFilePath($fs_sign);
        } catch (FunctionsEx $e) {

            $this->logAndExceptionExit(3, $e, "Ошибка при разборе 'fs_name_sign'");
        }

        $signFileAssoc = $fileTable::getAssocByIdMainDocumentAndHash($application_id, $hash_sign);

        $validator = new Validator(new MessageParser(true), new InternalSignature());

        try {

            $validateResults = $validator->validate($fs_sign);
        } catch (CSPValidatorEx $e) {

            if ($e->getCode() == 4) {

                if ($validator->isInvalidMessageType()) {
                    $this->errorExit(self::FILE_IS_NOT_INTERNAL_SIGN_RESULT, 'Проверяемый файл не является встроенной подписью');
                }
                if ($validator->isIncorrectParameter() && ($signFileAssoc['file_size'] / 1024 < 20)) {
                    $this->errorExit(self::FILE_IS_EXTERNAL_SIGN_RESULT, 'Загружен файл открепленной подписи');
                }
                if ($validator->isCSPNotReadyToReturnData() && ($signFileAssoc['file_size'] == 0)) {
                    $this->errorExit(4, 'Передан пустой файл');
                }
            }
            $this->logAndExceptionExit(5, $e, 'Произошла внутренняя ошибка');
        } catch (Exception $e) {
            $this->logAndExceptionExit(5, $e, 'Произошла внутренняя ошибка');
        }

        $transaction = new Transaction();

        // Заполняем транзакцию для создания записи в таблице подписей
        foreach ($validateResults as &$result) {

            $transaction->add($signTable, 'create', [
                $signFileAssoc['id'],
                0,
                null,
                $result['fio'],
                $result['certificate'],
                $result['signature_verify']['result'] ? 1 : 0,
                $result['signature_verify']['message'],
                $result['signature_verify']['user_message'],
                $result['certificate_verify']['result'] ? 1 : 0,
                $result['certificate_verify']['message'],
                $result['certificate_verify']['user_message']
            ]);

            // Удаляем результаты, которые не нужны на клиентской стороне
            unset($result['signature_verify']['message']);
            unset($result['certificate_verify']['message']);
        }
        unset($result);

        try {
            $transaction->start();
        } catch (DataBaseEx $e) {
            $this->logAndExceptionExit(6, $e, "Произошла ошибка при добавлении записей в таблицу подписей");
        }

        // Все прошло успешно
        $this->successExit(['validate_results' => $validateResults]);
    }


    /**
     * Реализация абстрактного метода
     *
     */
    protected function getErrorLogger(): Logger
    {
        return new Logger(LOGS_API_ERRORS, 'InternalSignatureVerifier.log');
    }
}