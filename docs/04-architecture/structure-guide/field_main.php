<?php
/**
 * Title: Zenith Field Catalog Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: field_main
 *
 * Purpose:
 * This file is a PHP-readable visual guide for the current field_main structure.
 * It is not a seed file, not a runtime migration script, and not a replacement
 * for the canonical Zenith documents. It exists so the Field Catalog can be
 * reviewed in a familiar PHP structure format.
 *
 * Important:
 * - field_main is the global Zenith Field Catalog.
 * - field_main defines canonical reusable field meaning.
 * - Forms and database collections reference field_main records by field_id.
 * - Form fields and collection fields may add local usage overrides.
 * - Local usage overrides must not redefine the canonical field meaning here.
 *
 * Sparse-default rule:
 * Individual field-property definitions below omit properties when the value is
 * the default. Example: type_data defaults to S, so field_name does not repeat
 * type_data => S.
 */

# Variable
$created_at = '2018-08-20T14:18:39Z';
$updated_at = '2026-06-24T00:00:00Z';

/**
 * Default field-property values.
 *
 * These defaults are interpreted by the application and should not be repeated
 * inside each field property unless the value is intentionally different.
 */
$field_property_default = [
	'type_data' => 'S', // default runtime value shape is String
	'type_field' => 'TXT', // default suggested UI control is Text Box
	'format_text' => 'TXT', // default text format is Plain Text
	'point_count' => 0, // default reward value is zero
	'is_auto_complete' => false, // default: autocomplete disabled
	'is_translatable' => false, // default: stored value is not multilingual
	'is_mandatory' => false, // default: not required
	'is_relational' => false, // default: not normally a reference field
	'is_indexed' => false, // default: no common indexing suggestion
	'status_record_lifecycle' => 'ACT', // default: Active
	'visibility_scope' => 'INH', // default: inherit visibility
	'level_nsfw' => 'N', // default: None
	'is_system' => false, // default: user/editable field, not system-owned
	'collection_count' => 0, // default cached collection usage count
	'form_count' => 0, // default cached form usage count
];

/**
 * Multilingual short-text structure used by field_label, field_description,
 * placeholder_text, option_label, option_description, and validation_message.
 *
 * English must exist when the value is user-facing.
 * method_translation defaults to HUM and should be omitted when the value is Human Translation.
 * status_review defaults to APR and should be omitted when the value is Approved.
 * status_translation was a legacy/working-name idea; use status_review.
 */
$multilingual_text_sample = [
	'eng' => [
		'text' => 'Field Name', // required for English/default text when parent field is present
		'method_translation' => 'HUM', // optional; how this text was produced when tracked
		'status_review' => 'APR', // optional; approved review status when review is managed
	],
];

/**
 * field_main sample record.
 *
 * This sample describes the Field Catalog record for field_name itself.
 * It keeps your explanatory comments, but default-valued properties may be
 * omitted in actual stored sparse records.
 */
$field_main = [
	# Primary
	'_id' => 1, // physical MongoDB identity; referenced elsewhere as field_id, auto-incremented from id_counter collection
	'field_name' => 'field_name', // required but in the UI auto-typed based on field_label; stable technical field name in snake_case

	# Basic
	'field_label' => [
		'eng' => [
			'text' => 'Field Name', // required user-facing label
			'method_translation' => 'HUM', // optional; how this text was produced when tracked
			'status_review' => 'APR', // optional; approved review status when review is managed
	],
	], // required but empty by default
	'field_description' => [
		'eng' => [
			'text' => 'Stores the stable technical name of a reusable Field Catalog entry. Used by forms, database collections, import/export, validation, generation, and structure comparison to identify the field meaning without relying on the visible label.',
			'method_translation' => 'HUM', // optional; how this text was produced when tracked
			'status_review' => 'APR', // optional; approved review status when review is managed
		],
	], // suggestively required but not hard required, empty by default
	'type_data' => 'S', // default: S when omitted; general runtime value shape
	'default_value' => null, // empty by default; must match type_data when supplied
	'point_count' => 0, // default: 0; reward points when filled in generated/user projects, not a statistic

	# Form
	'type_field' => 'TXT', // default: TXT; suggested UI control, not the only allowed local control
	'format_text' => 'TXT', // default: TXT; TXT, HTML, MD, BBC, JSON, XML, YAML, CSV
	'placeholder_text' => [
		'eng' => [
			'text' => 'Example: project_name',
			'method_translation' => 'HUM',
			'status_review' => 'APR',
		],
	], // empty by default
	'field_option' => [ // empty by default but is required if type is enumerated; use only for small controlled enumerations
		[
			'option_code' => 'TXT', // stable code within this field if this were an enumerated field
			'option_label' => [
				'eng' => [
					'text' => 'Text Box',
					'method_translation' => 'HUM',
					'status_review' => 'APR',
				],
			],
			'option_description' => [
				'eng' => [
					'text' => 'Displays a single-line text input.',
					'method_translation' => 'HUM',
					'status_review' => 'APR',
				],
			],
			'sort_order' => 10,
			'status_record_lifecycle' => 'ACT', // default: ACT; omit when storing sparse defaults
		],
	],
	'constraint_text_input' => ['ADS', 'NOL'], // empty by default, not required, validation only; accepted text format, not cleanup
	'type_input_cleanup' => ['TRM', 'SNK'], // empty by default, not required, cleanup before save; does not decide whether input is valid
	'is_auto_complete' => false, // default: false; omit when storing sparse defaults
	'is_translatable' => false, // default: false; this field stores technical text, not multilingual content

	# Validation
	'is_mandatory' => true, // default: false; field_name is required for Field Catalog records
	'min_character' => 1, // omit when not applicable, default 1 when applicable
	'max_character' => 100, // omit when not applicable, default 100 when applicable
	'min_number' => null, // omit when not applicable, default 1 when applicable
	'max_number' => null, // omit when not applicable, default 100 when applicable
	'validation_rule_list' => [ // not required, omit if not needed, advanced validation only; do not use for ordinary min/max/required/options
		[
			'validation_rule_key' => 'field_name_unique',
			'type_validation_rule' => 'UNQ', // unique in collection
			'source_field_id' => 1, // field_id for field_name
			'compare_field_id' => null,
			'validation_value' => 'field_main',
			'validation_pattern' => null,
			'validation_message' => [
				'eng' => [
					'text' => 'Field Name must be unique in the Field Catalog.',
					'method_translation' => 'HUM',
					'status_review' => 'APR',
				],
			],
			'is_enabled' => true,
			'sort_order' => 10,
		],
	],

	# Database
	'type_sql' => 'VARCHAR', // optional SQL storage suggestion; not required for every field, default: VARCHAR
	'is_relational' => false, // default: false; true when this field normally stores a reference
	'field_reference' => [ // optional lightweight default reference-picker/display behavior, empty by default
		'reference_database_id' => null, // target database_id when known
		'reference_collection_id' => null, // target collection_id when known
		'reference_field_id' => null, // actual target field_id or collection field usage ID, not a string name
		'display_field_id' => null, // field_id used for picker/display label
		'reference_filter_rule' => [
			[
				'filter_field_id' => null, // actual field_id, not field name string
				'operator_filter' => 'EQ', // default: EQ
				'value' => 'ACT',
			],
		],
	],
	'is_indexed' => true, // default is false, common indexing suggestion only; actual indexes belong to database_collection.index_list[]

	# Record Handling
	'status_record_lifecycle' => 'ACT', // default: ACT; omit when storing sparse defaults
	'visibility_scope' => 'INH', // default: INH; omit when storing sparse defaults
	'level_access' => null, // default is missing/null, missing/null means inherited or unspecified access
	'level_nsfw' => 'N', // default: N; omit when storing sparse defaults
	'is_system' => true, // default: false; system Field Catalog records store true

	# Cached Usage Statistic
	'collection_count' => 0, // default: 0, cached count of database_collection records using this field
	'form_count' => 0, // default: 0, cached count of form_main records using this field

	# Audit
	'field_note' => null, // optional free-text note for internal use, not user-facing
	'created_at' => $created_at, // default: current timestamp
	'updated_at' => $updated_at, // default: empty, shows up only after an update has been done
];

/**
 * Current field_main logical field order.
 *
 * Keep this order when writing documentation, schema previews, exports,
 * generated structure summaries, and UI structure inspectors.
 */
$field_main_field_order = [
	'_id',
	'field_name',
	'field_label',
	'field_description',
	'type_data',
	'default_value',
	'point_count',
	'type_field',
	'format_text',
	'placeholder_text',
	'field_option',
	'constraint_text_input',
	'type_input_cleanup',
	'is_auto_complete',
	'is_translatable',
	'is_mandatory',
	'min_character',
	'max_character',
	'min_number',
	'max_number',
	'validation_rule_list',
	'type_sql',
	'is_relational',
	'field_reference',
	'is_indexed',
	'status_record_lifecycle',
	'visibility_scope',
	'level_access',
	'level_nsfw',
	'is_system',
	'collection_count',
	'form_count',
	'created_at',
	'updated_at',
];

/**
 * Embedded structures owned by field_main.
 *
 * This keeps your original comment-heavy guide, but fixes syntax so it can be
 * linted as PHP. The descriptions in brackets are filled in instead of removed.
 */
