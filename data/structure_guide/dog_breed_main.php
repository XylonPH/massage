<?php
/**
 * Title: Zenith Dog Breed Common Reference Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: dog_breed_main
 * Database: common_reference
 * Version: 2.02
 *
 * Purpose:
 * This file is a PHP-readable visual guide for the current dog_breed_main structure.
 * It is not a seed file, not a runtime migration script, and not a replacement
 * for the canonical Zenith documents. It exists so the Dog Breed Common
 * Reference workflow can be reviewed and refined before implementation.
 *
 * Important:
 * - Database: common_reference.
 * - It stores canonical reusable dog breed reference data.
 * - Other collections should store dog_breed_id when referencing this collection; dog_breed_id points to dog_breed_main._id.
 * - dog_breed_key remains in the current structure as a stable readable identifier pending later review. If it is not used by the application, it may be replaced by a slug field later.
 * - Dog breed names must be queried from common_reference.dog_breed_main; do not hardcode breed lists.
 *
 * Sparse-default rule:
 * Stored records may omit a value only when the default is known and can be safely inferred.
 * For dog_breed_main, the only current document-level default is status_record_lifecycle = ACT.
 *
 * Required fields for managed dog_breed_main records:
 * - _id: required numeric primary identifier, assigned from the common reference ID counter.
 * - dog_breed_key: required stable lowercase snake_case key.
 * - dog_breed_name: required multilingual display name, at least English for baseline data.
 * - status_record_lifecycle: required in the managed structure, default ACT when sparse.
 * - created_at: required system creation timestamp.
 * - updated_at: required system update timestamp.
 */

# Variable
$created_at = '2026-06-19T11:44:00Z';
$updated_at = '2026-07-01T20:08:26Z';

/**
 * Default document values for dog_breed_main.
 */
$dog_breed_property_default = [
	'status_record_lifecycle' => 'ACT',
];

/**
 * Multilingual short/long text structure used by dog_breed_name and descriptions.
 * method_translation defaults to HUM and should be omitted when the value is human-translated.
 * status_review defaults to APR and should be omitted when the value is approved.
 */
$multilingual_text_sample = [
	'eng' => [
		'text' => 'Belgian Malinois',
		'method_translation' => 'HUM',
		'status_review' => 'APR',
	],
];

/**
 * dog_breed_main sample record.
 *
 * This sample uses Belgian Malinois because it is one of the more complete records
 * in the provided dog_breed_main.json file. Default-valued management fields may
 * be omitted in actual stored sparse records.
 */
$dog_breed_main = [
	// Required numeric primary identifier. Assigned from the common_reference ID counter collection and not typed manually by the user.
	'_id' => 35,

	// Required stable technical key in lowercase snake_case. Used by imports, lookups, comparisons, and readable references.
	'dog_breed_key' => 'belgian_malinois',

	// Required multilingual breed display name. Baseline records should at least include English.
	'dog_breed_name' => [
		'eng' => [
			'text' => 'Belgian Malinois',
		],
	],

	// Optional multilingual list of common names, historic names, local names, registry variants, or informal names.
	'alternative_name_list' => [
		[
			'alternative_name' => [
				'eng' => [
					'text' => 'Malinois',
				],
			],
		],
		[
			'alternative_name' => [
				'eng' => [
					'text' => 'Belgian Shepherd Dog (Malinois)',
				],
			],
		],
	],

	// Optional controlled value describing whether the entry is a pure breed, village dog, landrace, hybrid, mixed breed, or another recognized breed identity type.
	'type_dog_breed' => 'PUR',

	// Optional general-purpose dog group used for browsing and filtering outside any single registry system.
	'group_dog_breed' => 'HER',

	// Optional size level used for quick filtering, search facets, and user-friendly breed comparison.
	'level_dog_size' => 'LRG',

	// Optional broad coat classification used for grooming, filtering, and quick breed comparison.
	'type_dog_coat' => 'SHO',

	// Optional array of numeric country IDs from common_reference.country_main. This stores references only, not embedded country records.
	'origin_country_id_list' => [
		56,
	],

	// Optional typical adult height range in centimeters. Store min and max when source data supports a range.
	'height_cm' => [
		'min' => 55.88,
		'max' => 66.04,
	],

	// Optional typical adult weight range in kilograms. Store min and max when source data supports a range.
	'weight_kg' => [
		'min' => 18.14,
		'max' => 36.29,
	],

	// Optional typical lifespan range in years. This is breed-level reference data, not a prediction for an individual dog.
	'lifespan_year' => [
		'min' => 14,
		'max' => 16,
	],

	// Optional general trait scoring object. Scores use 1–5 reference values and should not be treated as guarantees for individual dogs.
	'trait_score' => [
		'energy' => 4,
		'trainability' => 5,
		'grooming_need' => 2,
		'shedding' => 3,
		'barking' => 3,
		'guarding_tendency' => 4,
		'child_friendliness' => 3,
		'dog_friendliness' => 3,
		'stranger_friendliness' => 3,
	],

	// Optional registry-specific classification object. Current records support AKC and FCI; add new registry keys only after reviewing codes and fields.
	'registry_classification' => [
		'akc' => [
			'group_akc_registry' => 'HER',
			'status_recognition' => 'REC',
		],
		'fci' => [
			'breed_fci_registry' => 15,
			'group_fci_registry' => '1',
			'section_fci_registry' => '1',
			'status_recognition' => 'REC',
		],
	],

	// Optional translatable summary for list cards, search previews, and compact detail views.
	'short_description' => [
		'eng' => [
			'text' => 'A Belgian herding and working dog breed known for intelligence, athleticism, and high drive.',
		],
	],

	// Optional translatable temperament profile. Should describe common breed-level tendencies without promising individual behavior.
	'temperament_description' => [
		'eng' => [
			'text' => 'Confident, Smart, Hardworking',
		],
	],

	// Optional translatable care summary covering general responsible care considerations for the breed.
	'care_description' => [
		'eng' => [
			'text' => 'Requires high daily exercise, structured training, mental work, and responsible handling.',
		],
	],

	// Optional translatable training summary describing typical training considerations, difficulty, or handler suitability.
	'training_description' => [
		'eng' => [
			'text' => 'Highly trainable but best suited to consistent handlers who can provide structure and work.',
		],
	],

	// Optional translatable exercise summary describing the breed-level movement and stimulation needs.
	'exercise_description' => [
		'eng' => [
			'text' => 'Needs vigorous physical exercise and mental stimulation every day.',
		],
	],

	// Optional translatable grooming summary describing coat care, brushing, shedding, or professional grooming considerations.
	'grooming_description' => [
		'eng' => [
			'text' => 'Short coat with moderate grooming needs; regular brushing is useful, especially during shedding periods.',
		],
	],

	// Required managed lifecycle status. Default is ACT, but managed editing screens should understand the field explicitly.
	'status_record_lifecycle' => 'ACT',

	// Optional internal maintenance note. This is not multilingual and is not intended as public breed content.
	'record_note' => 'Sample record used for the dog_breed_main structure guide.',

	// Required system timestamp for when the record was created/imported into common_reference.
	'created_at' => $created_at,

	// Required system timestamp for the most recent managed update to the record.
	'updated_at' => $updated_at,
];

