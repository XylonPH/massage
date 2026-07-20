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
}
