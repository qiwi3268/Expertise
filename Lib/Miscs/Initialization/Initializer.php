<?php


namespace Lib\Miscs\Initialization;

use Lib\Exceptions\MiscInitializer as SelfEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;

use Lib\Singles\PrimitiveValidator;


/**
 * Предназначен для инициализации справочников
 *
 */
class Initializer
{

    private const SINGLE_MISC_INTERFACE = 'Tables\Miscs\Interfaces\SingleMisc';
    private const DEPENDENT_MISC_INTERFACE = 'Tables\Miscs\Interfaces\DependentMisc';

    private const DEFAULT_MISC_NAMESPACE = '\Tables\Miscs';

    /**
     *
     *
     */
    protected int $paginationSize;

    /**
     * Массив одиночных справочников
     *
     */
    protected array $singleMiscs = [];

    /**
     * Массив зависимых справочников
     *
     */
    protected array $dependentMiscs = [];


    /**
     * Конструктор класса
     *
     * Предназначен для инициализации справочников
     *
     * @param array $miscs индексный массив с названиями справочников.<br>
     * Если просто название справочника, то берется класс с таким же названием из пакета \Tables\Miscs.<br>
     * Если название включает символ '\', то к его имени не приписывается название пакета
     * @param int $paginationSize количество справочников на стрнице
     * @throws PrimitiveValidatorEx
     * @throws SelfEx
     */
    public function __construct(array $miscs, int $paginationSize = 8)
    {
        $primitiveValidator = new PrimitiveValidator();

        foreach ($miscs as $misc) {

            $className = contains($misc, '\\') ? $misc : self::DEFAULT_MISC_NAMESPACE . "\\{$misc}";

            $primitiveValidator->validateClassExist($className);

            if (($interfaces = class_implements($className)) === false) {
                throw new SelfEx("Возникла ошибка при работе функции class_implements", 1);
            }

            if (in_array(self::SINGLE_MISC_INTERFACE, $interfaces)) {

                $this->singleMiscs[$misc] = $className::getAllAssocWhereActive();
            } elseif (in_array(self::DEPENDENT_MISC_INTERFACE, $interfaces)) {

                $this->dependentMiscs[$misc] = $className::getAllAssocWhereActiveCorrMain();
            } else {

                throw new SelfEx("Класс справочника: '{$className}' не реализует один из требуемых интерфейсов: SingleMisc / DependentMisc", 2);
            }
        }
        $this->paginationSize = $paginationSize;
    }


    /**
     * Предназначен для разбивки одиночных справочников по страницам
     *
     * @return array разбитые постранично справочники
     * @throws SelfEx
     */
    public function getPaginationSingleMiscs(): array
    {
        if (empty($this->singleMiscs)) throw new SelfEx("Вызван метод Lib\Miscs\Initialization\Initializer::getPaginationSingleMiscs при пустом массиве singleMiscs", 3);

        $result = [];

        foreach ($this->singleMiscs as $miscName => $misc) {
            $result[$miscName] = array_chunk($misc, $this->paginationSize, false);
        }
        return $result;
    }


    /**
     * Предназначен для разбивки зависимых справочников по страницам
     *
     * @return array разбитые постранично справочники
     * @throws SelfEx
     */
    public function getPaginationDependentMiscs(): array
    {
        if (empty($this->dependentMiscs)) throw new SelfEx("Вызван метод Lib\Miscs\Initialization\Initializer::getPaginationDependentMiscs при пустом массиве dependentMiscs", 4);

        $result = [];

        foreach ($this->dependentMiscs as $miscName => $mainMiscIds) {

            // Цикл по справочнику в зависимоти от id-главного справочника
            foreach ($mainMiscIds as $mainMiscId => $misc) {

                $result[$miscName][$mainMiscId] = array_chunk($misc, $this->paginationSize, false);
            }
        }
        return $result;
    }
}