<?php


use Lib\Files\Mappings\RequiredMappingsSetter;
use Lib\Singles\NodeStructure;
use Classes\Application\Files\Initialization\Initializator;
use Tables\Structures\documentation_1;
use Tables\Structures\documentation_2;



$requiredMappings = new RequiredMappingsSetter();

$requiredMappings->setMappingLevel1(2);

$Initializator = new Initializator($requiredMappings, $_GET['a']);

$structureWithFiles[0] = [];
$structureWithFiles[1] = [];

$needsFiles = $Initializator->getNeedsFilesWithSigns()[2][1];

if (!is_null($needsFiles)) {

    $filesInStructure = Initializator::getFilesInDepthStructure($needsFiles, new NodeStructure(documentation_1::getAllActive()));

    $structureWithFiles[0] = array_filter($filesInStructure, fn($node) => isset($node['files']));
}

$needsFiles = $Initializator->getNeedsFilesWithSigns()[2][2];

if (!is_null($needsFiles)) {

    $filesInStructure = Initializator::getFilesInDepthStructure($needsFiles, new NodeStructure(documentation_2::getAllActive()));

    $structureWithFiles[1] = array_filter($filesInStructure, fn($node) => isset($node['files']));
}

//var_dump($structureWithFiles);

function GetSignColor(array $sign): string
{
    $result = (int)$sign['signature_result'] + (int)$sign['certificate_result'];

    switch ($result) {
        case 0 : return 'red';
        case 1 : return 'orange';
        case 2 : return 'green';
    }
}

?>


<?php foreach ($structureWithFiles as $documentationType => $structure): ?>
    <div style="margin-top: 30px; margin-bottom: 30px;">
        <?php if ($documentationType === 0): ?>
            <span style="font-weight: bold;">Производственные/Непроизводственные</span>
        <?php else: ?>
            <span style="font-weight: bold;">Линейные</span>
        <?php endif; ?>
    </div>

    <?php foreach ($structure as $node): ?>
        <div style="margin-bottom: 15px;">
            <span style="padding-left: <?= $node['depth'] * 25 + 15 ?>px"><?= $node['name'] ?></span>

            <?php foreach ($node['files'] as $file): ?>
                <div>
                    <span style="padding-left: <?= $node['depth'] * 25 + 45 ?>px; font-weight: bold;"><?= $file['file_name'] ?></span>
                </div>

                <?php if (!empty($file['signs']['external'])): ?>
                    <div>
                        <span style="padding-left: <?= $node['depth'] * 25 + 65 ?>px; font-style: italic;">Открепленные подписи:</span>
                    </div>
                    <?php foreach ($file['signs']['external'] as $sign): ?>
                        <div>
                            <span style="padding-left: <?= $node['depth'] * 25 + 85 ?>px; color: <?= GetSignColor($sign) ?>"><?= $sign['fio'] ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (!empty($file['signs']['internal'])): ?>
                    <div>
                        <span style="padding-left: <?= $node['depth'] * 25 + 65 ?>px; font-style: italic;">Встроенные подписи:</span>
                    </div>
                    <?php foreach ($file['signs']['internal'] as $sign): ?>
                        <div>
                            <span style="padding-left: <?= $node['depth'] * 25 + 85 ?>px; color: <?= GetSignColor($sign) ?>"><?= $sign['fio'] ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>
    <?php endforeach; ?>

<?php endforeach; ?>