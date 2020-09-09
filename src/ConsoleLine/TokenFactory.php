<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer\ConsoleLine;

use Fi1a\Tokenizer\IToken;
use Fi1a\Tokenizer\ITokenFactory;

/**
 * Фабрика классов токенов консольной строки
 */
class TokenFactory implements ITokenFactory
{
    /**
     * Фабрика классов токенов консольной строки
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
    ): IToken {
        return new Token($type, $image, $startLine, $endLine, $startColumn, $endColumn);
    }
}
