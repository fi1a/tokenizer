<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer;

/**
 * Абстрактный токен
 */
abstract class AToken implements IToken
{
    public const E_TYPE = 210;

    /**
     * Возвращает доступные типы токенов
     *
     * @return int[]
     */
    abstract protected function getTypes(): array;

    /**
     * @var int
     */
    private $type = null;

    /**
     * @var string
     */
    private $image = '';

    /**
     * @var int
     */
    private $startLine = null;

    /**
     * @var int
     */
    private $endLine = null;

    /**
     * @var int
     */
    private $startColumn = null;

    /**
     * @var int
     */
    private $endColumn = null;

    /**
     * @inheritDoc
     */
    public function __construct(
        int $type,
        string $image,
        int $startLine,
        int $endLine,
        int $startColumn,
        int $endColumn
    ) {
        $this->setType($type)
            ->setImage($image)
            ->setStartLine($startLine)
            ->setEndLine($endLine)
            ->setStartColumn($startColumn)
            ->setEndColumn($endColumn);
    }

    /**
     * @inheritDoc
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @inheritDoc
     */
    public function getStartLine(): int
    {
        return $this->startLine;
    }

    /**
     * @inheritDoc
     */
    public function getEndLine(): int
    {
        return $this->endLine;
    }

    /**
     * @inheritDoc
     */
    public function getStartColumn(): int
    {
        return $this->startColumn;
    }

    /**
     * @inheritDoc
     */
    public function getEndColumn(): int
    {
        return $this->endColumn;
    }

    /**
     * Устанавливает тип токена
     *
     * @param int $type тип токена
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    protected function setType(int $type): self
    {
        if (!in_array($type, $this->getTypes())) {
            throw new InvalidArgumentException('Unknown type');
        }
        $this->type = $type;

        return $this;
    }

    /**
     * Устанавливает изображение токена
     *
     * @param string $image изображение
     *
     * @return $this
     */
    protected function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Устанавливает номер первой строки
     *
     * @param int $startLine номер строки
     *
     * @return $this
     */
    protected function setStartLine(int $startLine): self
    {
        $this->startLine = $startLine;

        return $this;
    }

    /**
     * Устанавливает номер последней строки
     *
     * @param int $endLine номер строки
     *
     * @return $this
     */
    protected function setEndLine(int $endLine): self
    {
        $this->endLine = $endLine;

        return $this;
    }

    /**
     * Устанавливает номер символа в первой строке
     *
     * @param int $startColumn номер символа
     *
     * @return $this
     */
    protected function setStartColumn(int $startColumn): self
    {
        $this->startColumn = $startColumn;

        return $this;
    }

    /**
     * Устанавливает номер символа в последней строке
     *
     * @param int $endColumn номер символа
     *
     * @return $this
     */
    protected function setEndColumn(int $endColumn): self
    {
        $this->endColumn = $endColumn;

        return $this;
    }
}
