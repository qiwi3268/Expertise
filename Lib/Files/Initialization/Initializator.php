<?php


namespace Lib\Files\Initialization;

use Lib\Exceptions\File as SelfEx;
use Lib\Files\Mappings\RequiredMappingsSetter;
use Lib\Files\Mappings\FilesTableMapping;
use Lib\Signs\Mappings\SignsTableMapping;
use Lib\Singles\NodeStructure;


// Абстрактный класс для инициализации сохраненных файлов
//
abstract class Initializator
{

    private array $filesRequiredMappings; // Массив нужных маппингов файловых таблиц
    private array $signsRequiredMappings; // Массив нужных маппингов таблиц подписей


    // Принимает параметры-----------------------------------
    // filesRequiredMappings RequiredMappingsSetter : объект класса с установленными ранее нужными маппингами
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\File:
    //   ошибка в маппинг таблице (файлов)
    //   ошибка в маппинг таблице (подписей)
    //
    protected function __construct(RequiredMappingsSetter $filesRequiredMappings)
    {
        $signsRequiredMappings = [];

        // Проверка классов нужных маппингов
        foreach ($filesRequiredMappings->getMappings() as $mapping_level_1_code => $mapping_level_2) {

            foreach ($mapping_level_2 as $mapping_level_2_code => $className) {

                $filesMapping = new FilesTableMapping($mapping_level_1_code, $mapping_level_2_code);

                if (!is_null($filesMapping->getErrorCode())) {
                    throw new SelfEx("Ошибка в маппинг таблице (файлов) в классе '{$className}': '{$filesMapping->getErrorText()}'", $filesMapping->getErrorCode());
                }
                unset($filesMapping);

                // Формирование маппингов для таблиц подписей, аналогичных по стркутуре с filesRequiredMappings
                $signsMapping = new SignsTableMapping($mapping_level_1_code, $mapping_level_2_code);

                $signsMappingErrorCode = $signsMapping->getErrorCode();

                if (is_null($signsMappingErrorCode)) {
                    $signsRequiredMappings[$mapping_level_1_code][$mapping_level_2_code] = $signsMapping->getClassName();
                } elseif ($signsMappingErrorCode == 1) {
                    // Не существует соответствующего класса таблицы подписей к классу файловой таблицы
                    $signsRequiredMappings[$mapping_level_1_code][$mapping_level_2_code] = null;
                } else {
                    throw new SelfEx("Ошибка в маппинг таблице (подписей) в классе '{$className}': '{$signsMapping->getErrorText()}'", $signsMappingErrorCode);
                }
                unset($signsMapping);
            }
        }
        $this->filesRequiredMappings = $filesRequiredMappings->getMappings();
        $this->signsRequiredMappings = $signsRequiredMappings;
    }



