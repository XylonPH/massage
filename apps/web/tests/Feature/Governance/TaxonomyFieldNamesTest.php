<?php

namespace Tests\Feature\Governance;

use App\Support\Taxonomy\TaxonomyOptions;
use Tests\TestCase;

class TaxonomyFieldNamesTest extends TestCase
{
    public function test_amenity_list_and_accessibility_feature_list_are_the_taxonomy_field_names(): void
    {
        $this->assertNotEmpty(TaxonomyOptions::for('amenity_list'));
        $this->assertNotEmpty(TaxonomyOptions::for('accessibility_feature_list'));
        $this->assertSame([], TaxonomyOptions::for('amenities'));
        $this->assertSame([], TaxonomyOptions::for('accessibility_information'));
    }

    public function test_parking_availability_taxonomy_field_exists(): void
    {
        $options = TaxonomyOptions::for('parking_availability');

        $this->assertArrayHasKey('NONE', $options);
        $this->assertArrayHasKey('PRK_ONS_FREE', $options);
        $this->assertArrayHasKey('PRK_ONS_PAID', $options);
        $this->assertArrayHasKey('PRK_STR', $options);
        $this->assertArrayHasKey('PRK_NEARBY_PAID', $options);
        $this->assertArrayHasKey('PRK_VALET', $options);
        $this->assertArrayHasKey('PRK_MOTO_ONLY', $options);
    }
}
