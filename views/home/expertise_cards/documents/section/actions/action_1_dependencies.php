<?php
$_dependencies = new \Classes\Section\Actions\HtmlDependenciesManagerAction1();
$_blockDependencies = json_encode($_dependencies->getBlockDependencies());
?>

<input id="block_dependencies" type="hidden" value='<?= $_blockDependencies ?>'>