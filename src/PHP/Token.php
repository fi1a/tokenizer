<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer\PHP;

use Fi1a\Tokenizer\AToken;

/**
 * PHP токен
 */
class Token extends AToken
{
    /**
     * Неизвестный токен
     */
    public const T_UNKNOWN_TOKEN_TYPE = 1;

    /**
     * abstract
     */
    public const T_ABSTRACT = 10;

    /**
     * &=
     */
    public const T_AND_EQUAL = 20;

    /**
     * array()
     */
    public const T_ARRAY = 30;

    /**
     * (array)
     */
    public const T_ARRAY_CAST = 40;

    /**
     * as
     */
    public const T_AS = 50;

    public const T_BAD_CHARACTER = 60;

    /**
     * &&
     */
    public const T_BOOLEAN_AND = 70;

    /**
     * ||
     */
    public const T_BOOLEAN_OR = 80;

    /**
     * (bool) or (boolean)
     */
    public const T_BOOL_CAST = 90;

    /**
     * break
     */
    public const T_BREAK = 100;

    /**
     * callable
     */
    public const T_CALLABLE = 110;

    /**
     * case
     */
    public const T_CASE = 120;

    /**
     * catch
     */
    public const T_CATCH = 130;

    /**
     * class
     */
    public const T_CLASS = 140;

    /**
     * __CLASS__
     */
    public const T_CLASS_C = 150;

    /**
     * clone
     */
    public const T_CLONE = 160;

    /**
     * ?> or %>
     */
    public const T_CLOSE_TAG = 170;

    /**
     * // or #, and /* *\/
     */
    public const T_COMMENT = 180;

    /**
     * .=
     */
    public const T_CONCAT_EQUAL = 190;

    /**
     * const
     */
    public const T_CONST = 200;

    /**
     * "foo" or 'bar'
     */
    public const T_CONSTANT_ENCAPSED_STRING = 210;

    /**
     * continue
     */
    public const T_CONTINUE = 220;

    /**
     * {$
     */
    public const T_CURLY_OPEN = 230;

    /**
     * --
     */
    public const T_DEC = 240;

    /**
     * declare
     */
    public const T_DECLARE = 250;

    /**
     * default
     */
    public const T_DEFAULT = 260;

    /**
     * __DIR__
     */
    public const T_DIR = 270;

    /**
     * /=
     */
    public const T_DIV_EQUAL = 280;

    /**
     * 0.12
     */
    public const T_DNUMBER = 290;

    /**
     * /** *\/
     */
    public const T_DOC_COMMENT = 300;

    /**
     * do
     */
    public const T_DO = 310;

    /**
     * ${
     */
    public const T_DOLLAR_OPEN_CURLY_BRACES = 320;

    /**
     * =>
     */
    public const T_DOUBLE_ARROW = 330;

    /**
     * (real), (double) or (float)
     */
    public const T_DOUBLE_CAST = 340;

    /**
     * ::
     */
    public const T_DOUBLE_COLON = 350;

    /**
     * echo
     */
    public const T_ECHO = 360;

    /**
     * ...
     */
    public const T_ELLIPSIS = 370;

    /**
     * else
     */
    public const T_ELSE = 380;

    /**
     * elseif
     */
    public const T_ELSEIF = 390;

    /**
     * empty
     */
    public const T_EMPTY = 400;

    /**
     * " $a"
     */
    public const T_ENCAPSED_AND_WHITESPACE = 410;

    /**
     * enddeclare
     */
    public const T_ENDDECLARE = 420;

    /**
     * endfor
     */
    public const T_ENDFOR = 430;

    /**
     * endforeach
     */
    public const T_ENDFOREACH = 440;

    /**
     * endif
     */
    public const T_ENDIF = 450;

    /**
     * endswitch
     */
    public const T_ENDSWITCH = 460;

    /**
     * endwhile
     */
    public const T_ENDWHILE = 470;

    /**
     * heredoc syntax
     */
    public const T_END_HEREDOC = 480;

    /**
     * eval()
     */
    public const T_EVAL = 490;

    /**
     * exit or die
     */
    public const T_EXIT = 500;

    /**
     * extends
     */
    public const T_EXTENDS = 510;

    /**
     * __FILE__
     */
    public const T_FILE = 520;

    /**
     * final
     */
    public const T_FINAL = 530;

    /**
     * finally
     */
    public const T_FINALLY = 540;

