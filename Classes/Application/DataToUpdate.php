<?php


namespace Classes\Application;


/**
 * Предназначен для сохранения (обновленных) новых данных анкеты заявления
 *
 */
class DataToUpdate
{

    static private ?array $flatAssoc = null;
    static private array $dataToUpdate = [];


    /**
     * Конструктор класса
     *
     * Запрещаем создание экземпляра класса
     *
     */
    private function __construct()
    {
    }


    /**
     * Предназначен для установки "плоского" ассоциативного массива заявления
     *
     * @param array $assoc
     */
    static public function setFlatAssoc(array $assoc): void
    {
        self::$flatAssoc = $assoc;
    }


    /**
     * Предназначен для добавления нового <b>числового</b> значения из формы в общий список данных, которым нужен update
     *
     * <b>***</b> Поле в БД представлено числом (например, дата или справочник)
     *
     * @param string $form_value значение из переданной формы <i>(всегда строка)</i>
     * @param string $columnName имя столбца в БД, оно же имя ключа в ассоциативном массиве
     */
    static public function addInt(string $form_value, string $columnName): void
    {
        // Из формы пришло пустое значение и в БД что-то записано (пользователь удалил информацию)
        if ($form_value === '') {

            if (!is_null(self::$flatAssoc[$columnName])) {
                self::$dataToUpdate[$columnName] = null;
            }

       // Пользователь отправил данные отличающиеся от записи в БД
       // Жесткое сравнение необходимо, чтобы отличать введенный 0 и NULL из БД и т.д.
        } elseif (($int = (int)$form_value) !== self::$flatAssoc[$columnName]) {

            self::$dataToUpdate[$columnName] = $int;
        }
    }


    /**
     * Предназначен для добавления нового <b>строкового</b> значения из формы в общий список данных, которым нужен update
     *
     * <b>***</b> Поле в БД представлено строкой
     *
     * @param string $form_value значение из переданной формы <i>(всегда строка)</i>
     * @param string $columnName имя столбца в БД, оно же имя ключа в ассоциативном массиве
     */
    static public function addString(string $form_value, string $columnName): void
    {
        // В БД ничего не записано и из формы ничего не пришло : не обновляем данные
        if (
            (is_null(self::$flatAssoc[$columnName]) || self::$flatAssoc[$columnName] === '')
            && $form_value === ''
        ) {
            return;
        }

        // Пользователь отправил данные, отличающиеся от записи в БД
        if (self::$flatAssoc[$columnName] !== $form_value) self::$dataToUpdate[$columnName] = $form_value;
    }


    /**
     * Предназначен для проверки на пустоту установленных данных к обновлению
     *
     * @return bool
     */
    static public function isEmpty(): bool
    {
        return empty(self::$dataToUpdate);
    }


    /**
     * Предназначен для получения данных, которым нужен update
     *
     * @return array
     */
    static public function get(): array
    {
        return self::$dataToUpdate;
    }
}