$field_main_embedded_structure = [
	'field_option' => [ // Controlled options for small enumerations; stored only when the field has selectable options.
		'option_code' => 'ACT', // required when field_option is present, empty by default
		'option_label' => [ // required when field_option is present, empty by default
			'eng' => [ // default language entry is eng
				'text' => 'Active',
				'method_translation' => 'HUM',
				'status_review' => 'APR',
			],
		],
		'option_description' => [ // required when field_option is present, empty by default
			'eng' => [
				'text' => 'The record is active and selectable.',
				'method_translation' => 'HUM',
				'status_review' => 'APR',
			],
		],
		'sort_order' => 10, // empty by default, increment of 10
		'status_record_lifecycle' => 'ACT', // default: ACT
	],

	'validation_rule_list' => [ // Advanced validation only; do not use for ordinary min/max/required/options.
		'validation_rule_key' => 'example_required_when',
		'type_validation_rule' => 'RW',
		'source_field_id' => 1,
		'compare_field_id' => 2,
		'validation_value' => true,
		'validation_pattern' => null,
		'validation_message' => [
			'eng' => [
				'text' => '[field_label] is required when the condition is met.',
				'method_translation' => 'HUM',
				'status_review' => 'APR',
			],
		],
		'is_enabled' => true, // default: true, when present
		'sort_order' => 10, // empty by default, increment of 10
	],

	'field_reference' => [ // Default lookup/reference picker behavior; not the structural relationship authority.
		'reference_database_id' => 1, // required when field_reference is present, empty by default
		'reference_collection_id' => 10, // required when field_reference is present, empty by default
		'reference_field_id' => 100, // required when field_reference is present, empty by default
		'display_field_id' => 101, // optional field_id used for picker/display label
		'reference_filter_rule' => [
			[
				'filter_field_id' => 102, // actual field_id, not field name string
				'operator_filter' => 'EQ', // default: EQ
				'value' => 'ACT',
			],
		],
	],
];

/**
 * Field-property guide for field_main. Every field and sub-field is listed, but default-valued properties are omitted.
 * These describe the properties used by field_main itself.
 * For readability, label/description/message examples here assume English text directly.
 * In actual field_main records, these translatable properties must follow the multilingual object sample above:
 * - field_label
 * - field_description
 * - placeholder_text
 * - field_option.option_label
 * - field_option.option_description
 * - validation_rule_list.validation_message
 *
 *  Other field_main properties are not translated unless a later accepted schema change adds them.
 */
