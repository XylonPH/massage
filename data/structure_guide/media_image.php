<?php
/**
 * Title: Massage Nexus Media Image Structure Guide
 * Version: 1.12
 * Collection: media_image
 * Description: Stores one image asset record, its renditions, attribution, relationships, and lifecycle.
 * Purpose: Documents the media_image record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * This file is a PHP-readable visual structure guide.
 * It is not a seed file, not a runtime migration script, and not a generated
 * production schema. It exists so the database structure can be reviewed in a
 * familiar PHP array format before implementation.
 *
 * Layer rule:
 * - *_default contains omission defaults for actual stored record fields only.
 * - *_field_property describes schema/field metadata only.
 * - Do not mix field-definition metadata into record defaults.
 * Current scope:
 * - media_image stores image file records and image metadata.
 * - Image variants are embedded in image_variant_list; no media_image_variant
 *   collection is created in this version.
 */

# Variable
$created_at = '2026-07-06T00:00:00Z';
$updated_at = '2026-07-21T04:27:07Z';
/**
 * Multilingual short-text sample.
 * Used by content_title, short_description, caption_text, alt_text, and similar
 * bounded user-facing text values. English is shown as sample data only; the
 * original language of authored content is controlled by language_original_id.
 */
$multilingual_text_sample = [
	'eng' => [
		'text' => 'Sample text', // required when this language value exists
		'method_translation' => 'HUM', // optional; omit when default Human Translation applies
		'status_review' => 'A', // optional; A = Approved
	],
];

/**
 * Actual record-level defaults for media_image.
 */
$media_image_default = [
	'tag_id_list' => [],
	'related_organization_id_list' => [],
	'related_establishment_id_list' => [],
	'related_practitioner_id_list' => [],
	'related_service_id_list' => [],
	'related_product_id_list' => [],
	'creator_user_id_list' => [],
	'photographer_user_id_list' => [],
	'editor_user_id_list' => [],
	'source_media_image_id' => null,
	'level_nsfw' => 'N',
	'image_variant_list' => [],
	'detected_person_list' => [],
	'recognized_person_list' => [],
	'visibility_scope' => 'INH',
	'status_review' => 'P',
	'status_record_lifecycle' => 'ACT',
];

/**
 * media_image sample record.
 */
$media_image = [
	# Primary
	'_id' => 7001, // Physical MongoDB identity for the media_image record.

	# Core File
	'image_title' => [ // Optional multilingual title for identifying the image internally or publicly.
		'eng' => ['text' => 'Warm Massage Room Cover Image', 'method_translation' => 'HUM', 'status_review' => 'A'],
	],
	'file_name' => 'warm-massage-room-cover.webp', // Stored file name including extension.
	'file_extension' => 'webp', // Lowercase file extension without the leading period.
	'mime_type' => 'image/webp', // Internet media type for the image file.
	'file_size_byte' => 245760, // File size in bytes.
	'width_pixel' => 1600, // Image width in pixels.
	'height_pixel' => 900, // Image height in pixels.
	'storage_path' => 'storage/upload/media/image/2026/07/warm-massage-room-cover.webp', // Internal/local storage path used by the application.
	'storage_url' => null, // use when served from CDN or public external storage

	# Description
	'alt_text' => [ // Multilingual alternative text for accessibility and image understanding.
		'eng' => ['text' => 'Warm massage room with folded towels and soft lighting.', 'method_translation' => 'HUM', 'status_review' => 'A'],
	],
	'caption_text' => [ // Optional multilingual caption for displaying the image in public contexts.
		'eng' => ['text' => 'A calm treatment room helps first-time clients feel more comfortable.', 'method_translation' => 'HUM', 'status_review' => 'A'],
	],

	# Classification / Relationship
	'tag_id_list' => [301, 410], // List of tag IDs attached to the image.
	'related_organization_id_list' => [201], // Organization IDs related to the image.
	'related_establishment_id_list' => [301], // Establishment IDs related to the image.
	'related_practitioner_id_list' => [], // Practitioner IDs related to the image.
	'related_service_id_list' => [601], // Service IDs related to the image.
	'related_product_id_list' => [], // Product IDs related to the image.
	'level_nsfw' => 'N', // Image sensitivity classification for moderation and display handling.

	# Credit / Source
	'method_media_creation' => 'PH', // PH = photographed, AI = AI generated, IL = illustrated, ED = edited/composited, IMP = imported
	'creator_user_id_list' => [504], // User IDs credited with creating the image or visual work.
	'photographer_user_id_list' => [504], // User IDs credited as photographers for the image.
	'editor_user_id_list' => [506], // User IDs credited with editing, compositing, or post-processing the image.
	'ai_tool_name' => null, // Name of the AI tool used when the image was generated or AI-assisted.
	'source_media_image_id' => null, // Reference to an original media_image record when this image is edited or derived from another image.
	'source_url' => null, // Original external source URL when the image is imported or externally sourced.

	# Variant / Recognition
	'image_variant_list' => [ // Embedded list of derived image files such as thumbnails, small versions, cropped versions, or compressed versions.
		[
			'type_image_variant' => 'TH', // TH = Thumbnail
			'file_name' => 'warm-massage-room-cover-thumb.webp',
			'file_extension' => 'webp',
			'mime_type' => 'image/webp',
			'file_size_byte' => 24576,
			'width_pixel' => 320,
			'height_pixel' => 180,
			'storage_path' => 'storage/upload/media/image/2026/07/warm-massage-room-cover-thumb.webp',
			'storage_url' => null,
		],
	],
	'detected_person_list' => [ // Optional machine-detected person regions in the image. Does not confirm identity by itself.
		[
			'detected_person_number' => 1,
			'confidence_level' => 0.82,
			'bounding_box' => ['x' => 0.42, 'y' => 0.18, 'width' => 0.16, 'height' => 0.28],
		],
	],
	'recognized_person_list' => [ // Optional recognized or proposed person/entity links in the image, subject to confirmation and privacy rules.
		[
			'target_collection' => 'practitioner_main',
			'target_id' => 401,
			'confidence_level' => 0.74,
			'is_confirmed' => false,
		],
	],

	# Handling
	'visibility_scope' => 'PUB', // Visibility rule for the image record.
	'status_review' => 'A', // Moderation or approval review status for the image.
	'status_record_lifecycle' => 'ACT', // Database lifecycle state for the image record.

	# Audit
	'created_at' => $created_at, // UTC timestamp when this image record was created.
	'created_by_user_id' => 504, // User ID that created this image record.
	'updated_at' => $updated_at, // UTC timestamp when this image record was last updated.
	'updated_by_user_id' => 506, // User ID that last updated this image record.
];

