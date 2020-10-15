<?php


namespace Lib\Files\Initialization;

use Lib\Exceptions\File as SelfEx;
use Lib\Files\Mappings\RequiredMappingsSetter;
use Lib\Files\Mappings\FilesTableMapping;
use Lib\Signs\Mappings\SignsTableMapping;
use Lib\Singles\NodeStructure;
use ArrayIterator;


/**
 * Абстрактный класс для инициализации сохраненных файлов
 *
 */
abstract class Initializer
{

    /**
     * Массив нужных маппингов файловых таблиц
     *
     */
    private array $filesMappings;

    /**
     * Массив нужных маппингов таблиц подписей
     *
     */
    private array $signsMappings;


    /**
     * Конструктор класса
     *
     * @param RequiredMappingsSetter $requiredMappingsSetter объект класса с установленными ранее нужными маппингами
     * @throws SelfEx
     */
    protected function __construct(RequiredMappingsSetter $requiredMappingsSetter)
    {
        $signsMappings = [];

        // Проверка классов нужных маппингов
        foreach ($requiredMappingsSetter->getMappings() as $mapping_level_1_code => $mapping_level_2) {

            foreach ($mapping_level_2 as $mapping_level_2_code => $className) {

                $filesMapping = new FilesTableMapping($mapping_level_1_code, $mapping_level_2_code);

                if (!is_null($filesMapping->getErrorCode())) {
                    throw new SelfEx("Ошибка в маппинг таблице (файлов) в классе '{$className}': '{$filesMapping->getErrorText()}'", $filesMapping->getErrorCode());
                }
                unset($filesMapping);

                // Формирование маппингов для таблиц подписей, аналогичных по стркутуре с filesMappings
                $signsMapping = new SignsTableMapping($mapping_level_1_code, $mapping_level_2_code);

                $signsMappingErrorCode = $signsMapping->getErrorCode();

                if (is_null($signsMappingErrorCode)) {
                    $signsMappings[$mapping_level_1_code][$mapping_level_2_code] = $signsMapping->getClassName();
                } elseif ($signsMappingErrorCode == 1) {
                    // Не существует соответствующего класса таблицы подписей к классу файловой таблицы
                    $signsMappings[$mapping_level_1_code][$mapping_level_2_code] = null;
                } else {
                    throw new SelfEx("Ошибка в маппинг таблице (подписей) в классе '{$className}': '{$signsMapping->getErrorText()}'", $signsMappingErrorCode);
                }
                unset($signsMapping);
            }
        }
        $this->filesMappings = $requiredMappingsSetter->getMappings();
        $this->signsMappings = $signsMappings;
    }


    /**
     * Предназначен для получения массива маппингов файловых таблиц
     *
     * @return array
     */
    public function getFilesMappings(): array
    {
        return $this->filesMappings;
    }


    /**
     * Предназначен для получения первых маппингов маппингов первого и второго уровня файловых таблиц
     *
     * Фактически метод является синтаксическим сахаром для случаев, когда установлен всего 1 маппинг
     *
     * @return array ассоциативный массив с ключами '1' и '2', в которых находятся
     * int значения соответствующих маппингов
     */
    public function getFirstFilesMappings(): array
    {
        $mapping_level_1 = array_key_first($this->filesMappings);
        $mapping_level_2 = array_key_first($this->filesMappings[$mapping_level_1]);

        return [
            1 => $mapping_level_1,
            2 => $mapping_level_2
        ];
    }


    /**
     * Предназначен для полученя нужных (is_needs=1) файлов и подписей к ним,
     * находящихся в документе и в маппингах filesMappings
     *
     * У кажого файла есть свойство 'signs', которое:<br>
     * - null, если для данного маппинга файловой таблицы не предусмотрены подписи;<br>
     * - включает в себя массивы подписей встроенных и открепленных: 'internal' = [], 'external' = []<br>
     * <b>***</b> Из массива файлов будут автоматически удалены файлы, которые являются открепленными подписями
     *
     * @return array структура массива аналогична <i>filesMappings</i>. Вместо названия класса - массив с нужные файлами / null
     * @throws SelfEx
     */
    public function getNeedsFilesWithSigns(): array
    {
        $result = [];

        foreach ($this->filesMappings as $mapping_level_1_code => $mapping_level_2) {

            foreach ($mapping_level_2 as $mapping_level_2_code => $fileClassName) {

                if (is_null($files = $this->getFiles($fileClassName))) {
                    $result[$mapping_level_1_code][$mapping_level_2_code] = null;
                    continue;
                }

                // Формирование id файлов для запроса IN
                $ids = compressArrayByKey($files, 'id');

                $files = new ArrayIterator($files);

                $signClassName = $this->signsMappings[$mapping_level_1_code][$mapping_level_2_code];

                // Значение для выхода из текущей итерации
                $exitValue = false;

                if (is_null($signClassName)) {

                    $exitValue = null;
                } elseif (is_null($signs = $signClassName::getAllAssocByIds($ids))) {

                    $exitValue = [
                      'internal' => [],
                      'external' => []
                    ];
                }

                if ($exitValue !== false) {

                    foreach ($files as $index => $file) {
                        $files[$index]['signs'] = $exitValue;
                    }
                    $result[$mapping_level_1_code][$mapping_level_2_code] = $files->getArrayCopy();
                    unset($files);
                    continue;
                }

                $signs = new ArrayIterator($signs);

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
                    // Вероятнее всего, требуемый файл не попал в выборку getAllAssocWhereNeedsByIdMainDocument по причине is_needs=0
                    throw new SelfEx("Осталась(лись) подпись с id: '{$ids}' из таблицы подписей: '{$signClassName}', которая не подошла ни к одному из файлов");
                }
                $result[$mapping_level_1_code][$mapping_level_2_code] = $files->getArrayCopy();
            }
        }
        return $result;
    }


    /**
     * Вспомогательный метод для поиска индекса нужного файла
     *
     * @param array $files индексный масив с ассоциативными массивами файлов
     * @param int $neededId id нужного файла
     * @return int найденный индекс в массиве files
     * @throws SelfEx
     */
    private function getFileIndex(array $files, int $neededId): int
    {
        foreach ($files as $index => $file) {
            if ($file['id'] == $neededId) return $index;
        }
        throw new SelfEx("В массиве файлов не найден нужный id: '{$neededId}'");
    }


    /**
     * Предназначен для получения файлов внутри стркутуры
     *
     * <b>***</b> files должны иметь прямую принадлежность к стркутуре, т.е. ключ id_structure_node
     *
     * @param array $files индексный масив с ассоциативными массивами файлов
     * @param NodeStructure $NodeStructure экземпляр класса NodeStructure
     * @return array "глубинная" структура, в узлах которой находятся файлы
     */
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


    /**
     * Предназначен для получения индексного массива с ассоциативными массивами файлов,
     * которые нужны для реализующего класса
     *
     * @param string $fileClassName является названием класса таблицы,
     * лежащем в установленном маппинге {@see FILE_TABLE_MAPPING}.
     * <br>
     * Таким образом, при реализации данного метода требуется предусмотреть, что имена таблиц будут
     * <b>разными</b>, если было передано более 1 маппинга
     * @return array|null <b>array</b> индексный массив с ассоциативными массива внутри, если записи существуют<br>
     * <b>null</b> в противном случае
     */
    abstract protected function getFiles(string $fileClassName): ?array;
}
