<?php

namespace App\Filament\Editorial\Resources\Establishments\Schemas;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Enums\RecordLifecycleStatus;
use Illuminate\Support\Facades\File;

class EstablishmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Establishment Details')
                    ->tabs([
                        Tab::make('Identity')
                            ->schema([
                                TextInput::make('display_name.eng')
                                    ->label('Establishment Name (English)')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('display_name.zho')
                                    ->label('Establishment Name (Chinese)')
                                    ->maxLength(255),
                                Textarea::make('short_description.eng')
                                    ->label('Short Description (English)')
                                    ->rows(3),
                                Textarea::make('short_description.zho')
                                    ->label('Short Description (Chinese)')
                                    ->rows(3),
                                TextInput::make('email')
                                    ->label('Business Email')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('contact_number')
                                    ->label('Contact Number')
                                    ->maxLength(255),
                                Select::make('status_record_lifecycle')
                                    ->label('Record Lifecycle Status')
                                    ->options(RecordLifecycleStatus::class)
                                    ->default(RecordLifecycleStatus::DRAFT)
                                    ->required(),
                            ]),
                        Tab::make('Classification')
                            ->schema([
                                Select::make('type_spa')
                                    ->label('Spa Type')
                                    ->options(self::getTaxonomyOptions('type_spa'))
                                    ->searchable()
                                    ->required(),
                                Select::make('level_spa_market')
                                    ->label('Spa Market Class')
                                    ->options(self::getTaxonomyOptions('level_spa_market')),
                                Select::make('type_physical_setting')
                                    ->label('Physical Setting')
                                    ->options(self::getTaxonomyOptions('type_physical_setting')),
                                Select::make('type_establishment_operation')
                                    ->label('Operation Model')
                                    ->options(self::getTaxonomyOptions('type_establishment_operation')),
                                Select::make('status_establishment')
                                    ->label('Establishment Status (Operating Condition)')
                                    ->options(self::getTaxonomyOptions('status_establishment'))
                                    ->required(),
                            ])->columns(2),
                        Tab::make('Access & Delivery')
                            ->schema([
                                Select::make('mode_service_delivery')
                                    ->label('Service Delivery Mode')
                                    ->multiple()
                                    ->options(self::getTaxonomyOptions('mode_service_delivery')),
                                Select::make('mode_access')
                                    ->label('Access Model')
                                    ->options(self::getTaxonomyOptions('mode_access')),
                                Select::make('type_client_access')
                                    ->label('Client Access')
                                    ->options(self::getTaxonomyOptions('type_client_access')),
                                Select::make('target_client_focus')
                                    ->label('Target Client Focus')
                                    ->multiple()
                                    ->options(self::getTaxonomyOptions('target_client_focus')),
                            ])->columns(2),
                    ])->columnSpanFull()
            ]);
    }

    private static function getTaxonomyOptions(string $fieldName): array
    {
        $path = base_path('data/taxonomy/massage_nexus/establishment_classification.json');
        
        if (!File::exists($path)) {
            return [];
        }

        $data = json_decode(File::get($path), true);
        
        foreach ($data as $field) {
            if ($field['field_name'] === $fieldName) {
                $options = [];
                foreach ($field['field_option'] ?? [] as $option) {
                    $options[$option['option_code']] = $option['option_label'];
                }
                return $options;
            }
        }
        
        return [];
    }
}
