<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer\CSS3Selector;

use Fi1a\Tokenizer\AToken;

/**
 * Токен CSS3 селектора
 */
class Token extends AToken
{
    /**
     * Неизвестный токен
     */
    public const T_UNKNOWN_TOKEN_TYPE = 1;

    public const T_WHITE_SPACE = 10;

    /**
     * div
     */
    public const T_TAG = 20;

    /**
     * .class-name
     */
    public const T_CLASS = 30;

    /**
     * [
     */
    public const T_OPEN_ATTRIBUTE = 40;

    /**
     * ]
     */
    public const T_CLOSE_ATTRIBUTE = 50;

    /**
     *  ----
     * [name]
     *  ----
     */
    public const T_ATTRIBUTE = 60;

    /**
     * =
     */
    public const T_EQUAL = 70;

    /**
     * ' "
     */
    public const T_QUOTE = 80;

    /**        ----------
     * [class="class-name"]
     *         ----------
     */
    public const T_ATTRIBUTE_VALUE = 90;

    /**
     * |=
     */
    public const T_EITHER_EQUAL = 100;

    /**
     * *=
     */
    public const T_CONTAINING = 110;

    /**
     * ~=
     */
    public const T_CONTAINING_WORD = 120;

    /**
     * $=
     */
    public const T_ENDING_EXACTLY = 130;

    /**
     * !=
     */
    public const T_NOT_EQUAL = 140;

    /**
     * ^=
     */
    public const T_BEGINNING_EXACTLY = 150;

    /**
     * div > div
     */
    public const T_DIRECT_CHILD = 160;

    /**
     * prev ~ siblings
     */
    public const T_SIBLING_AFTER = 170;

    /**
     * prev + next
     */
    public const T_SIBLING_NEXT = 180;

    /**
     * #id
     */
    public const T_ID = 190;

    /**
     * div:pseudo
     */
    public const T_PSEUDO = 200;

    /**
     * (
     */
    public const T_OPEN_BRACKET = 210;

    /**
     * )
     */
    public const T_CLOSE_BRACKET = 220;

    /**        -
     * div:eq("0")
     *         -
     */
    public const T_PSEUDO_VALUE = 230;

    /**
     * div, a
     */
    public const T_MULTIPLE_SELECTOR = 240;

    /**
     * @var int[]
     */
    private static $types = [
        self::T_UNKNOWN_TOKEN_TYPE,
        self::T_WHITE_SPACE,
        self::T_TAG,
        self::T_CLASS,
        self::T_OPEN_ATTRIBUTE,
        self::T_CLOSE_ATTRIBUTE,
        self::T_ATTRIBUTE,
        self::T_EQUAL,
        self::T_QUOTE,
        self::T_ATTRIBUTE_VALUE,
        self::T_EITHER_EQUAL,
        self::T_CONTAINING,
        self::T_CONTAINING_WORD,
        self::T_ENDING_EXACTLY,
        self::T_NOT_EQUAL,
        self::T_BEGINNING_EXACTLY,
        self::T_DIRECT_CHILD,
        self::T_SIBLING_AFTER,
        self::T_SIBLING_NEXT,
        self::T_ID,
        self::T_PSEUDO,
        self::T_OPEN_BRACKET,
        self::T_CLOSE_BRACKET,
        self::T_PSEUDO_VALUE,
        self::T_MULTIPLE_SELECTOR,
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
