<?php


namespace Tables\Docs;

use Tables\Docs\Interfaces\ChildDocument;
use Tables\CommonInterfaces\Existent;

use Tables\Docs\Traits\GroupTable as GroupTableTrait;
use Tables\Docs\Traits\ChildDocument as ChildDocumentTrait;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'doc_group_documentation_2'</i>
 *
 */
final class group_documentation_2 implements ChildDocument, Existent
{

    static private string $tableName = 'doc_group_documentation_2';
    static private string $stageTableName = 'stage_group_documentation_2';

    use GroupTableTrait;
    use ChildDocumentTrait;
    use ExistentTrait;
}