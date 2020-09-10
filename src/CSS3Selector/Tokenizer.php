<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer\CSS3Selector;

use Fi1a\Tokenizer\AParseFunction;
use Fi1a\Tokenizer\IToken;

/**
 * Лексический анализатор для CSS3 селектора
 */
class Tokenizer extends AParseFunction
{
    /**
     * @var array
     */
    private static $attributeSequence = [
        2 => [
            [
                Token::T_EQUAL,
                Token::T_EITHER_EQUAL,
                Token::T_CONTAINING,
                Token::T_CONTAINING_WORD,
                Token::T_ENDING_EXACTLY,
                Token::T_NOT_EQUAL,
                Token::T_BEGINNING_EXACTLY,
            ],
            [
                Token::T_ATTRIBUTE,
            ],
        ],
        3 => [
            [
                Token::T_QUOTE,
            ],
            [
                Token::T_EQUAL,
                Token::T_EITHER_EQUAL,
                Token::T_CONTAINING,
                Token::T_CONTAINING_WORD,
                Token::T_ENDING_EXACTLY,
                Token::T_NOT_EQUAL,
                Token::T_BEGINNING_EXACTLY,
            ],
            [
                Token::T_ATTRIBUTE,
            ],
        ],
        4 => [
            [
                Token::T_QUOTE,
            ],
            [
                Token::T_WHITE_SPACE,
            ],
            [
                Token::T_EQUAL,
                Token::T_EITHER_EQUAL,
                Token::T_CONTAINING,
                Token::T_CONTAINING_WORD,
                Token::T_ENDING_EXACTLY,
                Token::T_NOT_EQUAL,
                Token::T_BEGINNING_EXACTLY,
            ],
            [
                Token::T_ATTRIBUTE,
            ],
        ],
        5 => [
            [
                Token::T_QUOTE,
            ],
            [
                Token::T_WHITE_SPACE,
            ],
            [
                Token::T_EQUAL,
                Token::T_EITHER_EQUAL,
                Token::T_CONTAINING,
                Token::T_CONTAINING_WORD,
                Token::T_ENDING_EXACTLY,
                Token::T_NOT_EQUAL,
                Token::T_BEGINNING_EXACTLY,
            ],
            [
                Token::T_WHITE_SPACE,
            ],
            [
                Token::T_ATTRIBUTE,
            ],
        ],
    ];

    /**
     * @var array
     */
    private static $pseudoSequence = [
        // :eq(value
        2 => [
            [Token::T_OPEN_BRACKET,],
            [Token::T_PSEUDO,],
        ],
        // :eq( value
        // :eq("value
        3 => [
            [Token::T_QUOTE, Token::T_WHITE_SPACE,],
            [Token::T_OPEN_BRACKET,],
            [Token::T_PSEUDO,],
        ],
        // :eq( "value
        4 => [
            [Token::T_QUOTE,],
            [Token::T_WHITE_SPACE,],
            [Token::T_OPEN_BRACKET,],
            [Token::T_PSEUDO,],
        ],
    ];

    /**
     * @var array
     */
    private static $attributeOperators = [
        '|' => Token::T_EITHER_EQUAL,
        '*' => Token::T_CONTAINING,
        '~' => Token::T_CONTAINING_WORD,
        '$' => Token::T_ENDING_EXACTLY,
        '!' => Token::T_NOT_EQUAL,
        '^' => Token::T_BEGINNING_EXACTLY,
    ];

    /**
     * @var array
     */
    private static $singleWithParse = [
        '#' => 'parseId',
        ':' => 'parsePseudo',
        '.' => 'parseClass',
    ];

    /**
     * @var array
     */
    private static $single = [
        ',' => Token::T_MULTIPLE_SELECTOR,
        '[' => Token::T_OPEN_ATTRIBUTE,
        ']' => Token::T_CLOSE_ATTRIBUTE,
        '(' => Token::T_OPEN_BRACKET,
        ')' => Token::T_CLOSE_BRACKET,
        '=' => Token::T_EQUAL,
        '>' => Token::T_DIRECT_CHILD,
        '~' => Token::T_SIBLING_AFTER,
        '+' => Token::T_SIBLING_NEXT,
    ];

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

        if (preg_match('/[\t\s\n]/', $source[$current])) {
            $this->setParseFunction('parseWhitespace');

            return;
        }
        if (
            array_key_exists($source[$current], static::$attributeOperators)
            && isset($source[$current + 1]) && $source[$current + 1] === '='
        ) {
            $image = $source[$current] . $source[$current + 1];
            $type = static::$attributeOperators[$source[$current]];
            $current++;

            return;
        }

        if (array_key_exists($source[$current], static::$singleWithParse)) {
            $this->setParseFunction(static::$singleWithParse[$source[$current]]);

            return;
        }

        if (array_key_exists($source[$current], static::$single)) {
            $image = $source[$current];
            $type = static::$single[$source[$current]];

            return;
        }

        if ($source[$current] === '"') {
            $image = $source[$current];
            $type = Token::T_QUOTE;
            $quote = !$quote;

            return;
        }
        if ($source[$current] === '\'') {
            $image = $source[$current];
            $type = Token::T_QUOTE;
            $single = !$single;

            return;
        }

