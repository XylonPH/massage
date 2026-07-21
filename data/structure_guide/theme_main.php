<?php
/**
 * Title: Zenith Theme Structure Guide
 * Version: 1.23
 * Collection: theme_main
 * Description: Stores one reusable visual-theme definition and its configurable design values.
 * Purpose: Documents the theme_main record shape for review, validation, comparison, and implementation without acting as runtime configuration.
 *
 * Notes:
 * This file is a PHP-readable visual guide for the current theme_main structure.
 * It is not a seed file, not a runtime migration script, and not a replacement
 * for the canonical Zenith documents. It exists so reusable visual theme structure
 * can be reviewed in a familiar PHP structure format.
 *
 * Important:
 * - theme_main is a top-level reusable theme catalog.
 * - Projects select a theme through project_main.theme_id or an equivalent project theme reference.
 * - theme_main owns visual style defaults, not generated code templates.
 * - theme_main does not own project-specific content, project workflow, or form sectioning.
 * - theme_main values should be ecosystem-aware, but not hard-code a fixed desktop/mobile/web layout.
 * - Colors are stored as HEX strings without the leading #. Renderers add # when required.
 * - theme_main stores no binary images, videos, fonts, or icons. It stores styling rules and references only.
 *
 * Sparse-default rule:
 * Individual property definitions below omit properties when the value is the default.
 * Example: a simple theme only needs the config sections it actually customizes.
 */

# Variable
$created_at = '2026-06-24T00:00:00Z';
$updated_at = '2026-07-21T10:48:10Z';
/**
 * Default theme_main property values.
 * These defaults are interpreted by the application and should not be repeated
 * inside each stored theme_main record unless intentionally different.
 */
$theme_main_default = [
	'status_record_lifecycle' => 'ACT', // default: Active; UI label: Theme Status
	'mode_appearance' => 'LGT', // default: Light appearance mode
	'supported_ecosystem_list' => [], // default: renderer decides or theme is treated as generally reusable
	'is_responsive' => true, // theme is intended to adapt to viewport/orientation differences // default: theme can adapt across supported screen sizes/orientations
	'is_locked' => false, // locked themes are protected from normal editing // default: editable theme definition
	'is_animated' => false, // default: no motion/animation unless enabled
];

/**
 * Multilingual short-text structure sample.
 *
 * theme_label and theme_description are multilingual when displayed to users.
 * theme_note is an internal plain-text note and is not multilingual.
 * method_translation defaults to HUM and should be omitted when Human Translation.
 * status_review defaults to APR and should be omitted when Approved.
 */
$multilingual_text_sample = [
	'eng' => [
		'text' => 'Default Light',
		'method_translation' => 'HUM', // optional; default: HUM; omit when Human Translation
		'status_review' => 'APR', // optional; default: APR; omit when Approved
	],
];

/**
 * theme_main sample record.
 *
 * This example describes a clean light application theme.
 * Color values are stored as HEX without #.
 */
