<?php


namespace Lib\Singles;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


//use PhpSpreadsheet\Reader\IReadFilter;
//use PhpSpreadsheet\Style\Fill;


require ROOT . '/vendor/autoload.php';

// Класс-обертка для работы с PHPSpreadSheet
//
class PHPSpreadSheetAddon
{

    private Spreadsheet $spreadSheet;
    private object $activeSheet;

    public function __construct()
    {
        $this->spreadSheet = new Spreadsheet();
        $this->activeSheet = $this->spreadSheet->getActiveSheet();
    }


    // Предназначен для записи массива в активную страницу
    // Принимает параметры------------------------------------
    //            array : массив (в т.ч. ассоциативный), значения которого будут добавлены на страницу
    // startCell string : верхняя левая координата, начиная с которой будет вставлен массив данных
    //
    public function writeArray(array $array, string $startCell = 'A1', ?string $nullValue = NULL)
    {
        $this->activeSheet->fromArray(
            $array,
            $nullValue,   // Элементы массива с этим значением не будут установлены
            $startCell
        );
    }


    // Предназначен для выгрузки (отдачи) xlsx файла в браузер
    // Принимает параметры------------------------------------
    // unloadName string : имя файла для выгрузки, включая расширение 'xlsx'
    //
    public function unload(string $unloadName)
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $unloadName . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($this->spreadSheet, 'Xlsx');
        $writer->save('php://output');
    }
}


$spreadsheet = new PHPSpreadSheetAddon();
$spreadsheet->writeArray([123, 234, 345]);
$spreadsheet->unload('hey.xlsx');
//$sheet = $spreadsheet->getActiveSheet();

/*
$sheet->setCellValue('A1', 'Hello World');


$writer = new Xlsx($spreadsheet);
//$writer->save('hello world.xlsx');

//Редирект вывода на клиентсткий браузер
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=lalal.xlsx");
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

*/



