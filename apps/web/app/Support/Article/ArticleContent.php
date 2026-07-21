<?php

namespace App\Support\Article;

use DOMDocument;
use DOMElement;
use DOMNode;

class ArticleContent
{
    /** @var array<string, array<int, string>> */
    private const ALLOWED_ELEMENTS = [
        'p' => ['class', 'style'],
        'h2' => ['class', 'style'],
        'h3' => ['class', 'style'],
        'h4' => ['class', 'style'],
        'ul' => ['class'],
        'ol' => ['class'],
        'li' => [],
        'strong' => [],
        'em' => [],
        'u' => [],
        's' => [],
        'blockquote' => ['class'],
        'a' => ['href', 'title', 'target', 'rel'],
        'figure' => ['class', 'data-media-image-id'],
        'img' => ['src', 'alt', 'width', 'height', 'loading'],
        'figcaption' => [],
        'br' => [],
        'hr' => [],
    ];

    /** @var array<int, string> */
    private const ALLOWED_CLASSES = [
        'mn-section-title', 'mn-image-center', 'mn-image-wide', 'mn-callout', 'mn-source-note',
    ];

    public function sanitize(string $html): string
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div id="mn-article-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD,
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('mn-article-root');
        if (! $root) {
            return '';
        }

        $this->cleanChildren($root);

        $output = '';
        foreach ($root->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }

        return trim($output);
    }

    /** @return array{plain_text: string, word_count: int, visual_seconds: int, spoken_seconds: int} */
    public function metrics(string $html, bool $dense = false): array
    {
        $withoutTokens = preg_replace('/\[media:(?:image|video)_[^\]]+\]/iu', ' ', $html) ?? $html;
        $plainText = html_entity_decode(strip_tags($withoutTokens), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $plainText = trim(preg_replace('/\s+/u', ' ', $plainText) ?? '');
        preg_match_all('/[\p{L}\p{N}]+(?:[\'’\-][\p{L}\p{N}]+)*/u', $plainText, $matches);
        $wordCount = count($matches[0]);

        return [
            'plain_text' => $plainText,
            'word_count' => $wordCount,
            'visual_seconds' => $wordCount === 0 ? 0 : (int) ceil(($wordCount / ($dense ? 200 : 225)) * 60),
            'spoken_seconds' => $wordCount === 0 ? 0 : (int) ceil(($wordCount / 150) * 60),
        ];
    }

    /** @return array<int, array{source_title: string, source_organization: ?string, source_url: ?string, publication_identifier: ?string}> */
    public function parseSources(?string $input): array
    {
        if (! is_string($input) || trim($input) === '') {
            return [];
        }

        $sources = [];
        foreach (preg_split('/\R/u', $input) ?: [] as $line) {
            if (trim($line) === '') {
                continue;
            }

            $parts = array_map('trim', explode('|', $line, 4));
            $url = $parts[2] ?? null;
            $safeUrl = is_string($url) && preg_match('~^https?://~i', $url) && filter_var($url, FILTER_VALIDATE_URL)
                ? mb_substr($url, 0, 1000)
                : null;
            $sources[] = [
                'source_title' => mb_substr($parts[0], 0, 200),
                'source_organization' => isset($parts[1]) && $parts[1] !== '' ? mb_substr($parts[1], 0, 200) : null,
                'source_url' => $safeUrl,
                'publication_identifier' => isset($parts[3]) && $parts[3] !== '' ? mb_substr($parts[3], 0, 120) : null,
            ];
        }

        return $this->normalizeSources($sources);
    }

    /** @param array<int, mixed> $sources
     * @return array<int, array{source_title: string, source_organization: ?string, source_url: ?string, publication_identifier: ?string}>
     */
    public function normalizeSources(array $sources): array
    {
        $normalized = [];

        foreach (array_slice($sources, 0, 30) as $source) {
            if (! is_array($source)) {
                continue;
            }

            $title = trim((string) ($source['source_title'] ?? ''));
            $organization = trim((string) ($source['source_organization'] ?? ''));
            $url = trim((string) ($source['source_url'] ?? ''));
            $identifier = trim((string) ($source['publication_identifier'] ?? ''));

            if ($title === '' && $organization === '' && $url === '' && $identifier === '') {
                continue;
            }

            $safeUrl = preg_match('~^https?://~i', $url) && filter_var($url, FILTER_VALIDATE_URL)
                ? mb_substr($url, 0, 1000)
                : null;
            $normalized[] = array_filter([
                'source_title' => $title !== '' ? mb_substr($title, 0, 200) : null,
                'source_organization' => $organization !== '' ? mb_substr($organization, 0, 200) : null,
                'source_url' => $safeUrl,
                'publication_identifier' => $identifier !== '' ? mb_substr($identifier, 0, 120) : null,
            ], static fn (mixed $value): bool => $value !== null && $value !== '');
        }

        return $normalized;
    }

    private function cleanChildren(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            $name = strtolower($node->tagName);
            if (! array_key_exists($name, self::ALLOWED_ELEMENTS)) {
                if (in_array($name, ['script', 'style', 'iframe', 'object', 'embed', 'form'], true)) {
                    $node->parentNode?->removeChild($node);

                    continue;
                }

                $this->cleanChildren($node);
                while ($node->firstChild) {
                    $node->parentNode?->insertBefore($node->firstChild, $node);
                }
                $node->parentNode?->removeChild($node);

                continue;
            }

            foreach (iterator_to_array($node->attributes) as $attribute) {
                if (! in_array(strtolower($attribute->name), self::ALLOWED_ELEMENTS[$name], true)) {
                    $node->removeAttribute($attribute->name);
                }
            }

            $this->cleanElementAttributes($node, $name);
            $this->cleanChildren($node);
        }
    }

    private function cleanElementAttributes(DOMElement $node, string $name): void
    {
        if ($node->hasAttribute('style')) {
            $style = strtolower(trim($node->getAttribute('style')));
            if (preg_match('/^text-align:\s*(left|center|right|justify);?$/', $style, $match)) {
                $node->setAttribute('style', 'text-align: '.$match[1].';');
            } else {
                $node->removeAttribute('style');
            }
        }

        if ($node->hasAttribute('class')) {
            $classes = array_intersect(preg_split('/\s+/', $node->getAttribute('class')) ?: [], self::ALLOWED_CLASSES);
            $classes === [] ? $node->removeAttribute('class') : $node->setAttribute('class', implode(' ', $classes));
        }

        if ($name === 'a' && $node->hasAttribute('href')) {
            $href = trim($node->getAttribute('href'));
            if (! preg_match('~^(https?://|/|#|mailto:)~i', $href)) {
                $node->removeAttribute('href');
            }
            if ($node->getAttribute('target') === '_blank') {
                $node->setAttribute('rel', 'noopener noreferrer');
            } else {
                $node->removeAttribute('target');
            }
        }

        if ($name === 'img') {
            $src = $node->getAttribute('src');
            if (! preg_match('~^/media/image/[A-Za-z0-9]{16}$~', $src)) {
                $node->removeAttribute('src');
            }
            $node->setAttribute('loading', 'lazy');
        }

        if ($name === 'figure' && $node->hasAttribute('data-media-image-id')) {
            if (! preg_match('/^[A-Za-z0-9]{16}$/', $node->getAttribute('data-media-image-id'))) {
                $node->removeAttribute('data-media-image-id');
            }
        }
    }
}
