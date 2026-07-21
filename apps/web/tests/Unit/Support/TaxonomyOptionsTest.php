<?php

namespace Tests\Unit\Support;

use App\Support\Taxonomy\TaxonomyOptions;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TaxonomyOptionsTest extends TestCase
{
    #[DataProvider('taxonomyFieldProvider')]
    public function test_returns_expected_options_for_field(
        string $fieldName,
        array $expectedOptions,
    ): void {
        $options = TaxonomyOptions::for($fieldName);

        foreach ($expectedOptions as $code => $label) {
            $this->assertSame($label, $options[$code] ?? null);
        }
    }

    public function test_returns_options_for_known_establishment_field(): void
    {
        $options = TaxonomyOptions::for('type_spa');

        $this->assertNotEmpty($options);
        foreach ($options as $code => $label) {
            $this->assertIsString($code);
            $this->assertIsString($label);
        }
    }

    public function test_returns_options_from_shared_contact_taxonomy(): void
    {
        $this->assertNotEmpty(TaxonomyOptions::for('type_contact_channel'));
    }

    public function test_unknown_field_returns_empty_array(): void
    {
        $this->assertSame([], TaxonomyOptions::for('no_such_field'));
    }

    /** @return array<string, array{string, array<string, string>}> */
    public static function taxonomyFieldProvider(): array
    {
        return [
            'establishment classification' => ['type_spa', ['DY' => 'Day Spa']],
            'contact channel' => ['type_contact_channel', ['PHN' => 'Phone', 'MSG' => 'Messaging App']],
            'contact number' => ['type_contact_number', ['M' => 'Mobile', 'L' => 'Landline']],
        ];
    }
}
