<?php

use core\Classes\Session;
use Exception as SelfEx;
use Lib\Responsible\Responsible;
use Lib\Singles\PrimitiveValidator;
use Tables\user;
use Tables\people_name;
use Tables\Docs\application;
use Tables\Responsible\type_3\application as resp_application_type_3;
use Tables\applicant_access_group;
use Tables\Actions\application as ApplicationActions;
use core\Classes\RoutesXMLHandler;
use Tables\Structures\documentation_1;
use Lib\Singles\Helpers\PageAddress;
use Tables\assigned_expert_total_cc;

use Tables\Helpers\Helper;
use Lib\DataBase\Transaction;
use Tables\test;
use Tables\Locators\DocumentTypeTableLocator;
use Classes\Application\Files\Initialization\DocumentationFilesFacade;
use Lib\Miscs\Validation\SingleMisc;
use Lib\Actions\ExecutionActionsResult;

$comments = [
    ['id'=> 1, 'files' => [1]],
    ['id'=> 2, 'files' => [1,2]],
    ['id'=> 4, 'files' => [3,4]],
    ['id'=> 5, 'files' => [5]],
];


$groups = calculateGroups($comments);
vd($groups);


function calculateGroups(array $comments): array
{
    if(count($comments) != count(compressUniquenessArrayValuesByKey($comments, 'id'))) {
        throw new LogicException("В массиве с замечаниями имеются повторяющиеся id");
    }

    $fileGroups = [];

    // Массив индексов замечаний без прикрепленного файла
    $commentsWithoutFiles = [];

    // Формирование файловых групп
    foreach ($comments as $commentIndex => $comment) {

        // Проверка отсутствия прикрепленных файлов к замечанию
        if (is_null($comment['files'])) {

            $commentsWithoutFiles[] = $commentIndex;
            continue;
        }

        $attachmentFileIds = [];

        // Массив индексов групп, в которые есть вхождения файлов текущего итерируемого замечания
        $entryIndexes = [];

        foreach ($comment['files'] as $fileId) {

            foreach ($fileGroups as $groupIndex => $groupFileIds) {

                // До этого не было вхождения предыдущего прикрепленного файла в эту же группу
                if (in_array($groupIndex, $entryIndexes)) {
                    continue;
                }

                foreach ($groupFileIds as $groupFileId) {

                    // Индекс итерируемого файла к замечанию принадлежит группе
                    if ($fileId == $groupFileId) {
                        $entryIndexes[] = $groupIndex;
                        break 2;
                    }
                }
            }
            $attachmentFileIds[] = $fileId;
        }

        if (empty($entryIndexes)) {

            // Вхождения в другие группы отсутсуют, создаем новую
            $fileGroups[] = $attachmentFileIds;
        } else {

            $newGroup = [];

            foreach ($entryIndexes as $index) {

                $newGroup = [...$newGroup, ...$fileGroups[$index]];
                unset($fileGroups[$index]);
            }
            $fileGroups[] = [...$newGroup, ...array_diff($attachmentFileIds, $newGroup)];
        }
    }

    $groupCount = 0;

    // Результирующий массив
    $commentGroups = [];

    // Отдельная обработка для замечаний без прикрепленных файлов
    foreach ($commentsWithoutFiles as $commentIndex) {

        $commentGroups[$groupCount] = $comments[$commentIndex]['id'];
        unset($comments[$commentIndex]);
        $groupCount++;
    }
    unset($comment);

    // Формирование групп замечаний
    foreach ($fileGroups as $fileGroup) {

        foreach ($fileGroup as $group_fileId) {

            foreach ($comments as $commentIndex => $comment) {

                foreach ($comment['files'] as $comment_fileId) {

                    if ($comment_fileId == $group_fileId) {

                        $commentGroups[$groupCount][] = $comment['id'];
                        unset($comments[$commentIndex]);
                        break;
                    }
                }
            }
        }
        $groupCount++;
    }
    return $commentGroups;
}