    /**
     * for
     */
    public const T_FOR = 550;

    /**
     * foreach
     */
    public const T_FOREACH = 560;

    /**
     * function or cfunction
     */
    public const T_FUNCTION = 570;

    /**
     * __FUNCTION__
     */
    public const T_FUNC_C = 580;

    /**
     * global
     */
    public const T_GLOBAL = 590;

    /**
     * goto
     */
    public const T_GOTO = 600;

    /**
     * __halt_compiler()
     */
    public const T_HALT_COMPILER = 610;

    /**
     * if
     */
    public const T_IF = 620;

    /**
     * implements
     */
    public const T_IMPLEMENTS = 630;

    /**
     * ++
     */
    public const T_INC = 640;

    /**
     * include()
     */
    public const T_INCLUDE = 650;

    /**
     * include_once()
     */
    public const T_INCLUDE_ONCE = 660;

    /**
     * text outside PHP
     */
    public const T_INLINE_HTML = 670;

    /**
     * instanceof
     */
    public const T_INSTANCEOF = 680;

    /**
     * insteadof
     */
    public const T_INSTEADOF = 690;

    /**
     * (int) or (integer)
     */
    public const T_INT_CAST = 700;

    /**
     * interface
     */
    public const T_INTERFACE = 710;

    /**
     * isset()
     */
    public const T_ISSET = 720;

    /**
     * ==
     */
    public const T_IS_EQUAL = 730;

    /**
     * >=
     */
    public const T_IS_GREATER_OR_EQUAL = 740;

    /**
     * ===
     */
    public const T_IS_IDENTICAL = 750;

    /**
     * != or <>
     */
    public const T_IS_NOT_EQUAL = 760;

    /**
     * !==
     */
    public const T_IS_NOT_IDENTICAL = 770;

    /**
     * <=
     */
    public const T_IS_SMALLER_OR_EQUAL = 780;

    /**
     * <=>
     */
    public const T_SPACESHIP = 790;

    /**
     * __LINE__
     */
    public const T_LINE = 800;

    /**
     * list()
     */
    public const T_LIST = 810;

    /**
     * 123, 012, 0x1ac, etc.
     */
    public const T_LNUMBER = 820;

    /**
     * and
     */
    public const T_LOGICAL_AND = 830;

    /**
     * or
     */
    public const T_LOGICAL_OR = 840;

    /**
     * xor
     */
    public const T_LOGICAL_XOR = 850;

    /**
     * __METHOD__
     */
    public const T_METHOD_C = 860;

    /**
     * -=
     */
    public const T_MINUS_EQUAL = 870;

    /**
     * %=
     */
    public const T_MOD_EQUAL = 880;

    /**
     * *=
     */
    public const T_MUL_EQUAL = 890;

    /**
     * namespace
     */
    public const T_NAMESPACE = 900;

    /**
     * __NAMESPACE__
     */
    public const T_NS_C = 910;

    /**
     * \
     */
    public const T_NS_SEPARATOR = 920;

    /**
     * new
     */
    public const T_NEW = 930;

    /**
     * "$a[0]"
     */
    public const T_NUM_STRING = 940;

    /**
     * (object)
     */
    public const T_OBJECT_CAST = 950;

    /**
     * ->
     */
    public const T_OBJECT_OPERATOR = 960;

    /**
     * <?php, <? or <%
     */
    public const T_OPEN_TAG = 970;

    /**
     * <?= or <%=
     */
    public const T_OPEN_TAG_WITH_ECHO = 980;

    /**
     * |=
     */
    public const T_OR_EQUAL = 990;

    /**
     * ::
     */
    public const T_PAAMAYIM_NEKUDOTAYIM = 1000;

    /**
     * +=
     */
    public const T_PLUS_EQUAL = 1010;

    public const T_POW = 1020;

    /**
     * **=
     */
    public const T_POW_EQUAL = 1030;

    /**
     * print()
     */
    public const T_PRINT = 1040;

    /**
     * private
     */
    public const T_PRIVATE = 1050;

    /**
     * public
     */
    public const T_PUBLIC = 1060;

    /**
     * protected
     */
    public const T_PROTECTED = 1070;

    /**
     * require()
     */
    public const T_REQUIRE = 1080;

    /**
     * require_once()
     */
    public const T_REQUIRE_ONCE = 1090;

