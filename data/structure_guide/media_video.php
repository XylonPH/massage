<?php
/**
 * Title: Massage Nexus Media Video Structure Guide
 * Version: 1.20
 * Collection: media_video
 * Description: Stores one video asset record, its renditions, attribution, relationships, and lifecycle.
 * Purpose: Documents the media_video record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
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
 * - media_video stores video file records and video metadata.
 * - Video variants are embedded in video_variant_list; no media_video_variant
 *   collection is created in this version.
 */

# Variable
$created_at = '2026-07-06T00:00:00Z';
$updated_at = '2026-07-21T08:49:01Z';
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
		'status_review' => 'APR', // optional; APR = Approved
	],
];

/**
 * Actual record-level defaults for media_video.
 */
$media_video_default = [
	'tag_id_list' => [],
	'related_organization_id_list' => [],
	'related_establishment_id_list' => [],
	'related_practitioner_id_list' => [],
	'related_service_id_list' => [],
	'related_product_id_list' => [],
	'creator_user_id_list' => [],
	'videographer_user_id_list' => [],
	'editor_user_id_list' => [],
	'source_media_video_id' => null,
	'thumbnail_media_image_id' => null,
	'preview_media_image_id_list' => [],
	'level_nsfw' => 'N',
	'video_variant_list' => [],
	'detected_person_list' => [],
	'recognized_person_list' => [],
	'visibility_scope' => 'INH',
	'status_review' => 'PND',
	'status_record_lifecycle' => 'ACT',
];

/**
 * media_video sample record.
 */
$media_video = [
	# Primary
	'_id' => 'Mv7K2pQ9xR4tV8zN', // Canonical 16-character media video identifier.

	# Core File
	'video_title' => [ // Optional multilingual title for identifying the video internally or publicly.
		'eng' => ['text' => 'First Massage Consultation Demo', 'method_translation' => 'HUM', 'status_review' => 'APR'],
	],
	'file_name' => 'first-massage-consultation-demo.mp4', // Stored file name including extension.
	'file_extension' => 'mp4', // Lowercase file extension without the leading period.
	'mime_type' => 'video/mp4', // Internet media type for the video file.
	'file_size_byte' => 52428800, // File size in bytes.
	'duration' => 185, // duration in seconds
	'width_pixel' => 1920, // Video frame width in pixels.
	'height_pixel' => 1080, // Video frame height in pixels.
	'storage_path' => 'storage/upload/media/video/2026/07/first-massage-consultation-demo.mp4', // Internal/local storage path used by the application.
	'storage_url' => null, // Public, CDN, or external URL when the file is served outside local/internal storage.

	# Description
	'caption_text' => [ // Optional multilingual caption for displaying the video in public contexts.
		'eng' => ['text' => 'A short demonstration of asking about pressure, comfort, and allergies before a massage.', 'method_translation' => 'HUM', 'status_review' => 'APR'],
	],

	# Classification / Relationship
	'tag_id_list' => ['Tg7K2pQ9xR4tV8zN', 'Tg8K2pQ9xR4tV8zN'], // List of tag IDs attached to the video.
	'related_organization_id_list' => ['Or8K2pQ9xR4tV7zN'], // Organization IDs related to the video.
	'related_establishment_id_list' => ['Es7K2pQ9xR4tV8zN'], // Establishment IDs related to the video.
	'related_practitioner_id_list' => ['P8rC3mL7xT1qV5nK'], // Practitioner IDs related to the video.
	'related_service_id_list' => ['Sv8K2pQ9xR4tV7zN'], // Service IDs related to the video.
	'related_product_id_list' => [], // Product IDs related to the video.
	'level_nsfw' => 'N', // Video sensitivity classification for moderation and display handling.

	# Credit / Source
	'method_media_creation' => 'VD', // VD = video recorded, AI = AI generated, ED = edited/composited, IMP = imported
	'creator_user_id_list' => ['U5rK8mP2xN7qL4vA'], // User IDs credited with creating the video or visual work.
	'videographer_user_id_list' => ['U5rK8mP2xN7qL4vA'], // User IDs credited as videographers for the video.
	'editor_user_id_list' => ['U2pR7vX4kT9mC5qL'], // User IDs credited with editing, compositing, or post-processing the video.
	'ai_tool_name' => null, // Name of the AI tool used when the video was generated or AI-assisted.
	'source_media_video_id' => null, // Reference to an original media_video record when this video is edited or derived from another video.
	'source_url' => null, // Original external source URL when the video is imported or externally sourced.

	# Thumbnail / Variant / Recognition
	'thumbnail_media_image_id' => 'Im7K2pQ9xR4tV8zN', // Reference to the main thumbnail image for this video.
	'preview_media_image_id_list' => ['Im8K2pQ9xR4tV8zN'], // List of media_image IDs used as preview frames or screenshots for this video.
	'video_variant_list' => [ // Embedded list of derived video files such as alternate resolutions or compressed encodes.
		[
			'type_video_variant' => '720', // 720 = 720p encode
			'file_name' => 'first-massage-consultation-demo-720p.mp4',
			'file_extension' => 'mp4',
			'mime_type' => 'video/mp4',
			'file_size_byte' => 18432000,
			'duration' => 185,
			'width_pixel' => 1280,
			'height_pixel' => 720,
			'storage_path' => 'storage/upload/media/video/2026/07/first-massage-consultation-demo-720p.mp4',
			'storage_url' => null,
		],
	],
	'detected_person_list' => [ // Optional machine-detected person appearances in the video. Does not confirm identity by itself.
		[
			'detected_person_number' => 1,
			'confidence_level' => 0.87,
			'time_start' => 12,
			'time_end' => 38,
		],
	],
	'recognized_person_list' => [ // Optional recognized or proposed person/entity links in the video, subject to confirmation and privacy rules.
		[
			'target_collection' => 'practitioner_main',
			'target_id' => 'P8rC3mL7xT1qV5nK',
			'confidence_level' => 0.78,
			'is_confirmed' => false,
		],
	],

	# Handling
	'visibility_scope' => 'PUB', // Visibility rule for the video record.
	'status_review' => 'APR', // Moderation or approval review status for the video.
	'status_record_lifecycle' => 'ACT', // Database lifecycle state for the video record.

	# Audit
	'created_at' => $created_at, // UTC timestamp when this video record was created.
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA', // User ID that created this video record.
	'updated_at' => $updated_at, // UTC timestamp when this video record was last updated.
	'updated_by_user_id' => 'U2pR7vX4kT9mC5qL', // User ID that last updated this video record.
];

