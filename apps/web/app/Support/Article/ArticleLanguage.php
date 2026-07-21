<?php

namespace App\Support\Article;

final class ArticleLanguage
{
    /** @var array<int, array{key: string, label_key: string}> */
    private const SUPPORTED = [
        3049 => ['key' => 'eng', 'label_key' => 'article.language_english'],
        3600 => ['key' => 'fil', 'label_key' => 'article.language_filipino'],
        5750 => ['key' => 'ind', 'label_key' => 'article.language_indonesian'],
        6488 => ['key' => 'jpn', 'label_key' => 'article.language_japanese'],
        10522 => ['key' => 'por', 'label_key' => 'article.language_portuguese'],
        12559 => ['key' => 'spa', 'label_key' => 'article.language_spanish'],
        13027 => ['key' => 'tha', 'label_key' => 'article.language_thai'],
        14409 => ['key' => 'vie', 'label_key' => 'article.language_vietnamese'],
    ];

    /** @return array<int, array{key: string, label_key: string}> */
    public static function all(): array
    {
        return self::SUPPORTED;
    }

    /** @return array<int, int> */
    public static function ids(): array
    {
        return array_keys(self::SUPPORTED);
    }

    /** @return array<int, string> */
    public static function keys(): array
    {
        return array_column(self::SUPPORTED, 'key');
    }

    public static function keyForId(int $id): string
    {
        return self::SUPPORTED[$id]['key'] ?? self::SUPPORTED[3049]['key'];
    }
}
