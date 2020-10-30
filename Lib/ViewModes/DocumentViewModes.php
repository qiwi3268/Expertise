<?php


namespace Lib\ViewModes;


/**
 * Предназначен для объединения классов доступа к режимам просмотра в общий тип
 *
 */
abstract class DocumentViewModes
{

    /**
     * Режим просмотра по умолчанию, существующий у каждого документа
     *
     * @return bool
     */
    public function view(): bool
    {
        return true;
    }
}