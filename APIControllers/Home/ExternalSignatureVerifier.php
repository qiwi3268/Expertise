<?php


namespace APIControllers\Home;

use core\Classes\Exceptions\Request as RequestEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Transaction as TransactionEx;
use Lib\Exceptions\Shell as ShellEx;
use Lib\Exceptions\CSPMessageParser as CSPMessageParserEx;
use Lib\Exceptions\CSPValidator as CSPValidatorEx;
use Lib\Exceptions\TableMappings as TableMappingsEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use functions\Exceptions\Functions as FunctionsEx;

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
 * API предназначен для
 *
 * API result:

 *
 */
class ExternalSignatureVerifier extends APIController
{

    /**
     * Реализация абстрактного метода
     *
     * @throws RequestEx
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

        // *** Опускаем проверку на null по причине предшествующего API_file_checker
        // todo написать про то что файл чекер нужен заранее
        $dataFileAssoc = $fileTable::getAssocByIdMainDocumentAndHash($application_id, $hash_data);
        $signFileAssoc = $fileTable::getAssocByIdMainDocumentAndHash($application_id, $hash_sign);

        if ($signFileAssoc['file_size'] / 1024 > 20) {
            $this->errorExit(5, 'Загруженный файл не является открепленной подписью');
        }

        $validator = new Validator(new MessageParser(true), new ExternalSignature());

        try {

            $validateResults = $validator->validate($fs_data, $fs_sign);
        } catch (ShellEx $e) {

            // Lib\CSP\Shell:exec
            // Исполняемая команда: не произвела вывод или произошла ошибка
            $this->logAndExceptionExit(6, $e, "Произошла внутренняя ошибка 'Lib\Exceptions\Shell'");
        } catch (FunctionsEx $e) {

            // getHandlePregMatch
            // Произошла ошибка или нет вхождений шаблона при работе функции getHandlePregMatch
            $this->logAndExceptionExit(7, $e, "Произошла внутренняя ошибка 'functions\Exceptions\Functions'");
        } catch (CSPMessageParserEx $e) {

            // Lib\CSP\MessageParser::getFIO
            $this->logAndExceptionExit(8, $e, "Произошла внутренняя ошибка 'Lib\Exceptions\CSPMessageParser'");
        } catch (CSPValidatorEx $e) {

            // Lib\CSP\Validator::validate
            $code = $e->getCode();

            // Вместо открепленной подписи загружен файл без подписи
            if ($code == 4 && $validator->isSignatureVerifyingNotStarted()) {
                $this->errorExit(777);
            }

            //  Был передан пустой файл открепленной подписи
            if ($code == 4 && $validator->isCantOpenFile() && ($signFileAssoc['file_size'] == 0)) {
                $this->errorExit(9, 'Передан пустой файл открепленной подписи');
            }

            $this->logAndExceptionExit(10, $e, "Произошла внутренняя ошибка 'Lib\Exceptions\CSPValidator'");
        }

        // Открепленная подпись не может содержать более одного подписанта, т.е.
        // в данном случае встроенная подпись меньше 20 Кб
        if (count($validateResults) > 1) {
            $this->errorExit(11, 'Загруженный файл не является открепленной подписью');
        }

        $id_data = $dataFileAssoc['id'];
        $id_sign = $signFileAssoc['id'];

        $transaction = new Transaction();

        // Заполняем транзакцию для создания записи в таблице подписей
        foreach ($validateResults as &$result) {

            $transaction->add($signTable, 'create', [
                $id_sign,
                1,
                $id_data,
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
            $this->logAndExceptionExit(12, $e, "Произошла ошибка при добавлении записи в таблицу подписей: '{$signTable}'");
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
        return new Logger(LOGS_API_ERRORS, 'ExternalSignatureVerifier.log');
    }
}