$media_image_field_order = [
	'_id',
	'image_title',
	'file_name',
	'file_extension',
	'mime_type',
	'file_size_byte',
	'width_pixel',
	'height_pixel',
	'storage_path',
	'storage_url',
	'alt_text',
	'caption_text',
	'tag_id_list',
	'related_organization_id_list',
	'related_establishment_id_list',
	'related_practitioner_id_list',
	'related_service_id_list',
	'related_product_id_list',
	'level_nsfw',
	'method_media_creation',
	'creator_user_id_list',
	'photographer_user_id_list',
	'editor_user_id_list',
	'ai_tool_name',
	'source_media_image_id',
	'source_url',
	'image_variant_list',
	'detected_person_list',
	'recognized_person_list',
	'visibility_scope',
	'status_review',
	'status_record_lifecycle',
	'created_at',
	'created_by_user_id',
	'updated_at',
	'updated_by_user_id',
];

$media_image_embedded_structure = [
	'image_variant_list' => [
		'type_image_variant' => 'TH',
		'file_name' => 'sample-thumb.webp',
		'file_extension' => 'webp',
		'mime_type' => 'image/webp',
		'file_size_byte' => 24576,
		'width_pixel' => 320,
		'height_pixel' => 180,
		'storage_path' => 'storage/upload/media/image/sample-thumb.webp',
		'storage_url' => null,
	],
	'detected_person_list' => [
		'detected_person_number' => 1,
		'confidence_level' => 0.82,
		'bounding_box' => ['x' => 0.42, 'y' => 0.18, 'width' => 0.16, 'height' => 0.28],
	],
	'recognized_person_list' => [
		'target_collection' => 'practitioner_main',
		'target_id' => 401,
		'confidence_level' => 0.74,
		'is_confirmed' => false,
	],
];

