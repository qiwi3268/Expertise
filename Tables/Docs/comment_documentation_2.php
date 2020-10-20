<?php


namespace Tables\Docs;

use Tables\Docs\Interfaces\ChildDocument;
use Tables\Docs\Interfaces\Responsible;
use Tables\CommonInterfaces\Existent;

use Tables\Docs\Traits\CommentTable as CommentTableTrait;
use Tables\Docs\Traits\ChildDocument as ChildDocumentTrait;
use Tables\Docs\Traits\Responsible as ResponsibleTrait;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'doc_comment_documentation_2'</i>
 *
 */
final class comment_documentation_2 implements ChildDocument, Existent, Responsible
{

    static private string $tableName = 'doc_comment_documentation_2';
    static private string $stageTableName = 'stage_comment_documentation_2';
    static private string $documentationTableName = 'file_documentation_2';

    use CommentTableTrait;
    use ChildDocumentTrait;
    use ExistentTrait;
    use ResponsibleTrait;
}