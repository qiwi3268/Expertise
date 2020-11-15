<?php


namespace APIControllers\Home;

use core\Classes\Exceptions\Request as RequestEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Transaction as TransactionEx;
use Lib\Exceptions\CSPValidator as CSPValidatorEx;
use Lib\Exceptions\TableMappings as TableMappingsEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use functions\Exceptions\Functions as FunctionsEx;
use Lib\Exceptions\Logger as LoggerEx;
use ReflectionException;
use Exception;

use core\Classes\Request\HttpRequest;
use Lib\DataBase\Transaction;
use core\Classes\ControllersInterface\APIController;
use Lib\CSP\MessageParser;
use Lib\CSP\ExternalSignature;
use Lib\CSP\Validator;
use Lib\TableMappings\TableMappingsXMLHandler;
use Lib\Singles\Logger;
use Classes\Application\Helpers\Helper as ApplicationHelper;


/**
 * API предназначен для валидации открепленной подписи к файлу
 * В случае корректной подписи создается соответствующая запись в таблице подписей
 *
 * <b>***</b> Предполагается, что перед использованием данного API был вызов FileChecker
 * для открепленной подписи и для исходного файла
 *
 * API result:
 * - ok     - ['validate_results']
 * - finesr - Проверяемый файл не является открепленной подписью
 * - 1      - Ошибка при обработке XML схемы table_mappings
 * - 2      - Ошибка при валидации XML схемы table_mappings
 * - 3      - Ошибка при разборе 'fs_name_data' / 'fs_name_sign'
 * - 4      - id заявления исходного файла не равен id заявления файла подписи
 * - 5      - Передан пустой файл
 * - 6      - Произошла внутренняя ошибка
 * - 7      - Загруженный файл не является открепленной подписью
 * - 8      - Произошла ошибка при добавлении записи в таблицу подписей
 *
 */
class ExternalSignatureVerifier extends APIController
{

    /**
     * Код выхода, соответствующий случаю, когда проверяемый файл не является открепленной подписью
     *
     */
    private const FILE_IS_NOT_EXTERNAL_SIGN_RESULT = 'finesr';


    /**
     * Реализация абстрактного метода
     *
     * @throws DataBaseEx
     * @throws RequestEx
     * @throws TransactionEx
     * @throws ReflectionException
     */
    public function doExecute(): void
    {

        list(
            'fs_name_data'    => $fs_data,
            'fs_name_sign'    => $fs_sign,
            'mapping_level_1' => $ml_1,
            'mapping_level_2' => $ml_2
            ) = $this->getCheckedRequiredParams(HttpRequest::POST, ['fs_name_data', 'fs_name_sign', 'mapping_level_1', 'mapping_level_2']);

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

            // Получение id файлов
            list(
                'application_id' => $application_id,
                'file_name'      => $hash_data
                ) = ApplicationHelper::parseApplicationFilePath($fs_data);

            list(
                'application_id' => $tmp_id,
                'file_name'      => $hash_sign
                ) = ApplicationHelper::parseApplicationFilePath($fs_sign);
        } catch (FunctionsEx $e) {

            $this->logAndExceptionExit(3, $e, "Ошибка при разборе 'fs_name_data' / 'fs_name_sign'");
        }

        // Проверка на то, что id исходного файла и файла подписи относятся к одному заявлению
        if ($application_id != $tmp_id) {
            $this->logAndErrorExit(4, "id заявления исходного файла: '{$application_id}' не равен id заявления файла подписи: '{$tmp_id}'");
        }

        $dataFileAssoc = $fileTable::getAssocByHash($hash_data);
        $signFileAssoc = $fileTable::getAssocByHash($hash_sign);

        if ($signFileAssoc['file_size'] / 1024 > 20) {
            $this->errorExit(self::FILE_IS_NOT_EXTERNAL_SIGN_RESULT, 'Проверяемый файл не является открепленной подписью');
        }

        $validator = new Validator(new MessageParser(true), new ExternalSignature());

        try {

            $validateResults = $validator->validate($fs_data, $fs_sign);
        } catch (CSPValidatorEx $e) {

            if ($e->getCode() == 4) {

                if ($validator->isSignatureVerifyingNotStarted()) {
                    $this->errorExit(self::FILE_IS_NOT_EXTERNAL_SIGN_RESULT, 'Проверяемый файл не является открепленной подписью');
                }
                if ($validator->isCantOpenFile() && ($signFileAssoc['file_size'] == 0)) {
                    $this->errorExit(5, 'Передан пустой файл');
                }
            }
            $this->logAndExceptionExit(6, $e, 'Произошла внутренняя ошибка');
        } catch (Exception $e) {
            $this->logAndExceptionExit(6, $e, 'Произошла внутренняя ошибка');
        }

        $validateResult = array_shift($validateResults);

        // Открепленная подпись не может содержать более одного подписанта, т.е. в данном случае встроенная подпись меньше 20 Кб
        if (!empty($validateResults)) {
            $this->errorExit(7, 'Загруженный файл не является открепленной подписью');
        }

        $transaction = new Transaction();

        // Заполняем транзакцию для создания записи в таблице подписей
        $transaction->add($signTable, 'create', [
            $signFileAssoc['id'],
            1,
            $dataFileAssoc['id'],
            $validateResult['fio'],
            $validateResult['certificate'],
            $validateResult['signature_verify']['result'] ? 1 : 0,
            $validateResult['signature_verify']['message'],
            $validateResult['signature_verify']['user_message'],
            $validateResult['certificate_verify']['result'] ? 1 : 0,
            $validateResult['certificate_verify']['message'],
            $validateResult['certificate_verify']['user_message']
        ]);

        // Удаляем данные, которые не нужны на клиентской стороне
        unset($validateResult['signature_verify']['message']);
        unset($validateResult['certificate_verify']['message']);

        try {
            $transaction->start();
        } catch (DataBaseEx $e) {
            $this->logAndExceptionExit(8, $e, "Произошла ошибка при добавлении записи в таблицу подписей");
        }

        // Все прошло успешно
        $this->successExit(['validate_results' => [$validateResult]]);
    }


    /**
     * Реализация абстрактного метода
     *
     * @throws LoggerEx
     */
    protected function getErrorLogger(): Logger
    {
        return new Logger(LOGS_API_ERRORS . '/ExternalSignatureVerifier.log');
    }
}