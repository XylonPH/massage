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
        $this->assertSame(['N', 'M', 'S', 'E'], array_map(fn (NsfwLevel $case) => $case->value, NsfwLevel::cases()));
    }

    public function test_labels_match_the_shared_level_nsfw_taxonomy(): void
    {
        // data/taxonomy/shared/record_lifecycle_and_review.json, field_name "level_nsfw" (_id 43)
        // is this project's authoritative source for these codes/labels; every consumer
        // (article form, Quote editor) must agree with it, not with each other.
        $this->assertSame('None', NsfwLevel::None->getLabel());
        $this->assertSame('Mild', NsfwLevel::Mild->getLabel());
        $this->assertSame('Sensitive', NsfwLevel::Sensitive->getLabel());
        $this->assertSame('Explicit', NsfwLevel::Explicit->getLabel());
    }
}
