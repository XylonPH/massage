<?php

namespace Tests\Unit\Support;

use App\Support\Directory\PublicContactLink;
use PHPUnit\Framework\TestCase;

class PublicContactLinkTest extends TestCase
{
    public function test_it_allows_supported_public_contact_actions_and_rejects_unsafe_urls(): void
    {
        $channels = (new PublicContactLink)->present([
            ['contact_url' => 'https://example.com/contact'],
            ['contact_url' => 'tel:+63285550148'],
            ['contact_url' => 'sms:+639175550148'],
            ['contact_url' => 'mailto:hello@example.test'],
            ['contact_url' => 'javascript:alert(1)'],
            ['contact_url' => 'data:text/html,unsafe'],
            ['contact_url' => 'tel:not-a-number'],
        ]);

        $this->assertCount(4, $channels);
        $this->assertTrue($channels[0]['is_external']);
        $this->assertFalse($channels[1]['is_external']);
        $this->assertSame(
            ['https://example.com/contact', 'tel:+63285550148', 'sms:+639175550148', 'mailto:hello@example.test'],
            array_column($channels, 'contact_url'),
        );
    }

    public function test_it_exposes_the_same_scheme_validation_used_by_management_forms(): void
    {
        $links = new PublicContactLink;

        $this->assertTrue($links->allows('https://example.com/contact'));
        $this->assertTrue($links->allows('mailto:hello@example.test'));
        $this->assertFalse($links->allows('javascript:alert(1)'));
        $this->assertFalse($links->allows('tel:not-a-number'));
    }
}
