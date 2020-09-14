<?php


namespace Lib\CSP;


/**
 * Предназначен для получения hash-файла для исходного файла
 *
 */
class FileHash
{

    /**
     * Предназначен для получения hash-файла
     *
     * @param string $hashDir абсолютный путь в ФС сервера, куда будет сохранен результирующий hash-файл
     * @param string $hashAlg алгоритм hash'ирования
     * @param string $filePath абсолютный путь в ФС сервера к исходному файлу
     * @return string вывод исполняемой команды
     * @throws \Lib\Exceptions\Shell
     */
    public function execHash(string $hashDir, string $hashAlg, string $filePath): string
    {
        // -dir      : абсолютный путь в ФС сервера, куда будет сохранен результирующий hash-файл
        // -provtype : тип криптопровайдера
        // -hashAlg  : алгоритм hash'ирования
        // -hex      : сохранить хэш файла в виде шестнадцатеричной строки
        $cmd = sprintf('%s -hash -dir "%s" -provtype 80 -hashAlg "%s" -hex "%s" 2>&1', Shell::CPROCSP, $hashDir, $hashAlg, $filePath);

        return Shell::exec($cmd);
    }
}