$theme_main = [
	# Primary
	'_id' => 1, // physical MongoDB identity; referenced elsewhere as theme_id
	'theme_key' => 'default_light', // required stable theme key in snake_case

	# Basic
	'theme_label' => [ // User-facing name of the theme.
		'eng' => [
			'text' => 'Default Light', // maximum 75 characters per language value
		],
	],
	'theme_description' => [ // Short explanation of the theme.
		'eng' => [
			'text' => 'A clean light theme for generated application interfaces.', // maximum 255 characters per language value
		],
	],
	'type_theme' => 'APP', // Application Theme; describes the theme purpose/family, not the target platform alone
	'mode_appearance' => 'LGT', // Light; appearance behavior such as light, dark, or system-adaptive
	'supported_ecosystem_list' => ['DSK', 'WEB'], // values from type_ecosystem; limits where this theme is intended to be used
	'status_record_lifecycle' => 'ACT', // default: ACT; UI label: Theme Status

	# Color
	'color_config' => [ // Color palette values. Hex strings without #.
		'primary_color' => '2563EB', // main action/brand color; HEX without #
		'secondary_color' => '64748B', // supporting neutral/secondary color; HEX without #
		'accent_color' => '7C3AED', // emphasis/highlight color used sparingly; HEX without #

		'background_color' => 'F8FAFC', // main page/window background; HEX without #
		'surface_color' => 'FFFFFF', // card/panel/control surface background; HEX without #
		'surface_alt_color' => 'F1F5F9', // alternate surface used for subtle contrast; HEX without #

		'text_color' => '0F172A', // default readable text color; HEX without #
		'muted_text_color' => '64748B', // secondary/help/placeholder text color; HEX without #
		'inverse_text_color' => 'FFFFFF', // text used on filled/dark/accent surfaces; HEX without #

		'border_color' => 'CBD5E1', // default border/outline color; HEX without #
		'divider_color' => 'E2E8F0', // separator/divider color; HEX without #

		'success_color' => '16A34A', // success/valid/complete state color; HEX without #
		'warning_color' => 'D97706', // warning/caution state color; HEX without #
		'danger_color' => 'DC2626', // error/destructive state color; HEX without #
		'info_color' => '0284C7', // informational state color; HEX without #
		'fyi_color' => '0891B2', // FYI/note/tip state color; HEX without #
	],

	# Typography
	'typography_config' => [ // Typography defaults for the theme.
		'font_family' => 'Segoe UI', // preferred UI font family; renderer may substitute when unavailable
		'base_font_size' => 10, // default body/control font size
		'heading_font_size' => 16, // primary page/section heading size
		'subheading_font_size' => 13, // secondary heading or card title size
		'small_font_size' => 9, // help text, captions, status bar text
		'line_height' => 1.35, // default line-height multiplier
		'font_weight_normal' => 400, // normal text weight
		'font_weight_medium' => 500, // medium emphasis text weight
		'font_weight_bold' => 600, // strong emphasis/title weight
	],

	# Spacing
	'spacing_config' => [ // Spacing and padding defaults.
		'page_padding' => 16, // default outer page padding
		'panel_padding' => 12, // default panel/container padding
		'card_padding' => 12, // default card/stat tile padding
		'section_gap' => 12, // vertical/horizontal gap between major sections
		'group_gap' => 10, // gap between smaller groups inside a section
		'field_gap' => 8, // gap between form controls
		'button_gap' => 8, // gap between adjacent buttons
	],

	# Border
	'border_config' => [ // Border radius and width defaults.
		'border_radius' => 8, // default rounded corner radius
		'border_radius_small' => 4, // small controls/compact chips
		'border_radius_large' => 12, // dialogs/large panels
		'border_width' => 1, // default border width
	],

	# Shadow
	'shadow_config' => [ // Shadow style defaults.
		'card_shadow' => 'SOFT', // default card shadow depth/style
		'dialog_shadow' => 'MED', // default modal/dialog shadow depth/style
		'popover_shadow' => 'SOFT', // menus/dropdowns/popovers shadow depth/style
	],

	# Icon
	'icon_config' => [ // Icon style defaults.
		'icon_style' => 'OUTLINE', // preferred icon family/style
		'icon_size' => 18, // default icon size in pixels
		'icon_stroke_width' => 2, // stroke width for outline icons
	],

	# Motion / Animation
	'is_animated' => true, // enables use of animation_config; false means renderers should minimize theme motion
	'animation_config' => [ // Motion and animation settings.
		'duration_short_ms' => 120, // quick hover/focus/inline motion duration
		'duration_medium_ms' => 200, // normal component transition duration
		'duration_long_ms' => 320, // slower page/dialog transition duration
		'easing_standard' => 'EASE_OUT', // default easing for ordinary motion
		'easing_emphasis' => 'EASE_IN_OUT', // easing for emphasized or larger motion
		'hover_motion' => 'SUBTLE', // hover feedback style
		'focus_motion' => 'RING', // keyboard/focus feedback style
		'loading_motion' => 'SPINNER', // default loading indicator style
		'transition_style' => 'FADE', // default in-place transition style
		'page_transition_style' => 'NONE', // page navigation transition style
		'dialog_transition_style' => 'SCALE_FADE', // dialog enter/exit transition style
		'list_insert_motion' => 'SLIDE_FADE', // list/table row insert motion style
	],

	# Media
	'media_config' => [ // Image and video display settings.
		'image_corner_radius' => 8, // default image corner radius
		'image_fit_mode' => 'COVER', // default image fill/crop behavior
		'image_placeholder_style' => 'MUTED', // placeholder/skeleton style before image loads
		'video_corner_radius' => 8, // default video corner radius
		'video_fit_mode' => 'CONTAIN', // default video fit behavior
		'video_control_style' => 'STANDARD', // default video control styling
	],

	# Charts / Graphs
	'chart_config' => [ // Graph and chart defaults.
		'chart_palette' => 'DEFAULT', // named chart palette; series_color_list can override concrete colors
		'grid_line_color' => 'E2E8F0', // chart grid line color; HEX without #
		'axis_text_color' => '64748B', // chart axis label color; HEX without #
		'positive_color' => '16A34A', // positive metric/series color; HEX without #
		'negative_color' => 'DC2626', // negative metric/series color; HEX without #
		'neutral_color' => '64748B', // neutral metric/series color; HEX without #
		'series_color_list' => [
			'2563EB',
			'16A34A',
			'D97706',
			'DC2626',
			'7C3AED',
		],
	],

	# Components
	'component_config' => [ // Common component size defaults.
		'button_height' => 32, // default button height
		'input_height' => 30, // default text input height
		'combo_box_height' => 30, // default dropdown/combo-box height
		'table_row_height' => 28, // default table row height
		'table_header_height' => 32, // default table header height
		'card_min_height' => 72, // default minimum height for stat cards/cards
		'toolbar_height' => 40, // default toolbar height
		'status_bar_height' => 24, // default status bar height
	],

	# Behavior
	'is_responsive' => true, // theme is intended to adapt to viewport/orientation differences
	'is_locked' => false, // locked themes are protected from normal editing

	# Note
	'theme_note' => 'Default reusable light application theme.', // internal note; not multilingual

	# Audit
	'created_at' => $created_at, // Date and time when the theme record was created.
	'updated_at' => $updated_at, // Date and time when the theme record was last updated.
	'updated_by_device_id' => 1, // Device that last updated the theme record.
];

