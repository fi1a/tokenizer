<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer\PHP;

use Fi1a\Tokenizer\ITokenizer;
use Fi1a\Tokenizer\ITokenizerFactory;

/**
 * Фабрика лексического анализатора PHP
 */
class TokenizerFactory implements ITokenizerFactory
{
    /**
     * @inheritDoc
     */
    public static function factory(string $source, array $options = []): ITokenizer
    {
        return new Tokenizer70($source);
    }
}
