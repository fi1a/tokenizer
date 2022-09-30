<?php

declare(strict_types=1);

namespace Fi1a\Unit\Tokenizer\PHP;

use Fi1a\Tokenizer\ITokenizer;
use Fi1a\Tokenizer\PHP\Token;
use Fi1a\Tokenizer\PHP\Tokenizer70;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование для php >= 7.0
 */
class Tokenizer70Test extends TestCase
{
    /**
     * Методы исходного кода
     */
    public function testSource(): void
    {
        $encoding = 'cp1251';
        $utf8 = file_get_contents(__DIR__ . '/Fixtures/base_class_syntax.txt');
        $cp1251 = iconv('UTF-8', $encoding, $utf8);
        $tokenizer = new Tokenizer70($cp1251, $encoding);
        $this->assertEquals($cp1251, $tokenizer->getSource());
        $this->assertEquals($utf8, $tokenizer->getSourceIntEnc());
        $this->assertEquals($encoding, $tokenizer->getEncoding());
    }

    /**
     * Перебор токенов
     */
    public function testIteration(): void
    {
        $tokenizer = new Tokenizer70(file_get_contents(__DIR__ . '/Fixtures/base_class_syntax.txt'));
        $iCount = 0;
        while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
            $this->assertTrue(is_numeric($token->getType()));
            $this->assertEquals($token->getType(), $tokenizer->peekType());
            $iCount++;
        }
        $this->assertEquals($iCount, $tokenizer->getCount());
        while (($token = $tokenizer->prev()) !== ITokenizer::T_BOF) {
            $this->assertTrue(is_numeric($token->getType()));
            $this->assertEquals($token->getType(), $tokenizer->peekType());
            $iCount--;
        }
        $iCount--;
        $this->assertEquals(0, $iCount);

        $token = $tokenizer->current();
        $this->assertTrue(is_numeric($token->getType()));
        $this->assertEquals($token->getType(), $tokenizer->peekType());
    }

    /**
     * Перебор типов токенов
     */
    public function testPeekIteration(): void
    {
        $tokenizer = new Tokenizer70(file_get_contents(__DIR__ . '/Fixtures/base_class_syntax.txt'));
        while (($type = $tokenizer->peekNextType()) !== ITokenizer::T_EOF) {
            $this->assertEquals($tokenizer->peekType(), $type);
        }
        while (($type = $tokenizer->peekPrevType()) !== ITokenizer::T_BOF) {
            $this->assertEquals($tokenizer->peekType(), $type);
        }
    }

    /**
     * Сравнение изображений с оригиналом
     */
    public function testImage(): void
    {
        $source = file_get_contents(__DIR__ . '/Fixtures/base_class_syntax.txt');
        $tokenizer = new Tokenizer70($source);
        $grid = new TextGrid($source);
        while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
            $this->assertEquals(
                $grid->get(
                    $token->getStartLine(),
                    $token->getStartColumn(),
                    $token->getEndLine(),
                    $token->getEndColumn()
                ),
                $token->getImage(),
                'начало ' . $token->getStartLine() . ':' . $token->getStartColumn()
                . ' конец ' . $token->getEndLine() . ':' . $token->getEndColumn()
            );
        }
    }

    /**
     * Возврат значений
     */
    public function testLookAt(): void
    {
        $source = file_get_contents(__DIR__ . '/Fixtures/base_class_syntax.txt');
        $tokenizer = new Tokenizer70($source);
        $this->assertEquals(ITokenizer::T_BOF, $tokenizer->lookAtPrev(1));
        $this->assertEquals(ITokenizer::T_BOF, $tokenizer->lookAtPrevType(1));
        $this->assertEquals(ITokenizer::T_BOF, $tokenizer->lookAtPrevImage(1));
        $this->assertEquals(Token::T_OPEN_TAG, $tokenizer->lookAtNext(1)->getType());
        $this->assertEquals(Token::T_OPEN_TAG, $tokenizer->lookAtNextType(1));
        $this->assertTrue(is_string($tokenizer->lookAtNextImage(1)));
        while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
            $token;
        }
        $this->assertEquals(Token::T_UNKNOWN_TOKEN_TYPE, $tokenizer->lookAtPrev(1)->getType());
        $this->assertEquals(Token::T_UNKNOWN_TOKEN_TYPE, $tokenizer->lookAtPrevType(1));
        $this->assertTrue(is_string($tokenizer->lookAtPrevImage(1)));
        $this->assertEquals(ITokenizer::T_EOF, $tokenizer->lookAtNextType(1));
        $this->assertEquals(ITokenizer::T_EOF, $tokenizer->lookAtNextType(1));
        $this->assertEquals(ITokenizer::T_EOF, $tokenizer->lookAtNextImage(1));
    }
}