/**
 * Current dog_breed_main logical field order.
 * Keep this order when writing documentation, schema previews, exports, generated
 * structure summaries, and UI structure inspectors.
 */
$dog_breed_main_field_order = [
	'_id',
	'dog_breed_key',
	'dog_breed_name',
	'alternative_name_list',
	'type_dog_breed',
	'group_dog_breed',
	'level_dog_size',
	'type_dog_coat',
	'origin_country_id_list',
	'height_cm',
	'weight_kg',
	'lifespan_year',
	'trait_score',
	'registry_classification',
	'short_description',
	'temperament_description',
	'care_description',
	'training_description',
	'exercise_description',
	'grooming_description',
	'status_record_lifecycle',
	'record_note',
	'created_at',
	'updated_at',
];

/**
 * Embedded/object structures owned by dog_breed_main.
 */
$dog_breed_main_embedded_structure = [
	'alternative_name_list' => [
		'alternative_name' => [
			'eng' => [
				'text' => 'Malinois',
				'method_translation' => 'HUM',
				'status_review' => 'APR',
			],
		],
		'status_record_lifecycle' => 'ACT',
		'sort_order' => 10,
	],
	'measurement_range' => [
		'min' => 0,
		'max' => 0,
	],
	'lifespan_range' => [
		'min' => 0,
		'max' => 0,
	],
	'trait_score' => [
		'energy' => 3,
		'trainability' => 3,
		'grooming_need' => 3,
		'shedding' => 3,
		'barking' => 3,
		'guarding_tendency' => 3,
		'child_friendliness' => 3,
		'dog_friendliness' => 3,
		'stranger_friendliness' => 3,
	],
	'registry_classification' => [
		'akc' => [
			'group_akc_registry' => 'HER',
			'status_recognition' => 'REC',
		],
		'fci' => [
			'breed_fci_registry' => 15,
			'group_fci_registry' => '1',
			'section_fci_registry' => '1',
			'status_recognition' => 'REC',
		],
	],
];

/**
 * Field-property guide for dog_breed_main.
 * For readability, label/description examples here assume English text directly.
 * In actual records, translatable properties must follow the multilingual object sample above.
 */
