<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer\ConsoleLine;

use Fi1a\Tokenizer\AParseFunction;
use Fi1a\Tokenizer\IToken;

/**
 * Лексический анализатор для консольной строки
 */
class Tokenizer extends AParseFunction
{
    /**
     * @inheritDoc
     */
    public function __construct(string $source, ?string $encoding = null)
    {
        $this->setParseFunction('parse');
        parent::__construct($source, $encoding);
    }

    /**
     * Базовая функция парсинга
     *
     * @param IToken[]    $tokens
     */
    protected function parse(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $current++;
        if (!$source || $current >= mb_strlen($source)) {
            $finish = true;

            return;
        }
        if (!isset($source[$current - 1]) || $source[$current - 1] !== '\\') {
            if ($source[$current] === ' ') {
                $image = $source[$current];
                $type = Token::T_WHITE_SPACE;

                return;
            } elseif ($source[$current] === '=') {
                $image = $source[$current];
                $type = Token::T_EQUAL;

                return;
            } elseif ($source[$current] === '|' && isset($source[$current + 1]) && $source[$current + 1] === '|') {
                $image = $source[$current] . $source[$current + 1];
                $current++;
                $type = Token::T_OR;

                return;
            } elseif ($source[$current] === '|') {
                $image = $source[$current];
                $type = Token::T_PIPE;

                return;
            } elseif ($source[$current] === '&' && isset($source[$current + 1]) && $source[$current + 1] === '&') {
                $image = $source[$current] . $source[$current + 1];
                $current++;
                $type = Token::T_AND;

                return;
            } elseif ($source[$current] === '&') {
                $image = $source[$current];
                $type = Token::T_AMPERSAND;

                return;
            } elseif ($source[$current] === ';') {
                $image = $source[$current];
                $type = Token::T_SEMICOLON;

                return;
            } elseif ($source[$current] === '!') {
                $image = $source[$current];
                $type = Token::T_NOT;

                return;
            } elseif ($source[$current] === '{') {
                $image = $source[$current];
                $type = Token::T_BRACES_OPEN;

                return;
            } elseif ($source[$current] === '}') {
                $image = $source[$current];
                $type = Token::T_BRACES_CLOSE;

                return;
            } elseif ($source[$current] === '(') {
                $image = $source[$current];
                $type = Token::T_PARENTHESES_OPEN;

                return;
            } elseif ($source[$current] === ')') {
                $image = $source[$current];
                $type = Token::T_PARENTHESES_CLOSE;

                return;
            } elseif ($source[$current] === '"') {
                $image = $source[$current];
                $type = Token::T_QUOTE;
                $quote = !$quote;

                return;
            } elseif ($source[$current] === '\'') {
                $image = $source[$current];
                $type = Token::T_QUOTE;
                $single = !$single;

                return;
            } elseif ($source[$current] === '-' && isset($source[$current + 1]) && $source[$current + 1] === '-') {
                $this->setParseFunction('parseOption');

                return;
            } elseif ($source[$current] === '-') {
                $this->setParseFunction('parseShortOption');

                return;
            }
        }

        $count = count($tokens);

        if (
            (!$quote && !$single && $count >= 2
                && $tokens[$count - 1]->getType() === Token::T_EQUAL
                && $tokens[$count - 2]->getType() === Token::T_OPTION
            ) || (($quote || $single) && $count >= 3
                && $tokens[$count - 2]->getType() === Token::T_EQUAL
                && $tokens[$count - 3]->getType() === Token::T_OPTION)
        ) {
            $this->setParseFunction('parseOptionValue');

            return;
        }
        $this->setParseFunction('parseArgument');
    }

    /**
     * Парсинг аргумента
     *
     * @param IToken[]    $tokens
     */
    protected function parseArgument(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $type = Token::T_ARGUMENT;
        $this->parseBase($source, $current, $image, $quote, $single);
    }

    /**
     * Основной парсер
     */
    protected function parseBase(
        string &$source,
        int &$current,
        string &$image,
        bool &$quote,
        bool &$single
    ): void {
        do {
            if (
                !$quote
                && !$single
                && (
                    $source[$current] === '&'
                    || $source[$current] === ';'
                    || $source[$current] === '|'
                    || $source[$current] === '!'
                    || $source[$current] === '{'
                    || $source[$current] === '}'
                    || $source[$current] === '('
                    || $source[$current] === ')'
                )
            ) {
                $loop = false;
            } else {
                if (isset($source[$current])) {
                    $image .= $source[$current];
                }
                $loop = $this->logicWQS($source, $current, $quote, $single);
                $current++;
            }
        } while ($loop);
        $current--;
        $this->setParseFunction('parse');
    }

    /**
     * Парсинг опции
     *
     * @param IToken[]    $tokens
     */
    protected function parseOption(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $type = Token::T_OPTION;
        do {
            if (isset($source[$current])) {
                $image .= $source[$current];
            }
            $loop = $this->logicWQS($source, $current, $quote, $single);
            if ($loop && !$quote && !$single && $source[$current] !== '\\' && $source[$current + 1] === '=') {
                $loop = false;
            }
            $current++;
        } while ($loop);
        $current--;
        $this->setParseFunction('parse');
    }

    /**
     * Парсинг значения опции
     *
     * @param IToken[]    $tokens
     */
    protected function parseOptionValue(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $type = Token::T_OPTION_VALUE;
        $this->parseBase($source, $current, $image, $quote, $single);
    }

    /**
     * Парсинг опций в сокращенной нотации
     *
     * @param IToken[]    $tokens
     */
    protected function parseShortOption(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $type = Token::T_SHORT_OPTION;
        $this->parseBase($source, $current, $image, $quote, $single);
    }

    /**
     * Основная логика выхода из цикла
     */
    protected function logicWQS(string $source, int $current, bool $quote, bool $single): bool
    {
        if (!isset($source[$current + 1])) {
            return false;
        } elseif (!$quote && !$single && $source[$current] !== '\\' && $source[$current + 1] === ' ') {
            return false;
        } elseif (!$single && $quote && $source[$current] !== '\\' && $source[$current + 1] === '"') {
            return false;
        } elseif (!$quote && $single && $source[$current] !== '\\' && $source[$current + 1] === '\'') {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public static function getTokenFactory()
    {
        return TokenFactory::class;
    }
}