/**
 * Field order for theme_main.
 */
$theme_main_field_order = [
	'_id',
	'theme_key',
	'theme_label',
	'theme_description',
	'type_theme',
	'mode_appearance',
	'supported_ecosystem_list',
	'status_record_lifecycle',
	'color_config',
	'typography_config',
	'spacing_config',
	'border_config',
	'shadow_config',
	'icon_config',
	'is_animated',
	'animation_config',
	'media_config',
	'chart_config',
	'component_config',
	'is_responsive',
	'is_locked',
	'theme_note',
	'created_at',
	'updated_at',
	'updated_by_device_id',
];

/**
 * Embedded structures used by theme_main.
 */
$theme_main_embedded_structure = [
	'color_config' => [
		'type_structure' => 'object',
		'description' => 'Theme color values stored as HEX strings without #.',
	],
	'typography_config' => [
		'type_structure' => 'object',
		'description' => 'Font family, font size, line height, and weight defaults.',
	],
	'spacing_config' => [
		'type_structure' => 'object',
		'description' => 'Spacing and padding defaults.',
	],
	'border_config' => [
		'type_structure' => 'object',
		'description' => 'Border radius and border width defaults.',
	],
	'shadow_config' => [
		'type_structure' => 'object',
		'description' => 'Shadow style defaults.',
	],
	'icon_config' => [
		'type_structure' => 'object',
		'description' => 'Icon style defaults.',
	],
	'animation_config' => [
		'type_structure' => 'object',
		'description' => 'Motion and animation timing/style defaults. Used only when is_animated is true.',
	],
	'media_config' => [
		'type_structure' => 'object',
		'description' => 'Image and video display defaults.',
	],
	'chart_config' => [
		'type_structure' => 'object',
		'description' => 'Chart, graph, and data-visualization defaults.',
	],
	'component_config' => [
		'type_structure' => 'object',
		'description' => 'Common component size defaults.',
	],
];

/**
 * Field-property guide for theme_main.
 * Every top-level field is listed, but default-valued properties are omitted.
 * For readability, label/description examples here assume English text directly.
 * Comments in the sample record explain how each field is intended to be used by renderers.
 * Embedded config property guides below explain the child properties inside each config object.
 */
