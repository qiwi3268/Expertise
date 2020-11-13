<?php


namespace APIControllers\Home;

use core\Classes\Exceptions\Request as RequestEx;
use Lib\Exceptions\DataBase;
use Lib\Exceptions\TableMappings as TableMappingsEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Lib\Exceptions\Transaction as TransactionEx;
use Lib\Exceptions\Logger as LoggerEx;
use ReflectionException;

use core\Classes\Request\HttpRequest;
use core\Classes\ControllersInterface\APIController;
use Lib\TableMappings\TableMappingsXMLHandler;
use Lib\DataBase\Transaction;
use Lib\Singles\Logger;
use Lib\Singles\PrimitiveValidator;


/**
 * API предназначен для установки флага 'is_needs' у файла в контексте документа
 *
 * API result:
 * - ok
 * - 1 - Ошибка при декодировании входного json'а
 * - 2 - Ошибка при валидации входного json'а
 * - 3 - Ошибка при валидации массива с файлами
 * - 4 - Переданые пустые массивы to_save и to_delete
 * - 5 - Ошибка при инициализации XML схемы табличных маппингов
 * - 6 - Ошибка при обработке XML схемы table_mappings
 * - 7 - Ошибка при валидации XML схемы table_mappings
 * - 8 - Файл id: ... таблицы класса: '...' не существует
 * - 9 - Ошибка при обновлении флага 'is_needs'
 *
 */
class FileNeedsSetter extends APIController
{

    private TableMappingsXMLHandler $XMLHandler;

    /**
     * Сохраненные наименования классов файловых таблиц
     *
     */
    private array $cacheClasses = [];


    /**
     * Реализация абстрактного метода
     *
     * @throws RequestEx
     * @throws TransactionEx
     * @throws ReflectionException
     */
    public function doExecute(): void
    {
        $json = $this->getCheckedRequiredParams(HttpRequest::POST, ['file_needs_json'])['file_needs_json'];

        $primitiveValidator = new PrimitiveValidator();

        // Валидация json'а
        try {
            $fileNeedsAssoc = $primitiveValidator->getAssocArrayFromJson($json, 4);
        } catch (PrimitiveValidatorEx $e) {
            $this->logAndExceptionExit(1, $e, "Ошибка при декодировании входного json'а");
        }

        // Валидация входного json'а
        try {

            $primitiveValidator->validateAssociativeArray($fileNeedsAssoc, [
                'to_save'   => ['is_array'],
                'to_delete' => ['is_array']
            ]);
        } catch (PrimitiveValidatorEx $e) {
            $this->logAndExceptionExit(2, $e, "Ошибка при валидации входного json'а");
        }

        // Валидация массивов с файлами
        foreach ($fileNeedsAssoc as $typeName => $type) {

            foreach ($type as $index => $file) {

                try {

                    $primitiveValidator->validateAssociativeArray($file, [
                        'id_file'         => ['is_int'],
                        'mapping_level_1' => ['is_int'],
                        'mapping_level_2' => ['is_int']
                    ]);
                } catch (PrimitiveValidatorEx $e) {
                    $this->logAndExceptionExit(3, $e, "Ошибка при валидации массива с файлами");
                }
            }
        }

        // Проверка на наличие файлов в массивах
        if (empty($fileNeedsAssoc['to_save']) && empty($fileNeedsAssoc['to_delete'])) {
            $this->logAndErrorExit(4, 'Переданые пустые массивы to_save и to_delete');
        }

        try {
            $this->XMLHandler = new TableMappingsXMLHandler();
        } catch (TableMappingsEx $e) {
            $this->logAndExceptionExit(5, $e);
        }

        // Проверка указанных маппингов на корректность
        // + запись свойства 'class_name' каждому файлу, для использования в дальнейшем
        // + проверка существования записи файла
        foreach ($fileNeedsAssoc as &$type) {

            foreach ($type as &$file) {

                try {
                    $className = $this->getClassName($file['mapping_level_1'], $file['mapping_level_2']);
                } catch (TableMappingsEx $e) {
                    $this->logAndExceptionExit(6, $e, 'Ошибка при обработке XML схемы table_mappings');
                } catch (XMLValidatorEx $e) {
                    $this->logAndExceptionExit(7, $e, 'Ошибка при валидации XML схемы table_mappings');
                }

                // Проверка существования записи указанного файла
                if (!$className::checkExistById($file['id_file'])) {

                    $this->logAndErrorExit(8, "Файл id: {$file['id_file']} таблицы класса: '{$className}' не существует");
                }
                $file['class_name'] = $className;

                unset($level_2);
            }
            unset($file);
        }
        unset($type);

        $transaction = new Transaction();

        // Заполняем транзакцию
        // Сначала ставим метку ненужности, потом нужности. В случае, если на стороне клиентского js будет ошибка - файл останется "нужным"
        foreach ($fileNeedsAssoc['to_delete'] as $file) {
            $transaction->add($file['class_name'], 'setNeedsToFalseById', [$file['id_file']]);
        }
        foreach ($fileNeedsAssoc['to_save'] as $file) {
            $transaction->add($file['class_name'], 'setNeedsToTrueById', [$file['id_file']]);
        }

        try {
            $transaction->start();
        } catch (DataBaseEx $e) {
            $this->logAndExceptionExit(9, $e, "Ошибка при обновлении флага 'is_needs'");
        }

        // Все прошло успешно
        $this->successExit();
    }


    /**
     * Предназначен для получения полного наименования класса файловой таблицы
     *
     * @param int $ml_1
     * @param int $ml_2
     * @return string
     * @throws TableMappingsEx
     * @throws XMLValidatorEx
     */
    private function getClassName(int $ml_1, int $ml_2): string
    {
        if (isset($this->cacheClasses[$ml_1][$ml_2])) {
            return $this->cacheClasses[$ml_1][$ml_2];
        }

        $level_2 = $this->XMLHandler->getLevel2($ml_1, $ml_2);
        $className = $this->XMLHandler->validateLevel2Structure($level_2)->getHandledLevel2Value($level_2)['file_table_class'];

        $this->cacheClasses[$ml_1][$ml_2] = $className;

        return $className;
    }


    /**
     * Реализация абстрактного метода
     *
     * @throws LoggerEx
     */
    protected function getErrorLogger(): Logger
    {
        return new Logger(LOGS_API_ERRORS . '/FileNeedsSetter.log');
    }
}