$field_main_field_property = [
	# Primary
	'_id' => [
		'field_label' => 'Field ID',
		'field_description' => 'Physical MongoDB identity for the Field Catalog record. Other structures reference this value as field_id.',
		'type_data' => 'I',
		'type_sql' => 'INT',
		'is_mandatory' => true,
		'is_system' => true,
		'is_indexed' => true,
	],

	'field_name' => [
		'field_label' => 'Field Name',
		'field_description' => 'Stable technical field name in snake_case. It identifies the reusable field meaning and is not a user-facing label.',
		'is_mandatory' => true,
		'min_character' => 1,
		'max_character' => 50,
		'constraint_text_input' => ['ADS', 'NOL'],
		'type_input_cleanup' => ['TRM', 'SNK'],
		'type_sql' => 'VARCHAR',
		'is_indexed' => true,
	],

	# Basic
	'field_label' => [
		'field_label' => 'Field Label',
		'field_description' => 'User-facing multilingual label for the reusable field.',
		'type_data' => 'O', // maximum of 75 characters when English text is used, but can be longer when other languages are used or when method_translation is not HUM; not required to be unique
		'type_field' => 'JSE',
		'is_translatable' => true,
		'is_mandatory' => true,
	],

	'field_description' => [
		'field_label' => 'Field Description',
		'field_description' => 'Multilingual explanation of actual field use. It should explain purpose, stored value, and effect on validation, forms, generation, import/export, lookup behavior, or database mapping.',
		'type_data' => 'O', // maximum of 255 characters for multilingual text when English text is used, but can be longer when other languages are used or when method_translation is not HUM; not required
		'type_field' => 'JSE',
		'format_text' => 'MD',
		'is_translatable' => true,
	],

	'type_data' => [
		'field_label' => 'Data Type',
		'field_description' => 'Classifies the general runtime value shape of a field.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			[
				'option_code' => 'B',
				'option_label' => 'Boolean',
				'option_description' => 'Boolean are true/false or yes/no values.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'I',
				'option_label' => 'Integer',
				'option_description' => 'Integer values are whole numbers.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'F',
				'option_label' => 'Float',
				'option_description' => 'Float values are decimal numbers.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'S',
				'option_label' => 'String',
				'option_description' => 'String values are sequences of characters.',
				'sort_order' => 40,
			],
			[
				'option_code' => 'A',
				'option_label' => 'Array',
				'option_description' => 'Array values are ordered collections of elements.',
				'sort_order' => 50,
			],
			[
				'option_code' => 'O',
				'option_label' => 'Object',
				'option_description' => 'Object values are instances of classes.',
				'sort_order' => 60,
			],
			[
				'option_code' => 'C',
				'option_label' => 'Callable',
				'option_description' => 'Callable values are functions or methods.',
				'sort_order' => 70,
			],
			[
				'option_code' => 'R',
				'option_label' => 'Resource',
				'option_description' => 'Resource values are references to external resources.',
				'sort_order' => 80,
			],
			[
				'option_code' => 'N',
				'option_label' => 'Null',
				'option_description' => 'Null values represent the absence of a value.',
				'sort_order' => 90,
			],
			[
				'option_code' => 'Y',
				'option_label' => 'Binary',
				'option_description' => 'Binary values are sequences of bytes.',
				'sort_order' => 100,
			],
			[
				'option_code' => 'H',
				'option_label' => 'Hexadecimal',
				'option_description' => 'Hexadecimal values are base-16 numbers.',
				'sort_order' => 110,
			],
		], // actual options copied from type_data.field_option
		'default_value' => 'S', // default: S; omit when storing sparse defaults
	],

	'default_value' => [
		'field_label' => 'Default Value',
		'field_description' => 'Optional default value for generated or managed data. Must match the selected type_data.',
		'type_data' => 'O',
		'type_field' => 'JSE',
	],

	'point_count' => [
		'field_label' => 'Point Count',
		'field_description' => 'Point value awarded when the field is filled in projects with a user point system. It is not a statistics count.',
		'type_data' => 'I',
		'type_field' => 'NMB',
		'min_number' => 0,
		'type_sql' => 'INT',
		'default_value' => 0, // default: 0; omit when storing sparse defaults
	],

	# Form suggestion
	'type_field' => [
		'field_label' => 'Field Type',
		'field_description' => 'Suggested UI control type for forms or generated interfaces.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			[
				'option_code' => 'BTN',
				'option_label' => 'Button',
				'option_description' => 'Clickable element that performs an action when activated.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'BTG',
				'option_label' => 'Button Group',
				'option_description' => 'Group of buttons presented together.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'CLB',
				'option_label' => 'Calendar',
				'option_description' => 'Calendar control for selecting or viewing dates.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'CHK',
				'option_label' => 'Checkbox',
				'option_description' => 'Checkbox control for boolean or option selection.',
				'sort_order' => 40,
			],
			[
				'option_code' => 'CLR',
				'option_label' => 'Color Picker',
				'option_description' => 'Control for selecting a color.',
				'sort_order' => 50,
			],
			[
				'option_code' => 'CMB',
				'option_label' => 'Combo Box',
				'option_description' => 'Drop-down list combined with editable text input.',
				'sort_order' => 60,
			],
			[
				'option_code' => 'DTS',
				'option_label' => 'Date and Time Picker',
				'option_description' => 'Control for selecting date and time together.',
				'sort_order' => 70,
			],
			[
				'option_code' => 'DTI',
				'option_label' => 'Date Input',
				'option_description' => 'Input control for entering dates.',
				'sort_order' => 80,
			],
			[
				'option_code' => 'DTP',
				'option_label' => 'Date Picker',
				'option_description' => 'Control for selecting a date from a calendar.',
				'sort_order' => 90,
			],
			[
				'option_code' => 'DDL',
				'option_label' => 'Drop Down List',
				'option_description' => 'Drop-down list for selecting one option.',
				'sort_order' => 100,
			],
			[
				'option_code' => 'EML',
				'option_label' => 'Email',
				'option_description' => 'Input control for email addresses.',
				'sort_order' => 110,
			],
			[
				'option_code' => 'FLE',
				'option_label' => 'File',
				'option_description' => 'Control for selecting or uploading a file.',
				'sort_order' => 120,
			],
			[
				'option_code' => 'GNT',
				'option_label' => 'Gantt',
				'option_description' => 'Gantt chart control for schedules.',
				'sort_order' => 130,
			],
			[
				'option_code' => 'GRD',
				'option_label' => 'Grid',
				'option_description' => 'Table-like control for rows and columns.',
				'sort_order' => 140,
			],
			[
				'option_code' => 'HDN',
				'option_label' => 'Hidden',
				'option_description' => 'Hidden field not visible to the user.',
				'sort_order' => 150,
			],
			[
				'option_code' => 'IMG',
				'option_label' => 'Image',
				'option_description' => 'Image selection, upload, or display control.',
				'sort_order' => 160,
			],
			[
				'option_code' => 'LBX',
				'option_label' => 'List Box',
				'option_description' => 'List box control for selectable items.',
				'sort_order' => 170,
			],
			[
				'option_code' => 'LBL',
				'option_label' => 'Label',
				'option_description' => 'Display-only label.',
				'sort_order' => 180,
			],
			[
				'option_code' => 'LNK',
				'option_label' => 'Link',
				'option_description' => 'Link control or link display field.',
				'sort_order' => 190,
			],
			[
				'option_code' => 'LVW',
				'option_label' => 'List View',
				'option_description' => 'List-view control for item collections.',
				'sort_order' => 200,
			],
			[
				'option_code' => 'MAP',
				'option_label' => 'Map',
				'option_description' => 'Map or spatial display control.',
				'sort_order' => 210,
			],
			[
				'option_code' => 'NMB',
				'option_label' => 'Numeric Text Box',
				'option_description' => 'Input control for numeric values.',
				'sort_order' => 220,
			],
			[
				'option_code' => 'PBR',
				'option_label' => 'Panel Bar',
				'option_description' => 'Control with collapsible panels.',
				'sort_order' => 230,
			],
			[
				'option_code' => 'PWD',
				'option_label' => 'Password',
				'option_description' => 'Input control for passwords or secret text.',
				'sort_order' => 240,
			],
			[
				'option_code' => 'PVG',
				'option_label' => 'Pivot Grid',
				'option_description' => 'Data-analysis pivot grid control.',
				'sort_order' => 250,
			],
			[
				'option_code' => 'REF',
				'option_label' => 'Reference Picker',
				'option_description' => 'Control for selecting a referenced record.',
				'sort_order' => 260,
			],
			[
				'option_code' => 'RGP',
				'option_label' => 'Radio Group',
				'option_description' => 'Grouped radio-button options.',
				'sort_order' => 270,
			],
			[
				'option_code' => 'RTE',
				'option_label' => 'Rich Text Editor',
				'option_description' => 'Rich text editor control.',
				'sort_order' => 280,
			],
			[
				'option_code' => 'SCH',
				'option_label' => 'Scheduler',
				'option_description' => 'Scheduling or appointment control.',
				'sort_order' => 290,
			],
			[
				'option_code' => 'SLD',
				'option_label' => 'Slider',
				'option_description' => 'Slider control for selecting a value within a range.',
				'sort_order' => 300,
			],
			[
				'option_code' => 'SRT',
				'option_label' => 'Sortable',
				'option_description' => 'Sortable item control.',
				'sort_order' => 310,
			],
			[
				'option_code' => 'SWT',
				'option_label' => 'Switch',
				'option_description' => 'Binary switch control.',
				'sort_order' => 320,
			],
			[
				'option_code' => 'TAG',
				'option_label' => 'Tag Picker',
				'option_description' => 'Control for selecting or entering tags.',
				'sort_order' => 330,
			],
			[
				'option_code' => 'TEL',
				'option_label' => 'Telephone',
				'option_description' => 'Input control for telephone numbers.',
				'sort_order' => 340,
			],
			[
				'option_code' => 'TXT',
				'option_label' => 'Text Box',
				'option_description' => 'Single-line text input.',
				'sort_order' => 350,
			],
			[
				'option_code' => 'TXA',
				'option_label' => 'Text Area',
				'option_description' => 'Multi-line plain text input.',
				'sort_order' => 360,
			],
			[
				'option_code' => 'TMI',
				'option_label' => 'Time Input',
				'option_description' => 'Input control for entering time values.',
				'sort_order' => 370,
			],
			[
				'option_code' => 'TMP',
				'option_label' => 'Time Picker',
				'option_description' => 'Control for selecting a time value.',
				'sort_order' => 380,
			],
			[
				'option_code' => 'TRL',
				'option_label' => 'Tree List',
				'option_description' => 'Hierarchical tree-list control.',
				'sort_order' => 390,
			],
			[
				'option_code' => 'TRV',
				'option_label' => 'Tree View',
				'option_description' => 'Hierarchical tree-view control.',
				'sort_order' => 400,
			],
			[
				'option_code' => 'URL',
				'option_label' => 'URL',
				'option_description' => 'Input control for URL values.',
				'sort_order' => 410,
			],
			[
				'option_code' => 'JSE',
				'option_label' => 'JSON Editor',
				'option_description' => 'Editor for JSON content.',
				'sort_order' => 420,
			],
			[
				'option_code' => 'COD',
				'option_label' => 'Code Editor',
				'option_description' => 'Editor for source code or structured code-like text.',
				'sort_order' => 430,
			],
		], // actual options copied from type_field.field_option
		'default_value' => 'TXT', // default: TXT; omit when storing sparse defaults
	],

	'format_text' => [
		'field_label' => 'Text Format',
		'field_description' => 'Text representation format, such as plain text, HTML, Markdown, BBCode, JSON, XML, YAML, or CSV.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			[
				'option_code' => 'TXT',
				'option_label' => 'Plain Text',
				'option_description' => 'Plain unformatted text are simple sequences of characters without markup.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'HTML',
				'option_label' => 'HTML',
				'option_description' => 'HTML are standard markup tags used in web pages.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'MD',
				'option_label' => 'Markdown',
				'option_description' => 'Markdown are lightweight markup tags used in documentation.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'BBC',
				'option_label' => 'BBCode',
				'option_description' => 'BBCode are simple markup tags used in forums.',
				'sort_order' => 40,
			],
			[
				'option_code' => 'JSON',
				'option_label' => 'JSON',
				'option_description' => 'A JSON format are structured data interchange formats.',
				'sort_order' => 50,
			],
			[
				'option_code' => 'XML',
				'option_label' => 'XML',
				'option_description' => 'XML text format are structured data interchange formats.',
				'sort_order' => 60,
			],
			[
				'option_code' => 'YAML',
				'option_label' => 'YAML',
				'option_description' => 'YAML text format are structured data interchange formats.',
				'sort_order' => 70,
			],
			[
				'option_code' => 'CSV',
				'option_label' => 'CSV',
				'option_description' => 'CSV text format are structured data interchange formats.',
				'sort_order' => 80,
			],
		], // actual options copied from format_text.field_option
		'default_value' => 'TXT', // default: TXT; omit when storing sparse defaults
	],

	'placeholder_text' => [
		'field_label' => 'Placeholder Text',
		'field_description' => 'Optional multilingual placeholder or input hint.',
		'type_data' => 'O', // maximum of 100 characters for multilingual text when English text is used, but can be longer when other languages are used or when method_translation is not HUM; not required
		'type_field' => 'JSE',
		'is_translatable' => true,
	],

	'field_option' => [
		'field_label' => 'Field Option',
		'field_description' => 'Embedded controlled options for dropdowns, radio groups, checkbox groups, or enum-like fields.',
		'type_data' => 'A',
		'type_field' => 'GRD',
	],

	'constraint_text_input' => [
		'field_label' => 'Text Constraint',
		'field_description' => 'Array of validation constraints that determine whether a text value is acceptable.',
		'type_data' => 'A',
		'type_field' => 'CHK',
		'type_sql' => 'SET',
		'field_option' => [
			[
				'option_code' => 'ALP',
				'option_label' => 'Alphabetic Only',
				'option_description' => 'Allows alphabetic letters only.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'ALU',
				'option_label' => 'Uppercase Letters Only',
				'option_description' => 'Allows uppercase letters only.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'ALL',
				'option_label' => 'Lowercase Letters Only',
				'option_description' => 'Allows lowercase letters only.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'ANO',
				'option_label' => 'Alphanumeric Only',
				'option_description' => 'Allows letters and numbers only.',
				'sort_order' => 40,
			],
			[
				'option_code' => 'ADO',
				'option_label' => 'Alphanumeric and Dash Only',
				'option_description' => 'Allows letters, numbers, and dashes only.',
				'sort_order' => 50,
			],
			[
				'option_code' => 'AUO',
				'option_label' => 'Alphanumeric and Underscore Only',
				'option_description' => 'Allows letters, numbers, and underscores only.',
				'sort_order' => 60,
			],
			[
				'option_code' => 'ADS',
				'option_label' => 'Alphanumeric, Dash, and Underscore Only',
				'option_description' => 'Allows letters, numbers, dashes, and underscores only.',
				'sort_order' => 70,
			],
			[
				'option_code' => 'NUM',
				'option_label' => 'Numeric Characters Only',
				'option_description' => 'Allows numeric characters only.',
				'sort_order' => 80,
			],
			[
				'option_code' => 'DEC',
				'option_label' => 'Decimal Number Only',
				'option_description' => 'Allows a decimal-number text value.',
				'sort_order' => 90,
			],
			[
				'option_code' => 'SLG',
				'option_label' => 'Slug Format',
				'option_description' => 'Requires a URL-safe slug-style value.',
				'sort_order' => 100,
			],
			[
				'option_code' => 'COD',
				'option_label' => 'Code Format',
				'option_description' => 'Requires a compact code-style value.',
				'sort_order' => 110,
			],
			[
				'option_code' => 'SCH',
				'option_label' => 'Special Characters Required',
				'option_description' => 'Requires at least one special character.',
				'sort_order' => 120,
			],
			[
				'option_code' => 'EML',
				'option_label' => 'Email Format',
				'option_description' => 'Requires a valid email-address format.',
				'sort_order' => 130,
			],
			[
				'option_code' => 'URL',
				'option_label' => 'URL Format',
				'option_description' => 'Requires a valid URL format.',
				'sort_order' => 140,
			],
			[
				'option_code' => 'PHN',
				'option_label' => 'Phone Number Format',
				'option_description' => 'Requires a valid phone-number format.',
				'sort_order' => 150,
			],
			[
				'option_code' => 'IP',
				'option_label' => 'IP Address Format',
				'option_description' => 'Requires a valid IP address format.',
				'sort_order' => 160,
			],
			[
				'option_code' => 'IP4',
				'option_label' => 'IPv4 Address Format',
				'option_description' => 'Requires a valid IPv4 address format.',
				'sort_order' => 170,
			],
			[
				'option_code' => 'IP6',
				'option_label' => 'IPv6 Address Format',
				'option_description' => 'Requires a valid IPv6 address format.',
				'sort_order' => 180,
			],
			[
				'option_code' => 'UUID',
				'option_label' => 'UUID Format',
				'option_description' => 'Requires a valid UUID format.',
				'sort_order' => 190,
			],
			[
				'option_code' => 'HEX',
				'option_label' => 'Hexadecimal Characters Only',
				'option_description' => 'Allows hexadecimal characters only.',
				'sort_order' => 200,
			],
			[
				'option_code' => 'BIN',
				'option_label' => 'Binary Characters Only',
				'option_description' => 'Allows binary characters only.',
				'sort_order' => 210,
			],
			[
				'option_code' => 'JSON',
				'option_label' => 'JSON Text',
				'option_description' => 'Requires valid JSON text.',
				'sort_order' => 220,
			],
			[
				'option_code' => 'XML',
				'option_label' => 'XML Text',
				'option_description' => 'Requires valid XML text.',
				'sort_order' => 230,
			],
			[
				'option_code' => 'YAML',
				'option_label' => 'YAML Text',
				'option_description' => 'Requires valid YAML text.',
				'sort_order' => 240,
			],
			[
				'option_code' => 'CSV',
				'option_label' => 'CSV Text',
				'option_description' => 'Requires valid CSV text.',
				'sort_order' => 250,
			],
			[
				'option_code' => 'NOS',
				'option_label' => 'No Spaces',
				'option_description' => 'Rejects values containing spaces.',
				'sort_order' => 260,
			],
			[
				'option_code' => 'NOL',
				'option_label' => 'No Line Breaks',
				'option_description' => 'Rejects values containing line breaks.',
				'sort_order' => 270,
			],
		], // actual options copied from constraint_text_input.field_option
	],

	'type_input_cleanup' => [
		'field_label' => 'Cleanup Type',
		'field_description' => 'Array of normalization or cleanup actions applied before saving input.',
		'type_data' => 'A',
		'type_field' => 'CHK',
		'type_sql' => 'SET',
		'field_option' => [
			[
				'option_code' => 'TRM',
				'option_label' => 'Trim Spaces',
				'option_description' => 'Remove leading and trailing spaces before saving.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'NMS',
				'option_label' => 'Normalize Multiple Spaces',
				'option_description' => 'Collapse repeated internal spaces according to the cleanup implementation.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'UPR',
				'option_label' => 'Convert to Uppercase',
				'option_description' => 'Convert text to uppercase before saving.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'LWR',
				'option_label' => 'Convert to Lowercase',
				'option_description' => 'Convert text to lowercase before saving.',
				'sort_order' => 40,
			],
			[
				'option_code' => 'TIT',
				'option_label' => 'Convert to Title Case',
				'option_description' => 'Convert text to title case only when language-aware title-casing rules are available or the user explicitly confirms the preview.',
				'sort_order' => 50,
			],
			[
				'option_code' => 'SLG',
				'option_label' => 'Generate Slug',
				'option_description' => 'Generate a URL-safe slug value.',
				'sort_order' => 60,
			],
			[
				'option_code' => 'SNK',
				'option_label' => 'Generate Snake Case',
				'option_description' => 'Generate a snake_case value.',
				'sort_order' => 70,
			],
			[
				'option_code' => 'KBB',
				'option_label' => 'Generate Kebab Case',
				'option_description' => 'Generate a kebab-case value.',
				'sort_order' => 80,
			],
			[
				'option_code' => 'VER',
				'option_label' => 'Normalize Version Number',
				'option_description' => 'Normalize version-like input to the approved version-number format.',
				'sort_order' => 90,
			],
			[
				'option_code' => 'EML',
				'option_label' => 'Normalize Email',
				'option_description' => 'Normalize email-address input where safe.',
				'sort_order' => 100,
			],
			[
				'option_code' => 'URL',
				'option_label' => 'Normalize URL',
				'option_description' => 'Normalize URL input where safe.',
				'sort_order' => 110,
			],
			[
				'option_code' => 'PHN',
				'option_label' => 'Normalize Phone Number',
				'option_description' => 'Normalize phone-number input where safe.',
				'sort_order' => 120,
			],
			[
				'option_code' => 'NFC',
				'option_label' => 'Unicode NFC Normalization',
				'option_description' => 'Normalize Unicode text using NFC normalization.',
				'sort_order' => 130,
			],
			[
				'option_code' => 'NFD',
				'option_label' => 'Unicode NFD Normalization',
				'option_description' => 'Normalize Unicode text using NFD normalization.',
				'sort_order' => 140,
			],
		], // actual options copied from type_input_cleanup.field_option
	],

	'is_auto_complete' => [
		'field_label' => 'Autocomplete',
		'field_description' => 'Allows UI to offer autocomplete when suitable.',
		'type_data' => 'B',
		'type_field' => 'SWT',
		'type_sql' => 'BOOLEAN',
		'default_value' => false, // default: false; omit when storing sparse defaults
	],

	'is_translatable' => [
		'field_label' => 'Translatable',
		'field_description' => 'Indicates whether the stored value of this field may be multilingual/translatable.',
		'type_data' => 'B',
		'type_field' => 'SWT',
		'type_sql' => 'BOOLEAN',
		'default_value' => false, // default: false; omit when storing sparse defaults
	],

	# Validation
	'is_mandatory' => [
		'field_label' => 'Mandatory',
		'field_description' => 'Whether this field is normally required.',
		'type_data' => 'B',
		'type_field' => 'SWT',
		'type_sql' => 'BOOLEAN',
		'default_value' => false, // default: false; omit when storing sparse defaults
	],

	'min_character' => [
		'field_label' => 'Minimum Characters',
		'field_description' => 'Minimum character count for text values.',
		'type_data' => 'I',
		'type_field' => 'NMB',
		'min_number' => 0,
		'type_sql' => 'INT',
		'default_value' => 0, // default: 0; omit when storing sparse defaults
	],

	'max_character' => [
		'field_label' => 'Maximum Characters',
		'field_description' => 'Maximum character count for text values.',
		'type_data' => 'I',
		'type_field' => 'NMB',
		'min_number' => 1,
		'type_sql' => 'INT',
	],

	'min_number' => [
		'field_label' => 'Minimum Number',
		'field_description' => 'Minimum numeric value.',
		'type_data' => 'F',
		'type_field' => 'NMB',
		'type_sql' => 'DOUBLE',
		'default_value' => 0, // default: 0; omit when storing sparse defaults
	],

	'max_number' => [
		'field_label' => 'Maximum Number',
		'field_description' => 'Maximum numeric value.',
		'type_data' => 'F',
		'type_field' => 'NMB',
		'type_sql' => 'DOUBLE',
	],

	'validation_rule_list' => [
		'field_label' => 'Validation Rule List',
		'field_description' => 'Advanced validation rules only. Do not use this for ordinary required/min/max/options.',
		'type_data' => 'A',
		'type_field' => 'GRD',
	],

	# Database / storage suggestion
	'type_sql' => [
		'field_label' => 'SQL Type',
		'field_description' => 'Optional SQL storage suggestion for SQL-capable generators.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			[
				'option_code' => 'BIGINT',
				'option_label' => 'BIGINT',
				'option_description' => 'BIGINT SQL type are 64-bit signed integers.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'BINARY',
				'option_label' => 'BINARY',
				'option_description' => 'BINARY SQL type are fixed-length binary values.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'BIT',
				'option_label' => 'BIT',
				'option_description' => 'Bit-field SQL type are bit values.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'BLOB',
				'option_label' => 'BLOB',
				'option_description' => 'BLOB SQL type are binary large objects.',
				'sort_order' => 40,
			],
			[
				'option_code' => 'BOOLEAN',
				'option_label' => 'BOOLEAN',
				'option_description' => 'BOOLEAN SQL types are boolean values.',
				'sort_order' => 50,
			],
			[
				'option_code' => 'CHAR',
				'option_label' => 'CHAR',
				'option_description' => 'CHAR SQL type are fixed-length character values.',
				'sort_order' => 60,
			],
			[
				'option_code' => 'DATE',
				'option_label' => 'DATE',
				'option_description' => 'DATE SQL type are dates.',
				'sort_order' => 70,
			],
			[
				'option_code' => 'DATETIME',
				'option_label' => 'DATETIME',
				'option_description' => 'DATETIME SQL type are date and time values.',
				'sort_order' => 80,
			],
			[
				'option_code' => 'DECIMAL',
				'option_label' => 'DECIMAL',
				'option_description' => 'DECIMAL SQL type are fixed-point decimal values.',
				'sort_order' => 90,
			],
			[
				'option_code' => 'DOUBLE',
				'option_label' => 'DOUBLE',
				'option_description' => 'DOUBLE SQL type are double-precision floating-point values.',
				'sort_order' => 100,
			],
			[
				'option_code' => 'ENUM',
				'option_label' => 'ENUM',
				'option_description' => 'Enumeration SQL type are enumeration values.',
				'sort_order' => 110,
			],
			[
				'option_code' => 'FLOAT',
				'option_label' => 'FLOAT',
				'option_description' => 'Floating-point SQL type are floating-point values.',
				'sort_order' => 120,
			],
			[
				'option_code' => 'GEOMETRY',
				'option_label' => 'GEOMETRY',
				'option_description' => 'This option represents the GEOMETRY SQL type for spatial data.',
				'sort_order' => 130,
			],
			[
				'option_code' => 'GEOMETRYCOLLECTION',
				'option_label' => 'GEOMETRYCOLLECTION',
				'option_description' => 'This option represents the GEOMETRYCOLLECTION SQL type for spatial data.',
				'sort_order' => 140,
			],
			[
				'option_code' => 'INT',
				'option_label' => 'INT',
				'option_description' => 'This option represents the INT SQL type for 32-bit signed integers.',
				'sort_order' => 150,
			],
			[
				'option_code' => 'JSON',
				'option_label' => 'JSON',
				'option_description' => 'This option represents the JSON SQL type for JSON documents.',
				'sort_order' => 160,
			],
			[
				'option_code' => 'LINESTRING',
				'option_label' => 'LINESTRING',
				'option_description' => 'This option represents the LINESTRING SQL type for spatial data.',
				'sort_order' => 170,
			],
			[
				'option_code' => 'LONGBLOB',
				'option_label' => 'LONGBLOB',
				'option_description' => 'This option represents the LONGBLOB SQL type for large binary objects.',
				'sort_order' => 180,
			],
			[
				'option_code' => 'LONGTEXT',
				'option_label' => 'LONGTEXT',
				'option_description' => 'This option represents the LONGTEXT SQL type for long-length text.',
				'sort_order' => 190,
			],
			[
				'option_code' => 'MEDIUMBLOB',
				'option_label' => 'MEDIUMBLOB',
				'option_description' => 'This option represents the MEDIUMBLOB SQL type for medium binary objects.',
				'sort_order' => 200,
			],
			[
				'option_code' => 'MEDIUMINT',
				'option_label' => 'MEDIUMINT',
				'option_description' => 'This option represents the MEDIUMINT SQL type for 24-bit signed integers.',
				'sort_order' => 210,
			],
			[
				'option_code' => 'MEDIUMTEXT',
				'option_label' => 'MEDIUMTEXT',
				'option_description' => 'This option represents the MEDIUMTEXT SQL type for medium-length text.',
				'sort_order' => 220,
			],
			[
				'option_code' => 'MULTILINESTRING',
				'option_label' => 'MULTILINESTRING',
				'option_description' => 'This option represents the MULTILINESTRING SQL type for spatial data.',
				'sort_order' => 230,
			],
			[
				'option_code' => 'MULTIPOINT',
				'option_label' => 'MULTIPOINT',
				'option_description' => 'This option represents the MULTIPOINT SQL type for spatial data.',
				'sort_order' => 240,
			],
			[
				'option_code' => 'MULTIPOLYGON',
				'option_label' => 'MULTIPOLYGON',
				'option_description' => 'This option represents the MULTIPOLYGON SQL type for spatial data.',
				'sort_order' => 250,
			],
			[
				'option_code' => 'NCHAR',
				'option_label' => 'NCHAR',
				'option_description' => 'This option represents the NCHAR SQL type for fixed-length Unicode characters.',
				'sort_order' => 260,
			],
			[
				'option_code' => 'NVARCHAR',
				'option_label' => 'NVARCHAR',
				'option_description' => 'This option represents the NVARCHAR SQL type for variable-length Unicode characters.',
				'sort_order' => 270,
			],
			[
				'option_code' => 'POINT',
				'option_label' => 'POINT',
				'option_description' => 'This option represents the POINT SQL type for spatial data.',
				'sort_order' => 280,
			],
			[
				'option_code' => 'POLYGON',
				'option_label' => 'POLYGON',
				'option_description' => 'This option represents the POLYGON SQL type for spatial data.',
				'sort_order' => 290,
			],
			[
				'option_code' => 'REAL',
				'option_label' => 'REAL',
				'option_description' => 'This option represents the REAL SQL type for single-precision floating-point numbers.',
				'sort_order' => 300,
			],
			[
				'option_code' => 'SERIAL',
				'option_label' => 'SERIAL',
				'option_description' => 'This option represents the SERIAL SQL type for auto-incrementing integers.',
				'sort_order' => 310,
			],
			[
				'option_code' => 'SET',
				'option_label' => 'SET',
				'option_description' => 'This option represents the SET SQL type for multiple allowed values.',
				'sort_order' => 320,
			],
			[
				'option_code' => 'SMALLINT',
				'option_label' => 'SMALLINT',
				'option_description' => 'This option represents the SMALLINT SQL type for 16-bit signed integers.',
				'sort_order' => 330,
			],
			[
				'option_code' => 'TEXT',
				'option_label' => 'TEXT',
				'option_description' => 'This option represents the TEXT SQL type for variable-length text data.',
				'sort_order' => 340,
			],
			[
				'option_code' => 'TIME',
				'option_label' => 'TIME',
				'option_description' => 'This option represents the TIME SQL type for time values.',
				'sort_order' => 350,
			],
			[
				'option_code' => 'TIMESTAMP',
				'option_label' => 'TIMESTAMP',
				'option_description' => 'This option represents the TIMESTAMP SQL type for timestamp values.',
				'sort_order' => 360,
			],
			[
				'option_code' => 'TINYBLOB',
				'option_label' => 'TINYBLOB',
				'option_description' => 'This option represents the TINYBLOB SQL type for small binary objects.',
				'sort_order' => 370,
			],
			[
				'option_code' => 'TINYINT',
				'option_label' => 'TINYINT',
				'option_description' => 'This option represents the TINYINT SQL type for 8-bit signed integers.',
				'sort_order' => 380,
			],
			[
				'option_code' => 'TINYTEXT',
				'option_label' => 'TINYTEXT',
				'option_description' => 'This option represents the TINYTEXT SQL type for small text data.',
				'sort_order' => 390,
			],
			[
				'option_code' => 'UUID',
				'option_label' => 'UUID',
				'option_description' => 'This option represents the UUID SQL type or UUID storage suggestion.',
				'sort_order' => 400,
			],
			[
				'option_code' => 'VARBINARY',
				'option_label' => 'VARBINARY',
				'option_description' => 'This option represents the VARBINARY SQL type for variable-length binary data.',
				'sort_order' => 410,
			],
			[
				'option_code' => 'VARCHAR',
				'option_label' => 'VARCHAR',
				'option_description' => 'This option represents the VARCHAR SQL type for variable-length character data.',
				'sort_order' => 420,
			],
			[
				'option_code' => 'YEAR',
				'option_label' => 'YEAR',
				'option_description' => 'This option represents the YEAR SQL type.',
				'sort_order' => 430,
			],
		], // actual options copied from type_sql.field_option
		'default_value' => 'VARCHAR', // default: VARCHAR; omit when storing sparse defaults
	],

	'is_relational' => [
		'field_label' => 'Relational',
		'field_description' => 'Indicates that this field normally stores or participates in a reference.',
		'type_data' => 'B',
		'type_field' => 'SWT',
		'type_sql' => 'BOOLEAN',
		'default_value' => false, // default: false; omit when storing sparse defaults
	],

	'field_reference' => [
		'field_label' => 'Field Reference',
		'field_description' => 'Optional lightweight default reference-picker/display behavior. It is not a replacement for relationship_main.',
		'type_data' => 'O',
		'type_field' => 'JSE',
	],

	'is_indexed' => [
		'field_label' => 'Indexed',
		'field_description' => 'Whether this field is normally indexed for faster searching or sorting. Common indexing strategies include single-column indexes, composite indexes, and full-text indexes.',
		'type_data' => 'B',
		'type_field' => 'SWT',
		'type_sql' => 'BOOLEAN',
		'default_value' => false, // default: false; omit when storing sparse defaults
	],

	# Record handling
	'status_record_lifecycle' => [
		'field_label' => 'Record Lifecycle Status',
		'field_description' => 'Record Lifecycle Status are common status values that indicate the lifecycle stage of a record. Missing value means inherit or unspecified status.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			[
				'option_code' => 'DRA',
				'option_label' => 'Draft',
				'option_description' => 'Draft are incomplete or in-progress records that are not yet finalized.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'ACT',
				'option_label' => 'Active',
				'option_description' => 'Active records are currently valid and in use.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'INA',
				'option_label' => 'Inactive',
				'option_description' => 'Inactive records are not currently valid or in use.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'ARC',
				'option_label' => 'Archived',
				'option_description' => 'Archived records are stored for historical or compliance purposes.',
				'sort_order' => 40,
			],
			[
				'option_code' => 'DEL',
				'option_label' => 'Deleted',
				'option_description' => 'Deleted records are marked for removal.',
				'sort_order' => 50,
			],
			[
				'option_code' => 'MRG',
				'option_label' => 'Merged',
				'option_description' => 'Merged records are those that have been combined with another record.',
				'sort_order' => 60,
			],
			[
				'option_code' => 'DUP',
				'option_label' => 'Duplicate',
				'option_description' => 'Duplicate records are those that are identical or very similar to another record.',
				'sort_order' => 70,
			],
			[
				'option_code' => 'ERR',
				'option_label' => 'Error',
				'option_description' => 'Error records are those that have encountered an issue or are in an error state.',
				'sort_order' => 80,
			],
			[
				'option_code' => 'UNK',
				'option_label' => 'Unknown',
				'option_description' => 'Unknown records are those for which the lifecycle status is not specified or is unclear.',
				'sort_order' => 90,
			],
		], // actual options copied from status_record_lifecycle.field_option
		'default_value' => 'ACT', // default: ACT; omit when storing sparse defaults
	],

	'visibility_scope' => [
		'field_label' => 'Visibility Scope',
		'field_description' => 'Visibility Scope are common visibility values that indicate the intended audience or access level for a record. Missing value means inherit or unspecified visibility.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			[
				'option_code' => 'INH',
				'option_label' => 'Inherit',
				'option_description' => 'Inherit visibility from parent records.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'PUB',
				'option_label' => 'Public',
				'option_description' => 'Public records are visible to everyone, including unauthenticated users.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'UNL',
				'option_label' => 'Unlisted',
				'option_description' => 'Unlisted records are not publicly listed but may be accessible through direct links.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'PRV',
				'option_label' => 'Private',
				'option_description' => 'Private records are only visible to the owner or authorized users.',
				'sort_order' => 40,
			],
			[
				'option_code' => 'FRD',
				'option_label' => 'Friends',
				'option_description' => 'Friends records are visible to the owner and their friends.',
				'sort_order' => 50,
			],
			[
				'option_code' => 'GRP',
				'option_label' => 'Group',
				'option_description' => 'Group records are visible to members of the specified group.',
				'sort_order' => 60,
			],
			[
				'option_code' => 'ROL',
				'option_label' => 'Role-Based',
				'option_description' => 'Role-Based records are visible to users with the specified role.',
				'sort_order' => 70,
			],
			[
				'option_code' => 'CUS',
				'option_label' => 'Custom',
				'option_description' => 'Custom visibility settings. Select the users or groups that can view this record.',
				'sort_order' => 80,
			],
		], // actual options copied from visibility_scope.field_option
		'default_value' => 'INH', // default: INH; omit when storing sparse defaults
	],

	'level_access' => [
		'field_label' => 'Access Level',
		'field_description' => 'Access classification or override means that the record is restricted to a certain level of access. Missing value means inherit or unspecified access level.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			[
				'option_code' => 'PUB',
				'option_label' => 'Public',
				'option_description' => 'Public records are visible to everyone, including unauthenticated users.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'USR',
				'option_label' => 'User',
				'option_description' => 'User records are visible to the owner and authorized users.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'EDT',
				'option_label' => 'Editor',
				'option_description' => 'Editor records are visible to the owner and authorized editors.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'MOD',
				'option_label' => 'Moderator',
				'option_description' => 'Moderator records are visible to the owner and authorized moderators.',
				'sort_order' => 40,
			],
			[
				'option_code' => 'ADM',
				'option_label' => 'Administrator',
				'option_description' => 'Administrator records are visible to the owner and authorized administrators.',
				'sort_order' => 50,
			],
			[
				'option_code' => 'OWN',
				'option_label' => 'Owner',
				'option_description' => 'Owner records are visible to the owner only.',
				'sort_order' => 60,
			],
			[
				'option_code' => 'CUS',
				'option_label' => 'Custom',
				'option_description' => 'Custom access settings. Select the users or groups that can view this record.',
				'sort_order' => 70,
			],
		], // actual options copied from level_access.field_option
	],

	'level_nsfw' => [
		'field_label' => 'NSFW Level',
		'field_description' => 'NSFW means Not Safe For Work. It is a content classification that indicates the level of explicit or adult content in a record. Missing value means None.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			[
				'option_code' => 'N',
				'option_label' => 'None',
				'option_description' => 'None means that the record is safe for work and does not contain any explicit or adult content.',
				'sort_order' => 10,
			],
			[
				'option_code' => 'M',
				'option_label' => 'Mild',
				'option_description' => 'Mild means that the record contains some explicit or adult content but is not highly offensive.',
				'sort_order' => 20,
			],
			[
				'option_code' => 'S',
				'option_label' => 'Sensitive',
				'option_description' => 'Sensitive means that the record contains explicit or adult content and should be handled with care.',
				'sort_order' => 30,
			],
			[
				'option_code' => 'E',
				'option_label' => 'Explicit',
				'option_description' => 'Explicit means that the record contains explicit or adult content.',
				'sort_order' => 40,
			],
		], // actual options copied from level_nsfw.field_option
		'default_value' => 'N', // default: N; omit when storing sparse defaults
	],

	'is_system' => [
		'field_label' => 'System',
		'field_description' => 'Marks a Field Catalog record as system-owned.',
		'type_data' => 'B',
		'type_field' => 'SWT',
		'type_sql' => 'BOOLEAN',
	],

	# Cached usage counts
	'collection_count' => [
		'field_label' => 'Collection Count',
		'field_description' => 'Cached count of database_collection records using this field.',
		'type_data' => 'I',
		'type_field' => 'NMB',
		'min_number' => 0,
		'type_sql' => 'INT',
	],

	'form_count' => [
		'field_label' => 'Form Count',
		'field_description' => 'Cached count of project_form records using this field.',
		'type_data' => 'I',
		'type_field' => 'NMB',
		'min_number' => 0,
		'type_sql' => 'INT',
	],

	# Audit
	'created_at' => [
		'field_label' => 'Created At',
		'field_description' => 'Exact timestamp when the Field Catalog record was created. Default is the current timestamp when the record is first inserted.',
		'type_field' => 'DTS',
		'type_sql' => 'TIMESTAMP',
		'is_mandatory' => true,
	],

	'updated_at' => [
		'field_label' => 'Updated At',
		'field_description' => 'Exact timestamp when the Field Catalog record was last updated. Default is the current timestamp when the record is first updated.',
		'type_field' => 'DTS',
		'type_sql' => 'TIMESTAMP',
	],

];