$theme_main_field_property = [
	'_id' => [
		'field_label' => 'ID',
		'field_description' => 'Physical MongoDB identity for the theme record.',
		'type_data' => 'I',
		'type_sql' => 'INT',
		'is_mandatory' => true,
		'is_indexed' => true,
	],
	'theme_key' => [
		'field_label' => 'Theme Key',
		'field_description' => 'Stable unique key for this theme.',
		'type_data' => 'S',
		'type_field' => 'TXT',
		'type_sql' => 'VARCHAR',
		'max_character' => 75,
		'is_mandatory' => true,
	],
	'theme_label' => [
		'field_label' => 'Theme Label',
		'field_description' => 'User-facing name of the theme.',
		'type_data' => 'O',
		'type_field' => 'TXT',
		'type_sql' => 'JSON',
		'max_character' => 75,
		'is_mandatory' => true,
	],
	'theme_description' => [
		'field_label' => 'Theme Description',
		'field_description' => 'Short explanation of the theme.',
		'type_data' => 'O',
		'type_field' => 'TXA',
		'type_sql' => 'JSON',
		'max_character' => 255,
	],
	'type_theme' => [
		'field_label' => 'Theme Type',
		'field_description' => 'Purpose or usage family of the theme.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			'APP' => 'Application Theme',
			'ADM' => 'Admin Theme',
			'DASH' => 'Dashboard Theme',
			'DOC' => 'Document Theme',
			'RPT' => 'Report Theme',
			'PRT' => 'Print Theme',
		],
		'is_mandatory' => true,
	],
	'mode_appearance' => [
		'field_label' => 'Appearance Mode',
		'field_description' => 'Whether the theme is light, dark, or system adaptive.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			'LGT' => 'Light',
			'DRK' => 'Dark',
			'SYS' => 'System Adaptive',
		],
		'is_mandatory' => true,
	],
	'supported_ecosystem_list' => [
		'field_label' => 'Supported Ecosystems',
		'field_description' => 'List of ecosystem targets supported by this theme. Values come from type_ecosystem.',
		'type_data' => 'A',
		'type_field' => 'CHK',
		'type_sql' => 'JSON',
	],
	'status_record_lifecycle' => [
		'field_label' => 'Theme Status',
		'field_description' => 'Lifecycle status of the theme.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
	],
	'color_config' => [
		'field_label' => 'Color Config',
		'field_description' => 'Color palette values. Hex strings without #.',
		'type_data' => 'O',
		'type_sql' => 'JSON',
	],
	'typography_config' => [
		'field_label' => 'Typography Config',
		'field_description' => 'Typography defaults for the theme.',
		'type_data' => 'O',
		'type_sql' => 'JSON',
	],
	'spacing_config' => [
		'field_label' => 'Spacing Config',
		'field_description' => 'Spacing and padding defaults.',
		'type_data' => 'O',
		'type_sql' => 'JSON',
	],
	'border_config' => [
		'field_label' => 'Border Config',
		'field_description' => 'Border radius and width defaults.',
		'type_data' => 'O',
		'type_sql' => 'JSON',
	],
	'shadow_config' => [
		'field_label' => 'Shadow Config',
		'field_description' => 'Shadow style defaults.',
		'type_data' => 'O',
		'type_sql' => 'JSON',
	],
	'icon_config' => [
		'field_label' => 'Icon Config',
		'field_description' => 'Icon style defaults.',
		'type_data' => 'O',
		'type_sql' => 'JSON',
	],
	'is_animated' => [
		'field_label' => 'Animated',
		'field_description' => 'Whether this theme uses motion/animation defaults.',
		'type_data' => 'B',
		'type_field' => 'CHK',
		'type_sql' => 'BOOLEAN',
	],
	'animation_config' => [
		'field_label' => 'Animation Config',
		'field_description' => 'Motion and animation settings.',
		'type_data' => 'O',
		'type_sql' => 'JSON',
	],
	'media_config' => [
		'field_label' => 'Media Config',
		'field_description' => 'Image and video display settings.',
		'type_data' => 'O',
		'type_sql' => 'JSON',
	],
	'chart_config' => [
		'field_label' => 'Chart Config',
		'field_description' => 'Graph and chart defaults.',
		'type_data' => 'O',
		'type_sql' => 'JSON',
	],
	'component_config' => [
		'field_label' => 'Component Config',
		'field_description' => 'Common component size defaults.',
		'type_data' => 'O',
		'type_sql' => 'JSON',
	],
	'is_responsive' => [
		'field_label' => 'Responsive',
		'field_description' => 'Whether the theme is expected to adapt across screen sizes/orientations.',
		'type_data' => 'B',
		'type_field' => 'CHK',
		'type_sql' => 'BOOLEAN',
	],
	'is_locked' => [
		'field_label' => 'Locked',
		'field_description' => 'Whether the theme definition is locked against normal editing.',
		'type_data' => 'B',
		'type_field' => 'CHK',
		'type_sql' => 'BOOLEAN',
	],
	'theme_note' => [
		'field_label' => 'Theme Note',
		'field_description' => 'Internal note about the theme. Not multilingual.',
		'type_data' => 'S',
		'type_field' => 'TXA',
		'type_sql' => 'TEXT',
	],
	'created_at' => [
		'field_label' => 'Created At',
		'field_description' => 'Date and time when the theme record was created.',
		'type_data' => 'D',
		'type_sql' => 'DATETIME',
	],
	'updated_at' => [
		'field_label' => 'Updated At',
		'field_description' => 'Date and time when the theme record was last updated.',
		'type_data' => 'D',
		'type_sql' => 'DATETIME',
	],
	'updated_by_device_id' => [
		'field_label' => 'Updated By Device ID',
		'field_description' => 'Device that last updated the theme record.',
		'type_data' => 'I',
		'type_sql' => 'INT',
	],
];

