<?php


namespace Tables\Docs;

use Tables\Docs\Interfaces\ChildDocument;
use Tables\Docs\Interfaces\Responsible;
use Tables\CommonInterfaces\Existent;

use Tables\Docs\Traits\SectionTable as SectionTableTrait;
use Tables\Docs\Traits\ChildDocument as ChildDocumentTrait;
use Tables\Docs\Traits\Responsible as ResponsibleTrait;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'doc_section_documentation_2'</i>
 *
 */
final class section_documentation_2 implements ChildDocument, Existent, Responsible
{

    static private string $tableName = 'doc_section_documentation_2';
    static private string $stageTableName = 'stage_section_documentation_2';
    static private string $mainBlock341TableName = 'main_block_341_documentation_2';

    use SectionTableTrait;
    use ChildDocumentTrait;
    use ExistentTrait;
    use ResponsibleTrait;
}