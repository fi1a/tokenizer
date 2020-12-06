<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer\ConsoleLine;

use Fi1a\Tokenizer\AToken;

/**
 * Консольный токен
 */
class Token extends AToken
{
    /**
     * Неизвестный токен
     */
    public const T_UNKNOWN_TOKEN_TYPE = 1;

    public const T_WHITE_SPACE = 10;

    public const T_ARGUMENT = 20;

    public const T_OPTION = 30;

    public const T_EQUAL = 40;

    public const T_OPTION_VALUE = 50;

    public const T_SHORT_OPTION = 60;

    public const T_QUOTE = 70;

    public const T_AMPERSAND = 80;

    public const T_SEMICOLON = 90;

    public const T_AND = 100;

    public const T_OR = 110;

    public const T_NOT = 120;

    public const T_PIPE = 130;

    public const T_BRACES_OPEN = 140;

    public const T_BRACES_CLOSE = 150;

    public const T_PARENTHESES_OPEN = 160;

    public const T_PARENTHESES_CLOSE = 170;

    /**
     * @var int[]
     */
    private static $types = [
        self::T_UNKNOWN_TOKEN_TYPE,
        self::T_WHITE_SPACE,
        self::T_ARGUMENT,
        self::T_OPTION,
        self::T_EQUAL,
        self::T_OPTION_VALUE,
        self::T_SHORT_OPTION,
        self::T_QUOTE,
        self::T_AMPERSAND,
        self::T_SEMICOLON,
        self::T_AND,
        self::T_OR,
        self::T_NOT,
        self::T_PIPE,
        self::T_BRACES_OPEN,
        self::T_BRACES_CLOSE,
        self::T_PARENTHESES_OPEN,
        self::T_PARENTHESES_CLOSE,
    ];

    /**
     * Возвращает доступные типы токенов
     *
     * @return int[]
     */
    protected function getTypes(): array
    {
        return self::$types;
    }
}
