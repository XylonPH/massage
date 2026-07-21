<?php

namespace Tests\Unit;

use App\Support\Article\ArticleContent;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ArticleContentTest extends TestCase
{
    #[Test]
    public function it_sanitizes_html_with_an_explicit_allowlist(): void
    {
        $content = new ArticleContent;
        $html = $content->sanitize('<h2 onclick="alert(1)">Safe heading</h2><script>alert(1)</script><div><script>alert(2)</script><strong>kept</strong></div><a href="javascript:alert(1)">link</a><img src="https://tracker.test/a.png">');

        $this->assertStringContainsString('<h2>Safe heading</h2>', $html);
        $this->assertStringNotContainsString('script', $html);
        $this->assertStringNotContainsString('onclick', $html);
        $this->assertStringNotContainsString('javascript:', $html);
        $this->assertStringNotContainsString('tracker.test', $html);
        $this->assertStringContainsString('<strong>kept</strong>', $html);
    }

    #[Test]
    public function it_calculates_visual_and_spoken_reading_metrics_from_visible_text(): void
    {
        $content = new ArticleContent;
        $words = implode(' ', array_fill(0, 225, 'massage'));
        $metrics = $content->metrics('<p>'.$words.'</p>[media:image_1]');

        $this->assertSame(225, $metrics['word_count']);
        $this->assertSame(60, $metrics['visual_seconds']);
        $this->assertSame(90, $metrics['spoken_seconds']);
        $this->assertStringNotContainsString('media', $metrics['plain_text']);
    }

    #[Test]
    public function it_keeps_editor_formatting_but_rejects_arbitrary_styles(): void
    {
        $content = new ArticleContent;
        $html = $content->sanitize('<p style="text-align: center; color: red"><u>underlined</u></p><h3 style="text-align:right"><s>old</s></h3><p style="position:fixed">unsafe</p>');

        $this->assertStringContainsString('<u>underlined</u>', $html);
        $this->assertStringContainsString('<s>old</s>', $html);
        $this->assertStringContainsString('style="text-align: right;"', $html);
        $this->assertStringNotContainsString('color:', $html);
        $this->assertStringNotContainsString('position:', $html);
    }

    #[Test]
    public function it_normalizes_structured_source_records_without_delimiter_parsing(): void
    {
        $content = new ArticleContent;
        $sources = $content->normalizeSources([[
            'source_title' => 'A title containing | a pipe',
            'source_organization' => 'Example Publisher',
            'source_url' => 'https://example.test/source',
            'publication_identifier' => 'DOI 10.1234/example',
        ]]);

        $this->assertSame('A title containing | a pipe', $sources[0]['source_title']);
        $this->assertSame('Example Publisher', $sources[0]['source_organization']);
        $this->assertSame('https://example.test/source', $sources[0]['source_url']);
        $this->assertSame('DOI 10.1234/example', $sources[0]['publication_identifier']);
    }
}
