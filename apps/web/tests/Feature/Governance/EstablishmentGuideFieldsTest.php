<?php

namespace Tests\Feature\Governance;

use Tests\TestCase;

class EstablishmentGuideFieldsTest extends TestCase
{
    public function test_establishment_guide_includes_facility_and_date_fields(): void
    {
        $guide = require base_path('../../data/structure_guide/establishment_main.php');

        $expected = [
            'shower_availability', 'sauna_availability', 'steam_room_availability',
            'jacuzzi_availability', 'locker_availability', 'couple_room_availability',
            'private_room_availability', 'curtain_divider_information',
            'air_conditioning_information', 'room_types', 'bed_mat_chair_setup',
            'parking_availability_list', 'date_opened', 'date_opened_precision',
            'date_opened_qualifier', 'date_closed', 'date_closed_precision',
            'date_closed_qualifier',
        ];

        foreach ($expected as $field) {
            $this->assertContains($field, $guide['establishment_main_field_order'], "Missing field: {$field}");
            $this->assertArrayHasKey($field, $guide['establishment_main_field_property'], "Missing field property: {$field}");
        }
    }
}