/**
 * Color config property guide.
 */
$theme_main_color_config_property = [
	'primary_color' => 'Primary brand/action color. HEX without #.',
	'secondary_color' => 'Secondary or supporting UI color. HEX without #.',
	'accent_color' => 'Accent/highlight color. HEX without #.',
	'background_color' => 'Main application/page background color. HEX without #.',
	'surface_color' => 'Card/panel/surface color. HEX without #.',
	'surface_alt_color' => 'Alternate surface color. HEX without #.',
	'text_color' => 'Default text color. HEX without #.',
	'muted_text_color' => 'Secondary/muted text color. HEX without #.',
	'inverse_text_color' => 'Text color used on dark/filled surfaces. HEX without #.',
	'border_color' => 'Default border color. HEX without #.',
	'divider_color' => 'Divider/separator line color. HEX without #.',
	'success_color' => 'Success state color. HEX without #.',
	'warning_color' => 'Warning state color. HEX without #.',
	'danger_color' => 'Danger/error state color. HEX without #.',
	'info_color' => 'Informational state color. HEX without #.',
	'fyi_color' => 'FYI/note state color. HEX without #.',
];

/**
 * Typography config property guide.
 */
$theme_main_typography_config_property = [
	'font_family' => 'Preferred UI font family name.',
	'base_font_size' => 'Default font size.',
	'heading_font_size' => 'Primary heading font size.',
	'subheading_font_size' => 'Secondary heading font size.',
	'small_font_size' => 'Small/help text font size.',
	'line_height' => 'Default line-height multiplier.',
	'font_weight_normal' => 'Normal text weight.',
	'font_weight_medium' => 'Medium emphasis text weight.',
	'font_weight_bold' => 'Bold text weight.',
];

/**
 * Spacing config property guide.
 */
$theme_main_spacing_config_property = [
	'page_padding' => 'Default page padding.',
	'panel_padding' => 'Default panel padding.',
	'card_padding' => 'Default card padding.',
	'section_gap' => 'Gap between sections.',
	'group_gap' => 'Gap between groups inside a section.',
	'field_gap' => 'Gap between fields.',
	'button_gap' => 'Gap between buttons.',
];

/**
 * Border config property guide.
 */
$theme_main_border_config_property = [
	'border_radius' => 'Default border radius.',
	'border_radius_small' => 'Small control border radius.',
	'border_radius_large' => 'Large card/dialog border radius.',
	'border_width' => 'Default border width.',
];

/**
 * Shadow config property guide.
 */
$theme_main_shadow_config_property = [
	'card_shadow' => 'Default card shadow style.',
	'dialog_shadow' => 'Default dialog shadow style.',
	'popover_shadow' => 'Default popover shadow style.',
];

/**
 * Icon config property guide.
 */
$theme_main_icon_config_property = [
	'icon_style' => 'Default icon style.',
	'icon_size' => 'Default icon size.',
	'icon_stroke_width' => 'Default icon stroke width for stroke-based icons.',
];

/**
 * Animation config property guide.
 */
$theme_main_animation_config_property = [
	'duration_short_ms' => 'Short animation duration in milliseconds.',
	'duration_medium_ms' => 'Medium animation duration in milliseconds.',
	'duration_long_ms' => 'Long animation duration in milliseconds.',
	'easing_standard' => 'Default easing style.',
	'easing_emphasis' => 'Easing style for emphasized motion.',
	'hover_motion' => 'Default hover effect style.',
	'focus_motion' => 'Default focus effect style.',
	'loading_motion' => 'Default loading motion style.',
	'transition_style' => 'Default local transition style.',
	'page_transition_style' => 'Default page navigation transition style.',
	'dialog_transition_style' => 'Default dialog open/close transition style.',
	'list_insert_motion' => 'Default motion for inserted list/table items.',
];

/**
 * Media config property guide.
 */
