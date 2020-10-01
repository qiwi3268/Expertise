<?php
    $_dependencies = new \Classes\TotalCC\Actions\DisplayDependenciesAction1();
    $_blockDependencies = json_encode($_dependencies->getBlockDependencies());
    $_requireDependencies = json_encode($_dependencies->getRequireDependencies());
?>
<input id="block_dependencies" type="hidden" value='<?= $_blockDependencies ?>'>
<input id="require_dependencies" type="hidden" value='<?= $_requireDependencies ?>'>


