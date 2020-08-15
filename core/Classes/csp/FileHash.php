<?php


namespace csp;


class FileHash{
    
    
    // Предназначен для получения hash-файла, сгенерированного на основе
    // Принимает параметры-----------------------------------
    // hashDir  string : абсолютный путь в ФС сервера, куда будет сохранен результирующий hash-файл
    // hashAlg  string : алгоритм hash'ирования
    // filePath string : абсолютный путь в ФС сервера к исходному файлу
    // Возвращает параметры----------------------------------
    // string  : вывод исполняемой команды
    //
    public function execHash(string $hashDir, string $hashAlg, string $filePath):string {
        // -dir      : абсолютный путь в ФС сервера, куда будет сохранен результирующий hash-файл
        // -provtype : тип криптопровайдера
        // -hashAlg  : алгоритм hash'ирования
        // -hex      : сохранить хэш файла в виде шестнадцатеричной строки
        $cmd = sprintf('%s -hash -dir "%s" -provtype 80 -hashAlg "%s" -hex "%s" 2>&1', \Shell::CPROCSP, $hashDir, $hashAlg, $filePath);
 
        return \Shell::exec($cmd);
    }
}