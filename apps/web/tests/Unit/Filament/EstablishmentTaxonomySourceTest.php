<?php

namespace Tests\Unit\Filament;

use App\Filament\Editorial\Resources\Establishments\Schemas\EstablishmentForm;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use Tests\TestCase;

class EstablishmentTaxonomySourceTest extends TestCase
{
    /** @param array<string, string> $expectedOptions */
    #[DataProvider('taxonomyFieldProvider')]
    public function test_the_establishment_form_reads_repository_taxonomy_options(
        string $fieldName,
        array $expectedOptions,
    ): void {
        $method = new ReflectionMethod(EstablishmentForm::class, 'getTaxonomyOptions');
        $options = $method->invoke(null, $fieldName);

        foreach ($expectedOptions as $code => $label) {
            $this->assertSame($label, $options[$code] ?? null);
        }
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