$dog_breed_main_field_property = [
	'_id' => [
		'field_label' => 'Dog Breed ID',
		'field_description' => 'Required numeric primary identifier for a dog breed common-reference record. It is assigned by the common reference ID counter and should be treated as dog_breed_id by referencing structures.',
		'type_data' => 'I',
		'type_sql' => 'INT',
		'is_mandatory' => true,
		'is_system' => true,
		'is_indexed' => true,
	],
	'dog_breed_key' => [
		'field_label' => 'Dog Breed Key',
		'field_description' => 'Required stable technical key in lowercase snake_case. It supports imports, comparisons, readable URLs or selectors, and cross-checks when numeric IDs are not convenient.',
		'min_character' => 1,
		'max_character' => 75,
		'constraint_text_input' => [
			'ADS',
			'NOL',
		],
		'type_input_cleanup' => [
			'TRM',
			'SNK',
		],
		'type_sql' => 'VARCHAR',
		'is_mandatory' => true,
		'is_indexed' => true,
	],
	'dog_breed_name' => [
		'field_label' => 'Dog Breed Name',
		'field_description' => 'Required multilingual display name used in lists, selectors, search results, and translated UI surfaces. Baseline records should at least provide English text.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'is_mandatory' => true,
		'is_indexed' => true,
	],
	'alternative_name_list' => [
		'field_label' => 'Alternative Name List',
		'field_description' => 'Optional list of multilingual alternative names, historical names, common names, or local names for the dog breed.',
		'type_data' => 'A',
		'type_field' => 'LST',
		'type_sql' => 'JSON',
	],
	'type_dog_breed' => [
		'field_label' => 'Dog Breed Type',
		'field_description' => 'Indicates what kind of breed identity this record represents, such as a recognized pure breed, local village dog population, landrace, hybrid, developing breed, or historical entry.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			[
				'option_code' => 'PUR',
				'option_label' => 'Purebred',
				'option_description' => 'Use for a breed maintained through a recognized or documented breeding population with a relatively consistent type, ancestry, and breed identity.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'VAR',
				'option_label' => 'Breed Variety',
				'option_description' => 'Use for a named variety within a broader breed, such as a coat, size, regional, or registry-recognized variety that should remain connected to the parent breed concept.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'LAN',
				'option_label' => 'Landrace',
				'option_description' => 'Use for a locally adapted dog population shaped mainly by geography, work, and natural selection rather than a closed modern breed registry.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'VIL',
				'option_label' => 'Village Dog',
				'option_description' => 'Use for free-breeding local or regional dog populations that are not managed as formal pure breeds but have cultural or geographic identity.',
				'sort_order' => 40,
			],
			[
				'option_code' => 'MIX',
				'option_label' => 'Mixed Breed',
				'option_description' => 'Use when the record intentionally represents mixed-breed dogs rather than a single recognized breed or stable named population.',
				'sort_order' => 50,
			],
			[
				'option_code' => 'HYB',
				'option_label' => 'Hybrid Breed',
				'option_description' => 'Use for deliberately crossed designer or hybrid breed types with a recurring name or expected parent-breed combination.',
				'sort_order' => 60,
			],
			[
				'option_code' => 'DEV',
				'option_label' => 'Developing Breed',
				'option_description' => 'Use for a breed or population still being standardized, documented, or evaluated before broader recognition.',
				'sort_order' => 70,
			],
			[
				'option_code' => 'HIS',
				'option_label' => 'Historical Breed',
				'option_description' => 'Use for extinct, obsolete, or primarily historical breed entries kept for reference and cross-linking.',
				'sort_order' => 80,
			],
			[
				'option_code' => 'UNK',
				'option_label' => 'Unknown',
				'option_description' => 'Use when the correct value cannot be determined from the available source data.',
				'sort_order' => 90,
			],
		],
		'is_mandatory' => true,
		'is_indexed' => true,
	],
	'group_dog_breed' => [
		'field_label' => 'Dog Breed Group',
		'field_description' => 'Broad functional or cultural group used for Zenith browsing and filtering. This is not a substitute for AKC or FCI registry-specific groups.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			[
				'option_code' => 'HER',
				'option_label' => 'Herding',
				'option_description' => 'Breeds historically selected to gather, drive, or manage livestock; often attentive, responsive, and work-oriented.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'WRK',
				'option_label' => 'Working',
				'option_description' => 'AKC Working Group for breeds developed for guarding, pulling, rescue, and other demanding work.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'SPT',
				'option_label' => 'Sporting',
				'option_description' => 'AKC Sporting Group for breeds developed for finding, flushing, pointing, or retrieving game birds.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'HND',
				'option_label' => 'Hound',
				'option_description' => 'AKC Hound Group for breeds that hunt primarily by scent or sight.',
				'sort_order' => 40,
			],
			[
				'option_code' => 'TER',
				'option_label' => 'Terrier',
				'option_description' => 'AKC Terrier Group for breeds developed to work vermin or other quarry with persistence and boldness.',
				'sort_order' => 50,
			],
			[
				'option_code' => 'TOY',
				'option_label' => 'Toy',
				'option_description' => 'Smallest size band, useful for toy and very small companion breeds.',
				'sort_order' => 60,
			],
			[
				'option_code' => 'NON',
				'option_label' => 'Non-Sporting',
				'option_description' => 'Registry-style catchall group for breeds that do not fit neatly into other broad functions.',
				'sort_order' => 70,
			],
			[
				'option_code' => 'COM',
				'option_label' => 'Companion',
				'option_description' => 'Breeds or populations primarily maintained for companionship rather than a specific working role.',
				'sort_order' => 80,
			],
			[
				'option_code' => 'GUA',
				'option_label' => 'Guardian',
				'option_description' => 'Breeds or populations commonly associated with property, livestock, or family guarding roles.',
				'sort_order' => 90,
			],
			[
				'option_code' => 'HNT',
				'option_label' => 'Hunting',
				'option_description' => 'Breeds used broadly for hunting work when a more specific hound, sporting, scent, or sight group is not selected.',
				'sort_order' => 100,
			],
			[
				'option_code' => 'SCT',
				'option_label' => 'Scent',
				'option_description' => 'Breeds primarily associated with scent-tracking or scent-driven hunting behavior.',
				'sort_order' => 110,
			],
			[
				'option_code' => 'SIT',
				'option_label' => 'Sighthound',
				'option_description' => 'Breeds primarily associated with speed and visual pursuit of game.',
				'sort_order' => 120,
			],
			[
				'option_code' => 'SPZ',
				'option_label' => 'Spitz',
				'option_description' => 'Breeds with spitz-type traits such as prick ears, curled tail, dense coat, and northern or primitive working ancestry.',
				'sort_order' => 130,
			],
			[
				'option_code' => 'PRI',
				'option_label' => 'Primitive',
				'option_description' => 'Breeds or populations often described as ancient, basal, pariah, or less heavily modified by modern breed selection.',
				'sort_order' => 140,
			],
			[
				'option_code' => 'NAT',
				'option_label' => 'Native',
				'option_description' => 'Local or indigenous dog populations tied to a country, region, or cultural context.',
				'sort_order' => 150,
			],
			[
				'option_code' => 'MIX',
				'option_label' => 'Mixed',
				'option_description' => 'Use for broad mixed group classification when a more specific functional or registry group is not appropriate.',
				'sort_order' => 160,
			],
			[
				'option_code' => 'UNK',
				'option_label' => 'Unknown',
				'option_description' => 'Use when the correct value cannot be determined from the available source data.',
				'sort_order' => 170,
			],
		],
		'is_indexed' => true,
	],
	'level_dog_size' => [
		'field_label' => 'Dog Size Level',
		'field_description' => 'Approximate size band used for quick filtering and comparison. It simplifies height/weight ranges into user-friendly size levels.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			[
				'option_code' => 'TOY',
				'option_label' => 'Toy',
				'option_description' => 'Smallest size band, useful for toy and very small companion breeds.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'SML',
				'option_label' => 'Small',
				'option_description' => 'Small size band for breeds generally larger than toy dogs but still compact and easy to classify as small.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'MED',
				'option_label' => 'Medium',
				'option_description' => 'Middle size band for breeds that are neither clearly small nor large.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'LRG',
				'option_label' => 'Large',
				'option_description' => 'Large size band for breeds with substantial adult height or weight but not typically classed as giant.',
				'sort_order' => 40,
			],
			[
				'option_code' => 'GIA',
				'option_label' => 'Giant',
				'option_description' => 'Largest size band for very large breeds where scale strongly affects care, handling, space, and cost.',
				'sort_order' => 50,
			],
			[
				'option_code' => 'UNK',
				'option_label' => 'Unknown',
				'option_description' => 'Use when the correct value cannot be determined from the available source data.',
				'sort_order' => 60,
			],
		],
		'is_indexed' => true,
	],
	'type_dog_coat' => [
		'field_label' => 'Dog Coat Type',
		'field_description' => 'Broad coat structure used for grooming hints, filtering, and quick comparison. It describes the general coat profile, not every possible coat variation.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			[
				'option_code' => 'HAI',
				'option_label' => 'Hairless',
				'option_description' => 'Coat type where the breed is commonly hairless or nearly hairless, often requiring skin-focused care rather than coat brushing.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'SMO',
				'option_label' => 'Smooth',
				'option_description' => 'Very close, smooth coat lying flat to the body; usually low bulk but not necessarily low shedding.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'SHO',
				'option_label' => 'Short',
				'option_description' => 'Short coat length that may still shed or require routine brushing depending on density and undercoat.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'MED',
				'option_label' => 'Medium',
				'option_description' => 'Middle size band for breeds that are neither clearly small nor large.',
				'sort_order' => 40,
			],
			[
				'option_code' => 'LON',
				'option_label' => 'Long',
				'option_description' => 'Long coat requiring more frequent grooming, brushing, and attention to mats or tangles.',
				'sort_order' => 50,
			],
			[
				'option_code' => 'DBL',
				'option_label' => 'Double',
				'option_description' => 'Coat with outer coat and undercoat; often seasonal shedding and undercoat management are important.',
				'sort_order' => 60,
			],
			[
				'option_code' => 'WIR',
				'option_label' => 'Wire',
				'option_description' => 'Harsh or wiry coat texture, sometimes associated with hand-stripping or specialized coat maintenance.',
				'sort_order' => 70,
			],
			[
				'option_code' => 'CUR',
				'option_label' => 'Curly',
				'option_description' => 'Curled coat texture that may trap loose hair and require brushing, trimming, or professional grooming.',
				'sort_order' => 80,
			],
			[
				'option_code' => 'COR',
				'option_label' => 'Corded',
				'option_description' => 'Coat that naturally forms or is maintained in cords; grooming needs differ from ordinary brushing routines.',
				'sort_order' => 90,
			],
			[
				'option_code' => 'SIL',
				'option_label' => 'Silky',
				'option_description' => 'Fine, silky coat texture that can mat or tangle without regular maintenance.',
				'sort_order' => 100,
			],
			[
				'option_code' => 'WAV',
				'option_label' => 'Wavy',
				'option_description' => 'Wave-pattern coat that may combine shedding, tangling, or curl-like maintenance needs.',
				'sort_order' => 110,
			],
			[
				'option_code' => 'ROU',
				'option_label' => 'Rough',
				'option_description' => 'Coarse, rough, or rugged coat profile that may require brushing, stripping, or special care depending on breed.',
				'sort_order' => 120,
			],
			[
				'option_code' => 'BRO',
				'option_label' => 'Broken',
				'option_description' => 'Mixed smooth-and-wire coat texture often seen in terrier-type coats; care depends on coat expression.',
				'sort_order' => 130,
			],
			[
				'option_code' => 'UNK',
				'option_label' => 'Unknown',
				'option_description' => 'Use when the correct value cannot be determined from the available source data.',
				'sort_order' => 140,
			],
		],
		'is_indexed' => true,
	],
	'origin_country_id_list' => [
		'field_label' => 'Origin Country List',
		'field_description' => 'Array of numeric country IDs from common_reference.country_main. This is an ID list, not embedded country records.',
		'type_data' => 'A',
		'type_field' => 'MSEL',
		'type_sql' => 'JSON',
		'is_relational' => true,
		'field_reference' => [
			'database_name' => 'common_reference',
			'collection_name' => 'country_main',
			'reference_field' => '_id',
			'display_field' => 'country_name.eng.text',
		],
		'is_indexed' => true,
	],
	'height_cm' => [
		'field_label' => 'Height Range in Centimeters',
		'field_description' => 'Typical adult height range in centimeters. Use min and max from breed-level reference sources; individual dogs may fall outside the range.',
		'type_data' => 'O',
		'type_field' => 'RNG',
		'type_sql' => 'JSON',
	],
	'weight_kg' => [
		'field_label' => 'Weight Range in Kilograms',
		'field_description' => 'Typical adult weight range in kilograms. Use min and max from breed-level reference sources; individual dogs may vary by sex, line, health, and condition.',
		'type_data' => 'O',
		'type_field' => 'RNG',
		'type_sql' => 'JSON',
	],
	'lifespan_year' => [
		'field_label' => 'Lifespan Range in Years',
		'field_description' => 'Typical lifespan range in years for breed-level reference. This is not a health prediction for a specific animal.',
		'type_data' => 'O',
		'type_field' => 'RNG',
		'type_sql' => 'JSON',
	],
	'trait_score' => [
		'field_label' => 'Trait Score',
		'field_description' => 'Object containing 1–5 approximate trait scores for common dog breed characteristics. Scores are general reference data, not guarantees for individual dogs.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'type_sql' => 'JSON',
	],
	'registry_classification' => [
		'field_label' => 'Registry Classification',
		'field_description' => 'Object containing registry-specific breed classification data such as AKC group and FCI breed/group/section numbers.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'type_sql' => 'JSON',
	],
	'short_description' => [
		'field_label' => 'Short Description',
		'field_description' => 'Short translatable summary intended for cards, previews, and quick reading. It should identify what is notable without becoming a full article.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'format_text' => 'MD',
		'is_translatable' => true,
	],
	'temperament_description' => [
		'field_label' => 'Temperament Description',
		'field_description' => 'Translatable overview of common temperament tendencies. Use cautious breed-level wording and avoid implying that every dog behaves the same way.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'format_text' => 'MD',
		'is_translatable' => true,
	],
	'care_description' => [
		'field_label' => 'Care Description',
		'field_description' => 'Translatable care overview covering broad breed-level considerations such as responsible handling, veterinary care, environment, exercise, and socialization.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'format_text' => 'MD',
		'is_translatable' => true,
	],
	'training_description' => [
		'field_label' => 'Training Description',
		'field_description' => 'Translatable training overview describing common trainability patterns, handling considerations, or training needs for the breed.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'format_text' => 'MD',
		'is_translatable' => true,
	],
	'exercise_description' => [
		'field_label' => 'Exercise Description',
		'field_description' => 'Translatable exercise overview describing typical activity level, mental stimulation needs, and movement requirements.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'format_text' => 'MD',
		'is_translatable' => true,
	],
	'grooming_description' => [
		'field_label' => 'Grooming Description',
		'field_description' => 'Translatable grooming overview describing coat care, brushing frequency, shedding, bathing, or professional grooming needs.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'format_text' => 'MD',
		'is_translatable' => true,
	],
	'record_note' => [
		'field_label' => 'Record Note',
		'field_description' => 'Optional internal maintenance note about source quality, uncertainty, pending review, or editing context. It is not multilingual and should not be treated as public breed description.',
		'type_data' => 'S',
		'type_field' => 'TXA',
		'format_text' => 'MD',
	],
	'status_record_lifecycle' => [
		'field_label' => 'Dog Breed Status',
		'field_description' => 'Required managed lifecycle state for the dog breed record. ACT is the default; non-active states support review, deprecation, soft deletion, merging, duplicate tracking, and data-quality triage.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			[
				'option_code' => 'DRA',
				'option_label' => 'Draft',
				'option_description' => 'Record is being prepared and should not yet be treated as fully accepted reference data.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'ACT',
				'option_label' => 'Active',
				'option_description' => 'Record is the current usable reference entry for selectors, lookups, and project use.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'INA',
				'option_label' => 'Inactive',
				'option_description' => 'Record is retained but should generally not be selected for new use unless specifically needed.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'ARC',
				'option_label' => 'Archived',
				'option_description' => 'Record is kept for history or reference but is no longer part of normal active workflows.',
				'sort_order' => 40,
			],
			[
				'option_code' => 'DEL',
				'option_label' => 'Deleted',
				'option_description' => 'Record is soft-deleted and retained to avoid breaking references or audit history.',
				'sort_order' => 50,
			],
			[
				'option_code' => 'MRG',
				'option_label' => 'Merged',
				'option_description' => 'Record has been merged into another canonical entry and should point users toward the replacement record.',
				'sort_order' => 60,
			],
			[
				'option_code' => 'DUP',
				'option_label' => 'Duplicate',
				'option_description' => 'Record is identified as a duplicate candidate and needs review or merge resolution.',
				'sort_order' => 70,
			],
			[
				'option_code' => 'ERR',
				'option_label' => 'Error',
				'option_description' => 'Record contains known structural or data-quality problems requiring correction.',
				'sort_order' => 80,
			],
			[
				'option_code' => 'UNK',
				'option_label' => 'Unknown',
				'option_description' => 'Use when the correct value cannot be determined from the available source data.',
				'sort_order' => 90,
			],
		],
		'default_value' => 'ACT',
		'is_mandatory' => true,
		'is_indexed' => true,
	],
	'created_at' => [
		'field_label' => 'Date Created',
		'field_description' => 'Required system timestamp for when the record was created, imported, or first accepted into dog_breed_main management.',
		'type_field' => 'DTT',
		'type_sql' => 'DATETIME',
		'is_mandatory' => true,
		'is_system' => true,
	],
	'updated_at' => [
		'field_label' => 'Date Updated',
		'field_description' => 'Required system timestamp for the most recent managed change to the record.',
		'type_field' => 'DTT',
		'type_sql' => 'DATETIME',
		'is_mandatory' => true,
		'is_system' => true,
	],
];

