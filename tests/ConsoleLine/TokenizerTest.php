<?php

declare(strict_types=1);

namespace Fi1a\Unit\Tokenizer\ConsoleLine;

use Fi1a\Tokenizer\ConsoleLine\Token;
use Fi1a\Tokenizer\ConsoleLine\Tokenizer;
use Fi1a\Tokenizer\ITokenizer;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование парсинга консольной строки
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
                'info',
                1,
                ['info'],
                [Token::T_ARGUMENT],
            ],
            [
                'info test',
                3,
                ['info', ' ', 'test'],
                [Token::T_ARGUMENT, Token::T_WHITE_SPACE, Token::T_ARGUMENT],
            ],
            [
                'info\\ test',
                1,
                ['info\\ test'],
                [Token::T_ARGUMENT],
            ],
            [
                'info\\\\ test',
                3,
                ['info\\\\', ' ', 'test'],
                [Token::T_ARGUMENT, Token::T_WHITE_SPACE, Token::T_ARGUMENT],
            ],
            [
                '--colors',
                1,
                ['--colors'],
                [Token::T_OPTION],
            ],
            [
                '--colors --verbose',
                3,
                ['--colors', ' ', '--verbose'],
                [Token::T_OPTION, Token::T_WHITE_SPACE, Token::T_OPTION],
            ],
            [
                '--colors=',
                2,
                ['--colors', '='],
                [Token::T_OPTION, Token::T_EQUAL],
            ],
            [
                '--colors --verbose=2',
                5,
                ['--colors', ' ', '--verbose', '=', '2'],
                [Token::T_OPTION, Token::T_WHITE_SPACE, Token::T_OPTION, Token::T_EQUAL, Token::T_OPTION_VALUE],
            ],
            [
                '-lc',
                1,
                ['-lc'],
                [Token::T_SHORT_OPTION],
            ],
            [
                'info \\-lc',
                3,
                ['info', ' ', '\\-lc'],
                [Token::T_ARGUMENT, Token::T_WHITE_SPACE, Token::T_ARGUMENT],
            ],
            [
                'info --colors -lc ru,en --verbose=2',
                11,
                ['info', ' ', '--colors', ' ', '-lc', ' ', 'ru,en', ' ', '--verbose', '=', '2'],
                [
                    Token::T_ARGUMENT,
                    Token::T_WHITE_SPACE,
                    Token::T_OPTION,
                    Token::T_WHITE_SPACE,
                    Token::T_SHORT_OPTION,
                    Token::T_WHITE_SPACE,
                    Token::T_ARGUMENT,
                    Token::T_WHITE_SPACE,
                    Token::T_OPTION,
                    Token::T_EQUAL,
                    Token::T_OPTION_VALUE,
                ],
            ],
            [
                'info --locale="ru , en"',
                7,
                ['info', ' ', '--locale', '=', '"', 'ru , en', '"'],
                [
                    Token::T_ARGUMENT,
                    Token::T_WHITE_SPACE,
                    Token::T_OPTION,
                    Token::T_EQUAL,
                    Token::T_QUOTE,
                    Token::T_OPTION_VALUE,
                    Token::T_QUOTE,
                ],
            ],
            [
                'info --locale="ru ,\' en"',
                7,
                ['info', ' ', '--locale', '=', '"', 'ru ,\' en', '"'],
                [
                    Token::T_ARGUMENT,
                    Token::T_WHITE_SPACE,
                    Token::T_OPTION,
                    Token::T_EQUAL,
                    Token::T_QUOTE,
                    Token::T_OPTION_VALUE,
                    Token::T_QUOTE,
                ],
            ],
            [
                'info --locale=\'ru ", en\'',
                7,
                ['info', ' ', '--locale', '=', '\'', 'ru ", en', '\''],
                [
                    Token::T_ARGUMENT,
                    Token::T_WHITE_SPACE,
                    Token::T_OPTION,
                    Token::T_EQUAL,
                    Token::T_QUOTE,
                    Token::T_OPTION_VALUE,
                    Token::T_QUOTE,
                ],
            ],
            [
                'info --locale=""',
                6,
                ['info', ' ', '--locale', '=', '"', '"'],
                [
                    Token::T_ARGUMENT,
                    Token::T_WHITE_SPACE,
                    Token::T_OPTION,
                    Token::T_EQUAL,
                    Token::T_QUOTE,
                    Token::T_QUOTE,
                ],
            ],
            [
                'info --locale="" &',
                8,
                ['info', ' ', '--locale', '=', '"', '"', ' ', '&'],
                [
                    Token::T_ARGUMENT,
                    Token::T_WHITE_SPACE,
                    Token::T_OPTION,
                    Token::T_EQUAL,
                    Token::T_QUOTE,
                    Token::T_QUOTE,
                    Token::T_WHITE_SPACE,
                    Token::T_AMPERSAND,
                ],
            ],
            [
                'info & info',
                5,
                ['info', ' ', '&', ' ', 'info'],
                [
                    Token::T_ARGUMENT,
                    Token::T_WHITE_SPACE,
                    Token::T_AMPERSAND,
                    Token::T_WHITE_SPACE,
                    Token::T_ARGUMENT,
                ],
            ],
            [
                'info&',
                2,
                ['info', '&',],
                [
                    Token::T_ARGUMENT,
                    Token::T_AMPERSAND,
                ],
            ],
            [
                'info;info;',
                4,
                ['info', ';', 'info', ';'],
                [
                    Token::T_ARGUMENT,
                    Token::T_SEMICOLON,
                    Token::T_ARGUMENT,
                    Token::T_SEMICOLON,
                ],
            ],
            [
                'info;"info;"',
                5,
                ['info', ';', '"', 'info;', '"',],
                [
                    Token::T_ARGUMENT,
                    Token::T_SEMICOLON,
                    Token::T_QUOTE,
                    Token::T_ARGUMENT,
                    Token::T_QUOTE,
                ],
            ],
            [
                'info && info',
                5,
                ['info', ' ', '&&', ' ', 'info',],
                [
                    Token::T_ARGUMENT,
                    Token::T_WHITE_SPACE,
                    Token::T_AND,
                    Token::T_WHITE_SPACE,
                    Token::T_ARGUMENT,
                ],
            ],
            [
                'info || info',
                5,
                ['info', ' ', '||', ' ', 'info',],
                [
                    Token::T_ARGUMENT,
                    Token::T_WHITE_SPACE,
                    Token::T_OR,
                    Token::T_WHITE_SPACE,
                    Token::T_ARGUMENT,
                ],
            ],
            [
                'rm -r !(*.html)',
                8,
                ['rm', ' ', '-r', ' ', '!', '(', '*.html', ')',],
                [
                    Token::T_ARGUMENT,
                    Token::T_WHITE_SPACE,
                    Token::T_SHORT_OPTION,
                    Token::T_WHITE_SPACE,
                    Token::T_NOT,
                    Token::T_PARENTHESES_OPEN,
                    Token::T_ARGUMENT,
                    Token::T_PARENTHESES_CLOSE,
                ],
            ],
            [
                '{ls}',
                3,
                ['{', 'ls', '}',],
                [
                    Token::T_BRACES_OPEN,
                    Token::T_ARGUMENT,
                    Token::T_BRACES_CLOSE,
                ],
            ],
            [
                'ls | ls',
                5,
                ['ls', ' ', '|', ' ', 'ls'],
                [
                    Token::T_ARGUMENT,
                    Token::T_WHITE_SPACE,
                    Token::T_PIPE,
                    Token::T_WHITE_SPACE,
                    Token::T_ARGUMENT,
                ],
            ],
            [
                '(Command_x1 &&Command_x2) || (Command_x3 && Command_x4)',
                16,
                [
                    '(', 'Command_x1', ' ', '&&', 'Command_x2', ')', ' ', '||', ' ', '(', 'Command_x3',
                    ' ', '&&', ' ', 'Command_x4', ')',
                ],
                [
                    Token::T_PARENTHESES_OPEN,
                    Token::T_ARGUMENT,
                    Token::T_WHITE_SPACE,
                    Token::T_AND,
                    Token::T_ARGUMENT,
                    Token::T_PARENTHESES_CLOSE,
                    Token::T_WHITE_SPACE,
                    Token::T_OR,
                    Token::T_WHITE_SPACE,
                    Token::T_PARENTHESES_OPEN,
                    Token::T_ARGUMENT,
                    Token::T_WHITE_SPACE,
                    Token::T_AND,
                    Token::T_WHITE_SPACE,
                    Token::T_ARGUMENT,
                    Token::T_PARENTHESES_CLOSE,
                ],
            ],
            //['info --colors -lc ru,en --verbose=2', 11],
        ];
    }

    /**
     * Парсинг консольной строки
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