$theme_main_media_config_property = [
	'image_corner_radius' => 'Default image corner radius.',
	'image_fit_mode' => 'Default image fit mode.',
	'image_placeholder_style' => 'Default image placeholder style.',
	'video_corner_radius' => 'Default video corner radius.',
	'video_fit_mode' => 'Default video fit mode.',
	'video_control_style' => 'Default video control style.',
];

/**
 * Chart config property guide.
 */
$theme_main_chart_config_property = [
	'chart_palette' => 'Default chart palette name.',
	'grid_line_color' => 'Chart grid line color. HEX without #.',
	'axis_text_color' => 'Chart axis label text color. HEX without #.',
	'positive_color' => 'Positive value color. HEX without #.',
	'negative_color' => 'Negative value color. HEX without #.',
	'neutral_color' => 'Neutral value color. HEX without #.',
	'series_color_list' => 'Ordered chart series color list. HEX values without #.',
];

/**
 * Component config property guide.
 */
$theme_main_component_config_property = [
	'button_height' => 'Default button height.',
	'input_height' => 'Default text input height.',
	'combo_box_height' => 'Default combo box height.',
	'table_row_height' => 'Default table row height.',
	'table_header_height' => 'Default table header height.',
	'card_min_height' => 'Default minimum card height.',
	'toolbar_height' => 'Default toolbar height.',
	'status_bar_height' => 'Default status bar height.',
];

/**
 * Boundary guide.
 */

$theme_main_subfield_property = [
    'color_config' => $theme_main_color_config_property,
    'typography_config' => $theme_main_typography_config_property,
    'spacing_config' => $theme_main_spacing_config_property,
    'border_config' => $theme_main_border_config_property,
    'shadow_config' => $theme_main_shadow_config_property,
    'icon_config' => $theme_main_icon_config_property,
    'animation_config' => $theme_main_animation_config_property,
    'media_config' => $theme_main_media_config_property,
    'chart_config' => $theme_main_chart_config_property,
    'component_config' => $theme_main_component_config_property,
];

$theme_main_index_list = [
    [
        'index_key' => 'primary',
        'index_name' => '_id_',
        'type_index' => 'STD',
        'is_unique' => true,
        'is_sparse' => false,
        'index_field_list' => [
            ['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10],
        ],
        'sort_order' => 10,
    ],
];

$theme_main_boundary = [
	'belongs_in_theme_main' => [
		'Reusable visual theme identity and description.',
		'Theme type, appearance mode, and supported ecosystem list.',
		'Color, typography, spacing, border, shadow, icon, animation, media, chart, and component style defaults.',
		'Responsive, locked, and lifecycle status flags.',
	],
	'belongs_in_project_main' => [
		'Which theme a project selects.',
		'Project-specific theme override choices if later accepted.',
	],
	'belongs_in_project_form' => [
		'Logical form sections and groups.',
		'Form field placement and form-specific behavior.',
	],
	'belongs_in_template_main' => [
		'Generated file templates.',
		'Code templates, document templates, and scaffolding text.',
	],
	'belongs_outside_theme_main' => [
		'Binary image/video/font/icon files.',
		'Runtime user appearance preference unless stored as user settings.',
		'Actual generated application code.',
	],
];

return [
    'theme_main_default' => $theme_main_default,
    'multilingual_text_sample' => $multilingual_text_sample,
    'theme_main' => $theme_main,
    'theme_main_field_order' => $theme_main_field_order,
    'theme_main_embedded_structure' => $theme_main_embedded_structure,
    'theme_main_field_property' => $theme_main_field_property,
    'theme_main_color_config_property' => $theme_main_color_config_property,
    'theme_main_typography_config_property' => $theme_main_typography_config_property,
    'theme_main_spacing_config_property' => $theme_main_spacing_config_property,
    'theme_main_border_config_property' => $theme_main_border_config_property,
    'theme_main_shadow_config_property' => $theme_main_shadow_config_property,
    'theme_main_icon_config_property' => $theme_main_icon_config_property,
    'theme_main_animation_config_property' => $theme_main_animation_config_property,
    'theme_main_media_config_property' => $theme_main_media_config_property,
    'theme_main_chart_config_property' => $theme_main_chart_config_property,
    'theme_main_component_config_property' => $theme_main_component_config_property,
    'theme_main_subfield_property' => $theme_main_subfield_property,
    'theme_main_index_list' => $theme_main_index_list,
    'theme_main_boundary' => $theme_main_boundary,
];