/**
 * Sub-field guide for embedded structures.
 */
$dog_breed_main_subfield_property = [
	'multilingual_text' => [
		'text' => [
			'field_label' => 'Text',
			'field_description' => 'Language-specific text value.',
			'type_field' => 'TXA',
			'is_mandatory' => true,
		],
		'method_translation' => [
			'field_label' => 'Translation Method',
			'field_description' => 'How the text was originally translated or produced. Default HUM is omitted when the text is human-translated.',
			'type_field' => 'DDL',
			'type_sql' => 'ENUM',
			'field_option' => [
				[
					'option_code' => 'HUM',
					'option_label' => 'Human Translation',
					'option_description' => 'Text was translated or written by a person and is the default method for approved baseline entries.',
					'sort_order' => 10,
				],
				[
					'option_code' => 'AI',
					'option_label' => 'AI Translation',
					'option_description' => 'Text was produced or assisted by an AI model and may require human review depending on workflow rules.',
					'sort_order' => 20,
				],
				[
					'option_code' => 'MT',
					'option_label' => 'Machine Translation',
					'option_description' => 'Text was produced by a non-AI or traditional machine translation tool and may need review.',
					'sort_order' => 30,
				],
				[
					'option_code' => 'IMP',
					'option_label' => 'Imported Translation',
					'option_description' => 'Text came from an imported dataset or external source and should preserve source/review context when known.',
					'sort_order' => 40,
				],
				[
					'option_code' => 'MIG',
					'option_label' => 'Migrated Translation',
					'option_description' => 'Text was carried over from a previous Zenith structure or legacy source during migration.',
					'sort_order' => 50,
				],
				[
					'option_code' => 'UNK',
					'option_label' => 'Unknown',
					'option_description' => 'Use when the correct value cannot be determined from the available source data.',
					'sort_order' => 60,
				],
			],
			'default_value' => 'HUM',
		],
		'status_review' => [
			'field_label' => 'Review Status',
			'field_description' => 'Review state of the language-specific text when review is managed. Default APR is omitted when the text is approved.',
			'type_field' => 'DDL',
			'type_sql' => 'ENUM',
			'field_option' => [
				[
					'option_code' => 'NR',
					'option_label' => 'Not Reviewed',
					'option_description' => 'Translation has not yet been reviewed for accuracy, tone, terminology, or suitability.',
					'sort_order' => 10,
				],
				[
					'option_code' => 'PND',
					'option_label' => 'Pending',
					'option_description' => 'Translation is awaiting review or approval.',
					'sort_order' => 20,
				],
				[
					'option_code' => 'APR',
					'option_label' => 'Approved',
					'option_description' => 'Translation has been reviewed or accepted for use; this is the sparse default review status.',
					'sort_order' => 30,
				],
				[
					'option_code' => 'REJ',
					'option_label' => 'Rejected',
					'option_description' => 'Translation was reviewed and rejected; it should not be treated as usable until corrected.',
					'sort_order' => 40,
				],
				[
					'option_code' => 'DEF',
					'option_label' => 'Deferred',
					'option_description' => 'Review was intentionally postponed, usually because the language or content is lower priority.',
					'sort_order' => 50,
				],
				[
					'option_code' => 'SUP',
					'option_label' => 'Superseded',
					'option_description' => 'Translation has been replaced by a newer version but may remain for history.',
					'sort_order' => 60,
				],
				[
					'option_code' => 'REV',
					'option_label' => 'Needs Revision',
					'option_description' => 'Translation needs edits before it can be approved.',
					'sort_order' => 70,
				],
				[
					'option_code' => 'ESC',
					'option_label' => 'Escalated',
					'option_description' => 'Translation needs specialist, owner, or higher-level review.',
					'sort_order' => 80,
				],
			],
			'default_value' => 'APR',
		],
	],
	'alternative_name_list' => [
		'alternative_name' => [
			'field_label' => 'Alternative Name',
			'field_description' => 'Multilingual alternative name for the dog breed.',
			'type_data' => 'O',
			'type_field' => 'JSE',
			'is_translatable' => true,
			'is_mandatory' => true,
		],
		'sort_order' => [
			'field_label' => 'Sort Order',
			'field_description' => 'Optional order for displaying alternative names.',
			'type_data' => 'I',
			'type_field' => 'SPN',
			'type_sql' => 'INT',
		],
		'status_record_lifecycle' => [
			'field_label' => 'Alternative Name Status',
			'field_description' => 'Lifecycle status for this alternative name entry.',
			'type_field' => 'DDL',
			'type_sql' => 'ENUM',
			'field_option' => [
				[
					'option_code' => 'DRA',
					'option_label' => 'Draft',
					'option_description' => 'Record is being prepared and should not yet be treated as fully accepted reference data.',
					'sort_order' => 10,
				],
				[
					'option_code' => 'ACT',
					'option_label' => 'Active',
					'option_description' => 'Record is the current usable reference entry for selectors, lookups, and project use.',
					'sort_order' => 20,
				],
				[
					'option_code' => 'INA',
					'option_label' => 'Inactive',
					'option_description' => 'Record is retained but should generally not be selected for new use unless specifically needed.',
					'sort_order' => 30,
				],
				[
					'option_code' => 'ARC',
					'option_label' => 'Archived',
					'option_description' => 'Record is kept for history or reference but is no longer part of normal active workflows.',
					'sort_order' => 40,
				],
				[
					'option_code' => 'DEL',
					'option_label' => 'Deleted',
					'option_description' => 'Record is soft-deleted and retained to avoid breaking references or audit history.',
					'sort_order' => 50,
				],
				[
					'option_code' => 'MRG',
					'option_label' => 'Merged',
					'option_description' => 'Record has been merged into another canonical entry and should point users toward the replacement record.',
					'sort_order' => 60,
				],
				[
					'option_code' => 'DUP',
					'option_label' => 'Duplicate',
					'option_description' => 'Record is identified as a duplicate candidate and needs review or merge resolution.',
					'sort_order' => 70,
				],
				[
					'option_code' => 'ERR',
					'option_label' => 'Error',
					'option_description' => 'Record contains known structural or data-quality problems requiring correction.',
					'sort_order' => 80,
				],
				[
					'option_code' => 'UNK',
					'option_label' => 'Unknown',
					'option_description' => 'Use when the correct value cannot be determined from the available source data.',
					'sort_order' => 90,
				],
			],
			'default_value' => 'ACT',
		],
	],
	'measurement_range' => [
		'min' => [
			'field_label' => 'Minimum',
			'field_description' => 'Minimum value in the range.',
			'type_data' => 'F',
			'type_field' => 'SPN',
			'type_sql' => 'DECIMAL',
		],
		'max' => [
			'field_label' => 'Maximum',
			'field_description' => 'Maximum value in the range.',
			'type_data' => 'F',
			'type_field' => 'SPN',
			'type_sql' => 'DECIMAL',
		],
	],
	'lifespan_range' => [
		'min' => [
			'field_label' => 'Minimum Years',
			'field_description' => 'Minimum lifespan in years.',
			'type_data' => 'I',
			'type_field' => 'SPN',
			'type_sql' => 'INT',
		],
		'max' => [
			'field_label' => 'Maximum Years',
			'field_description' => 'Maximum lifespan in years.',
			'type_data' => 'I',
			'type_field' => 'SPN',
			'type_sql' => 'INT',
		],
	],
	'trait_score' => [
		'energy' => [
			'field_label' => 'Energy',
			'field_description' => 'Approximate energy level score from 1 to 5.',
			'type_data' => 'I',
			'type_field' => 'SLD',
			'type_sql' => 'TINYINT',
			'min_number' => 1,
			'max_number' => 5,
		],
		'trainability' => [
			'field_label' => 'Trainability',
			'field_description' => 'Approximate trainability score from 1 to 5.',
			'type_data' => 'I',
			'type_field' => 'SLD',
			'type_sql' => 'TINYINT',
			'min_number' => 1,
			'max_number' => 5,
		],
		'grooming_need' => [
			'field_label' => 'Grooming Need',
			'field_description' => 'Approximate grooming-need score from 1 to 5.',
			'type_data' => 'I',
			'type_field' => 'SLD',
			'type_sql' => 'TINYINT',
			'min_number' => 1,
			'max_number' => 5,
		],
		'shedding' => [
			'field_label' => 'Shedding',
			'field_description' => 'Approximate shedding score from 1 to 5.',
			'type_data' => 'I',
			'type_field' => 'SLD',
			'type_sql' => 'TINYINT',
			'min_number' => 1,
			'max_number' => 5,
		],
		'barking' => [
			'field_label' => 'Barking',
			'field_description' => 'Approximate barking tendency score from 1 to 5.',
			'type_data' => 'I',
			'type_field' => 'SLD',
			'type_sql' => 'TINYINT',
			'min_number' => 1,
			'max_number' => 5,
		],
		'guarding_tendency' => [
			'field_label' => 'Guarding Tendency',
			'field_description' => 'Approximate guarding tendency score from 1 to 5.',
			'type_data' => 'I',
			'type_field' => 'SLD',
			'type_sql' => 'TINYINT',
			'min_number' => 1,
			'max_number' => 5,
		],
		'child_friendliness' => [
			'field_label' => 'Child Friendliness',
			'field_description' => 'Approximate child-friendliness score from 1 to 5.',
			'type_data' => 'I',
			'type_field' => 'SLD',
			'type_sql' => 'TINYINT',
			'min_number' => 1,
			'max_number' => 5,
		],
		'dog_friendliness' => [
			'field_label' => 'Dog Friendliness',
			'field_description' => 'Approximate dog-friendliness score from 1 to 5.',
			'type_data' => 'I',
			'type_field' => 'SLD',
			'type_sql' => 'TINYINT',
			'min_number' => 1,
			'max_number' => 5,
		],
		'stranger_friendliness' => [
			'field_label' => 'Stranger Friendliness',
			'field_description' => 'Approximate stranger-friendliness score from 1 to 5.',
			'type_data' => 'I',
			'type_field' => 'SLD',
			'type_sql' => 'TINYINT',
			'min_number' => 1,
			'max_number' => 5,
		],
	],
	'registry_classification.akc' => [
		'group_akc_registry' => [
			'field_label' => 'AKC Registry Group',
			'field_description' => 'American Kennel Club (AKC) registry-specific group for this breed. AKC values are kept inside registry_classification.akc and should not be confused with Zenith\'s general group_dog_breed.',
			'type_field' => 'DDL',
			'type_sql' => 'ENUM',
			'field_option' => [
				[
					'option_code' => 'SPT',
					'option_label' => 'Sporting',
					'option_description' => 'AKC Sporting Group for breeds developed for finding, flushing, pointing, or retrieving game birds.',
					'sort_order' => 10,
				],
				[
					'option_code' => 'HND',
					'option_label' => 'Hound',
					'option_description' => 'AKC Hound Group for breeds that hunt primarily by scent or sight.',
					'sort_order' => 20,
				],
				[
					'option_code' => 'WRK',
					'option_label' => 'Working',
					'option_description' => 'AKC Working Group for breeds developed for guarding, pulling, rescue, and other demanding work.',
					'sort_order' => 30,
				],
				[
					'option_code' => 'TER',
					'option_label' => 'Terrier',
					'option_description' => 'AKC Terrier Group for breeds developed to work vermin or other quarry with persistence and boldness.',
					'sort_order' => 40,
				],
				[
					'option_code' => 'TOY',
					'option_label' => 'Toy',
					'option_description' => 'Small companion breeds where compact size and companionship are central to the breed identity.',
					'sort_order' => 50,
				],
				[
					'option_code' => 'NON',
					'option_label' => 'Non-Sporting',
					'option_description' => 'Registry-style catchall group for breeds that do not fit neatly into other broad functions.',
					'sort_order' => 60,
				],
				[
					'option_code' => 'HER',
					'option_label' => 'Herding',
					'option_description' => 'Breeds historically selected to gather, drive, or manage livestock; often attentive, responsive, and work-oriented.',
					'sort_order' => 70,
				],
				[
					'option_code' => 'MSC',
					'option_label' => 'Miscellaneous Class',
					'option_description' => 'AKC class for breeds that are not yet fully assigned to a regular AKC group.',
					'sort_order' => 80,
				],
				[
					'option_code' => 'FSS',
					'option_label' => 'Foundation Stock Service',
					'option_description' => 'AKC Foundation Stock Service entry for breeds recorded while recognition or development is still in progress.',
					'sort_order' => 90,
				],
			],
		],
		'status_recognition' => [
			'field_label' => 'AKC Recognition Status',
			'field_description' => 'Recognition status within the American Kennel Club (AKC) context, such as recognized, foundation-service, provisional, or unrecognized.',
			'type_field' => 'DDL',
			'type_sql' => 'ENUM',
			'field_option' => [
				[
					'option_code' => 'REC',
					'option_label' => 'Recognized',
					'option_description' => 'Registry recognizes the breed or classification as accepted/current within that registry context.',
					'sort_order' => 10,
				],
				[
					'option_code' => 'PRV',
					'option_label' => 'Provisionally Recognized',
					'option_description' => 'Registry has accepted the breed provisionally or in a transitional recognition stage.',
					'sort_order' => 20,
				],
				[
					'option_code' => 'CND',
					'option_label' => 'Candidate',
					'option_description' => 'Breed is a candidate or under consideration for recognition.',
					'sort_order' => 30,
				],
				[
					'option_code' => 'FND',
					'option_label' => 'Foundation',
					'option_description' => 'Breed is tracked in a foundation/development service or equivalent preliminary registry pathway.',
					'sort_order' => 40,
				],
				[
					'option_code' => 'DEV',
					'option_label' => 'In Development',
					'option_description' => 'Breed or recognition entry is still being developed or documented.',
					'sort_order' => 50,
				],
				[
					'option_code' => 'UNR',
					'option_label' => 'Unrecognized',
					'option_description' => 'Registry does not currently recognize the breed in the selected context.',
					'sort_order' => 60,
				],
				[
					'option_code' => 'WDN',
					'option_label' => 'Withdrawn',
					'option_description' => 'Recognition or listing has been withdrawn or removed from active consideration.',
					'sort_order' => 70,
				],
				[
					'option_code' => 'RET',
					'option_label' => 'Retired',
					'option_description' => 'Registry entry is retained historically but no longer active in normal recognition workflows.',
					'sort_order' => 80,
				],
				[
					'option_code' => 'DIS',
					'option_label' => 'Disputed',
					'option_description' => 'Recognition, classification, or identity is contested or requires careful review.',
					'sort_order' => 90,
				],
				[
					'option_code' => 'UNK',
					'option_label' => 'Unknown',
					'option_description' => 'Use when the correct value cannot be determined from the available source data.',
					'sort_order' => 100,
				],
			],
		],
	],
	'registry_classification.fci' => [
		'breed_fci_registry' => [
			'field_label' => 'FCI Breed Number',
			'field_description' => 'Fédération Cynologique Internationale (FCI) breed number, when the breed has an FCI standard or listing. This is registry-specific numeric reference data.',
			'type_data' => 'I',
			'type_field' => 'SPN',
			'type_sql' => 'INT',
		],
		'group_fci_registry' => [
			'field_label' => 'FCI Registry Group',
			'field_description' => 'Fédération Cynologique Internationale (FCI) numbered group. Interpret with FCI breed and section values rather than Zenith general grouping.',
			'type_field' => 'DDL',
			'type_sql' => 'ENUM',
			'field_option' => [
				[
					'option_code' => '1',
					'option_label' => 'Sheepdogs and Cattledogs',
					'option_description' => 'FCI Group 1 for sheepdogs and cattledogs, excluding Swiss mountain/cattle dogs handled in Group 2.',
					'sort_order' => 10,
				],
				[
					'option_code' => '2',
					'option_label' => 'Pinscher, Schnauzer, Molossoid and Swiss Mountain and Cattledogs',
					'option_description' => 'FCI Group 2 for pinscher/schnauzer types, molossoid breeds, and Swiss mountain/cattle dogs.',
					'sort_order' => 20,
				],
				[
					'option_code' => '3',
					'option_label' => 'Terriers',
					'option_description' => 'FCI Group 3 for terrier breeds.',
					'sort_order' => 30,
				],
				[
					'option_code' => '4',
					'option_label' => 'Dachshunds',
					'option_description' => 'FCI Group 4 for dachshunds.',
					'sort_order' => 40,
				],
				[
					'option_code' => '5',
					'option_label' => 'Spitz and Primitive Types',
					'option_description' => 'FCI Group 5 for spitz and primitive-type breeds.',
					'sort_order' => 50,
				],
				[
					'option_code' => '6',
					'option_label' => 'Scenthounds and Related Breeds',
					'option_description' => 'FCI Group 6 for scenthounds and related breeds.',
					'sort_order' => 60,
				],
				[
					'option_code' => '7',
					'option_label' => 'Pointing Dogs',
					'option_description' => 'FCI Group 7 for pointing dogs.',
					'sort_order' => 70,
				],
				[
					'option_code' => '8',
					'option_label' => 'Retrievers, Flushing Dogs and Water Dogs',
					'option_description' => 'FCI Group 8 for retrievers, flushing dogs, and water dogs.',
					'sort_order' => 80,
				],
				[
					'option_code' => '9',
					'option_label' => 'Companion and Toy Dogs',
					'option_description' => 'FCI Group 9 for companion and toy dog breeds.',
					'sort_order' => 90,
				],
				[
					'option_code' => '10',
					'option_label' => 'Sighthounds',
					'option_description' => 'FCI Group 10 for sighthounds, breeds that primarily hunt by sight and speed.',
					'sort_order' => 100,
				],
			],
		],
		'section_fci_registry' => [
			'field_label' => 'FCI Registry Section',
			'field_description' => 'Fédération Cynologique Internationale (FCI) section number within the selected FCI group. The same section number can mean different things depending on the group.',
			'type_field' => 'DDL',
			'type_sql' => 'ENUM',
			'field_option' => [
				[
					'option_code' => '1',
					'option_label' => 'Section 1',
					'option_description' => 'FCI Section 1 within the selected FCI group. Interpret together with the FCI group number, not as a standalone global category.',
					'sort_order' => 10,
				],
				[
					'option_code' => '2',
					'option_label' => 'Section 2',
					'option_description' => 'FCI Section 2 within the selected FCI group. Interpret together with the FCI group number, not as a standalone global category.',
					'sort_order' => 20,
				],
				[
					'option_code' => '3',
					'option_label' => 'Section 3',
					'option_description' => 'FCI Section 3 within the selected FCI group. Interpret together with the FCI group number, not as a standalone global category.',
					'sort_order' => 30,
				],
				[
					'option_code' => '4',
					'option_label' => 'Section 4',
					'option_description' => 'FCI Section 4 within the selected FCI group. Interpret together with the FCI group number, not as a standalone global category.',
					'sort_order' => 40,
				],
			],
		],
		'status_recognition' => [
			'field_label' => 'FCI Recognition Status',
			'field_description' => 'Recognition status within the Fédération Cynologique Internationale (FCI) context, such as recognized, provisional, candidate, or unrecognized.',
			'type_field' => 'DDL',
			'type_sql' => 'ENUM',
			'field_option' => [
				[
					'option_code' => 'REC',
					'option_label' => 'Recognized',
					'option_description' => 'Registry recognizes the breed or classification as accepted/current within that registry context.',
					'sort_order' => 10,
				],
				[
					'option_code' => 'PRV',
					'option_label' => 'Provisionally Recognized',
					'option_description' => 'Registry has accepted the breed provisionally or in a transitional recognition stage.',
					'sort_order' => 20,
				],
				[
					'option_code' => 'CND',
					'option_label' => 'Candidate',
					'option_description' => 'Breed is a candidate or under consideration for recognition.',
					'sort_order' => 30,
				],
				[
					'option_code' => 'FND',
					'option_label' => 'Foundation',
					'option_description' => 'Breed is tracked in a foundation/development service or equivalent preliminary registry pathway.',
					'sort_order' => 40,
				],
				[
					'option_code' => 'DEV',
					'option_label' => 'In Development',
					'option_description' => 'Breed or recognition entry is still being developed or documented.',
					'sort_order' => 50,
				],
				[
					'option_code' => 'UNR',
					'option_label' => 'Unrecognized',
					'option_description' => 'Registry does not currently recognize the breed in the selected context.',
					'sort_order' => 60,
				],
				[
					'option_code' => 'WDN',
					'option_label' => 'Withdrawn',
					'option_description' => 'Recognition or listing has been withdrawn or removed from active consideration.',
					'sort_order' => 70,
				],
				[
					'option_code' => 'RET',
					'option_label' => 'Retired',
					'option_description' => 'Registry entry is retained historically but no longer active in normal recognition workflows.',
					'sort_order' => 80,
				],
				[
					'option_code' => 'DIS',
					'option_label' => 'Disputed',
					'option_description' => 'Recognition, classification, or identity is contested or requires careful review.',
					'sort_order' => 90,
				],
				[
					'option_code' => 'UNK',
					'option_label' => 'Unknown',
					'option_description' => 'Use when the correct value cannot be determined from the available source data.',
					'sort_order' => 100,
				],
			],
		],
	],
];

