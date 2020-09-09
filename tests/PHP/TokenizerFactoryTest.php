<?php

declare(strict_types=1);

namespace Fi1a\Unit\Tokenizer\PHP;

use Fi1a\Tokenizer\ITokenizer;
use Fi1a\Tokenizer\PHP\TokenizerFactory;
use PHPUnit\Framework\TestCase;

/**
 * Тест фабрики TokenizerFactory
 */
class TokenizerFactoryTest extends TestCase
{
    /**
     * Возвращает экземпляр класса реализующего интерфейс ITokenizer
     */
    public function testFactory(): void
    {
        $this->assertInstanceOf(
            ITokenizer::class,
            TokenizerFactory::factory(file_get_contents(__DIR__ . '/Fixtures/base_class_syntax.txt'))
        );
    }
}