/**
 * Sub-field property guide for embedded structures.
 *
 * These are still properties of field_main-owned embedded structures, not new
 * top-level collections.
 */
$field_main_subfield_property = [
	'multilingual_text' => [
		'text' => [
			'field_label' => 'Text',
			'field_description' => 'Language-specific text value.',
			'is_mandatory' => true,
			'min_character' => 1,
			'type_field' => 'TXA',
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
					'option_description' => 'Human translations are produced by professional translators or native speakers. This is the default translation method and is omitted when the',
					'sort_order' => 10,
				],
				[
					'option_code' => 'AI',
					'option_label' => 'AI Translation',
					'option_description' => 'AI translations are produced by artificial intelligence or machine learning algorithms. This method is often faster and cheaper than human translation, but may be less accurate or natural.',
					'sort_order' => 20,
				],
				[
					'option_code' => 'MT',
					'option_label' => 'Machine Translation',
					'option_description' => 'Machine translations are produced by automated translation systems. This method is often faster and more scalable than human translation, but may be less accurate or natural.',
					'sort_order' => 30,
				],
				[
					'option_code' => 'IMP',
					'option_label' => 'Imported Translation',
					'option_description' => 'Imported translations are pre-existing translations that have been imported into the system. This method is often used when migrating content from another system.',
					'sort_order' => 40,
				],
				[
					'option_code' => 'MIG',
					'option_label' => 'Migrated Translation',
					'option_description' => 'Migrated translations are translations that have been migrated from a previous system. This method is often used when consolidating content from multiple systems.',
					'sort_order' => 50,
				],
				[
					'option_code' => 'UNK',
					'option_label' => 'Unknown',
					'option_description' => 'The method of translation is unknown or unspecified. This option is used when the source of the translation cannot be determined.',
					'sort_order' => 60,
				],
			],
			'default_value' => 'HUM', // default: HUM; omit when storing sparse defaults
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
					'option_description' => 'If the review status is Not Reviewed, it means that the text has not been evaluated or assessed for quality, accuracy, or appropriateness. This status indicates that the text is pending review and may require further attention or verification.',
					'sort_order' => 10,
				],
				[
					'option_code' => 'PND',
					'option_label' => 'Pending',
					'option_description' => 'If the review status is Pending, it means that the text is currently being reviewed or is awaiting review. This status indicates that the text is in the process of being evaluated.',
					'sort_order' => 20,
				],
				[
					'option_code' => 'APR',
					'option_label' => 'Approved',
					'option_description' => 'If the review status is Approved, it means that the text has been evaluated and found to be of acceptable quality, accuracy, and appropriateness. This status indicates that the text is ready for use.',
					'sort_order' => 30,
				],
				[
					'option_code' => 'REJ',
					'option_label' => 'Rejected',
					'option_description' => 'If the review status is Rejected, it means that the text has been evaluated and found to be unacceptable in terms of quality, accuracy, or appropriateness. This status indicates that the text needs to be revised or replaced.',
					'sort_order' => 40,
				],
				[
					'option_code' => 'DEF',
					'option_label' => 'Deferred',
					'option_description' => 'If the review status is Deferred, it means that the text is temporarily put on hold for review. This status indicates that the text is pending further action or consideration.',
					'sort_order' => 50,
				],
				[
					'option_code' => 'SUP',
					'option_label' => 'Superseded',
					'option_description' => 'If the review status is Superseded, it means that the text has been replaced by a newer version. This status indicates that the text is no longer in use.',
					'sort_order' => 60,
				],
				[
					'option_code' => 'REV',
					'option_label' => 'Needs Revision',
					'option_description' => 'If the review status is Needs Revision, it means that the text requires modifications or updates. This status indicates that the text is pending revision and may need to be reviewed again once the changes are made.',
					'sort_order' => 70,
				],
				[
					'option_code' => 'ESC',
					'option_label' => 'Escalated',
					'option_description' => 'If the review status is Escalated, it means that the text has been escalated to a higher level of review or management. This status indicates that the text is receiving additional attention or oversight.',
					'sort_order' => 80,
				],
			], // actual options copied from status_review.field_option
			'default_value' => 'APR', // default: APR; omit when storing sparse defaults
		],
	],

	'field_option[]' => [
		'option_code' => [
			'field_label' => 'Option Code',
			'field_description' => 'Stable option code within the owning Field Catalog record.',
			'is_mandatory' => true,
			'min_character' => 1,
			'max_character' => 10,
			'constraint_text_input' => ['ADS', 'NOL'],
			'type_input_cleanup' => ['TRM', 'UPR'],
			'type_sql' => 'VARCHAR',
		],
		'option_label' => [
			'field_label' => 'Option Label',
			'field_description' => 'Multilingual user-facing label for the option.',
			'type_data' => 'O',
			'type_field' => 'JSE',
			'is_translatable' => true,
			'is_mandatory' => true,
		],
		'option_description' => [
			'field_label' => 'Option Description',
			'field_description' => 'Multilingual description or help text for the option.',
			'type_data' => 'O',
			'type_field' => 'JSE',
			'format_text' => 'MD',
			'is_translatable' => true,
		],
		'sort_order' => [
			'field_label' => 'Sort Order',
			'field_description' => 'Display order of the option within the owning Field.',
			'type_data' => 'I',
			'type_field' => 'NMB',
			'type_sql' => 'INT',
		],
		'status_record_lifecycle' => [
			'field_label' => 'Record Lifecycle Status',
			'field_description' => 'Record Lifecycle Status are common status values that indicate the lifecycle stage of a record. Missing value means inherit or unspecified status.',
			'type_field' => 'DDL',
			'type_sql' => 'ENUM',
			'field_option' => [
				[
					'option_code' => 'DRA',
					'option_label' => 'Draft',
					'option_description' => 'Draft are incomplete or in-progress records that are not yet finalized.',
					'sort_order' => 10,
				],
				[
					'option_code' => 'ACT',
					'option_label' => 'Active',
					'option_description' => 'Active are records that are currently in use and valid.',
					'sort_order' => 20,
				],
				[
					'option_code' => 'INA',
					'option_label' => 'Inactive',
					'option_description' => 'Inactive are records that are not currently in use.',
					'sort_order' => 30,
				],
				[
					'option_code' => 'ARC',
					'option_label' => 'Archived',
					'option_description' => 'Archived are records that have been moved to a separate storage area for long-term retention.',
					'sort_order' => 40,
				],
				[
					'option_code' => 'DEL',
					'option_label' => 'Deleted',
					'option_description' => 'Deleted are records that have been marked for deletion.',
					'sort_order' => 50,
				],
				[
					'option_code' => 'MRG',
					'option_label' => 'Merged',
					'option_description' => 'Merged are records that have been combined with another record.',
					'sort_order' => 60,
				],
				[
					'option_code' => 'DUP',
					'option_label' => 'Duplicate',
					'option_description' => 'Duplicate are records that are identical to another record.',
					'sort_order' => 70,
				],
				[
					'option_code' => 'ERR',
					'option_label' => 'Error',
					'option_description' => 'Error are records that have validation errors or other issues.',
					'sort_order' => 80,
				],
				[
					'option_code' => 'UNK',
					'option_label' => 'Unknown',
					'option_description' => 'Unknown are records for which the lifecycle status is not specified.',
					'sort_order' => 90,
				],
			], // actual options copied from status_record_lifecycle.field_option
			'default_value' => 'ACT', // default: ACT; omit when storing sparse defaults
		],
	],

	'validation_rule_list[]' => [
		'validation_rule_key' => [
			'field_label' => 'Validation Rule Key',
			'field_description' => 'Stable key for the validation rule inside this Field Catalog record.',
			'is_mandatory' => true,
			'constraint_text_input' => ['ADS', 'NOL'],
			'type_input_cleanup' => ['TRM', 'SNK'],
			'type_sql' => 'VARCHAR',
		],
		'type_validation_rule' => [
			'field_label' => 'Validation Rule Type',
			'field_description' => 'Type of validation rule applied to the field value. This determines how the field value is validated against other values or patterns.',
			'type_field' => 'DDL',
			'type_sql' => 'ENUM',
			'is_mandatory' => true,
			'field_option' => [
				[
					'option_code' => 'AF',
					'option_label' => 'After Field',
					'option_description' => 'Requires this value to be after another field value.',
					'sort_order' => 10,
				],
				[
					'option_code' => 'BF',
					'option_label' => 'Before Field',
					'option_description' => 'Requires this value to be before another field value.',
					'sort_order' => 20,
				],
				[
					'option_code' => 'EQ',
					'option_label' => 'Equal to Field',
					'option_description' => 'Requires this value to equal another field value.',
					'sort_order' => 30,
				],
				[
					'option_code' => 'NE',
					'option_label' => 'Not Equal to Field',
					'option_description' => 'Requires this value to differ from another field value.',
					'sort_order' => 40,
				],
				[
					'option_code' => 'GT',
					'option_label' => 'Greater Than Field',
					'option_description' => 'Requires this value to be greater than another field value.',
					'sort_order' => 50,
				],
				[
					'option_code' => 'LT',
					'option_label' => 'Less Than Field',
					'option_description' => 'Requires this value to be less than another field value.',
					'sort_order' => 60,
				],
				[
					'option_code' => 'GTE',
					'option_label' => 'Greater Than or Equal to Field',
					'option_description' => 'Requires this value to be greater than or equal to another field value.',
					'sort_order' => 70,
				],
				[
					'option_code' => 'LTE',
					'option_label' => 'Less Than or Equal to Field',
					'option_description' => 'Requires this value to be less than or equal to another field value.',
					'sort_order' => 80,
				],
				[
					'option_code' => 'RW',
					'option_label' => 'Required When',
					'option_description' => 'Requires this field only when a condition is met.',
					'sort_order' => 90,
				],
				[
					'option_code' => 'RX',
					'option_label' => 'Regular Expression',
					'option_description' => 'Validates this value using a regular expression.',
					'sort_order' => 100,
				],
				[
					'option_code' => 'UNQ',
					'option_label' => 'Unique in Collection',
					'option_description' => 'Requires this value to be unique within a collection or configured scope.',
					'sort_order' => 110,
				],
				[
					'option_code' => 'EXS',
					'option_label' => 'Exists in Collection',
					'option_description' => 'Requires this value to exist in another collection or lookup source.',
					'sort_order' => 120,
				],
				[
					'option_code' => 'NEX',
					'option_label' => 'Must Not Exist in Collection',
					'option_description' => 'Requires this value not to exist in another collection or lookup source.',
					'sort_order' => 130,
				],
				[
					'option_code' => 'API',
					'option_label' => 'API Check',
					'option_description' => 'Validates this value through an API or external check.',
					'sort_order' => 140,
				],
				[
					'option_code' => 'CUS',
					'option_label' => 'Custom Rule',
					'option_description' => 'Uses a custom validation rule defined by the project or generator profile.',
					'sort_order' => 150,
				],
			], // actual options copied from type_validation_rule.field_option
		],
		'source_field_id' => [
			'field_label' => 'Source Field ID',
			'field_description' => 'Field ID used as the source of the validation rule.',
			'type_data' => 'I',
			'type_field' => 'REF',
			'type_sql' => 'INT',
		],
		'compare_field_id' => [
			'field_label' => 'Compare Field ID',
			'field_description' => 'Optional Field ID compared against the source field.',
			'type_data' => 'I',
			'type_field' => 'REF',
			'type_sql' => 'INT',
		],
		'validation_value' => [
			'field_label' => 'Validation Value',
			'field_description' => 'Flexible value used by the validation rule.',
			'type_data' => 'O',
			'type_field' => 'JSE',
		],
		'validation_pattern' => [
			'field_label' => 'Validation Pattern',
			'field_description' => 'Pattern used by regular-expression or custom validation rules.',
			'type_field' => 'COD',
			'format_text' => 'TXT',
		],
		'validation_message' => [
			'field_label' => 'Validation Message',
			'field_description' => 'Multilingual validation message shown when the rule fails.',
			'type_data' => 'O',
			'type_field' => 'JSE',
			'is_translatable' => true,
		],
		'is_enabled' => [
			'field_label' => 'Enabled',
			'field_description' => 'Whether the validation rule is active.',
			'type_data' => 'B',
			'type_field' => 'SWT',
			'type_sql' => 'BOOLEAN',
		],
		'sort_order' => [
			'field_label' => 'Sort Order',
			'field_description' => 'Execution or display order of the validation rule.',
			'type_data' => 'I',
			'type_field' => 'NMB',
			'type_sql' => 'INT',
		],
	],

	'field_reference' => [
		'reference_database_id' => [
			'field_label' => 'Reference Database',
			'field_description' => 'Target database_id for the reference picker when known.',
			'type_data' => 'I',
			'type_field' => 'REF',
			'type_sql' => 'INT',
		],
		'reference_collection_id' => [
			'field_label' => 'Reference Collection',
			'field_description' => 'Target collection_id for the reference picker when known.',
			'type_data' => 'I',
			'type_field' => 'REF',
			'type_sql' => 'INT',
		],
		'reference_field_id' => [
			'field_label' => 'Reference Field',
			'field_description' => 'Target identity field_id or collection field usage ID. This must be an actual ID, not a string field name.',
			'type_data' => 'I',
			'type_field' => 'REF',
			'type_sql' => 'INT',
		],
		'display_field_id' => [
			'field_label' => 'Display Field',
			'field_description' => 'Field ID used for picker/display labels.',
			'type_data' => 'I',
			'type_field' => 'REF',
			'type_sql' => 'INT',
		],
		'reference_filter_rule' => [
			'field_label' => 'Reference Filter Rule',
			'field_description' => 'Optional filters applied to the reference picker.',
			'type_data' => 'A',
			'type_field' => 'GRD',
		],
	],

	'field_reference.reference_filter_rule[]' => [
		'filter_field_id' => [
			'field_label' => 'Filter Field',
			'field_description' => 'Field ID used by the reference filter. This must be an actual ID, not a string field name.',
			'type_data' => 'I',
			'type_field' => 'REF',
			'type_sql' => 'INT',
			'is_mandatory' => true,
		],
		'operator_filter' => [
			'field_label' => 'Filter Operator',
			'field_description' => 'Operator used by the reference filter.',
			'type_field' => 'DDL',
			'type_sql' => 'ENUM',
			'is_mandatory' => true,
			'field_option' => [
				[
					'option_code' => 'EQ',
					'option_label' => 'Equals',
					'option_description' => 'Matches values equal to the filter value.',
					'sort_order' => 10,
				],
				[
					'option_code' => 'NE',
					'option_label' => 'Not Equals',
					'option_description' => 'Matches values not equal to the filter value.',
					'sort_order' => 20,
				],
				[
					'option_code' => 'GT',
					'option_label' => 'Greater Than',
					'option_description' => 'Matches values greater than the filter value.',
					'sort_order' => 30,
				],
				[
					'option_code' => 'GTE',
					'option_label' => 'Greater Than or Equal',
					'option_description' => 'Matches values greater than or equal to the filter value.',
					'sort_order' => 40,
				],
				[
					'option_code' => 'LT',
					'option_label' => 'Less Than',
					'option_description' => 'Matches values less than the filter value.',
					'sort_order' => 50,
				],
				[
					'option_code' => 'LTE',
					'option_label' => 'Less Than or Equal',
					'option_description' => 'Matches values less than or equal to the filter value.',
					'sort_order' => 60,
				],
				[
					'option_code' => 'CON',
					'option_label' => 'Contains',
					'option_description' => 'Matches values containing the filter value.',
					'sort_order' => 70,
				],
				[
					'option_code' => 'NCON',
					'option_label' => 'Does Not Contain',
					'option_description' => 'Matches values not containing the filter value.',
					'sort_order' => 80,
				],
				[
					'option_code' => 'IN',
					'option_label' => 'In List',
					'option_description' => 'Matches values contained in a list.',
					'sort_order' => 90,
				],
				[
					'option_code' => 'NIN',
					'option_label' => 'Not In List',
					'option_description' => 'Matches values not contained in a list.',
					'sort_order' => 100,
				],
				[
					'option_code' => 'EMP',
					'option_label' => 'Is Empty',
					'option_description' => 'Matches empty or missing values.',
					'sort_order' => 110,
				],
				[
					'option_code' => 'NEMP',
					'option_label' => 'Is Not Empty',
					'option_description' => 'Matches non-empty values.',
					'sort_order' => 120,
				],
				[
					'option_code' => 'BTW',
					'option_label' => 'Between',
					'option_description' => 'Matches values between two boundary values.',
					'sort_order' => 130,
				],
				[
					'option_code' => 'STW',
					'option_label' => 'Starts With',
					'option_description' => 'Matches text values that start with the filter value.',
					'sort_order' => 140,
				],
				[
					'option_code' => 'ENW',
					'option_label' => 'Ends With',
					'option_description' => 'Matches text values that end with the filter value.',
					'sort_order' => 150,
				],
				[
					'option_code' => 'RX',
					'option_label' => 'Regular Expression',
					'option_description' => 'Matches values using a regular expression.',
					'sort_order' => 160,
				],
			], // actual options copied from operator_filter.field_option
		],
		'value' => [
			'field_label' => 'Value',
			'field_description' => 'Flexible filter value. May be a string, number, boolean, array, date, or option code.',
			'type_data' => 'O',
			'type_field' => 'JSE',
		],
	],

];


