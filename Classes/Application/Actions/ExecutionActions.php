<?php


namespace Classes\Application\Actions;
use Lib\Exceptions\Actions as SelfEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Lib\Actions\ExecutionActions as MainExecutionActions;


class ExecutionActions extends MainExecutionActions
{

    // Ошибкам во время исполнения действия необходимо присваивать code 6

    public function action_1(): string
    {
        return true;
    }

    /**
     * Действие <i>"Назначить экспертов"</i>
     *
     * @return string
     * @throws SelfEx
     */
    public function action_2(): string
    {

        // Декодирование json'а
        //
        try {
            $experts = $this->primitiveValidator->getAssocArrayFromJson($this->getRequiredPOSTParameter('experts'));
        } catch (PrimitiveValidatorEx $e) {
            throw new SelfEx("Произошла ошибка при декодировании входной json-строки с назначенными экспертами : {$e->getMessage()}", 6);
        }

        // Валидация входного массива
        //
        foreach ($experts as $expert) {

            try {

                $this->primitiveValidator->validateAssociativeArray($expert, [
                    'id_expert'          => [[$this->primitiveValidator, 'validateInt']],
                    'lead'               => ['is_bool'],
                    'common_part'        => ['is_bool'],
                    'ids_main_block_341' => ['is_array']
                ]);

                foreach ($expert['ids_main_block_341'] as $id) {
                    $this->primitiveValidator->validateInt($id);
                }
            } catch (PrimitiveValidatorEx $e) {

                throw new SelfEx("Произошла ошибка при валидации массива с назначенными экспертами : {$e->getMessage()}", 6);
            }
        }




        return 'todo';
    }
}