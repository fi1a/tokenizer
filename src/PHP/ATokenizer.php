<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer\PHP;

use Fi1a\Tokenizer\ITokenFactory;

use const T_ABSTRACT;
use const T_AND_EQUAL;
use const T_ARRAY;
use const T_ARRAY_CAST;
use const T_AS;
use const T_BOOLEAN_AND;
use const T_BOOLEAN_OR;
use const T_BOOL_CAST;
use const T_BREAK;
use const T_CALLABLE;
use const T_CASE;
use const T_CATCH;
use const T_CLASS;
use const T_CLASS_C;
use const T_CLONE;
use const T_CLOSE_TAG;
use const T_COALESCE;
use const T_COMMENT;
use const T_CONCAT_EQUAL;
use const T_CONST;
use const T_CONSTANT_ENCAPSED_STRING;
use const T_CONTINUE;
use const T_CURLY_OPEN;
use const T_DEC;
use const T_DECLARE;
use const T_DEFAULT;
use const T_DIR;
use const T_DIV_EQUAL;
use const T_DNUMBER;
use const T_DO;
use const T_DOC_COMMENT;
use const T_DOLLAR_OPEN_CURLY_BRACES;
use const T_DOUBLE_ARROW;
use const T_DOUBLE_CAST;
use const T_DOUBLE_COLON;
use const T_ECHO;
use const T_ELLIPSIS;
use const T_ELSE;
use const T_ELSEIF;
use const T_EMPTY;
use const T_ENCAPSED_AND_WHITESPACE;
use const T_ENDDECLARE;
use const T_ENDFOR;
use const T_ENDFOREACH;
use const T_ENDIF;
use const T_ENDSWITCH;
use const T_ENDWHILE;
use const T_END_HEREDOC;
use const T_EVAL;
use const T_EXIT;
use const T_EXTENDS;
use const T_FILE;
use const T_FINAL;
use const T_FINALLY;
use const T_FOR;
use const T_FOREACH;
use const T_FUNCTION;
use const T_FUNC_C;
use const T_GLOBAL;
use const T_GOTO;
use const T_HALT_COMPILER;
use const T_IF;
use const T_IMPLEMENTS;
use const T_INC;
use const T_INCLUDE;
use const T_INCLUDE_ONCE;
use const T_INLINE_HTML;
use const T_INSTANCEOF;
use const T_INSTEADOF;
use const T_INTERFACE;
use const T_INT_CAST;
use const T_ISSET;
use const T_IS_EQUAL;
use const T_IS_GREATER_OR_EQUAL;
use const T_IS_IDENTICAL;
use const T_IS_NOT_EQUAL;
use const T_IS_NOT_IDENTICAL;
use const T_IS_SMALLER_OR_EQUAL;
use const T_LINE;
use const T_LIST;
use const T_LNUMBER;
use const T_LOGICAL_AND;
use const T_LOGICAL_OR;
use const T_LOGICAL_XOR;
use const T_METHOD_C;
use const T_MINUS_EQUAL;
use const T_MOD_EQUAL;
use const T_MUL_EQUAL;
use const T_NAMESPACE;
use const T_NEW;
use const T_NS_C;
use const T_NS_SEPARATOR;
use const T_NUM_STRING;
use const T_OBJECT_CAST;
use const T_OBJECT_OPERATOR;
use const T_OPEN_TAG;
use const T_OPEN_TAG_WITH_ECHO;
use const T_OR_EQUAL;
use const T_PLUS_EQUAL;
use const T_POW;
use const T_POW_EQUAL;
use const T_PRINT;
use const T_PRIVATE;
use const T_PROTECTED;
use const T_PUBLIC;
use const T_REQUIRE;
use const T_REQUIRE_ONCE;
use const T_RETURN;
use const T_SL;
use const T_SL_EQUAL;
use const T_SR;
use const T_SR_EQUAL;
use const T_START_HEREDOC;
use const T_STATIC;
use const T_STRING;
use const T_STRING_CAST;
use const T_STRING_VARNAME;
use const T_SWITCH;
use const T_THROW;
use const T_TRAIT;
use const T_TRAIT_C;
use const T_TRY;
use const T_UNSET;
use const T_UNSET_CAST;
use const T_USE;
use const T_VAR;
use const T_VARIABLE;
use const T_WHILE;
use const T_WHITESPACE;
use const T_XOR_EQUAL;
use const T_YIELD;
use const T_YIELD_FROM;

