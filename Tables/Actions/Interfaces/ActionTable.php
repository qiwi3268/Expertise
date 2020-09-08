<?php


namespace Tables\Actions\Interfaces;


// Интерфейс для валидации зависимых справочников
//
interface ActionTable
{

    // Предназначен для получения ассоциативных массивов активных действий
    //
    static public function getAllActive(): array;

    // Предназначен для получения ассоциативного массива действия по имени страницы
    //
    static public function getAssocByPageName(string $pageName): ?array;

    // Предназначен для получения ассоциативного массива действия по hash'у
    //
    static public function getAssocByHash(string $hash): ?array;

    // Предназначен для получения ассоциативного массива данных бизнесс-процесса, необходимых для работы callback-методов
    //
    static public function getAssocBusinessProcessById(int $id): array;
}