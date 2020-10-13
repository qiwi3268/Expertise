<?php


namespace Tables\Docs;

use Tables\Docs\Interfaces\Document;
use Tables\Docs\Interfaces\Responsible;
use Tables\CommonInterfaces\Existent;

use Tables\Docs\Traits\CommentTable as CommentTableTrait;
use Tables\Docs\Traits\Document as DocumentTrait;
use Tables\Docs\Traits\Responsible as ResponsibleTrait;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'doc_comment_documentation_1'</i>
 *
 */
final class comment_documentation_1 implements Document, Existent, Responsible
{

    static private string $tableName = 'doc_comment_documentation_1';
    static private string $stageTableName = 'stage_comment_documentation_1';

    use CommentTableTrait;
    use DocumentTrait;
    use ExistentTrait;
    use ResponsibleTrait;
}