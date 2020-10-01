<?php


namespace Classes\TotalCC\Actions;

use Lib\Exceptions\Form as SelfEx;
use functions\Exceptions\Functions as FunctionsEx;

use Lib\Form\DefaultFormParametersCreator;


/**
 * Предназначен для получения параметров по умолчанию для html-формы
 * в действии "Редактировать общую часть"
 *
 */
class DefaultFormParametersAction1 extends DefaultFormParametersCreator
{

    /**
     * Реализация абстрактного метода
     *
     * @return array
     * @throws SelfEx
     * @throws FunctionsEx
     */
    public function getDefaultParameters(): array
    {
        $result = [];

        // Сведения о заявителе
        // ad - applicant details
        $ad = getArrayWithReplacedNullValues($this->getResult('applicant_details')[0], '');

        // Источники финансирования
        // fs - financing sources
        $fs = getArrayWithReplacedNullValues($this->getResult('financing_sources')[0], '');

        $result['applicant_details'] = $ad;
        $result['financing_sources'] = $fs;

        return $result;
    }
}