/**
 * Taxonomy field map.
 * These dog_breed_main properties use controlled values from Shared Taxonomy v2.00.
 */
$dog_breed_main_taxonomy_field_map = [
	'type_dog_breed' => 'type_dog_breed',
	'group_dog_breed' => 'group_dog_breed',
	'level_dog_size' => 'level_dog_size',
	'type_dog_coat' => 'type_dog_coat',
	'registry_classification.akc.group_akc_registry' => 'group_akc_registry',
	'registry_classification.akc.status_recognition' => 'status_recognition',
	'registry_classification.fci.group_fci_registry' => 'group_fci_registry',
	'registry_classification.fci.section_fci_registry' => 'section_fci_registry',
	'registry_classification.fci.status_recognition' => 'status_recognition',
	'status_record_lifecycle' => 'status_record_lifecycle',
	'multilingual_text.method_translation' => 'method_translation',
	'multilingual_text.status_review' => 'status_review',
];

/**
 * Readiness checks suggested for the Dog Breed Common Reference workflow.
 */
$dog_breed_main_readiness_rule = [
	'Every record should have a numeric _id and unique dog_breed_key.',
	'dog_breed_name.eng.text should exist for all active records.',
	'type_dog_breed should use a code from Shared Taxonomy type_dog_breed.',
	'group_dog_breed, level_dog_size, type_dog_coat, registry groups, and recognition statuses should use Shared Taxonomy codes when present.',
	'origin_country_id_list should contain numeric country IDs that exist in common_reference.country_main.',
	'height_cm, weight_kg, and lifespan_year ranges should have min <= max when both values exist.',
	'trait_score values should be integers from 1 to 5 when present.',
	'Baseline translation readiness should check configured baseline languages only, not all language_main records.',
];

