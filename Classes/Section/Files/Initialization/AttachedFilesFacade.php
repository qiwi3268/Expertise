<?php


namespace Classes\Section\Files\Initialization;

use Lib\Exceptions\File as FileEx;
use LogicException;

use Lib\Singles\Helpers\FileHandler;


/**
 * Класс-обертка для работы с прикрепленными файлами к замечаниям
 *
 * Предназначен для работы в контексте:
 * - Сводного замечания / заключения
 * - Раздела
 * - Замечания<br>
 * Поскольку входным параметром является id замечаний.
 *
 * Паттерн: <i>Facade</i>
 *
 */
class AttachedFilesFacade
{

    private int $mapping_level_1;
    private int $mapping_level_2;

    /**
     * Объект инициализации прикрепленных файлов к замечаниям
     *
     */
    private  AttachedFilesInitializer $filesInitializer;

    /**
     * Индексный массив с id замечаний
     *
     */
    private array $commentIds;


    /**
     * Конструктор класса
     *
     * @param array $commentIds индексный массив с id замечаний
     * @param int $typeOfObject id вида объекта
     * @throws FileEx
     */
    public function __construct(array $commentIds, int $typeOfObject)
    {
        $this->filesInitializer = new AttachedFilesInitializer($commentIds, $typeOfObject);

        list(
            1 => $this->mapping_level_1,
            2 => $this->mapping_level_2
            ) = $this->filesInitializer->getFirstFilesMappings();

        $this->commentIds = $commentIds;
    }


    /**
     * Предназначен для получения нужных файлов и подписей к ним
     *
     * @uses \Classes\Section\Files\Initialization\AttachedFilesInitializer::getNeedsFilesWithSigns()
     * @return array индексный массив с ассоциативными массивами файлов нужных маппингов
     * @throws FileEx
     */
    public function getNeedsFilesWithSigns(): array
    {
        return $this->filesInitializer->getNeedsFilesWithSigns()[$this->mapping_level_1][$this->mapping_level_2] ?? [];
    }


    /**
     * Предназначен для упаковки файлов по id замечаний
     *
     * @param array $files индексный массив с ассоциативными массивами файлов
     * @return array массив формата:<br>
     * Ключ - id замечания<br>
     * Значение - индексный массив с прикрепленными файлами к замечанию / пустой массив
     * @throws LogicException
     */
    public function packFilesToCommentIds(array $files): array
    {
        $result = [];

        $filesCount = count($files);
        $entryCount = 0;

        foreach ($this->commentIds as $commentId) {

            $result[$commentId] = [];

            list(
                'count'   => $count,
                'indexes' => $indexes
                ) = arrayEntry($files, 'id_comment', $commentId);

            if ($count > 0) {

                foreach ($indexes as $index) {

                    $result[$commentId][] = $files[$index];
                    unset($files[$index]);
                }
                $entryCount += $count;
            }
        }
        if ($entryCount != $filesCount) {
            throw new LogicException("Количество needsFiles: {$filesCount}, в то время как подошедших файлов к замечаниям: {$entryCount}");
        }
        return $result;
    }


    /**
     * Предназначен для обработки файлов
     *
     * - устанавливает файловую иконку
     * - устанавливает результат валидации подписи для уникальных файлов
     * - устанавливает человекопонятный размер файла
     *
     * @uses \Lib\Singles\Helpers\FileHandler::setFileIconClass()
     * @uses \Lib\Singles\Helpers\FileHandler::calculateLinkValidateResultJSON()
     * @uses \Lib\Singles\Helpers\FileHandler::setHumanFileSize()
     * @param array $files <i>ссылка</i> на индексный массив с ассоциативными массивами файлов
     */
    static public function handleFiles(array &$files): void
    {
        FileHandler::setFileIconClass($files);
        FileHandler::calculateLinkValidateResultJSON($files);
        FileHandler::setHumanFileSize($files);
    }
}