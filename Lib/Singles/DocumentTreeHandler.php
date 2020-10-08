<?php


namespace Lib\Singles;

use Lib\Exceptions\DocumentTreeHandler as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Exceptions\Tables as TablesEx;

use Tables\Docs\Relations\HierarchyTree;


/**
 * Предназначен для обработки данных дерева документов, полученных методом:
 * {@see \Tables\Docs\Relations\HierarchyTree::getTree()}
 *
 * Идея класса заключается в том, что он хранит экземпляры полученных ранее объектов
 * собственного типа. Это позволяет работать с несколькими древьями по разным ключам.
 *
 * Класс сделан не по шаблону "Singleton", потому что может понадобиться получать деревья
 * к разным заявленям из одного места.
 *
 * Пример использования: в момент проверки доступа пользователя к документу устанавливается дерево.
 * Далее весь следующий клиентский код получает установленное ранее дерево по известному ключу,
 * тем самым экономя запросы к БД
 *
 */
final class DocumentTreeHandler
{

    /**
     * Хранилище объектов собственного типа
     *
     * Представляет ассоциативный массив формата:<br>
     * Ключ - уникальный ключ для установки / получения объекта<br>
     * Значение - объект собственного типа
     *
     */
    static private array $selfObjectStorage = [];


    /**
     * Массив иерархии документов
     *
     */
    private array $tree;


    /**
     * Конструктор класса
     *
     * @param array $tree массив иерархии документов
     */
    private function __construct(array $tree)
    {
        $this->tree = $tree;
    }


    /**
     * Предназначен для установки объекта по уникальному ключу.
     *
     * Для удобства метод возвращает созданный объект, чтобы не вызывать
     * метод получения
     *
     * @param string $key уникальный ключ, по котором сохранится новый объект
     * @param string $documentType тип документа
     * @param int $documentId id документа
     * @return static экземпляр текущего класса
     * @throws SelfEx
     * @throws DataBaseEx
     * @throws TablesEx
     */
    static public function setInstanceByKey(string $key, string $documentType, int $documentId): self
    {
        if (isset(self::$selfObjectStorage[$key])) {
            throw new SelfEx("Экземпляр объекта по ключу: '{$key}' уже существует в хранилище", 1);
        }

        $hierarchyTree = new HierarchyTree($documentType, $documentId);

        $selfObject = new self($hierarchyTree->getTree());

        self::$selfObjectStorage[$key] = $selfObject;

        return $selfObject;
    }


    /**
     * Предназначен для получения экземпляра текущего класса
     *
     * - <i>AccessToDocumentTree</i> устанавливается на этапе route callbacks в
     *  {@see \Lib\AccessToDocument\AccessToDocumentTree::__construct}
     * - <i>SectionActions</i> устанавливается в {@see \Classes\Section\Actions\Actions::__construct}
     *
     * @param string $key уникальный ключ, по которому ранее был установлен объект
     * @return static экземпляр текущего класса
     * @throws SelfEx
     */
    static public function getInstanceByKey(string $key): self
    {
        if (!isset(self::$selfObjectStorage[$key])) {
            throw new SelfEx("Экземпляр объекта по ключу: '{$key}' не существует в хранилище", 2);
        }

        return self::$selfObjectStorage[$key];
    }


    // cE - checkExist


    /**
     * Предназначен для проверки существования заявления
     *
     * @return bool
     */
    public function ce_application(): bool
    {
        return isset($this->tree['id']);
    }


    /**
     * Предназначен для проверки существования сводного замечания / заключения
     *
     * @return bool
     */
    public function ce_totalCC(): bool
    {
        // Так как сводное замечание / заключение может быть создано только при сохраненном виде объекта
        return isset($this->tree['id_type_of_object']) && isset($this->tree['children']['total_cc']);
    }


    /**
     * Предназначен для проверки существования раздела(ов)
     *
     * @return bool
     */
    public function ce_sections(): bool
    {
        return isset($this->tree['children']['total_cc']['children']);
    }


    /**
     * Предназначен для проверки существования конкретного раздела по его id
     *
     * @param int $sectionId id раздела
     * @return bool
     */
    public function ce_section(int $sectionId): bool
    {
        $arr = $this->tree['children']['total_cc']['children'];

        foreach ($arr as ['id' => $id]) {
            if ($sectionId == $id) {
                return true;
            }
        }
        return false;
    }


    /**
     * Предназначен для получения id заявления
     *
     * @return int
     */
    public function getApplicationId(): int
    {
        return $this->tree['id'];
    }


    /**
     * Предназначен для получения id вида объекта
     *
     * @return int
     */
    public function getTypeOfObjectId(): int
    {
        return $this->tree['id_type_of_object'];
    }


    /**
     * Предназначен для получения id сводного замечания / заключения
     *
     * @return int
     */
    public function getTotalCCId(): int
    {
        return $this->tree['children']['total_cc']['id'];
    }


    /**
     * Предназначен для получения индексного массива с разделами
     *
     * @return array индексный массив формата:<br>
     * 0 => ['id' => (int), 'children' => (array)],<br>
     * ...
     */
    public function getSections(): array
    {
        return $this->tree['children']['total_cc']['children'];
    }
}