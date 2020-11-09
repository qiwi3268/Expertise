<?php


namespace APIControllers\Home\FileUploader\Types;
use APIControllers\Home\FileUploader\Uploader;


class Type2 extends Uploader
{

    /**
     * Реализация абстрактного метода
     *
     */
    function getRequiredParams(): array
    {
        return ['id_structure_node'];
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