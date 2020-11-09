<?php


namespace APIControllers\Home\FileUploader\Types;
use APIControllers\Home\FileUploader\Uploader;


class Type1 extends Uploader
{

    /**
     * Реализация абстрактного метода
     *
     */
    function getRequiredParams(): array
    {
        return [];
    }

    /**
     * Реализация абстрактного метода
     *
     */
    function initializeProperties(): void
    {
        return;
    }
}