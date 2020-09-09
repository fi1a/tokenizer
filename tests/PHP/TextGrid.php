<?php

declare(strict_types=1);

namespace Fi1a\Unit\Tokenizer\PHP;

/**
 * Работа с текстом используя линии и колонки
 */
class TextGrid
{
    /**
     * @var string
     */
    protected static $workEncoding = 'UTF-8';

    /**
     * @var string
     */
    private $encoding = null;

    /**
     * @var string
     */
    private $text = '';

    /**
     * @var array
     */
    private $lines = [];

    /**
     * Конструктор
     *
     * @param string      $text     текст
     * @param string|null $encoding кодировка текста
     */
    public function __construct(string $text, ?string $encoding = null)
    {
        $this->setEncoding($encoding);
        $this->setText($text);
    }

    /**
     * Возвращает весь текст
     */
    public function getText(): string
    {
        return $this->convertEncodingToOrigin($this->text);
    }

    /**
     * Устанавливает текст
     *
     * @param string $text текст
     *
     * @return $this
     */
    protected function setText(string $text): self
    {
        $this->text = $this->convertEncodingToWork((string) $text);
        $this->setLines(explode("\n", $this->text));

        return $this;
    }

    /**
     * Возвращает часть текста исходя из переданных параметров
     *
     * <code>
     * $text = "The PHP development team announces the immediate availability of PHP 7.0.3.
     * This is a security release. Several security bugs were fixed in this release.
     * All PHP 7.0 users are encouraged to upgrade to this version.
     *
     * For source downloads of PHP 7.0.3 please visit our downloads page,
     * Windows source and binaries can be found on windows.php.net/download/.
     * The list of changes is recorded in the ChangeLog.";
     *
     * $grid = new TextGrid($text);
     * $grid->get(3, 5, 13, 34); // return ->
     * //users are encouraged to upgrade to this version.
     * //
     * //For source downloads of PHP 7.0.3
     *
     * $grid->get(13, 34, 3, 5); // return ->
     * //users are encouraged to upgrade to this version.
     * //
     * //For source downloads of PHP 7.0.3
     *
     * $grid->get(1, 1); // return "The PHP development team announces the immediate availability of PHP 7.0.3.\n"
     * $grid->get(1, 1, 1, 1); // return ""
     * </code>
     *
     * @param int      $startLine   номер начальной строки
     * @param int      $startColumn номер колонки в начальной строке
     * @param int|null $endLine номер конечной строки
     * @param int|null $endColumn номер колонки в конечной строке
     */
    public function get(int $startLine, int $startColumn, ?int $endLine = null, ?int $endColumn = null): string
    {
        if (is_null($endLine)) {
            $endLine = $startLine;
        }
        if (is_null($endColumn)) {
            $endColumn = $this->getCountColumns($endLine);
        }
        if ($startColumn === $endColumn && $startLine === $endLine) {
            return '';
        }
        $this->switchLinesColumns($startLine, $startColumn, $endLine, $endColumn);
        if ($startLine === $endLine) {
            return $this->getSingleLine($startLine, $startColumn, $endColumn);
        }

        return $this->getMultipleLines($startLine, $startColumn, $endLine, $endColumn);
    }

    /**
     * Меняет местами строки и колонки
     *
     * @param int $startLine   номер начальной строки
     * @param int $startColumn номер колонки в начальной строке
     * @param int $endLine     номер конечной строки
     * @param int $endColumn   номер колонки в конечной строке
     */
    private function switchLinesColumns(int &$startLine, int &$startColumn, int &$endLine, int &$endColumn): void
    {
        if ($startLine === $endLine && $startColumn > $endColumn) {
            [$endColumn, $startColumn] = [$startColumn, $endColumn];
        }
        if ($startLine > $endLine) {
            [$endLine, $startLine] = [$startLine, $endLine];
            [$endColumn, $startColumn] = [$startColumn, $endColumn];
        }
    }

    /**
     * Возвращает из одной строки
     *
     * @param int $startLine   номер начальной строки
     * @param int $startColumn номер колонки в начальной строке
     * @param int $endColumn   номер колонки в конечной строке
     */
    private function getSingleLine(int $startLine, int $startColumn, int $endColumn): string
    {
        $lines = $this->getLines();

        return mb_substr(
            $lines[$startLine - 1],
            $startColumn - 1,
            $endColumn - $startColumn
        );
    }

