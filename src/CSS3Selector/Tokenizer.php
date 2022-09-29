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
     * @var int[][][]
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
     * @var int[][][]
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
     * @var int[]
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
     * @var string[]
     */
    private static $singleWithParse = [
        '#' => 'parseId',
        ':' => 'parsePseudo',
        '.' => 'parseClass',
    ];

    /**
     * @var int[]
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

        $symbol = mb_substr($source, $current, 1);
        $prevSymbol = mb_substr($source, $current - 1, 1);
        $nextSymbol = mb_substr($source, $current + 1, 1);

        if (preg_match('/[\t\s\n]/', $symbol)) {
            $this->setParseFunction('parseWhitespace');

            return;
        }
        if (
            array_key_exists($symbol, self::$attributeOperators)
            && $nextSymbol === '='
        ) {
            $image = $symbol . $nextSymbol;
            $type = self::$attributeOperators[$symbol];
            $current++;

            return;
        }

        if (array_key_exists($symbol, self::$singleWithParse)) {
            $this->setParseFunction(self::$singleWithParse[$symbol]);

            return;
        }

        if (array_key_exists($symbol, self::$single)) {
            $image = $symbol;
            $type = self::$single[$symbol];

            return;
        }

        if ($symbol === '"') {
            $image = $symbol;
            $type = Token::T_QUOTE;
            $quote = !$quote;

            return;
        }
        if ($symbol === '\'') {
            $image = $symbol;
            $type = Token::T_QUOTE;
            $single = !$single;

            return;
        }

        if (
            $prevSymbol === '['
            && preg_match('/[a-z0-9\-\_]/i', $symbol)
        ) {
            $this->setParseFunction('parseAttribute');

            return;
        }
        if ($this->sequence($tokens, Token::T_ATTRIBUTE, self::$attributeSequence)) {
            $this->setParseFunction('parseAttributeValue');

            return;
        }
        if ($this->sequence($tokens, Token::T_PSEUDO, self::$pseudoSequence)) {
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
        /**
         * @var int $ind
         * @var int[][] $values
         */
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
            $symbol = mb_substr($source, $current, 1);
            $nextSymbol = mb_substr($source, $current + 1, 1);

            if ($current < mb_strlen($source)) {
                $image .= $symbol;
            }
            if (
                $current >= mb_strlen($source)
                || (!$single && !$quote && $nextSymbol === ']')
                || (!$single && !$quote && $nextSymbol === ')')
                || (!$single && $quote && $symbol !== '\\' && $nextSymbol === '"')
                || (!$quote && $single && $symbol !== '\\' && $nextSymbol === '\'')
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
        $this->parseWithEscape($source, $current, $image, $quote, $single);
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
        $this->parseWithEscape($source, $current, $image, $quote, $single);
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
            $symbol = mb_substr($source, $current, 1);
            $nextSymbol = mb_substr($source, $current + 1, 1);

            if ($current < mb_strlen($source)) {
                $image .= $symbol;
            }
            if ($current >= mb_strlen($source)) {
                $loop = false;
            } elseif (preg_match('/[^\s\t\n]/', $nextSymbol)) {
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
        $symbol = mb_substr($source, $current, 1);

        if (preg_match('/[^a-z0-9\-\_\*]/i', $symbol)) {
            $type = Token::T_UNKNOWN_TOKEN_TYPE;
            $image .= $symbol;
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
            $symbol = mb_substr($source, $current, 1);

            if ($current < mb_strlen($source)) {
                $image .= $symbol;
            }
            $loop = $this->logicWQS($source, $current, $quote, $single);
            $current++;
        } while ($loop);
        $current--;
        $this->setParseFunction('parse');
    }

    /**
     * Основной парсер для ID и классов
     */
    protected function parseWithEscape(
        string &$source,
        int &$current,
        string &$image,
        bool &$quote,
        bool &$single
    ): void {
        do {
            $symbol = mb_substr($source, $current, 1);
            $prevSymbol = mb_substr($source, $current - 1, 1);

            if (
                $current < mb_strlen($source)
                && (
                    $symbol !== '\\'
                    || ($current > 0 && $prevSymbol === '\\')
                )
            ) {
                $image .= $symbol;
            }
            $loop = $this->logicWQSWithEscape($source, $current, $quote, $single);
            $current++;
        } while ($loop);
        $current--;
        $this->setParseFunction('parse');
    }

    /**
     * Основная логика выхода из цикла
     */
    protected function logicWQSWithEscape(string $source, int $current, bool $quote, bool $single): bool
    {
        $symbol = mb_substr($source, $current, 1);
        $nextSymbol = mb_substr($source, $current + 1, 1);

        if ($current + 1 >= mb_strlen($source)) {
            return false;
        } elseif (preg_match('/[^a-z0-9\-\_\\\]/im', $nextSymbol) && $symbol !== '\\') {
            return false;
        }

        return true;
    }

    /**
     * Основная логика выхода из цикла
     */
    protected function logicWQS(string $source, int $current, bool $quote, bool $single): bool
    {
        $nextSymbol = mb_substr($source, $current + 1, 1);

        if ($current + 1 >= mb_strlen($source)) {
            return false;
        } elseif (preg_match('/[^a-z0-9\-\_]/i', $nextSymbol)) {
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
