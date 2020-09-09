<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer;

use const PHP_EOL;

/**
 * Методы для парсера
 */
abstract class AParseFunction extends ATokenizer
{
    /**
     * @var string
     */
    private $parseFunction = null;

    /**
     * Возвращает функцию парсинга
     */
    protected function getParseFunction(): string
    {
        return $this->parseFunction;
    }

    /**
     * Устанавливает функцию парсинга
     *
     * @return $this
     */
    protected function setParseFunction(string $parseFunction): self
    {
        $this->parseFunction = $parseFunction;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function tokenize(): void
    {
        $factory = static::getTokenFactory();
        $source = $this->escape($this->getSource());
        $endLine = 1;
        $startLine = 1;
        $startColumn = 1;
        $tokens = [];
        $finish = false;
        $current = -1;
        $image = '';
        $type = null;
        $quote = false;
        $single = false;
        do {
            $func = $this->getParseFunction();
            $this->$func($finish, $source, $current, $image, $type, $tokens, $quote, $single);
            if (!is_null($type)) {
                $image = $this->unescape($image);
                $endColumn = $startColumn + mb_strlen($image);
                $endLine += mb_substr_count($image, PHP_EOL);
                $tokens[] = $factory::factory($type, $image, $startLine, $endLine, $startColumn, $endColumn);
                $type = null;
                $image = '';
                $startLine = $endLine;
                $startColumn = $endColumn;
            }
        } while (!$finish);
        $this->setCount(count($tokens))
            ->setTokens($tokens);
    }

    /**
     * Преобразование
     */
    protected function escape(string $source): string
    {
        return str_replace('\\\\', chr(27), $source);
    }

    /**
     * Обратное преобразование
     */
    protected function unescape(string $source): string
    {
        return str_replace(chr(27), '\\\\', $source);
    }
}
