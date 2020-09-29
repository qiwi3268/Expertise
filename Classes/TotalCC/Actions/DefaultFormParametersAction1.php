<?php


namespace Classes\TotalCC\Actions;

use Lib\Exceptions\DefaultFormParameters as SelfEx;
use Lib\DefaultFormParameters\DefaultFormParametersCreator;


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
     */
    public function getDefaultParameters(): array
    {
        $result = [];

        // Сведения о заявителе
        // ad - applicant details
        $ad = $this->getResult('applicantDetails')[0];

        $result['applicantDetails'] = [
            'full_name' => $ad['full_name'] ?? '',
            'INN'       => $ad['INN'] ?? '',
            'KPP'       => $ad['KPP'] ?? '',
            'OGRN'      => $ad['OGRN'] ?? '',
            'address'   => $ad['address'] ?? '',
            'location'  => $ad['location'] ?? '',
            'email'     => $ad['email'] ?? '',
            'director'  => $ad['director'] ?? '',
        ];

        // Источники финансирования
        // fs - financing sources

        $fs = $this->getResult('financingSources');

        $result['financingSources'] = [
            $fs
        ];

        return $result;
    }
}