    /**
     * return
     */
    public const T_RETURN = 1100;

    /**
     * <<
     */
    public const T_SL = 1110;

    /**
     * <<=
     */
    public const T_SL_EQUAL = 1120;

    /**
     * >>
     */
    public const T_SR = 1130;

    /**
     * >>=
     */
    public const T_SR_EQUAL = 1140;

    /**
     * <<<
     */
    public const T_START_HEREDOC = 1150;

    /**
     * static
     */
    public const T_STATIC = 1160;

    /**
     * parent, self, etc.
     */
    public const T_STRING = 1170;

    /**
     * (string)
     */
    public const T_STRING_CAST = 1180;

    /**
     * "${a
     */
    public const T_STRING_VARNAME = 1190;

    /**
     * switch
     */
    public const T_SWITCH = 1200;

    /**
     * throw
     */
    public const T_THROW = 1210;

    /**
     * trait
     */
    public const T_TRAIT = 1220;

    /**
     * __TRAIT__
     */
    public const T_TRAIT_C = 1230;

    /**
     * try
     */
    public const T_TRY = 1240;

    /**
     * unset()
     */
    public const T_UNSET = 1250;

    /**
     * (unset)
     */
    public const T_UNSET_CAST = 1260;

    /**
     * use
     */
    public const T_USE = 1270;

    /**
     * var
     */
    public const T_VAR = 1280;

    /**
     * $foo
     */
    public const T_VARIABLE = 1290;

    /**
     * while
     */
    public const T_WHILE = 1300;

    /**
     * \t \r\n
     */
    public const T_WHITESPACE = 1310;

    /**
     * ^=
     */
    public const T_XOR_EQUAL = 1320;

    /**
     * yield
     */
    public const T_YIELD = 1330;

    /**
     * '<'
     */
    public const T_ANGLE_BRACKET_OPEN = 1340;

    /**
     * '>'
     */
    public const T_ANGLE_BRACKET_CLOSE = 1350;

    /**
     * '('
     */
    public const T_PARENTHESES_OPEN = 1360;

    /**
     * ')'
     */
    public const T_PARENTHESES_CLOSE = 1370;

    /**
     * '+'
     */
    public const T_PLUS = 1380;

    /**
     * '-'
     */
    public const T_MINUS = 1390;

    /**
     * '/'
     */
    public const T_DIV = 1400;

    /**
     * '%'
     */
    public const T_MOD = 1410;

    /**
     * '*'
     */
    public const T_MUL = 1420;

    /**
     * '.'
     */
    public const T_CONCAT = 1430;

    /**
     * ','
     */
    public const T_COMMA = 1440;

    /**
     * 'true'
     */
    public const T_TRUE = 1450;

    /**
     * 'false'
     */
    public const T_FALSE = 1460;

    /**
     * 'null'
     */
    public const T_NULL = 1470;

    /**
     * ??
     */
    public const T_COALESCE = 1480;

    /**
     * ??=
     */
    public const T_COALESCE_EQUAL = 1490;

    /**
     * fn
     */
    public const T_FN = 1500;

    /**
     * yield from
     */
    public const T_YIELD_FROM = 1510;

