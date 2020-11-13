<?php


namespace Lib\Logger;


abstract class Logger
{
    public const DATE       = 0;
    public const AUTHOR     = 1;
    public const MESSAGE    = 2;

    public const CSV_DELIMITER = ';';
    public const CSV_ENCLOSURE = '"';
    public const CSV_ESCAPE_CHAR = "\\";
}