    /**
     * Возвращает из множества строк
     *
     * @param int $startLine   номер начальной строки
     * @param int $startColumn номер колонки в начальной строке
     * @param int $endLine     номер конечной строки
     * @param int $endColumn   номер колонки в конечной строке
     */
    private function getMultipleLines(int $startLine, int $startColumn, int $endLine, int $endColumn): string
    {
        $lines = $this->getLines();
        $string = '';
        for ($line = $startLine; $line <= $endLine; $line++) {
            if ($line > $startLine) {
                $string .= "\n";
            }
            if ($line === $startLine) {
                $string .= mb_substr($lines[$line - 1], $startColumn - 1);

                continue;
            } elseif ($line === $endLine) {
                $string .= mb_substr($lines[$line - 1], 0, $endColumn - 1);

                continue;
            }
            $string .= $lines[$line - 1];
        }

        return $string;
    }

    /**
     * Возвращает строки
     *
     * @return string[]
     */
    private function getLines(): array
    {
        return $this->lines;
    }

    /**
     * Устанавливает строки
     *
     * @param string[] $lines
     *
     * @return $this
     */
    private function setLines(array $lines): self
    {
        $this->lines = array_values($lines);

        return $this;
    }

    /**
     * Вставляет часть текста исходя из переданных параметров
     *
     * <code>
     * $text = "The PHP development team announces the immediate availability of PHP 7.0.3.
     * This is a security release. Several security bugs were fixed in this release.";
     *
     * $grid = new TextGrid($text);
     * $grid->set("INSERT TEXT LINE 1\nINSERT TEXT LINE 2 ", 1, 9);
     * //The PHP INSERT TEXT LINE 1
     * //INSERT TEXT LINE 2 development team announces the immediate availability of PHP 7.0.3.
     * //This is a security release. Several security bugs were fixed in this release.
     *
     * $grid->set("INSERT TEXT LINE 1\nINSERT TEXT LINE 2 ", 1, 9, 1, 9);
     * </code>
     *
     * <code>
     * $text = "The PHP development team announces the immediate availability of PHP 7.0.3.
     * This is a security release. Several security bugs were fixed in this release.";
     *
     * $grid = new TextGrid($text);
     * $grid->set("INSERT TEXT LINE 1\nINSERT TEXT LINE 2 ", 1, 9, 1, 39);
     * //The PHP INSERT TEXT LINE 1
     * //INSERT TEXT LINE 2 immediate availability of PHP 7.0.3.
     * //This is a security release. Several security bugs were fixed in this release.
     * </code>
     *
     * @param string   $text        текст для вставки
     * @param int      $startLine   номер начальной строки
     * @param int      $startColumn номер колонки в начальной строке
     * @param int|null $endLine номер конечной строки для замены оригинального текста
     * @param int|null $endColumn номер колонки в конечной строке для замены оригинального текста
     *
     * @return string возвращает результирующий текст
     */
    public function set(
        string $text,
        int $startLine,
        int $startColumn,
        ?int $endLine = null,
        ?int $endColumn = null
    ): string {
        if (is_null($endLine) || is_null($endColumn)) {
            $endLine = $startLine;
            $endColumn = $startColumn;
        }
        $this->switchLinesColumns($startLine, $startColumn, $endLine, $endColumn);
        $this->delete($startLine, $startColumn, $endLine, $endColumn);
        $lines = $this->getLines();
        if ($startLine > count($lines)) {
            $lines = array_pad($lines, $startLine, '');
        }
        $insert = explode("\n", $text);
        if (count($insert) === 1) {
            return $this->setSingleLine($lines, $insert, $startLine, $startColumn);
        }

        return $this->setMultipleLines($lines, $insert, $startLine, $startColumn);
    }

    /**
     * Вставляет однострочный текст
     *
     * @param string[] $lines
     * @param string[] $insert
     */
    private function setSingleLine(array $lines, array $insert, int $startLine, int $startColumn): string
    {
        $lines[$startLine - 1] = mb_substr($lines[$startLine - 1], 0, $startColumn - 1)
            . $insert[0]
            . mb_substr($lines[$startLine - 1], $startColumn - 1);
        $this->setLines($lines);
        $this->text = implode("\n", $lines);

        return $this->getText();
    }

