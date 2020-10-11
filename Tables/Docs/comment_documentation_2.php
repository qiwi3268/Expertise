<?php


namespace Tables\Docs;

use Tables\Docs\Interfaces\Document;
use Tables\Docs\Interfaces\Responsible;
use Tables\CommonInterfaces\Existent;

use Tables\Docs\Traits\Document as DocumentTrait;
use Tables\Docs\Traits\Responsible as ResponsibleTrait;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'doc_comment_documentation_2'</i>
 *
 */
final class comment_documentation_2 implements Document, Existent, Responsible
{

    static private string $tableName = 'doc_comment_documentation_2';
    static private string $stageTableName = 'stage_comment_documentation_2';

    use Traits\CommentTable;
    use DocumentTrait;
    use ExistentTrait;
    use ResponsibleTrait;
}