/**
 * Legacy-to-current name guide.
 *
 * These are not fields to keep. They are here so old admin_field concepts can
 * be visually matched to the current Zenith Field Catalog structure.
 */
$field_main_legacy_name_map = [
	'field_title' => 'field_label',
	'status_tree' => 'status_record_lifecycle',
	'status_visibility' => 'visibility_scope',
	'status_nsfw' => 'level_nsfw',
	'is_system_cms' => 'is_system',
	'user_position' => 'level_access',
	'type_validation' => 'validation_rule_list or constraint_text_input, depending on meaning',
	'method_validation' => 'constraint_text_input',
	'field_value' => 'field_reference.display_field_id / reference picker behavior',
	'field_key' => 'field_reference.reference_field_id / target identity behavior',
	'mode_text' => 'format_text',
	'validation_list' => 'validation_rule_list.validation_message',
	'status_translation' => 'status_review',
	'create_log.create_datetime' => 'created_at',
	'update_log.update_datetime' => 'updated_at',
];

/**
 * Boundaries.
 *
 * What belongs here:
 * - canonical reusable field meaning;
 * - reusable labels and descriptions;
 * - general data type;
 * - suggested form control;
 * - default validation and cleanup;
 * - small controlled option lists;
 * - SQL/storage suggestion;
 * - default reference-picker metadata;
 * - usage counts.
 *
 * What does not belong here:
 * - Project-specific form layout;
 * - collection-specific storage path;
 * - collection-specific nested field placement;
 * - actual collection index definitions;
 * - relationship dependency authority;
 * - generated-project runtime data.
 */
