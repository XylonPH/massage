<?php

namespace Tests\Unit\Models;

use App\Models\Contribution;
use Tests\TestCase;

class ContributionTest extends TestCase
{
    public function test_new_fields_are_fillable_and_cast(): void
    {
        $contribution = Contribution::query()->create([
            'type_contribution' => 'ADD',
            'target_collection' => 'establishment_main',
            'submitted_by_user_id' => 'Us7K2pQ9xR4tV8zN',
            'proposed_data' => [],
            'submission_note' => 'Photos available on request.',
            'duplicate_candidate_establishment_id_list' => ['Es7K2pQ9xR4tV8zN'],
            'duplicate_acknowledged' => true,
            'is_visit_requested' => '1',
            'visit_preferred_time_note' => 'Weekday mornings.',
        ]);

        $this->assertSame('Photos available on request.', $contribution->submission_note);
        $this->assertSame(['Es7K2pQ9xR4tV8zN'], $contribution->duplicate_candidate_establishment_id_list);
        $this->assertTrue($contribution->duplicate_acknowledged);
        $this->assertTrue($contribution->is_visit_requested);

        $contribution->delete();
    }
}
