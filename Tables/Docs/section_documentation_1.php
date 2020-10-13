<?php


namespace Tables\Docs;

use Tables\Docs\Interfaces\Document;
use Tables\Docs\Interfaces\Responsible;
use Tables\CommonInterfaces\Existent;

use Tables\Docs\Traits\SectionTable as SectionTableTrait;
use Tables\Docs\Traits\Document as DocumentTrait;
use Tables\Docs\Traits\Responsible as ResponsibleTrait;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'doc_section_documentation_1'</i>
 *
 */
final class section_documentation_1 implements Document, Existent, Responsible
{

    static private string $tableName = 'doc_section_documentation_1';
    static private string $stageTableName = 'stage_section_documentation_1';
    static private string $mainBlock341TableName = 'main_block_341_documentation_1';

    use SectionTableTrait;
    use DocumentTrait;
    use ExistentTrait;
    use ResponsibleTrait;
}