$media_video_field_order = [
	'_id',
	'video_title',
	'file_name',
	'file_extension',
	'mime_type',
	'file_size_byte',
	'duration',
	'width_pixel',
	'height_pixel',
	'storage_path',
	'storage_url',
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
	'videographer_user_id_list',
	'editor_user_id_list',
	'ai_tool_name',
	'source_media_video_id',
	'source_url',
	'thumbnail_media_image_id',
	'preview_media_image_id_list',
	'video_variant_list',
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

$media_video_embedded_structure = [
	'video_variant_list' => [
		'type_video_variant' => '720',
		'file_name' => 'sample-720p.mp4',
		'file_extension' => 'mp4',
		'mime_type' => 'video/mp4',
		'file_size_byte' => 18432000,
		'duration' => 185,
		'width_pixel' => 1280,
		'height_pixel' => 720,
		'storage_path' => 'storage/upload/media/video/sample-720p.mp4',
		'storage_url' => null,
	],
	'detected_person_list' => [
		'detected_person_number' => 1,
		'confidence_level' => 0.87,
		'time_start' => 12,
		'time_end' => 38,
	],
	'recognized_person_list' => [
		'target_collection' => 'practitioner_main',
		'target_id' => 'P8rC3mL7xT1qV5nK',
		'confidence_level' => 0.78,
		'is_confirmed' => false,
	],
];

$media_video_field_property = [
	'_id' => ['field_label' => 'Media Video ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],
	'video_title' => ['field_label' => 'Video Title', 'field_description' => 'Optional multilingual title for identifying the video internally or publicly.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true],
	'file_name' => ['field_label' => 'File Name', 'field_description' => 'Stored file name including extension.', 'is_mandatory' => true, 'max_character' => 255],
	'file_extension' => ['field_label' => 'File Extension', 'field_description' => 'Lowercase file extension without the leading period.', 'max_character' => 20],
	'mime_type' => ['field_label' => 'MIME Type', 'field_description' => 'Internet media type for the video file.', 'max_character' => 100],
	'file_size_byte' => ['field_label' => 'File Size Byte', 'field_description' => 'File size in bytes.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'BIGINT', 'min_number' => 0],
	'duration' => ['field_label' => 'Duration', 'field_description' => 'Video duration in seconds.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'width_pixel' => ['field_label' => 'Width Pixel', 'field_description' => 'Video frame width in pixels.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 1],
	'height_pixel' => ['field_label' => 'Height Pixel', 'field_description' => 'Video frame height in pixels.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 1],
	'storage_path' => ['field_label' => 'Storage Path', 'field_description' => 'Internal/local storage path used by the application.', 'max_character' => 500],
	'storage_url' => ['field_label' => 'Storage URL', 'field_description' => 'Public, CDN, or external URL when the file is served outside local/internal storage.', 'max_character' => 1000],
	'caption_text' => ['field_label' => 'Caption Text', 'field_description' => 'Optional multilingual caption for displaying the video in public contexts.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true],
	'tag_id_list' => ['field_label' => 'Tag ID List', 'field_description' => 'List of tag IDs attached to the video.', 'type_data' => 'A', 'is_relational' => true, 'is_indexed' => true],
	'related_organization_id_list' => ['field_label' => 'Related Organization ID List', 'field_description' => 'Organization IDs related to the video.', 'type_data' => 'A', 'is_relational' => true],
	'related_establishment_id_list' => ['field_label' => 'Related Establishment ID List', 'field_description' => 'Establishment IDs related to the video.', 'type_data' => 'A', 'is_relational' => true],
	'related_practitioner_id_list' => ['field_label' => 'Related Practitioner ID List', 'field_description' => 'Practitioner IDs related to the video.', 'type_data' => 'A', 'is_relational' => true],
	'related_service_id_list' => ['field_label' => 'Related Service ID List', 'field_description' => 'Service IDs related to the video.', 'type_data' => 'A', 'is_relational' => true],
	'related_product_id_list' => ['field_label' => 'Related Product ID List', 'field_description' => 'Product IDs related to the video.', 'type_data' => 'A', 'is_relational' => true],
	'level_nsfw' => ['field_label' => 'NSFW Level', 'field_description' => 'Video sensitivity classification for moderation and display handling.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'method_media_creation' => ['field_label' => 'Media Creation Method', 'field_description' => 'Classifies how the video was produced, such as recorded, AI generated, edited, composited, or imported.', 'type_field' => 'DDL', 'type_sql' => 'ENUM'],
	'creator_user_id_list' => ['field_label' => 'Creator User ID List', 'field_description' => 'User IDs credited with creating the video or visual work.', 'type_data' => 'A', 'is_relational' => true],
	'videographer_user_id_list' => ['field_label' => 'Videographer User ID List', 'field_description' => 'User IDs credited as videographers for the video.', 'type_data' => 'A', 'is_relational' => true],
	'editor_user_id_list' => ['field_label' => 'Editor User ID List', 'field_description' => 'User IDs credited with editing, compositing, or post-processing the video.', 'type_data' => 'A', 'is_relational' => true],
	'ai_tool_name' => ['field_label' => 'AI Tool Name', 'field_description' => 'Name of the AI tool used when the video was generated or AI-assisted.', 'max_character' => 100],
	'source_media_video_id' => ['field_label' => 'Source Media Video ID', 'field_description' => 'Reference to an original media_video record when this video is edited or derived from another video.', 'type_data' => 'S', 'is_relational' => true],
	'source_url' => ['field_label' => 'Source URL', 'field_description' => 'Original external source URL when the video is imported or externally sourced.', 'max_character' => 1000],
	'thumbnail_media_image_id' => ['field_label' => 'Thumbnail Media Image ID', 'field_description' => 'Reference to the main thumbnail image for this video.', 'type_data' => 'S', 'is_relational' => true],
	'preview_media_image_id_list' => ['field_label' => 'Preview Media Image ID List', 'field_description' => 'List of media_image IDs used as preview frames or screenshots for this video.', 'type_data' => 'A', 'is_relational' => true],
	'video_variant_list' => ['field_label' => 'Video Variant List', 'field_description' => 'Embedded list of derived video files such as alternate resolutions or compressed encodes.', 'type_data' => 'A', 'type_field' => 'JSE'],
	'detected_person_list' => ['field_label' => 'Detected Person List', 'field_description' => 'Optional machine-detected person appearances in the video. Does not confirm identity by itself.', 'type_data' => 'A', 'type_field' => 'JSE'],
	'recognized_person_list' => ['field_label' => 'Recognized Person List', 'field_description' => 'Optional recognized or proposed person/entity links in the video, subject to confirmation and privacy rules.', 'type_data' => 'A', 'type_field' => 'JSE'],
	'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Visibility rule for the video record.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'status_review' => ['field_label' => 'Review Status', 'field_description' => 'Moderation or approval review status for the video.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state for the video record.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when this video record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'User ID that created this video record.', 'type_data' => 'S', 'is_relational' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when this video record was last updated.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'User ID that last updated this video record.', 'type_data' => 'S', 'is_relational' => true],
];

$media_video_subfield_property = [
	'video_variant_list.type_video_variant' => ['field_label' => 'Video Variant Type', 'field_description' => 'Controlled rendition purpose or resolution code.', 'type_data' => 'S', 'is_mandatory' => true],
	'video_variant_list.file_name' => ['field_label' => 'Variant File Name', 'field_description' => 'Stored rendition filename.', 'type_data' => 'S', 'is_mandatory' => true],
	'video_variant_list.file_extension' => ['field_label' => 'Variant File Extension', 'field_description' => 'Normalized rendition extension.', 'type_data' => 'S', 'is_mandatory' => true],
	'video_variant_list.mime_type' => ['field_label' => 'Variant MIME Type', 'field_description' => 'Rendition media type.', 'type_data' => 'S', 'is_mandatory' => true],
	'video_variant_list.file_size_byte' => ['field_label' => 'Variant File Size', 'field_description' => 'Rendition size in bytes.', 'type_data' => 'I', 'min_number' => 0],
	'video_variant_list.duration' => ['field_label' => 'Variant Duration', 'field_description' => 'Rendition duration in seconds.', 'type_data' => 'I', 'min_number' => 0],
	'video_variant_list.width_pixel' => ['field_label' => 'Variant Width', 'field_description' => 'Rendition width in pixels.', 'type_data' => 'I', 'min_number' => 1],
	'video_variant_list.height_pixel' => ['field_label' => 'Variant Height', 'field_description' => 'Rendition height in pixels.', 'type_data' => 'I', 'min_number' => 1],
	'video_variant_list.storage_path' => ['field_label' => 'Variant Storage Path', 'field_description' => 'Private storage path for the rendition.', 'type_data' => 'S'],
	'video_variant_list.storage_url' => ['field_label' => 'Variant Storage URL', 'field_description' => 'Optional approved delivery URL.', 'type_data' => 'S'],
	'detected_person_list.detected_person_number' => ['field_label' => 'Detected Person Number', 'field_description' => 'Stable ordinal within this detection result.', 'type_data' => 'I'],
	'detected_person_list.confidence_level' => ['field_label' => 'Detection Confidence', 'field_description' => 'Detection confidence from zero to one.', 'type_data' => 'D', 'min_number' => 0, 'max_number' => 1],
	'detected_person_list.time_start' => ['field_label' => 'Detection Start', 'field_description' => 'Start time in seconds.', 'type_data' => 'I', 'min_number' => 0],
	'detected_person_list.time_end' => ['field_label' => 'Detection End', 'field_description' => 'End time in seconds.', 'type_data' => 'I', 'min_number' => 0],
	'recognized_person_list.target_collection' => ['field_label' => 'Recognized Target Collection', 'field_description' => 'Collection containing the proposed recognized person.', 'type_data' => 'S'],
	'recognized_person_list.target_id' => ['field_label' => 'Recognized Target ID', 'field_description' => 'Identifier of the proposed recognized person.', 'type_data' => 'S', 'is_relational' => true],
	'recognized_person_list.confidence_level' => ['field_label' => 'Recognition Confidence', 'field_description' => 'Recognition confidence from zero to one.', 'type_data' => 'D', 'min_number' => 0, 'max_number' => 1],
	'recognized_person_list.is_confirmed' => ['field_label' => 'Recognition Confirmed', 'field_description' => 'Whether an authorized review confirmed the match.', 'type_data' => 'B'],
];

$media_video_index_list = [
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

$media_video_boundary = [
    'owns' => [
        'the media_video record fields and embedded structures documented in this file',
    ],
    'reference_field_list' => [
        'tag_id_list',
        'related_organization_id_list',
        'related_establishment_id_list',
        'related_practitioner_id_list',
        'related_service_id_list',
        'related_product_id_list',
        'creator_user_id_list',
        'videographer_user_id_list',
        'editor_user_id_list',
        'source_media_video_id',
        'thumbnail_media_image_id',
        'preview_media_image_id_list',
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
    'media_video_default' => $media_video_default,
    'media_video' => $media_video,
    'media_video_field_order' => $media_video_field_order,
    'media_video_embedded_structure' => $media_video_embedded_structure,
    'media_video_field_property' => $media_video_field_property,
    'media_video_subfield_property' => $media_video_subfield_property,
    'media_video_index_list' => $media_video_index_list,
    'media_video_boundary' => $media_video_boundary,
];