    /**
     * Вставляет многострочный текст
     *
     * @param string[] $lines
     * @param string[] $insert
     */
    private function setMultipleLines(array $lines, array $insert, int $startLine, int $startColumn): string
    {
        $line = $lines[$startLine - 1];
        $lines[$startLine - 1] = mb_substr($line, 0, $startColumn - 1) . $insert[0];
        array_splice(
            $lines,
            $startLine,
            0,
            [mb_substr($line, $startColumn - 1)]
        );
        $lines[$startLine] = $insert[count($insert) - 1] . $lines[$startLine];
        if (count($insert) > 2) {
            array_splice($lines, $startLine, 0, array_slice($insert, 1, count($insert) - 2));
        }
        $this->setLines($lines);
        $this->text = implode("\n", $lines);

        return $this->getText();
    }

    /**
     * Удаляет часть текста исходя из переданных параметров
     *
     * @param int      $startLine   номер начальной строки
     * @param int      $startColumn номер колонки в начальной строке
     * @param int|null $endLine номер конечной строки для замены оригинального текста
     * @param int|null $endColumn номер колонки в конечной строке для замены оригинального текста
     */
    public function delete(int $startLine, int $startColumn, ?int $endLine = null, ?int $endColumn = null): string
    {
        if (is_null($endLine) || is_null($endColumn)) {
            $endLine = $startLine;
            $endColumn = $startColumn + 1;
        }
        $this->switchLinesColumns($startLine, $startColumn, $endLine, $endColumn);
        if (
            $startColumn === $endColumn && $startLine === $endLine
            || $startLine > $this->getCountLines()
        ) {
            return $this->getText();
        }
        if ($startLine === $endLine) {
            return $this->deleteSingleLine($startLine, $startColumn, $endColumn);
        }

        return $this->deleteMultipleLines($startLine, $startColumn, $endLine, $endColumn);
    }

    /**
     * Удаляет из одной строки
     *
     * @param int $startLine   номер начальной строки
     * @param int $startColumn номер колонки в начальной строке
     * @param int $endColumn   номер колонки в конечной строке
     */
    private function deleteSingleLine(int $startLine, int $startColumn, int $endColumn): string
    {
        $lines = $this->getLines();
        $lines[$startLine - 1] = mb_substr($lines[$startLine - 1], 0, $startColumn - 1)
            . mb_substr($lines[$startLine - 1], $endColumn - 1);
        if ($endColumn > $this->getCountColumns($startLine)) {
            $lines[$startLine - 1] .= $lines[$startLine];
            unset($lines[$startLine]);
        }
        $this->setLines($lines);
        $this->text = implode("\n", $lines);

        return $this->getText();
    }

    /**
     * Удаляет из множества строк
     *
     * @param int $startLine   номер начальной строки
     * @param int $startColumn номер колонки в начальной строке
     * @param int $endLine     номер конечной строки
     * @param int $endColumn   номер колонки в конечной строке
     */
    private function deleteMultipleLines(int $startLine, int $startColumn, int $endLine, int $endColumn): string
    {
        $lines = $this->getLines();
        $lines[$startLine - 1] = mb_substr($lines[$startLine - 1], 0, $startColumn - 1)
            . mb_substr($lines[$endLine - 1], $endColumn - 1);
        array_splice($lines, $startLine, $endLine - $startLine, []);
        $this->setLines($lines);
        $this->text = implode("\n", $lines);

        return $this->getText();
    }

    /**
     * Возвращает кол-во строк в тексте
     */
    public function getCountLines(): int
    {
        return count($this->getLines());
    }

    /**
     * Возвращает кол-во колонок в линии
     *
     * @param int $line номер линии
     *
     * @return int|false
     */
    public function getCountColumns(int $line)
    {
        if ($line > $this->getCountLines()) {
            return false;
        }
        $lines = $this->getLines();

        return mb_strlen($lines[$line - 1]) + 1;
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
        $this->encoding = (string) $encoding;

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
