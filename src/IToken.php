<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer;

/**
 * Интерфейс токена
 */
interface IToken
{
    /**
     * Конструктор нового токена
     *
     * @param int $type тип токена
     * @param string  $image изображение
     * @param int $startLine номер первой строки
     * @param int $endLine номер последней строки
     * @param int $startColumn номер символа в первой строке
     * @param int $endColumn номер символа в последней строке
     */
    public function __construct(
        int $type,
        string $image,
        int $startLine,
        int $endLine,
        int $startColumn,
        int $endColumn
    );

    /**
     * Возвращает тип токена
     */
    public function getType(): int;

    /**
     * "Изображение" токена
     */
    public function getImage(): string;

    /**
     * Возвращает номер первой строки
     */
    public function getStartLine(): int;

    /**
     * Возвращает номер последне строки
     */
    public function getEndLine(): int;

    /**
     * Возвращает номер символа в первой строке
     */
    public function getStartColumn(): int;

    /**
     * Возвращает номер символа в последней строке
     */
    public function getEndColumn(): int;
}