$field_main_boundary = [
	'belongs_in_field_main' => [
		'canonical field meaning',
		'field_label',
		'field_description',
		'type_data',
		'type_field',
		'format_text',
		'field_option',
		'constraint_text_input',
		'type_input_cleanup',
		'validation_rule_list',
		'type_sql',
		'field_reference default',
		'collection_count',
		'form_count',
	],
	'belongs_in_form_main_field_usage' => [
		'form-specific label override',
		'form-specific placeholder',
		'form layout',
		'form grouping',
		'form read-only behavior',
		'form visibility condition',
	],
	'belongs_in_database_collection_field_list' => [
		'storage field name',
		'field path',
		'nested parent field',
		'collection-required override',
		'collection-specific stricter validation',
		'collection-specific default value',
	],
	'belongs_in_database_collection_index_list' => [
		'actual collection index definitions',
		'compound index field order',
		'unique index',
		'sparse index',
		'TTL index',
		'partial filter expression',
	],
	'belongs_in_relationship_main' => [
		'managed structural relationship',
		'source collection and source field',
		'target collection and target field',
		'cardinality',
		'dependency and impact review',
	],
];

return [
	'field_property_default' => $field_property_default,
	'multilingual_text_sample' => $multilingual_text_sample,
	'field_main' => $field_main,
	'field_main_field_order' => $field_main_field_order,
	'field_main_embedded_structure' => $field_main_embedded_structure,
	'field_main_field_property' => $field_main_field_property,
	'field_main_subfield_property' => $field_main_subfield_property,
	'field_main_legacy_name_map' => $field_main_legacy_name_map,
	'field_main_boundary' => $field_main_boundary,
];
