<?php


namespace Lib\Singles;
use LogicException;


/**
 * Предназначен для построения статистической диаграммы
 *
 */
class StatisticDiagram
{

    private ?int $maxCount;

    /**
     * Массив формата:
     *
     * Ключ - наименование столбца<br>
     * Значение - количество данных в столбце
     */
    private array $columns = [];


    /**
     * Конструктор класса
     *
     * @param int|null $maxCount <b>int</b> максимальное количество данных, по которым
     * будут построены все столбцы в диаграмме<br>
     * <b>null</b> если требуется, чтобы максимальное количество данных
     * было взято из размера максимального столбца
     */
    public function __construct(?int $maxCount = null)
    {
        $this->maxCount = $maxCount;
    }


    /**
     * Предназначен для добавления столбца к диаграмме
     *
     * @param string $label наименование столбца
     * @param int $count количество данных в столбце
     * @throws LogicException
     */
    public function addColumn(string $label, int $count): void
    {
        if (isset($this->columns[$label])) {
            throw new LogicException("Столбец диаграммы: '{$label}' уже добавлен");
        } elseif($count < 0) {
            throw new LogicException("Столбец диаграммы: '{$label}' имеет отриацительное количество данных: '{$count}'");
        }

        $this->columns[$label] = $count;
    }


    /**
     * Предназначен для получения массива диаграммы
     *
     * @return array ассоциативный массив формата:<br>
     * ключ - название столбца<br>
     * значение - ассоциативный массив формата:<br>
     * ['non_filled' => 2, 'filled' => 4], где:<br>
     * - non_filled - разница между максимальным количеством данных и данных из текущего столбца<br>
     * - filled - количество данных из текущего столбца
     * @throws LogicException
     */
    public function getDiagram(): array
    {
        $columns = $this->columns;

        if (empty($columns)) {
            throw new LogicException("Для получения диаграммы необходимо добавить столбцы");
        }

        $result = [];

        $max = max($columns);

        if (is_null($this->maxCount)) {

            $maxCount = $max;
        } else {

            if ($this->maxCount < $max) {
                throw new LogicException("Установленное в конструкторе класса значение: {$this->maxCount} меньше, чем количество данных в одном из столбцов");
            }
            $maxCount = $this->maxCount;
        }

        foreach ($columns as $label => $count) {

            $result[$label] = [
                'non_filled'  => $maxCount - $count,
                'filled'      => $count
            ];
        }
        return $result;
    }
}