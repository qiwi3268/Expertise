<?php


//todo: рефактор, код ревью, оформление

class EditFormInitializer extends MiscInitialization
{

    // Ассоциативный массив заявления (с сохраненными данными)
    private array $applicationAssoc;


    // Принимает параметры-----------------------------------
    // applicationAssoc array : ассоциативный массив заявления, в котором необходимо наличие всех справочников,
    // объявленных в коснтанте MISC_NAMES класса MiscInitialization
    //
    function __construct(array $applicationAssoc)
    {

        // Вызов родительского конструктора для инициализации справочников
        parent::__construct();

        // Проверка на то, что в полученном ассоциативном массиве заявления есть все инициализированные справочники
        $definedMiscs = array_merge($this->singleMiscs, $this->dependentMiscs);

        $lostProperties = array_diff_key($definedMiscs, $applicationAssoc);

        if (!empty($lostProperties)) {

            $lostProperties = implode(', ', array_keys($lostProperties));

            throw new Exception("В ассоциативном массиве заявления отсутствуют определенные в MISC_NAMES свойства: $lostProperties");
        }

        $this->applicationAssoc = $applicationAssoc;
    }


    public function getSingleMiscsIncludeInactive(): array
    {

        $result = $this->singleMiscs;

        foreach ($this->singleMiscs as $miscName => $activeMisc) {

            $savedItem = $this->applicationAssoc[$miscName];

            if (is_null($savedItem)) {
                continue;
            }

            // Сохраненный элемент может быть представлен массивом (с подмассивами в виде других сохраненных элементов),
            // если реализован выбор нескольких пунктов
            $tmp = [];

            if (is_array(current($savedItem))) {
                $tmp = $savedItem;
            } else {
                $tmp[] = $savedItem;
            }

            $miscIncudingInactive = $this->getMiscIncludeInactive($activeMisc, $tmp);

            // Перезаписываем текущий справочник
            $result[$miscName] = $miscIncudingInactive;
        }
        return $result;
    }


    public function getDependentMiscsIncludeInactive(): array
    {

        $result = $this->dependentMiscs;

        foreach ($this->dependentMiscs as $miscName => $mainMiscIds) {

            $savedItem = $this->applicationAssoc[$miscName];

            if (is_null($savedItem)) {
                continue;
            }

            // Сохраненный элемент может быть представлен массивом (с подмассивами в виде других сохраненных элементов),
            // если реализован выбор нескольких пунктов
            $tmp = [];

            if (is_array(current($savedItem))) {
                $tmp = $savedItem;
            } else {
                $tmp[] = $savedItem;
            }

            // Имя главного справочника для итерируемого (зависимого)
            $mainMiscName = parent::MISC_MAIN_NAMES[$miscName];
            // Главный элемент, который 100% сохранен в заявлении. Он не может быть представлен
            // массивом с другими элементами, т.е. выбор только одного пункта
            $mainSavedItem = $this->applicationAssoc[$mainMiscName];
            $mainSavedItemId = $mainSavedItem['id'];

            // Целевой зависимый активный справочник. Неактивные элементы справочника могут
            // быть только в подмассиве выбранного главного элемента
            $targetDependentActiveMisc = $mainMiscIds[$mainSavedItemId];

            $miscIncludingInactive = $this->getMiscIncludeInactive($targetDependentActiveMisc, $tmp);

            // Перезаписываем текущий справочник
            $result[$miscName][$mainSavedItemId] = $miscIncludingInactive;
        }
        return $result;
    }


    // Предназначен для получения массива справочника, включая активные на данный момент И
    // неактивные, но сохраненные данные в заявлении
    // Принимает параметры-----------------------------------
    // activeMisc array : активный на данный момент справочник. Массив с подмассивами в виде элементов (item) справочника
    // savedItems array : идентичный по структуре с activeMisc, но содержащий сохраненные элементы
    // Возвращает параметры----------------------------------
    // array : справочник, содержащий активные и неактивные (но сохраненные) элементы
    //
    private function getMiscIncludeInactive(array $activeMisc, array $savedItems): array
    {

        $result = $activeMisc;

        foreach ($savedItems as $savedItem) {

            $issetFlag = false;

            foreach ($activeMisc as ['id' => $activeItemId]) {

                // Обязательно нестрогое сравнение, т.к. id с параметризованного запроса возвращается int, а с простого - string
                if ($savedItem['id'] == $activeItemId) {

                    $issetFlag = true;
                    break;
                }
            }

            if (!$issetFlag) {
                $result[] = $savedItem;
            }
        }
        return $result;
    }
}