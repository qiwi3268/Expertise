<?php


namespace Lib\Singles;

use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetEx;
use PhpOffice\PhpSpreadsheet\Writer\Exception as WriterEx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

require ROOT . '/vendor/autoload.php';


/**
 * Класс-обертка для работы с PHPSpreadSheet
 *
 */
class PHPSpreadSheetAddon
{

    private Spreadsheet $spreadSheet;
    private object $activeSheet;


    /**
     * Конструктор класса
     *
     * @throws PhpSpreadsheetEx
     */
    public function __construct()
    {
        $this->spreadSheet = new Spreadsheet();
        $this->activeSheet = $this->spreadSheet->getActiveSheet();
    }


    /**
     * Предназначен для записи массива в активную страницу
     *
     * @param array $array массив (в т.ч. ассоциативный), значения которого будут добавлены на страницу
     * @param string $startCell верхняя левая координата, начиная с которой будет вставлен массив данных
     * @param string|null $nullValue элементы массива с этим значением не будут установлены
     * @throws PhpSpreadsheetEx
     */
    public function writeArray(array $array, string $startCell = 'A1', ?string $nullValue = null): void
    {
        $this->activeSheet->fromArray(
            $array,
            $nullValue,   //
            $startCell
        );
    }

    
    /**
     * Предназначен для выгрузки (отдачи) xlsx файла в браузер
     *
     * @param string $unloadName имя файла для выгрузки, <b>включая расширение 'xlsx'</b>
     * @throws WriterEx
     */
    public function unload(string $unloadName): void
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $unloadName . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($this->spreadSheet, 'Xlsx');
        $writer->save('php://output');
    }
}