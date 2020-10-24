<?php


namespace test;






class ApplicationFileUploader extends DocumentationFileUploader
{

    public function __construct(int $applicationId)
    {
        $this->inputName = 'download_files';

        parent::__construct($applicationId);
    }
}