/**
 * Абстрактный класс PHP лексического анализатора
 */
abstract class ATokenizer extends \Fi1a\Tokenizer\ATokenizer
{
    /**
     * Связь между типами токенов (zend => пакет)
     *
     * @var int[]
     * @psalm-param int[]
     */
    private static $typeMap = [
        null => Token::T_UNKNOWN_TOKEN_TYPE,
        T_ABSTRACT => Token::T_ABSTRACT,
        T_AND_EQUAL => Token::T_AND_EQUAL,
        T_ARRAY => Token::T_ARRAY,
        T_ARRAY_CAST => Token::T_ARRAY_CAST,
        T_AS => Token::T_AS,
        60 => Token::T_BAD_CHARACTER,
        T_BOOLEAN_AND => Token::T_BOOLEAN_AND,
        T_BOOLEAN_OR => Token::T_BOOLEAN_OR,
        T_BOOL_CAST => Token::T_BOOL_CAST,
        T_BREAK => Token::T_BREAK,
        T_CALLABLE => Token::T_CALLABLE,
        T_CASE => Token::T_CASE,
        T_CATCH => Token::T_CATCH,
        T_CLASS => Token::T_CLASS,
        T_CLASS_C => Token::T_CLASS_C,
        T_CLONE => Token::T_CLONE,
        T_CLOSE_TAG => Token::T_CLOSE_TAG,
        T_COMMENT => Token::T_COMMENT,
        T_CONCAT_EQUAL => Token::T_CONCAT_EQUAL,
        T_CONST => Token::T_CONST,
        T_CONSTANT_ENCAPSED_STRING => Token::T_CONSTANT_ENCAPSED_STRING,
        T_CONTINUE => Token::T_CONTINUE,
        T_CURLY_OPEN => Token::T_CURLY_OPEN,
        T_DEC => Token::T_DEC,
        T_DECLARE => Token::T_DECLARE,
        T_DEFAULT => Token::T_DEFAULT,
        T_DIR => Token::T_DIR,
        T_DIV_EQUAL => Token::T_DIV_EQUAL,
        T_DNUMBER => Token::T_DNUMBER,
        T_DOC_COMMENT => Token::T_DOC_COMMENT,
        T_DO => Token::T_DO,
        T_DOLLAR_OPEN_CURLY_BRACES => Token::T_DOLLAR_OPEN_CURLY_BRACES,
        T_DOUBLE_ARROW => Token::T_DOUBLE_ARROW,
        T_DOUBLE_CAST => Token::T_DOUBLE_CAST,
        T_DOUBLE_COLON => Token::T_DOUBLE_COLON,
        T_ECHO => Token::T_ECHO,
        T_ELLIPSIS => Token::T_ELLIPSIS,
        T_ELSE => Token::T_ELSE,
        T_ELSEIF => Token::T_ELSEIF,
        T_EMPTY => Token::T_EMPTY,
        T_ENCAPSED_AND_WHITESPACE => Token::T_ENCAPSED_AND_WHITESPACE,
        T_ENDDECLARE => Token::T_ENDDECLARE,
        T_ENDFOR => Token::T_ENDFOR,
        T_ENDFOREACH => Token::T_ENDFOREACH,
        T_ENDIF => Token::T_ENDIF,
        T_ENDSWITCH => Token::T_ENDSWITCH,
        T_ENDWHILE => Token::T_ENDWHILE,
        T_END_HEREDOC => Token::T_END_HEREDOC,
        T_EVAL => Token::T_EVAL,
        T_EXIT => Token::T_EXIT,
        T_EXTENDS => Token::T_EXTENDS,
        T_FILE => Token::T_FILE,
        T_FINAL => Token::T_FINAL,
        T_FINALLY => Token::T_FINALLY,
        T_FOR => Token::T_FOR,
        T_FOREACH => Token::T_FOREACH,
        T_FUNCTION => Token::T_FUNCTION,
        T_FUNC_C => Token::T_FUNC_C,
        T_GLOBAL => Token::T_GLOBAL,
        T_GOTO => Token::T_GOTO,
        T_HALT_COMPILER => Token::T_HALT_COMPILER,
        T_IF => Token::T_IF,
        T_IMPLEMENTS => Token::T_IMPLEMENTS,
        T_INC => Token::T_INC,
        T_INCLUDE => Token::T_INCLUDE,
        T_INCLUDE_ONCE => Token::T_INCLUDE_ONCE,
        T_INLINE_HTML => Token::T_INLINE_HTML,
        T_INSTANCEOF => Token::T_INSTANCEOF,
        T_INSTEADOF => Token::T_INSTEADOF,
        T_INT_CAST => Token::T_INT_CAST,
        T_INTERFACE => Token::T_INTERFACE,
        T_ISSET => Token::T_ISSET,
        T_IS_EQUAL => Token::T_IS_EQUAL,
        T_IS_GREATER_OR_EQUAL => Token::T_IS_GREATER_OR_EQUAL,
        T_IS_IDENTICAL => Token::T_IS_IDENTICAL,
        T_IS_NOT_EQUAL => Token::T_IS_NOT_EQUAL,
        T_IS_NOT_IDENTICAL => Token::T_IS_NOT_IDENTICAL,
        T_IS_SMALLER_OR_EQUAL => Token::T_IS_SMALLER_OR_EQUAL,
        790 => Token::T_SPACESHIP,
        T_LINE => Token::T_LINE,
        T_LIST => Token::T_LIST,
        T_LNUMBER => Token::T_LNUMBER,
        T_LOGICAL_AND => Token::T_LOGICAL_AND,
        T_LOGICAL_OR => Token::T_LOGICAL_OR,
        T_LOGICAL_XOR => Token::T_LOGICAL_XOR,
        T_METHOD_C => Token::T_METHOD_C,
        T_MINUS_EQUAL => Token::T_MINUS_EQUAL,
        T_MOD_EQUAL => Token::T_MOD_EQUAL,
        T_MUL_EQUAL => Token::T_MUL_EQUAL,
        T_NAMESPACE => Token::T_NAMESPACE,
        T_NS_C => Token::T_NS_C,
        T_NS_SEPARATOR => Token::T_NS_SEPARATOR,
        T_NEW => Token::T_NEW,
        T_NUM_STRING => Token::T_NUM_STRING,
        T_OBJECT_CAST => Token::T_OBJECT_CAST,
        T_OBJECT_OPERATOR => Token::T_OBJECT_OPERATOR,
        T_OPEN_TAG => Token::T_OPEN_TAG,
        T_OPEN_TAG_WITH_ECHO => Token::T_OPEN_TAG_WITH_ECHO,
        T_OR_EQUAL => Token::T_OR_EQUAL,
        T_PLUS_EQUAL => Token::T_PLUS_EQUAL,
        T_POW => Token::T_POW,
        T_POW_EQUAL => Token::T_POW_EQUAL,
        T_PRINT => Token::T_PRINT,
        T_PRIVATE => Token::T_PRIVATE,
        T_PUBLIC => Token::T_PUBLIC,
        T_PROTECTED => Token::T_PROTECTED,
        T_REQUIRE => Token::T_REQUIRE,
        T_REQUIRE_ONCE => Token::T_REQUIRE_ONCE,
        T_RETURN => Token::T_RETURN,
        T_SL => Token::T_SL,
        T_SL_EQUAL => Token::T_SL_EQUAL,
        T_SR => Token::T_SR,
        T_SR_EQUAL => Token::T_SR_EQUAL,
        T_START_HEREDOC => Token::T_START_HEREDOC,
        T_STATIC => Token::T_STATIC,
        T_STRING => Token::T_STRING,
        T_STRING_CAST => Token::T_STRING_CAST,
        T_STRING_VARNAME => Token::T_STRING_VARNAME,
        T_SWITCH => Token::T_SWITCH,
        T_THROW => Token::T_THROW,
        T_TRAIT => Token::T_TRAIT,
        T_TRAIT_C => Token::T_TRAIT_C,
        T_TRY => Token::T_TRY,
        T_UNSET => Token::T_UNSET,
        T_UNSET_CAST => Token::T_UNSET_CAST,
        T_USE => Token::T_USE,
        T_VAR => Token::T_VAR,
        T_VARIABLE => Token::T_VARIABLE,
        T_WHILE => Token::T_WHILE,
        T_WHITESPACE => Token::T_WHITESPACE,
        T_XOR_EQUAL => Token::T_XOR_EQUAL,
        T_YIELD => Token::T_YIELD,
        T_COALESCE => Token::T_COALESCE,
        282 => Token::T_COALESCE_EQUAL,
        343 => Token::T_FN,
        T_YIELD_FROM => Token::T_YIELD_FROM,
    ];