    // Предназначен для полученя нужных (is_needs=1) файлов и подписей к ним, находящихся в документе
    // и в требуемых маппингах filesRequiredMappings
    // У кажого файла есть свойство 'signs', которое:
    //    null, если для данного маппинга файловой таблицы не предусмотрены подписи;
    //    включает в себя массивы подписей встроенных и открепленных: 'internal' = [], 'external' = []
    // *** Из массива файлов будут автоматически удалены файлы, которые являются открепленными подписями
    // Возвращает параметры----------------------------------
    // array : структура массива аналогична filesRequiredMappings. Вместо названия класса - массив с нужные файлами / null
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\File:
    //   осталась(лись) подпись, которая не подошла ни к одному из файлов
    //
    public function getNeedsFilesWithSigns(): array
    {
        $result = [];

        foreach ($this->filesRequiredMappings as $mapping_level_1_code => $mapping_level_2) {

            foreach ($mapping_level_2 as $mapping_level_2_code => $fileClassName) {

                $files = $fileClassName::getNeedsAssocByIdMainDocument($this->getMainDocumentId());

                if (is_null($files)) {
                    $result[$mapping_level_1_code][$mapping_level_2_code] = null;
                    continue;
                }

                $files = new \ArrayIterator($files);

                // Формирование id файлов для запроса IN
                $ids = [];
                foreach ($files as ['id' => $id]) {
                    $ids[] = $id;
                }

                $signClassName = $this->signsRequiredMappings[$mapping_level_1_code][$mapping_level_2_code];

                if (is_null($signClassName) || is_null($signs = $signClassName::getAllAssocByIds($ids))) {

                    foreach ($files as $index => $file) {
                        $files[$index]['signs'] = null;
                    }
                    $result[$mapping_level_1_code][$mapping_level_2_code] = $files->getArrayCopy();
                    unset($files);
                    continue;
                }

                $signs = new \ArrayIterator($signs);

                // Хэш-массив id файлов для быстрого поиска
                $idsHash = getHashArray($ids);

                $files->rewind();
                while ($files->valid()) {

                    $fi = $files->key();
                    $file = $files->current();

                    // Делаем проверку, чтобы не обнулить данные, если в file была открепленная подпись и file c данными искался из других элементов
                    if (!isset($files[$fi]['signs']['internal'])) $files[$fi]['signs']['internal'] = [];
                    if (!isset($files[$fi]['signs']['external'])) $files[$fi]['signs']['external'] = [];

                    $signs->rewind();
                    while ($signs->valid()) {

                        $si = $signs->key();
                        $sign = $signs->current();

                        // Итерируемый file является встроенной подписью
                        if ($file['id'] == $sign['id_sign'] && $sign['is_external'] == 0 && is_null($sign['id_file'])) {

                            $files[$fi]['signs']['internal'][] = $sign;

                            $signs->offsetUnset($si);
                            continue;

                        // Итерируемый file является файлом, к которому есть открепленная подпись
                        } elseif ($file['id'] == $sign['id_file'] && $sign['is_external'] == 1 && isset($idsHash[$sign['id_sign']])) {

                            // Находим file открепленной подписи (файл id которого равен id_sign)
                            $ind = $this->getFileIndex($files->getArrayCopy(), $sign['id_sign']);

                            $files[$fi]['signs']['external'][] = $sign;

                            unset($idsHash[$sign['id_sign']]); // Удаляем file открепленной подписи из хэш-массива id файлов

                            $files->offsetUnset($ind); // Удаляем file открепленной подписи
                            $signs->offsetUnset($si);
                            continue;

                        // Итерируемый file является открепленной подписью к другому file
                        } elseif ($file['id'] == $sign['id_sign'] && $sign['is_external'] == 1 && isset($idsHash[$sign['id_file']])) {

                            // Находим file с данными (файл id которого равен id_file)
                            $ind = $this->getFileIndex($files->getArrayCopy(), $sign['id_file']);

                            $files[$ind]['signs']['external'][] = $sign;

                            unset($idsHash[$sign['id_file']]); // Удаляем file открепленной подписи из хэш-массива id файлов

                            $files->offsetUnset($fi); // Удаляем итерируемый file (открепленную подпись)
                            $signs->offsetUnset($si);
                            continue 2;
                        }
                        $signs->next();
                    }
                    $files->next();
                }

                if ($signs->count() > 0) {

                    $ids = [];
                    foreach ($signs as ['id' => $id]) {
                        $ids[] = $id;
                    }
                    $ids = implode(', ', $ids);
                    // Вероятнее всего, требуемый файл не попал в выборку getNeedsAssocByIdMainDocument по причине is_needs=0
                    throw new SelfEx("Осталась(лись) подпись с id: '{$ids}' из таблицы подписей: '{$signClassName}', которая не подошла ни к одному из файлов");
                }
                $result[$mapping_level_1_code][$mapping_level_2_code] = $files->getArrayCopy();
            }
        }
        return $result;
    }


    // Вспомогательный метод для поиска индекса нужного файла
    // Принимает параметры-----------------------------------
    // files array : индексный масив с ассоциативными массивами файлов
    // Возвращает параметры----------------------------------
    // int : найденный индекс в массиве files
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\File:
    //   в массиве файлов не найден нужный id
    //
    private function getFileIndex(array $files, int $neededId): int
    {
        foreach ($files as $index => $file) {
            if ($file['id'] == $neededId) return $index;
        }
        throw new SelfEx("В массиве файлов не найден нужный id: '{$neededId}'");
    }


    // Предназначен для получения файлов внутри стркутуры
    // *** files должны иметь прямую принадлежность к стркутуре, т.е. ключ id_structure_node
    // Принимает параметры-----------------------------------
    // files                 array : индексный масив с ассоциативными массивами файлов
    // NodeStructure NodeStructure : экземпляр класса NodeStructure
    // Возвращает параметры----------------------------------
    // array : "глубинная" структура, в узлах которой находятся файлы
    //
    static public function getFilesInDepthStructure(array $files, NodeStructure $NodeStructure): array
    {
        $structure = $NodeStructure->getDepthStructure();

        foreach ($structure as $structureIndex => $node) {

            foreach ($files as $fileIndex => $file) {

                if ($node['id'] == $file['id_structure_node']) {

                    $structure[$structureIndex]['files'][] = $file;
                    unset($files[$fileIndex]);
                }
            }
            if (empty($files)) break;
        }
        return $structure;
    }
}
