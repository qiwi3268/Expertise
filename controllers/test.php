<?php


use Lib\Files\Mappings\RequiredMappingsSetter;
use Lib\Singles\NodeStructure;
use Classes\Application\Files\Initialization\Initializator;
use Tables\Structures\documentation_1;


$requiredMappings = new RequiredMappingsSetter();

$requiredMappings->setMappingLevel1(2);

$Initializator = new Initializator($requiredMappings, 22);

$needsFiles = $Initializator->getNeedsFilesWithSigns()[2][1];

$filesInStructure = Initializator::getFilesInDepthStructure($needsFiles, new NodeStructure(documentation_1::getAllActive()));

$structureWithFiles = array_filter($filesInStructure, fn($node) => isset($node['files']));



var_dump($filesInStructure);











?>



<?php foreach ($structureWithFiles as $node): ?>
        <div style="margin-bottom: 15px;">
            <span style="padding-left: <?= $node['depth'] * 25 + 15 ?>px"><?= $node['name'] ?></span>
            <?php foreach ($node['files'] as $file): ?>
                <div>
                    <span style="padding-left: <?= $node['depth'] * 25 + 45 ?>px"><?= $file['file_name'] ?></span>
                </div>

                <div>
                    <span style="padding-left: <?= $node['depth'] * 25 + 65 ?>px">Открепленные подписи:</span>
                </div>
                <?php foreach ($file['signs']['external'] as $sign): ?>
                    <div>
                        <span style="padding-left: <?= $node['depth'] * 25 + 85 ?>px"><?= $sign['fio'] ?></span>
                    </div>
                <?php endforeach; ?>

            <?php endforeach; ?>
        </div>
<?php endforeach; ?>