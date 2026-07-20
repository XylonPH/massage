<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminServiceResourceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_instantiate_resource(): void
    {
        $this->assertTrue(class_exists(\App\Filament\Editorial\Resources\Services\ServiceResource::class));
        $this->assertTrue(class_exists(\App\Filament\Editorial\Resources\Services\Schemas\ServiceForm::class));
        $this->assertTrue(class_exists(\App\Filament\Editorial\Resources\Services\Tables\ServicesTable::class));
    }
}