/**
 * Boundaries.
 */
$dog_breed_main_boundary = [
	'belongs_in_dog_breed_main' => [
		'canonical dog breed identity and display names',
		'breed type/group/size/coat classification',
		'origin country ID list',
		'height, weight, and lifespan ranges',
		'general trait scores',
		'registry classification summaries',
		'common multilingual dog breed descriptions',
	],
	'does_not_belong_in_dog_breed_main' => [
		'individual dog profile data',
		'pet medical records',
		'project-specific dog breed overrides',
		'hardcoded dog breed lists inside utilities or calculators',
		'full country records embedded inside origin_country_id_list',
	],
];

return [
	'dog_breed_property_default' => $dog_breed_property_default,
	'multilingual_text_sample' => $multilingual_text_sample,
	'dog_breed_main' => $dog_breed_main,
	'dog_breed_main_field_order' => $dog_breed_main_field_order,
	'dog_breed_main_embedded_structure' => $dog_breed_main_embedded_structure,
	'dog_breed_main_field_property' => $dog_breed_main_field_property,
	'dog_breed_main_subfield_property' => $dog_breed_main_subfield_property,
	'dog_breed_main_taxonomy_field_map' => $dog_breed_main_taxonomy_field_map,
	'dog_breed_main_readiness_rule' => $dog_breed_main_readiness_rule,
	'dog_breed_main_boundary' => $dog_breed_main_boundary,
];
