<?php

declare(strict_types=1);

namespace Fi1a\Unit\Tokenizer\PHP;

use Fi1a\Tokenizer\InvalidArgumentException;
use Fi1a\Tokenizer\PHP\Token;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование php токена
 */
class TokenTest extends TestCase
{
    /**
     * Конструктор
     */
    public function testConstruct(): void
    {
        $token = new Token(Token::T_ABSTRACT, 'abstract', 1, 1, 1, 9);
        $this->assertEquals($token->getType(), Token::T_ABSTRACT);
        $this->assertEquals($token->getImage(), 'abstract');
        $this->assertEquals($token->getStartLine(), 1);
        $this->assertEquals($token->getStartColumn(), 1);
        $this->assertEquals($token->getEndLine(), 1);
        $this->assertEquals($token->getEndColumn(), 9);
    }

    /**
     * Исключение при неизвестном типе
     */
    public function testUnknownTypeException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Token(-1000, 'abstract', 1, 1, 1, 9);
    }
}
