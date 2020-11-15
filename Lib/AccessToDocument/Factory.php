<?php


namespace Lib\AccessToDocument;

use Lib\Exceptions\AccessToDocument as SelfEx;
use ReflectionException;
use ReflectionClass;


/**
 * Фабрика получения экземпляров классов проверки доступа
 * пользователя к документу в зависимости от его типа
 *
 */
class Factory
{

    /**
     * Предназначен для получения экземпляра класса проверки доступа
     *
     * @param string $documentType тип документа
     * @param array $params параметры, передающиеся в конструктор класса проверки доступа
     * @return AccessToDocument экземпляр класса проверки доступа пользователя к документу
     * @throws ReflectionException
     * @throws SelfEx
     */
    public function getObject(string $documentType, array $params): AccessToDocument
    {
        switch ($documentType) {
            case DOCUMENT_TYPE['application'] :
                $class = '\Classes\Application\AccessToDocument\AccessToApplication';
                break;

            case DOCUMENT_TYPE['total_cc'] :
                $class = '\Classes\TotalCC\AccessToDocument\AccessToTotalCC';
                break;

            case DOCUMENT_TYPE['section_documentation_1'] :
            case DOCUMENT_TYPE['section_documentation_2'] :
                $class = '\Classes\Section\AccessToDocument\AccessToSection';
                break;
            default :
                throw new SelfEx('Методу Lib\AccessToDocument\Factory::getObject не удалось распознать тип документа', 1001);
        }

        $reflectionClass = new ReflectionClass($class);

        return $reflectionClass->newInstanceArgs($params);
    }
}