<?php

use Lib\Singles\NodeStructure;
use Tables\Structures\documentation_1;


$nodeStructure = new NodeStructure(documentation_1::getAllActive());
$depthNodeStructure = $nodeStructure->getDepthStructure();

//var_dump($depthNodeStructure);

?>

<?php foreach ($depthNodeStructure as $node): ?>
    <div style="margin-bottom: 15px;">

        <?php if ($node['is_header']): ?>

            <span style="padding-left: <?= $node['depth'] * 25 + 15 ?>px; color: #6A7E9A;">
                <?= $node['name'] ?>
            </span>

        <?php else: ?>

            <span style="padding-left: <?= $node['depth'] * 25 + 15 ?>px">
                <?= $node['name'] ?>
            </span>

        <?php endif; ?>
    </div>
<?php endforeach; ?>





