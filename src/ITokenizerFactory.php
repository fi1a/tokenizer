<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer;

/**
 * Интерфейс фабричного класса лексического анализатора
 */
interface ITokenizerFactory
{
    /**
     * Возвращает экземпляр класса реализующего интерфейс ITokenizer
     *
     * @param string $source исходный код
     * @param mixed[] $options настройки
     */
    public static function factory(string $source, array $options = []): ITokenizer;
}
