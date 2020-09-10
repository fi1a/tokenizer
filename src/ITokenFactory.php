<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer;

/**
 * Интерфейс фабрики токенов
 */
interface ITokenFactory
{
    /**
     * Фабрика классов токенов
     *
     * @param int    $type        тип токена
     * @param string $image       изображение
     * @param int    $startLine   номер первой строки
     * @param int    $endLine     номер последней строки
     * @param int    $startColumn номер символа в первой строке
     * @param int    $endColumn   номер символа в последней строке
     */
    public static function factory(
        int $type,
        string $image,
        int $startLine,
        int $endLine,
        int $startColumn,
        int $endColumn
    ): IToken;
}