        if (
            isset($source[$current - 1])
            && $source[$current - 1] === '['
            && preg_match('/[a-z0-9\-\_]/i', $source[$current])
        ) {
            $this->setParseFunction('parseAttribute');

            return;
        }
        if ($this->sequence($tokens, Token::T_ATTRIBUTE, static::$attributeSequence)) {
            $this->setParseFunction('parseAttributeValue');

            return;
        }
        if ($this->sequence($tokens, Token::T_PSEUDO, static::$pseudoSequence)) {
            $this->setParseFunction('parsePseudoValue');

            return;
        }

        $this->setParseFunction('parseTag');
    }

    /**
     * Метод, определяющий последовательность
     *
     * @param IToken[] $tokens
     * @param mixed[] $sequences
     */
    protected function sequence(array $tokens, int $type, array $sequences): bool
    {
        $count = count($tokens);
        $sequence = [];
        for ($ind = $count - 1; $ind >= 0; $ind--) {
            $sequence[] = $tokens[$ind]->getType();
            if ($type === $tokens[$ind]->getType()) {
                break;
            }
        }
        if (!array_key_exists(count($sequence), $sequences)) {
            return false;
        }
        foreach ($sequences[count($sequence)] as $ind => $values) {
            if (!in_array($sequence[$ind], $values)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Парсинг значения атрибута
     *
     * @param IToken[]    $tokens
     */
    protected function parsePseudoValue(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $type = Token::T_PSEUDO_VALUE;
        $this->parseValue($source, $current, $image, $quote, $single);
    }

    /**
     * Парсер значений
     */
    protected function parseValue(
        string &$source,
        int &$current,
        string &$image,
        bool &$quote,
        bool &$single
    ): void {
        $loop = true;

        do {
            if (isset($source[$current])) {
                $image .= $source[$current];
            }
            if (
                !isset($source[$current + 1])
                || (!$single && !$quote && $source[$current + 1] === ']')
                || (!$single && !$quote && $source[$current + 1] === ')')
                || (!$single && $quote && $source[$current] !== '\\' && $source[$current + 1] === '"')
                || (!$quote && $single && $source[$current] !== '\\' && $source[$current + 1] === '\'')
            ) {
                $loop = false;
            }
            $current++;
        } while ($loop);
        $current--;
        $this->setParseFunction('parse');
    }

    /**
     * Парсинг псевдокласса
     *
     * @param IToken[]    $tokens
     */
    protected function parsePseudo(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $type = Token::T_PSEUDO;
        $this->parseBase($source, $current, $image, $quote, $single);
    }

    /**
     * Парсинг идентификатора
     *
     * @param IToken[]    $tokens
     */
    protected function parseId(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $type = Token::T_ID;
        $this->parseBase($source, $current, $image, $quote, $single);
    }

    /**
     * Парсинг значения атрибута
     *
     * @param IToken[]    $tokens
     */
    protected function parseAttributeValue(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $type = Token::T_ATTRIBUTE_VALUE;
        $this->parseValue($source, $current, $image, $quote, $single);
    }

    /**
     * Парсинг названия атрибута
     *
     * @param IToken[]    $tokens
     */
    protected function parseAttribute(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $type = Token::T_ATTRIBUTE;
        $this->parseBase($source, $current, $image, $quote, $single);
    }

    /**
     * Парсинг класса
     *
     * @param IToken[]    $tokens
     */
    protected function parseClass(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $type = Token::T_CLASS;
        $this->parseBase($source, $current, $image, $quote, $single);
    }

    /**
     * Парсинг пробела
     *
     * @param IToken[]    $tokens
     */
    protected function parseWhitespace(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $type = Token::T_WHITE_SPACE;
        $loop = true;
        do {
            if (isset($source[$current])) {
                $image .= $source[$current];
            }
            if (!isset($source[$current + 1])) {
                $loop = false;
            } elseif (preg_match('/[^\s\t\n]/', $source[$current + 1])) {
                $loop = false;
            }
            $current++;
        } while ($loop);
        $current--;
        $this->setParseFunction('parse');
    }

    /**
     * Парсинг тега
     *
     * @param IToken[]    $tokens
     */
    protected function parseTag(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        if (preg_match('/[^a-z0-9\-\_\*]/i', $source[$current])) {
            $type = Token::T_UNKNOWN_TOKEN_TYPE;
            $image .= $source[$current];
            $current++;

            return;
        }
        $type = Token::T_TAG;
        $this->parseBase($source, $current, $image, $quote, $single);
    }

    /**
     * Основной парсер для тегов и т.д.
     */
    protected function parseBase(
        string &$source,
        int &$current,
        string &$image,
        bool &$quote,
        bool &$single
    ): void {
        do {
            if (isset($source[$current])) {
                $image .= $source[$current];
            }
            $loop = $this->logicWQS($source, $current, $quote, $single);
            $current++;
        } while ($loop);
        $current--;
        $this->setParseFunction('parse');
    }

    /**
     * Основная логика выхода из цикла
     */
    protected function logicWQS(string $source, int $current, bool $quote, bool $single): bool
    {
        if (!isset($source[$current + 1])) {
            return false;
        } elseif (preg_match('/[^a-z0-9\-\_]/i', $source[$current + 1])) {
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
