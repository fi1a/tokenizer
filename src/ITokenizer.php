<?php

declare(strict_types=1);

namespace Fi1a\Tokenizer;

/**
 * Интерфейс лексического анализатора
 */
interface ITokenizer
{
    public const T_EOF = -2;

    public const T_BOF = -1;

    /**
     * Конструктор
     *
     * @param string $source   исходный код
     * @param string $encoding кодировка исходного текста
     */
    public function __construct(string $source, ?string $encoding = null);

    /**
     * Возвращает исходный код
     */
    public function getSource(): string;

    /**
     * Возвращает кодировку исходного текста
     */
    public function getEncoding(): string;

    /**
     * Возвращает исходный код в UTF-8 кодировке
     */
    public function getSourceIntEnc(): string;

    /**
     * Возвращает следующий токен. При достижении конца очереди возвращает ITokenizer::T_EOF.
     *
     * @return IToken|int
     */
    public function next(int $index = 1);

    /**
     * Возвращает предыдущий токен. При достижении начала очереди возвращает ITokenizer::T_BOF.
     *
     * @return IToken|int
     */
    public function prev(int $index = 1);

    /**
     * Возвращает текущий токен
     *
     * @return IToken|int
     */
    public function current();

    /**
     * Возвращает тип текущего токена. Если достигнут конец очереди, возвращает ITokenizer::T_EOF.
     */
    public function peekType(): int;

    /**
     * Возвращает тип следующего токена. Если достигнут конец очереди, возвращает ITokenizer::T_EOF.
     */
    public function peekNextType(): int;

    /**
     * Возвращает тип предыдущего токена. Если достигнуто начало очереди, возвращает ITokenizer::T_BOF.
     */
    public function peekPrevType(): int;

    /**
     * Возвращает кол-во токенов
     */
    public function getCount(): int;

    /**
     * Возвращает текущий индекс токена
     */
    public function getIndex(): int;

    /**
     * Возвращает токены
     *
     * @return IToken[]
     */
    public function getTokens(): array;

    /**
     * Возвращает следующий тип без смещения указателя
     */
    public function lookAtNextType(int $count = 1): int;

    /**
     * Возвращает следующее изображение без смещения указателя
     *
     * @return string|int
     */
    public function lookAtNextImage(int $count = 1);

    /**
     * Возвращает предыдущей тип без смещения указателя
     */
    public function lookAtPrevType(int $count = 1): int;

    /**
     * Возвращает предыдущее изображение без смещения указателя
     *
     * @return string|int
     */
    public function lookAtPrevImage(int $count = 1);

    /**
     * Возвращает класс фабрики токенов
     *
     * @return ITokenFactory
     */
    public static function getTokenFactory();
}
