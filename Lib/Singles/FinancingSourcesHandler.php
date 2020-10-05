<?php


namespace Lib\Singles;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Lib\Exceptions\MiscValidator as MiscValidatorEx;

use Lib\Miscs\Validation\SingleMisc;


/**
 * Предназначен для обработки JSON'а с источниками финансирования
 *
 */
final class FinancingSourcesHandler
{

    /**
     * Декодированный из json-строки массив с источниками финансирования
     *
     */
    private array $financingSources;

    private PrimitiveValidator $validator;


    /**
     * Конструктор класса
     *
     * @param string $financingSourcesJson входной json
     * @throws PrimitiveValidatorEx
     */
    public function __construct(string $financingSourcesJson)
    {
        $validator = new PrimitiveValidator();

        $this->financingSources = $validator->getAssocArrayFromJson($financingSourcesJson);
        $this->validator = $validator;
    }


    /**
     * Предназначен для валидации массива с источниками финансирования
     *
     * @throws PrimitiveValidatorEx
     * @throws MiscValidatorEx
     * @throws DataBaseEx
     */
    public function validateArray(): void
    {
        foreach ($this->financingSources as $source) {

            $this->validator->validateSomeInclusions($source['type'], '1', '2', '3', '4');

            switch ($source['type']) {

                case '1' :

                    $budgetLevel = new SingleMisc((is_null($source['budget_level']) ? '' : $source['budget_level']), '\Tables\Miscs\budget_level');

                    $settings = [
                        'budget_level' => ['is_null', [$budgetLevel, 'validate']],
                        'no_data'      => [[$this->validator, 'validateSomeInclusions', null, '1']],
                        'percent'      => ['is_null', [$this->validator, 'validatePercent']]
                    ];
                    break;

                case '2' :

                    $settings = [
                        'full_name' => ['is_null', 'is_string'],
                        'INN'       => ['is_null', [$this->validator, 'validateINN']],
                        'KPP'       => ['is_null', [$this->validator, 'validateKPP']],
                        'OGRN'      => ['is_null', [$this->validator, 'validateOGRN']],
                        'address'   => ['is_null', 'is_string'],
                        'location'  => ['is_null', 'is_string'],
                        'telephone' => ['is_null', 'is_string'],
                        'email'     => ['is_null', [$this->validator, 'validateEmail']],
                        'no_data'   => [[$this->validator, 'validateSomeInclusions', null, '1']],
                        'percent'   => ['is_null', [$this->validator, 'validatePercent']]
                    ];
                    break;

                case '3' :
                case '4' :

                    $settings = [
                        'no_data' => [[$this->validator, 'validateSomeInclusions', null, '1']],
                        'percent' => ['is_null', [$this->validator, 'validatePercent']]
                    ];
                    break;
            }
            $this->validator->validateAssociativeArray($source, $settings);
        }
    }


    /**
     * Предназначен для получения массива с источниками финансирования
     *
     * @return array
     */
    public function getArray(): array
    {
        return $this->financingSources;
    }
}