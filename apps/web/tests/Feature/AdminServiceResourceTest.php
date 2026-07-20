<?php

namespace Tests\Feature;

use App\Filament\Editorial\Resources\Services\Schemas\ServiceForm;
use App\Filament\Editorial\Resources\Services\ServiceResource;
use App\Filament\Editorial\Resources\Services\Tables\ServicesTable;
use Tests\TestCase;

class AdminServiceResourceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_instantiate_resource(): void
    {
        $this->assertTrue(class_exists(ServiceResource::class));
        $this->assertTrue(class_exists(ServiceForm::class));
        $this->assertTrue(class_exists(ServicesTable::class));
    }
}
