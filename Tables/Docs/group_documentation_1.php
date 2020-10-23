<?php


namespace Tables\Docs;

use Tables\Docs\Interfaces\ChildDocument;
use Tables\CommonInterfaces\Existent;

use Tables\Docs\Traits\GroupTable as GroupTableTrait;
use Tables\Docs\Traits\ChildDocument as ChildDocumentTrait;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'doc_group_documentation_1'</i>
 *
 */
final class group_documentation_1 implements ChildDocument, Existent
{

    static private string $tableName = 'doc_group_documentation_1';
    static private string $stageTableName = 'stage_group_documentation_1';

    use GroupTableTrait;
    use ChildDocumentTrait;
    use ExistentTrait;
}