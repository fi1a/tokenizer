<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer;

/**
 * Абстрактный класс лексического анализатора, реализующий интерфейс ITokenizer
 */
abstract class ATokenizer implements ITokenizer
{
    /**
     * Преобразует исходный код в токены
     */
    abstract protected function tokenize(): void;

    /**
     * @var string
     */
    protected static $workEncoding = 'UTF-8';

    /**
     * @var string
     */
    private $encoding = '';

    /**
     * @var IToken[]
     */
    private $tokens = [];

    /**
     * @var string
     */
    private $source = '';

    /**
     * @var int
     */
    private $index = -1;

    /**
     * @var int
     */
    private $count = 0;

    /**
     * @inheritDoc
     */
    public function __construct(string $source, ?string $encoding = null)
    {
        $this->setEncoding($encoding)
            ->setSource($source)
            ->tokenize();
    }

    /**
     * Устанавливает исходный код
     *
     * @param string $source исходный код
     *
     * @return $this
     */
    protected function setSource(string $source): self
    {
        $this->tokens = [];
        $this->source = $this->convertEncodingToWork($source);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSource(): string
    {
        return $this->convertEncodingToOrigin($this->source);
    }

    /**
     * @inheritDoc
     */
    public function getSourceIntEnc(): string
    {
        return $this->source;
    }

    /**
     * @inheritDoc
     */
    public function next(int $index = 1)
    {
        $tokens = $this->getTokens();
        $index = $this->getIndex() + $index;
        if ($index >= $this->getCount()) {
            return self::T_EOF;
        }
        $this->setIndex($index);

        return $tokens[$index];
    }

    /**
     * @inheritDoc
     */
    public function prev(int $index = 1)
    {
        $index = $this->getIndex() - $index;
        if ($index < 0) {
            return self::T_BOF;
        }
        $tokens = $this->getTokens();
        $this->setIndex($index);

        return $tokens[$index];
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        $index = $this->getIndex();
        $tokens = $this->getTokens();

        return $tokens[$index];
    }

    /**
     * @inheritDoc
     */
    public function peekType(): int
    {
        $tokens = $this->getTokens();

        return $tokens[$this->getIndex()]->getType();
    }

    /**
     * @inheritDoc
     */
    public function peekNextType(): int
    {
        $token = $this->next();
        if (!($token instanceof IToken)) {
            return self::T_EOF;
        }

        return $token->getType();
    }

    /**
     * @inheritDoc
     */
    public function peekPrevType(): int
    {
        $token = $this->prev();
        if (!($token instanceof IToken)) {
            return self::T_BOF;
        }

        return $token->getType();
    }

    /**
     * Устанавливает токены
     *
     * @param IToken[] $tokens токены
     *
     * @return $this
     */
    protected function setTokens(array $tokens): self
    {
        $this->tokens = $tokens;

        return $this;
    }

    /**
     * Возвращает токены
     *
     * @return IToken[]
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * Возвращает текущий индекс токена
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * Устанавливает текущий индекс токена
     *
     * @return $this
     */
    protected function setIndex(int $index): self
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Возвращает кол-во токенов
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Устанавливает кол-во токенов
     *
     * @return $this
     */
    protected function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function lookAtNext(int $count = 1)
    {
        $tokens = $this->getTokens();
        $index = $this->getIndex() + $count;
        if ($index >= $this->getCount()) {
            return self::T_EOF;
        }

        return $tokens[$index];
    }

    /**
     * @inheritDoc
     */
    public function lookAtNextType(int $count = 1): int
    {
        $token = $this->lookAtNext($count);
        if (!($token instanceof IToken)) {
            return $token;
        }

        return $token->getType();
    }

    /**
     * @inheritDoc
     */
    public function lookAtNextImage(int $count = 1)
    {
        $token = $this->lookAtNext($count);
        if (!($token instanceof IToken)) {
            return $token;
        }

        return $token->getImage();
    }

    /**
     * @inheritDoc
     */
    public function lookAtPrev(int $count = 1)
    {
        $index = $this->getIndex() - $count;
        if ($index < 0) {
            return self::T_BOF;
        }
        $tokens = $this->getTokens();

        return $tokens[$index];
    }

    /**
     * @inheritDoc
     */
    public function lookAtPrevType(int $count = 1): int
    {
        $token = $this->lookAtPrev($count);
        if (!($token instanceof IToken)) {
            return $token;
        }

        return $token->getType();
    }

    /**
     * @inheritDoc
     */
    public function lookAtPrevImage(int $count = 1)
    {
        $token = $this->lookAtPrev($count);
        if (!($token instanceof IToken)) {
            return $token;
        }

        return $token->getImage();
    }

    /**
     * Устанавливает кодировку исходного текста
     *
     * @param string $encoding кодировка (UTF-8,...)
     *
     * @return $this
     */
    protected function setEncoding(?string $encoding = null): self
    {
        if (!$encoding) {
            $encoding = static::$workEncoding;
        }
        $this->encoding = $encoding;

        return $this;
    }

    /**
     * Возвращает кодировку исходного текста
     */
    public function getEncoding(): string
    {
        return $this->encoding;
    }

    /**
     * Конвертирует кодировку в рабочую
     *
     * @param string $string строка
     *
     * @return string сконвертированная строка
     */
    protected function convertEncodingToWork(string $string): string
    {
        if (mb_strtoupper($this->getEncoding()) !== static::$workEncoding) {
            $string = iconv($this->getEncoding(), static::$workEncoding, $string);
        }

        return $string;
    }

    /**
     * Конвертирует кодировку в оригинальную
     *
     * @param string $string строка
     *
     * @return string сконвертированная строка
     */
    protected function convertEncodingToOrigin(string $string): string
    {
        if (mb_strtoupper($this->getEncoding()) !== static::$workEncoding) {
            $string = iconv(static::$workEncoding, $this->getEncoding(), $string);
        }

        return $string;
    }
}
