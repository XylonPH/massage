<?php

namespace Tests\Unit;

use App\Enums\NsfwLevel;
use PHPUnit\Framework\TestCase;

class NsfwLevelTest extends TestCase
{
    public function test_explicit_case_matches_the_real_persisted_and_validated_code(): void
    {
        $this->assertSame('E', NsfwLevel::Explicit->value);
    }

    public function test_case_codes_match_what_the_article_form_actually_validates(): void
    {
        $this->assertSame(['N', 'S', 'M', 'E'], array_map(fn (NsfwLevel $case) => $case->value, NsfwLevel::cases()));
    }
}