    /**
     * @var array
     */
    protected static $types = [
        self::T_UNKNOWN_TOKEN_TYPE,
        self::T_ABSTRACT,
        self::T_AND_EQUAL,
        self::T_ARRAY,
        self::T_ARRAY_CAST,
        self::T_AS,
        self::T_BAD_CHARACTER,
        self::T_BOOLEAN_AND,
        self::T_BOOLEAN_OR,
        self::T_BOOL_CAST,
        self::T_BREAK,
        self::T_CALLABLE,
        self::T_CASE,
        self::T_CATCH,
        self::T_CLASS,
        self::T_CLASS_C,
        self::T_CLONE,
        self::T_CLOSE_TAG,
        self::T_COMMENT,
        self::T_CONCAT_EQUAL,
        self::T_CONST,
        self::T_CONSTANT_ENCAPSED_STRING,
        self::T_CONTINUE,
        self::T_CURLY_OPEN,
        self::T_DEC,
        self::T_DECLARE,
        self::T_DEFAULT,
        self::T_DIR,
        self::T_DIV_EQUAL,
        self::T_DNUMBER,
        self::T_DOC_COMMENT,
        self::T_DO,
        self::T_DOLLAR_OPEN_CURLY_BRACES,
        self::T_DOUBLE_ARROW,
        self::T_DOUBLE_CAST,
        self::T_DOUBLE_COLON,
        self::T_ECHO,
        self::T_ELLIPSIS,
        self::T_ELSE,
        self::T_ELSEIF,
        self::T_EMPTY,
        self::T_ENCAPSED_AND_WHITESPACE,
        self::T_ENDDECLARE,
        self::T_ENDFOR,
        self::T_ENDFOREACH,
        self::T_ENDIF,
        self::T_ENDSWITCH,
        self::T_ENDWHILE,
        self::T_END_HEREDOC,
        self::T_EVAL,
        self::T_EXIT,
        self::T_EXTENDS,
        self::T_FILE,
        self::T_FINAL,
        self::T_FINALLY,
        self::T_FOR,
        self::T_FOREACH,
        self::T_FUNCTION,
        self::T_FUNC_C,
        self::T_GLOBAL,
        self::T_GOTO,
        self::T_HALT_COMPILER,
        self::T_IF,
        self::T_IMPLEMENTS,
        self::T_INC,
        self::T_INCLUDE,
        self::T_INCLUDE_ONCE,
        self::T_INLINE_HTML,
        self::T_INSTANCEOF,
        self::T_INSTEADOF,
        self::T_INT_CAST,
        self::T_INTERFACE,
        self::T_ISSET,
        self::T_IS_EQUAL,
        self::T_IS_GREATER_OR_EQUAL,
        self::T_IS_IDENTICAL,
        self::T_IS_NOT_EQUAL,
        self::T_IS_NOT_IDENTICAL,
        self::T_IS_SMALLER_OR_EQUAL,
        self::T_SPACESHIP,
        self::T_LINE,
        self::T_LIST,
        self::T_LNUMBER,
        self::T_LOGICAL_AND,
        self::T_LOGICAL_OR,
        self::T_LOGICAL_XOR,
        self::T_METHOD_C,
        self::T_MINUS_EQUAL,
        self::T_MOD_EQUAL,
        self::T_MUL_EQUAL,
        self::T_NAMESPACE,
        self::T_NS_C,
        self::T_NS_SEPARATOR,
        self::T_NEW,
        self::T_NUM_STRING,
        self::T_OBJECT_CAST,
        self::T_OBJECT_OPERATOR,
        self::T_OPEN_TAG,
        self::T_OPEN_TAG_WITH_ECHO,
        self::T_OR_EQUAL,
        self::T_PAAMAYIM_NEKUDOTAYIM,
        self::T_PLUS_EQUAL,
        self::T_POW,
        self::T_POW_EQUAL,
        self::T_PRINT,
        self::T_PRIVATE,
        self::T_PUBLIC,
        self::T_PROTECTED,
        self::T_REQUIRE,
        self::T_REQUIRE_ONCE,
        self::T_RETURN,
        self::T_SL,
        self::T_SL_EQUAL,
        self::T_SR,
        self::T_SR_EQUAL,
        self::T_START_HEREDOC,
        self::T_STATIC,
        self::T_STRING,
        self::T_STRING_CAST,
        self::T_STRING_VARNAME,
        self::T_SWITCH,
        self::T_THROW,
        self::T_TRAIT,
        self::T_TRAIT_C,
        self::T_TRY,
        self::T_UNSET,
        self::T_UNSET_CAST,
        self::T_USE,
        self::T_VAR,
        self::T_VARIABLE,
        self::T_WHILE,
        self::T_WHITESPACE,
        self::T_XOR_EQUAL,
        self::T_YIELD,
        self::T_ANGLE_BRACKET_OPEN,
        self::T_ANGLE_BRACKET_CLOSE,
        self::T_PARENTHESES_OPEN,
        self::T_PARENTHESES_CLOSE,
        self::T_PLUS,
        self::T_MINUS,
        self::T_DIV,
        self::T_MOD,
        self::T_MUL,
        self::T_CONCAT,
        self::T_COMMA,
        self::T_TRUE,
        self::T_FALSE,
        self::T_NULL,
        self::T_COALESCE,
        self::T_COALESCE_EQUAL,
        self::T_FN,
        self::T_YIELD_FROM,
    ];

    /**
     * Возвращает доступные типы токенов
     *
     * @return int[]
     */
    protected function getTypes(): array
    {
        return static::$types;
    }
}
