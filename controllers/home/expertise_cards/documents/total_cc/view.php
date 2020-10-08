<?php


use Lib\Singles\TemplateMaker;
use Tables\FinancingSources\FinancingSourcesAggregator;


$financingSourcesAggregator = new FinancingSourcesAggregator(
    FinancingSourcesAggregator::COMMON_PART_TABLE_TYPE,
    CURRENT_DOCUMENT_ID
);

TemplateMaker::registration(
    'view_financing_sources',
    TemplateMaker::HOME_WITH_DATA_VIEW . 'financing_sources.php',
    [
        'financing_sources' => $financingSourcesAggregator->getFinancingSources()
    ]
);