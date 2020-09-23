<?php


namespace Tables\Docs\Relations;

use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Exceptions\Tables as TablesEx;
use Tables\Docs\application as doc_application;


/**
 * Таблица: <i>'doc_application'</i>
 *
 * Отношения таблицы документа заявление с дочерними элементами
 *
 */
final class application
{

    /**
     * Предназначен для получения дочерних документов от заявления
     *
     * Метод документа как родительского
     *
     * @param int $id_application
     * @return array ассоциативный массив формата:<br><br>
     * <i>int</i> 'id' => id заявления<br>
     * <br>
     * <i>int|null</i> 'id_type_of_object' => <b>int</b> id выбранного вида объекта<br>
     * <b>null</b> вид объекта не выбран<br>
     * <br>
     * <i>array</i> 'children' => ассоциативный массив с дочерними документами (ключами массива):<br>
     * <br>
     * <i>array|null</i> 'contact' => <b>array</b> если дочерний документ договора существует, то
     * результат метода:: todo <br>
     * <b>null</b> дочерний договор не существует<br>
     * <br>
     * <i>array|null</i> 'total_tc' => <b>array</b> если дочерний документ сводного замечания / заключения существует, то
     * результат метода {@see \Tables\Docs\Relations\total_cc::getChildrenById()}<br>
     * <b>null</b> дочернее сводное замечание / заключение не существует<br>
     * <br>
     *
     * @throws DataBaseEx
     * @throws TablesEx
     */
    static public function getChildrenById(int $id_application): array
    {
        $id_type_of_object = doc_application::getIdTypeOfObjectById($id_application);

        $children = [];

        // Договор
        $children['contract'] = null;

        // Сводное замечание / заключение
        if (
            !is_null($id_type_of_object)
            && !is_null($id_total_cc = total_cc::getIdByIdMainDocument($id_application))
        ) {

            $children['total_cc'] = total_cc::getChildrenById($id_total_cc, $id_type_of_object);
        } else {

            $children['total_cc'] = null;
        }

        return [
            'id'                => $id_application,
            'id_type_of_object' => $id_type_of_object,
            'children'          => $children
        ];
    }
}