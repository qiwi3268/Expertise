<?php

namespace Lib\AccessToDocuments;


abstract class AccessToDocuments
{

    protected int $documentId;
    protected ?array $businessAssoc = null;


    public function __construct(int $documentId)
    {
        $this->documentId = $documentId;
        $this->setBusinessAssoc();
    }


    abstract public function setBusinessAssoc(): void;

    abstract public function checkAccess(): bool;
}