# Лексические анализаторы.

[![Latest Version][badge-release]][packagist]
[![Software License][badge-license]][license]
[![PHP Version][badge-php]][php]
[![Build Status][badge-build]][build]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]

Пакет fi1a/tokenizer предоставляет инструменты для разбора входной последовательности консольной строки, CSS3 селекторов и PHP кода.

## Установка

Установить этот пакет можно как зависимость, используя Composer.

``` bash
composer require fi1a/tokenizer
```

**Внимание!** Совместимость версий не гарантируется при переходе major или minor версии.
Указывайте допустимую версию пакета в своем проекте следующим образом: ```"fi1a/tokenizer": ">=1.1.0 <1.2.0"```.

## Общая архитектура пакета

В библиотеке представлены следующие интерфейсы:

* Fi1a\Tokenizer\IToken - интерфейс токена;
* Fi1a\Tokenizer\ITokenFactory - интерфейс фабрики токенов;
* Fi1a\Tokenizer\ITokenizer - интерфейс лексического анализатора;
* Fi1a\Tokenizer\ITokenizerFactory - интерфейс фабричного класса лексического анализатора.

И абстрактные классы:

* Fi1a\Tokenizer\AToken - токен, реализующий интерфейс IToken;
* Fi1a\Tokenizer\ATokenizer - класс, реализующий интерфейс ITokenizer.

В библиотеке имеются следующие лексические анализаторы для разбора:

* консольной строки;
* CSS3 селекторов;
* PHP кода.

## Разбор командной строки

Позволяет разобрать строку с командами на токены для последующей обработки.

```php
use Fi1a\Tokenizer\ConsoleLine\Tokenizer;
use Fi1a\Tokenizer\ITokenizer;

$tokenizer = new Tokenizer('info --locale="ru , en"');

while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
    $token->getImage(); // 'info', ' ', '--locale', '=', '"', 'ru , en', '"'
}
```

## Разбор CSS3 селекторов

Позволяет разобрать строку с CSS3 селекторами на токены для последующей обработки.

```php
use Fi1a\Tokenizer\CSS3Selector\Tokenizer;
use Fi1a\Tokenizer\ITokenizer;

$tokenizer = new Tokenizer('div.e-class1.m_class2 .b-class3');

while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
    $token->getImage(); // 'div', '.e-class1', '.m_class2', ' ', '.b-class3'
}
```

## Разбор PHP кода

Позволяет разобрать строку с PHP кодом на токены для последующей обработки.

```php
use Fi1a\Tokenizer\PHP\TokenizerFactory;
use Fi1a\Tokenizer\ITokenizer;

$tokenizer = TokenizerFactory::factory("<?php class Foo {}");

while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
    $token->getImage(); // '<?php ', 'class', ' ', 'Foo', ' ', '{', '}'
}
```

[badge-release]: https://img.shields.io/packagist/v/fi1a/tokenizer?label=release
[badge-license]: https://img.shields.io/github/license/fi1a/tokenizer?style=flat-square
[badge-php]: https://img.shields.io/packagist/php-v/fi1a/tokenizer?style=flat-square
[badge-build]: https://img.shields.io/travis/fi1a/tokenizer?style=flat-square
[badge-coverage]: https://img.shields.io/coveralls/github/fi1a/tokenizer/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/fi1a/tokenizer.svg?style=flat-square&colorB=mediumvioletred

[packagist]: https://packagist.org/packages/fi1a/tokenizer
[license]: https://github.com/fi1a/tokenizer/blob/master/LICENSE
[php]: https://php.net
[build]: https://travis-ci.org/fi1a/tokenizer
[coverage]: https://coveralls.io/r/fi1a/tokenizer?branch=master
[downloads]: https://packagist.org/packages/fi1a/tokenizer