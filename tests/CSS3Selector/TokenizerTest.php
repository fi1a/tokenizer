<?php

declare(strict_types=1);

namespace Fi1a\Unit\Tokenizer\CSS3Selector;

use Fi1a\Tokenizer\CSS3Selector\Token;
use Fi1a\Tokenizer\CSS3Selector\Tokenizer;
use Fi1a\Tokenizer\ITokenizer;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование парсинга CSS3 селектора
 */
class TokenizerTest extends TestCase
{
    /**
     * Провайдер данных для теста testTokenizer
     *
     * @return mixed[]
     */
    public function dataProviderTokenizer(): array
    {
        return [
            [
                '',
                0,
                [],
                [],
            ],
            [
                'div',
                1,
                ['div'],
                [Token::T_TAG],
            ],
            [
                "div div\n\ndiv\tdiv",
                7,
                ['div', ' ', 'div', "\n\n", 'div', "\t", 'div'],
                [
                    Token::T_TAG,
                    Token::T_WHITE_SPACE,
                    Token::T_TAG,
                    Token::T_WHITE_SPACE,
                    Token::T_TAG,
                    Token::T_WHITE_SPACE,
                    Token::T_TAG,
                ],
            ],
            [
                'div.e-class1.m_class2 .b-class3',
                5,
                ['div', '.e-class1', '.m_class2', ' ', '.b-class3',],
                [Token::T_TAG, Token::T_CLASS, Token::T_CLASS, Token::T_WHITE_SPACE, Token::T_CLASS,],
            ],
            [
                'div[name][data-rel]',
                7,
                ['div', '[', 'name', ']', '[', 'data-rel', ']'],
                [
                    Token::T_TAG,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_CLOSE_ATTRIBUTE,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_CLOSE_ATTRIBUTE,
                ],
            ],
            [
                'div[name="some_name"][data-rel=1]',
                13,
                ['div', '[', 'name', '=', '"', 'some_name', '"', ']', '[', 'data-rel', '=', '1', ']'],
                [
                    Token::T_TAG,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_EQUAL,
                    Token::T_QUOTE,
                    Token::T_ATTRIBUTE_VALUE,
                    Token::T_QUOTE,
                    Token::T_CLOSE_ATTRIBUTE,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_EQUAL,
                    Token::T_ATTRIBUTE_VALUE,
                    Token::T_CLOSE_ATTRIBUTE,
                ],
            ],
            [
                'div[name^="some_name"]',
                8,
                ['div', '[', 'name', '^=', '"', 'some_name', '"', ']',],
                [
                    Token::T_TAG,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_BEGINNING_EXACTLY,
                    Token::T_QUOTE,
                    Token::T_ATTRIBUTE_VALUE,
                    Token::T_QUOTE,
                    Token::T_CLOSE_ATTRIBUTE,
                ],
            ],
            [
                'div[name!="some_name"]',
                8,
                ['div', '[', 'name', '!=', '"', 'some_name', '"', ']',],
                [
                    Token::T_TAG,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_NOT_EQUAL,
                    Token::T_QUOTE,
                    Token::T_ATTRIBUTE_VALUE,
                    Token::T_QUOTE,
                    Token::T_CLOSE_ATTRIBUTE,
                ],
            ],
            [
                'div[name$="some_name"]',
                8,
                ['div', '[', 'name', '$=', '"', 'some_name', '"', ']',],
                [
                    Token::T_TAG,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_ENDING_EXACTLY,
                    Token::T_QUOTE,
                    Token::T_ATTRIBUTE_VALUE,
                    Token::T_QUOTE,
                    Token::T_CLOSE_ATTRIBUTE,
                ],
            ],
            [
                'div[name~="some_name"]',
                8,
                ['div', '[', 'name', '~=', '"', 'some_name', '"', ']',],
                [
                    Token::T_TAG,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_CONTAINING_WORD,
                    Token::T_QUOTE,
                    Token::T_ATTRIBUTE_VALUE,
                    Token::T_QUOTE,
                    Token::T_CLOSE_ATTRIBUTE,
                ],
            ],
            [
                'div[name*="some_name"]',
                8,
                ['div', '[', 'name', '*=', '"', 'some_name', '"', ']',],
                [
                    Token::T_TAG,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_CONTAINING,
                    Token::T_QUOTE,
                    Token::T_ATTRIBUTE_VALUE,
                    Token::T_QUOTE,
                    Token::T_CLOSE_ATTRIBUTE,
                ],
            ],
            [
                'div[name|="some_name"]',
                8,
                ['div', '[', 'name', '|=', '"', 'some_name', '"', ']',],
                [
                    Token::T_TAG,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_EITHER_EQUAL,
                    Token::T_QUOTE,
                    Token::T_ATTRIBUTE_VALUE,
                    Token::T_QUOTE,
                    Token::T_CLOSE_ATTRIBUTE,
                ],
            ],
            [
                'div[name |= "some_name"]',
                10,
                ['div', '[', 'name', ' ', '|=', ' ', '"', 'some_name', '"', ']',],
                [
                    Token::T_TAG,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_WHITE_SPACE,
                    Token::T_EITHER_EQUAL,
                    Token::T_WHITE_SPACE,
                    Token::T_QUOTE,
                    Token::T_ATTRIBUTE_VALUE,
                    Token::T_QUOTE,
                    Token::T_CLOSE_ATTRIBUTE,
                ],
            ],
            [
                '* div',
                3,
                ['*', ' ', 'div',],
                [
                    Token::T_TAG,
                    Token::T_WHITE_SPACE,
                    Token::T_TAG,
                ],
            ],
            [
                'div > a',
                5,
                ['div', ' ', '>', ' ', 'a',],
                [
                    Token::T_TAG,
                    Token::T_WHITE_SPACE,
                    Token::T_DIRECT_CHILD,
                    Token::T_WHITE_SPACE,
                    Token::T_TAG,
                ],
            ],
            [
                'div ~ a',
                5,
                ['div', ' ', '~', ' ', 'a',],
                [
                    Token::T_TAG,
                    Token::T_WHITE_SPACE,
                    Token::T_SIBLING_AFTER,
                    Token::T_WHITE_SPACE,
                    Token::T_TAG,
                ],
            ],
            [
                'div + a',
                5,
                ['div', ' ', '+', ' ', 'a',],
                [
                    Token::T_TAG,
                    Token::T_WHITE_SPACE,
                    Token::T_SIBLING_NEXT,
                    Token::T_WHITE_SPACE,
                    Token::T_TAG,
                ],
            ],
            [
                'div + #id',
                5,
                ['div', ' ', '+', ' ', '#id',],
                [
                    Token::T_TAG,
                    Token::T_WHITE_SPACE,
                    Token::T_SIBLING_NEXT,
                    Token::T_WHITE_SPACE,
                    Token::T_ID,
                ],
            ],
            [
                'div:pseudoclass',
                2,
                ['div', ':pseudoclass',],
                [
                    Token::T_TAG,
                    Token::T_PSEUDO,
                ],
            ],
            [
                'div:pseudoclass("value")',
                7,
                ['div', ':pseudoclass', '(', '"', 'value', '"', ')',],
                [
                    Token::T_TAG,
                    Token::T_PSEUDO,
                    Token::T_OPEN_BRACKET,
                    Token::T_QUOTE,
                    Token::T_PSEUDO_VALUE,
                    Token::T_QUOTE,
                    Token::T_CLOSE_BRACKET,
                ],
            ],
            [
                'div:pseudoclass()',
                4,
                ['div', ':pseudoclass', '(', ')',],
                [
                    Token::T_TAG,
                    Token::T_PSEUDO,
                    Token::T_OPEN_BRACKET,
                    Token::T_CLOSE_BRACKET,
                ],
            ],
            [
                'a, div:pseudoclass()',
                7,
                ['a', ',', ' ', 'div', ':pseudoclass', '(', ')',],
                [
                    Token::T_TAG,
                    Token::T_MULTIPLE_SELECTOR,
                    Token::T_WHITE_SPACE,
                    Token::T_TAG,
                    Token::T_PSEUDO,
                    Token::T_OPEN_BRACKET,
                    Token::T_CLOSE_BRACKET,
                ],
            ],

            [
                'div[name="some_\"name"]',
                8,
                ['div', '[', 'name', '=', '"', 'some_\"name', '"', ']',],
                [
                    Token::T_TAG,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_EQUAL,
                    Token::T_QUOTE,
                    Token::T_ATTRIBUTE_VALUE,
                    Token::T_QUOTE,
                    Token::T_CLOSE_ATTRIBUTE,
                ],
            ],
            [
                'div[name=\'some_\\\'name\']',
                8,
                ['div', '[', 'name', '=', '\'', 'some_\\\'name', '\'', ']',],
                [
                    Token::T_TAG,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_EQUAL,
                    Token::T_QUOTE,
                    Token::T_ATTRIBUTE_VALUE,
                    Token::T_QUOTE,
                    Token::T_CLOSE_ATTRIBUTE,
                ],
            ],
            [
                'div[name=\'some_\\\'name\']  ',
                9,
                ['div', '[', 'name', '=', '\'', 'some_\\\'name', '\'', ']', '  ',],
                [
                    Token::T_TAG,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_EQUAL,
                    Token::T_QUOTE,
                    Token::T_ATTRIBUTE_VALUE,
                    Token::T_QUOTE,
                    Token::T_CLOSE_ATTRIBUTE,
                    Token::T_WHITE_SPACE,
                ],
            ],
            [
                'div&div',
                3,
                ['div', '&', 'div',],
                [
                    Token::T_TAG,
                    Token::T_UNKNOWN_TOKEN_TYPE,
                    Token::T_TAG,
                ],
            ],
            [
                'div[name=""]',
                7,
                ['div', '[', 'name', '=', '"', '"', ']',],
                [
                    Token::T_TAG,
                    Token::T_OPEN_ATTRIBUTE,
                    Token::T_ATTRIBUTE,
                    Token::T_EQUAL,
                    Token::T_QUOTE,
                    Token::T_QUOTE,
                    Token::T_CLOSE_ATTRIBUTE,
                ],
            ],
        ];
    }

    /**
     * Парсинг CSS3 селектора
     *
     * @param string[]  $images
     * @param int[]  $types
     *
     * @dataProvider dataProviderTokenizer
     */
    public function testTokenizer(string $command, int $count, array $images, array $types): void
    {
        $tokenizer = new Tokenizer($command);
        $imagesEquals = [];
        $typesEquals = [];
        $image = '';
        while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
            $imagesEquals[] = $token->getImage();
            $typesEquals[] = $token->getType();
            $image .= $token->getImage();
        }

        $this->assertEquals($command, $image);
        $this->assertEquals($images, $imagesEquals);
        $this->assertEquals($types, $typesEquals);
        $this->assertEquals($count, $tokenizer->getCount());
    }
}
