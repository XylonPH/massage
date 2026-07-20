<?php

namespace App\Filament\Editorial\Resources\Establishments\Schemas;

use App\Enums\RecordLifecycleStatus;
use App\Rules\PublicContactUrl;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
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
                                    ->label('Establishment Name')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('short_description.eng')
                                    ->label('Short Description')
                                    ->rows(3),
                                Textarea::make('description.eng')
                                    ->label('Full Description')
                                    ->helperText('The complete public description shown on the profile page.')
                                    ->rows(8),
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
                                    ->default(RecordLifecycleStatus::Active)
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
                        Tab::make('Location & Contact')
                            ->schema([
                                Textarea::make('address_public')
                                    ->label('Public Address')
                                    ->helperText('Use an approximate area instead when an exact address should not be public.')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                TextInput::make('coordinate_latitude')
                                    ->label('Map Latitude')
                                    ->numeric()
                                    ->minValue(-90)
                                    ->maxValue(90),
                                TextInput::make('coordinate_longitude')
                                    ->label('Map Longitude')
                                    ->numeric()
                                    ->minValue(-180)
                                    ->maxValue(180),
                                Textarea::make('direction_note.eng')
                                    ->label('Directions (English)')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                Textarea::make('parking_note.eng')
                                    ->label('Parking Information (English)')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                Repeater::make('landmark_list')
                                    ->label('Nearby Landmarks')
                                    ->schema([
                                        TextInput::make('landmark_name')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('walking_duration_minute')
                                            ->label('Walking Time (minutes)')
                                            ->numeric()
                                            ->minValue(0),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->columnSpanFull(),
                                Repeater::make('contact_channel_list')
                                    ->label('Public Business Contact Channels')
                                    ->helperText('Do not enter private personal contact details.')
                                    ->schema([
                                        Select::make('type_contact_channel')
                                            ->label('Channel Type')
                                            ->options(self::getTaxonomyOptions('type_contact_channel'))
                                            ->required(),
                                        Select::make('type_contact_number')
                                            ->label('Number Type')
                                            ->options(self::getTaxonomyOptions('type_contact_number')),
                                        TextInput::make('contact_label')
                                            ->label('Public Label')
                                            ->required()
                                            ->maxLength(100),
                                        TextInput::make('contact_value')
                                            ->label('Displayed Value')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('contact_url')
                                            ->label('Action URL')
                                            ->helperText('Use http, https, tel, mailto, or sms.')
                                            ->required()
                                            ->rules([new PublicContactUrl])
                                            ->maxLength(2048),
                                        Select::make('status_contact_channel')
                                            ->label('Channel Status')
                                            ->options(self::getTaxonomyOptions('status_contact_channel')),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->columnSpanFull(),
                            ])->columns(2),
                        Tab::make('Facilities')
                            ->schema([
                                Repeater::make('treatment_area_list')
                                    ->label('Treatment Areas')
                                    ->helperText('Named treatment rooms, suites, or stations with their privacy and capacity.')
                                    ->schema([
                                        TextInput::make('treatment_area_name')
                                            ->label('Area Name')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('type_treatment_area')
                                            ->label('Area Type')
                                            ->options(self::getTaxonomyOptions('type_treatment_area')),
                                        Select::make('level_treatment_privacy')
                                            ->label('Privacy Level')
                                            ->options(self::getTaxonomyOptions('level_treatment_privacy')),
                                        Select::make('type_treatment_capacity')
                                            ->label('Capacity')
                                            ->options(self::getTaxonomyOptions('type_treatment_capacity')),
                                        TextInput::make('treatment_area_note')
                                            ->label('Public Note')
                                            ->maxLength(255),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->columnSpanFull(),
                                Select::make('room_types')
                                    ->label('Treatment Room Types')
                                    ->multiple()
                                    ->options(self::getTaxonomyOptions('room_types')),
                                Select::make('bed_mat_chair_setup')
                                    ->label('Bed/Mat/Chair Setup')
                                    ->multiple()
                                    ->options(self::getTaxonomyOptions('bed_mat_chair_setup')),
                                Select::make('shower_availability')
                                    ->label('Shower Availability')
                                    ->options(self::getTaxonomyOptions('shower_availability')),
                                Select::make('sauna_availability')
                                    ->label('Sauna Availability')
                                    ->options(self::getTaxonomyOptions('sauna_availability')),
                                Select::make('steam_room_availability')
                                    ->label('Steam Room Availability')
                                    ->options(self::getTaxonomyOptions('steam_room_availability')),
                                Select::make('jacuzzi_availability')
                                    ->label('Jacuzzi Availability')
                                    ->options(self::getTaxonomyOptions('jacuzzi_availability')),
                                Select::make('locker_availability')
                                    ->label('Locker Availability')
                                    ->options(self::getTaxonomyOptions('locker_availability')),
                                Select::make('couple_room_availability')
                                    ->label('Couple Room Availability')
                                    ->options(self::getTaxonomyOptions('couple_room_availability')),
                                Select::make('private_room_availability')
                                    ->label('Private Room Availability')
                                    ->options(self::getTaxonomyOptions('private_room_availability')),
                                Select::make('curtain_divider_information')
                                    ->label('Curtain/Divider Information')
                                    ->options(self::getTaxonomyOptions('curtain_divider_information')),
                                Select::make('air_conditioning_information')
                                    ->label('Air-Conditioning')
                                    ->options(self::getTaxonomyOptions('air_conditioning_information')),
                            ])->columns(3),
                        Tab::make('Amenities & Accessibility')
                            ->schema([
                                ToggleButtons::make('amenities')
                                    ->label('General Amenities')
                                    ->multiple()
                                    ->inline()
                                    ->options(self::getTaxonomyOptions('amenities')),
                                ToggleButtons::make('accessibility_information')
                                    ->label('Accessibility Features')
                                    ->multiple()
                                    ->inline()
                                    ->options(self::getTaxonomyOptions('accessibility_information')),
                            ])->columns(2),
                    ])->columnSpanFull(),
            ]);
    }

    private static function getTaxonomyOptions(string $fieldName): array
    {
        $repositoryRoot = dirname(base_path(), 2);
        $paths = [
            $repositoryRoot.'/data/taxonomy/massage_nexus/establishment_classification.json',
            $repositoryRoot.'/data/taxonomy/shared/person_identity_and_contact.json',
        ];

        foreach ($paths as $path) {
            if (! File::exists($path)) {
                continue;
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
        }

        return [];
    }
}