$media_image_field_property = [
	'_id' => ['field_label' => 'Media Image ID', 'field_description' => 'Physical MongoDB identity for the media_image record.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],
	'image_title' => ['field_label' => 'Image Title', 'field_description' => 'Optional multilingual title for identifying the image internally or publicly.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true],
	'file_name' => ['field_label' => 'File Name', 'field_description' => 'Stored file name including extension.', 'is_mandatory' => true, 'max_character' => 255],
	'file_extension' => ['field_label' => 'File Extension', 'field_description' => 'Lowercase file extension without the leading period.', 'max_character' => 20],
	'mime_type' => ['field_label' => 'MIME Type', 'field_description' => 'Internet media type for the image file.', 'max_character' => 100],
	'file_size_byte' => ['field_label' => 'File Size Byte', 'field_description' => 'File size in bytes.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'BIGINT', 'min_number' => 0],
	'width_pixel' => ['field_label' => 'Width Pixel', 'field_description' => 'Image width in pixels.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 1],
	'height_pixel' => ['field_label' => 'Height Pixel', 'field_description' => 'Image height in pixels.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 1],
	'storage_path' => ['field_label' => 'Storage Path', 'field_description' => 'Internal/local storage path used by the application.', 'max_character' => 500],
	'storage_url' => ['field_label' => 'Storage URL', 'field_description' => 'Public, CDN, or external URL when the file is served outside local/internal storage.', 'max_character' => 1000],
	'alt_text' => ['field_label' => 'Alt Text', 'field_description' => 'Multilingual alternative text for accessibility and image understanding.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true],
	'caption_text' => ['field_label' => 'Caption Text', 'field_description' => 'Optional multilingual caption for displaying the image in public contexts.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true],
	'tag_id_list' => ['field_label' => 'Tag ID List', 'field_description' => 'List of tag IDs attached to the image.', 'type_data' => 'A', 'is_relational' => true, 'is_indexed' => true],
	'related_organization_id_list' => ['field_label' => 'Related Organization ID List', 'field_description' => 'Organization IDs related to the image.', 'type_data' => 'A', 'is_relational' => true],
	'related_establishment_id_list' => ['field_label' => 'Related Establishment ID List', 'field_description' => 'Establishment IDs related to the image.', 'type_data' => 'A', 'is_relational' => true],
	'related_practitioner_id_list' => ['field_label' => 'Related Practitioner ID List', 'field_description' => 'Practitioner IDs related to the image.', 'type_data' => 'A', 'is_relational' => true],
	'related_service_id_list' => ['field_label' => 'Related Service ID List', 'field_description' => 'Service IDs related to the image.', 'type_data' => 'A', 'is_relational' => true],
	'related_product_id_list' => ['field_label' => 'Related Product ID List', 'field_description' => 'Product IDs related to the image.', 'type_data' => 'A', 'is_relational' => true],
	'level_nsfw' => ['field_label' => 'NSFW Level', 'field_description' => 'Image sensitivity classification for moderation and display handling.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'method_media_creation' => ['field_label' => 'Media Creation Method', 'field_description' => 'Classifies how the image was produced, such as photographed, AI generated, illustrated, edited, composited, or imported.', 'type_field' => 'DDL', 'type_sql' => 'ENUM'],
	'creator_user_id_list' => ['field_label' => 'Creator User ID List', 'field_description' => 'User IDs credited with creating the image or visual work.', 'type_data' => 'A', 'is_relational' => true],
	'photographer_user_id_list' => ['field_label' => 'Photographer User ID List', 'field_description' => 'User IDs credited as photographers for the image.', 'type_data' => 'A', 'is_relational' => true],
	'editor_user_id_list' => ['field_label' => 'Editor User ID List', 'field_description' => 'User IDs credited with editing, compositing, or post-processing the image.', 'type_data' => 'A', 'is_relational' => true],
	'ai_tool_name' => ['field_label' => 'AI Tool Name', 'field_description' => 'Name of the AI tool used when the image was generated or AI-assisted.', 'max_character' => 100],
	'source_media_image_id' => ['field_label' => 'Source Media Image ID', 'field_description' => 'Reference to an original media_image record when this image is edited or derived from another image.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
	'source_url' => ['field_label' => 'Source URL', 'field_description' => 'Original external source URL when the image is imported or externally sourced.', 'max_character' => 1000],
	'image_variant_list' => ['field_label' => 'Image Variant List', 'field_description' => 'Embedded list of derived image files such as thumbnails, small versions, cropped versions, or compressed versions.', 'type_data' => 'A', 'type_field' => 'JSE'],
	'detected_person_list' => ['field_label' => 'Detected Person List', 'field_description' => 'Optional machine-detected person regions in the image. Does not confirm identity by itself.', 'type_data' => 'A', 'type_field' => 'JSE'],
	'recognized_person_list' => ['field_label' => 'Recognized Person List', 'field_description' => 'Optional recognized or proposed person/entity links in the image, subject to confirmation and privacy rules.', 'type_data' => 'A', 'type_field' => 'JSE'],
	'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Visibility rule for the image record.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'status_review' => ['field_label' => 'Review Status', 'field_description' => 'Moderation or approval review status for the image.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state for the image record.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when this image record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'User ID that created this image record.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when this image record was last updated.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'User ID that last updated this image record.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
];

$media_image_subfield_property = [
	'image_variant_list.type_image_variant' => ['field_label' => 'Image Variant Type', 'field_description' => 'Controlled rendition purpose or size code.', 'type_data' => 'S', 'is_mandatory' => true],
	'image_variant_list.file_name' => ['field_label' => 'Variant File Name', 'field_description' => 'Stored rendition filename.', 'type_data' => 'S', 'is_mandatory' => true],
	'image_variant_list.file_extension' => ['field_label' => 'Variant File Extension', 'field_description' => 'Normalized rendition extension.', 'type_data' => 'S', 'is_mandatory' => true],
	'image_variant_list.mime_type' => ['field_label' => 'Variant MIME Type', 'field_description' => 'Rendition media type.', 'type_data' => 'S', 'is_mandatory' => true],
	'image_variant_list.file_size_byte' => ['field_label' => 'Variant File Size', 'field_description' => 'Rendition size in bytes.', 'type_data' => 'I', 'min_number' => 0],
	'image_variant_list.width_pixel' => ['field_label' => 'Variant Width', 'field_description' => 'Rendition width in pixels.', 'type_data' => 'I', 'min_number' => 1],
	'image_variant_list.height_pixel' => ['field_label' => 'Variant Height', 'field_description' => 'Rendition height in pixels.', 'type_data' => 'I', 'min_number' => 1],
	'image_variant_list.storage_path' => ['field_label' => 'Variant Storage Path', 'field_description' => 'Private storage path for the rendition.', 'type_data' => 'S'],
	'image_variant_list.storage_url' => ['field_label' => 'Variant Storage URL', 'field_description' => 'Optional approved delivery URL.', 'type_data' => 'S'],
	'detected_person_list.detected_person_number' => ['field_label' => 'Detected Person Number', 'field_description' => 'Stable ordinal within this detection result.', 'type_data' => 'I'],
	'detected_person_list.confidence_level' => ['field_label' => 'Detection Confidence', 'field_description' => 'Detection confidence from zero to one.', 'type_data' => 'D', 'min_number' => 0, 'max_number' => 1],
	'detected_person_list.bounding_box' => ['field_label' => 'Bounding Box', 'field_description' => 'Normalized x, y, width, and height object.', 'type_data' => 'O'],
	'detected_person_list.bounding_box.x' => ['field_label' => 'Bounding Box X', 'field_description' => 'Normalized horizontal origin.', 'type_data' => 'D'],
	'detected_person_list.bounding_box.y' => ['field_label' => 'Bounding Box Y', 'field_description' => 'Normalized vertical origin.', 'type_data' => 'D'],
	'detected_person_list.bounding_box.width' => ['field_label' => 'Bounding Box Width', 'field_description' => 'Normalized box width.', 'type_data' => 'D'],
	'detected_person_list.bounding_box.height' => ['field_label' => 'Bounding Box Height', 'field_description' => 'Normalized box height.', 'type_data' => 'D'],
	'recognized_person_list.target_collection' => ['field_label' => 'Recognized Target Collection', 'field_description' => 'Collection containing the proposed recognized person.', 'type_data' => 'S'],
	'recognized_person_list.target_id' => ['field_label' => 'Recognized Target ID', 'field_description' => 'Identifier of the proposed recognized person.', 'type_data' => 'S', 'is_relational' => true],
	'recognized_person_list.confidence_level' => ['field_label' => 'Recognition Confidence', 'field_description' => 'Recognition confidence from zero to one.', 'type_data' => 'D', 'min_number' => 0, 'max_number' => 1],
	'recognized_person_list.is_confirmed' => ['field_label' => 'Recognition Confirmed', 'field_description' => 'Whether an authorized review confirmed the match.', 'type_data' => 'B'],
];

$media_image_index_list = [
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

$media_image_boundary = [
    'owns' => [
        'the media_image record fields and embedded structures documented in this file',
    ],
    'reference_field_list' => [
        'tag_id_list',
        'related_organization_id_list',
        'related_establishment_id_list',
        'related_practitioner_id_list',
        'related_service_id_list',
        'related_product_id_list',
        'creator_user_id_list',
        'photographer_user_id_list',
        'editor_user_id_list',
        'source_media_image_id',
        'created_by_user_id',
        'updated_by_user_id',
    ],
    'does_not_own' => [
        'records stored in referenced collections',
        'runtime authorization, migration, seeding, or deployment behavior',
    ],
];

return [
    'multilingual_text_sample' => $multilingual_text_sample,
    'media_image_default' => $media_image_default,
    'media_image' => $media_image,
    'media_image_field_order' => $media_image_field_order,
    'media_image_embedded_structure' => $media_image_embedded_structure,
    'media_image_field_property' => $media_image_field_property,
    'media_image_subfield_property' => $media_image_subfield_property,
    'media_image_index_list' => $media_image_index_list,
    'media_image_boundary' => $media_image_boundary,
];
