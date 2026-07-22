<?php

namespace Tests\Feature\Governance;

use Tests\TestCase;

class ContributionGuideFieldsTest extends TestCase
{
    public function test_user_contribution_guide_documents_the_add_a_spa_fields(): void
    {
        $guide = require base_path('../../data/structure_guide/user_contribution.php');

        $expected = [
            'submission_note', 'duplicate_candidate_establishment_id_list',
            'duplicate_acknowledged', 'is_visit_requested', 'visit_preferred_time_note',
        ];

        foreach ($expected as $field) {
            $this->assertContains($field, $guide['user_contribution_field_order'], "Missing field: {$field}");
            $this->assertArrayHasKey($field, $guide['user_contribution_field_property'], "Missing field property: {$field}");
        }
    }
}
