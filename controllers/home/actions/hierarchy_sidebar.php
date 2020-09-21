<?php

use Tables\Docs\Relations\HierarchyTree;

$hierarchyTree = new HierarchyTree(CURRENT_DOCUMENT_TYPE, CURRENT_DOCUMENT_ID);
$tree = $hierarchyTree->getTree();
var_dump($tree);