    /**
     * @var int[]
     * @psalm-param int[]
     */
    private static $stringTypeMap = [
        '<' => Token::T_ANGLE_BRACKET_OPEN,
        '>' => Token::T_ANGLE_BRACKET_CLOSE,
        '(' => Token::T_PARENTHESES_OPEN,
        ')' => Token::T_PARENTHESES_CLOSE,
        '+' => Token::T_PLUS,
        '-' => Token::T_MINUS,
        '/' => Token::T_DIV,
        '%' => Token::T_MOD,
        '*' => Token::T_MUL,
        '.' => Token::T_CONCAT,
        ',' => Token::T_COMMA,
        'true' => Token::T_TRUE,
        'false' => Token::T_FALSE,
        'null' => Token::T_NULL,
    ];

    /**
     * Возвращает связь между токенами
     *
     * @return int[]
     */
    protected static function getTypeMap(): array
    {
        return self::$typeMap;
    }

    /**
     * Возвращает связь между строкой и типом токена
     *
     * @return int[]
     */
    protected static function getStringTypeMap(): array
    {
        return self::$stringTypeMap;
    }

    /**
     * Преобразует исходный код в токены
     */
    protected function tokenize(): void
    {
        $this->setIndex(-1);
        $tokens = [];
        $endLine = 1;
        $endColumn = 1;
        $origin = token_get_all($this->getSource());
        /**
         * @var ITokenFactory $factory
         */
        $factory = static::getTokenFactory();
        $map = static::getTypeMap();
        $stringMap = static::getStringTypeMap();
        foreach ($origin as $token) {
            if (is_string($token)) {
                $token = [null, $token];
            }
            $type = null;
            $image = $token[1];
            if (!is_null($token[0]) && array_key_exists($token[0], $map)) {
                $type = $map[$token[0]];
            }
            // @codeCoverageIgnoreStart
            if (is_null($type)) {
                $type = Token::T_UNKNOWN_TOKEN_TYPE;
            }
            // @codeCoverageIgnoreEnd
            if ($type === Token::T_STRING || $type === Token::T_UNKNOWN_TOKEN_TYPE) {
                $stringImage = mb_strtolower($image);
                if (array_key_exists($stringImage, $stringMap)) {
                    $type = $stringMap[$stringImage];
                }
            }
            $startLine = $endLine;
            $lines = mb_substr_count($image, "\n");
            $endLine = $startLine + $lines;
            $startColumn = $endColumn;
            $endColumn = ($lines === 0
                ? $endColumn + mb_strlen($image)
                : mb_strlen(mb_substr($image, (int) mb_strrpos($image, "\n") + 1)) + 1
            );
            $tokens[] = $factory::factory($type, $image, $startLine, $endLine, $startColumn, $endColumn);
        }
        $this->setCount(count($tokens))
            ->setTokens($tokens);
    }

    /**
     * @inheritDoc
     */
    public static function getTokenFactory()
    {
        return TokenFactory::class;
    }
}
