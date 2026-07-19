<?php
/**
 * Title: Massage Nexus Media Video Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: media_video
 * Version: 1.01
 * This file is a PHP-readable visual structure guide.
 * It is not a seed file, not a runtime migration script, and not a generated
 * production schema. It exists so the database structure can be reviewed in a
 * familiar PHP array format before implementation.
 *
 * Layer rule:
 * - *_record_default contains defaults for actual stored record fields only.
 * - *_field_property describes schema/field metadata only.
 * - Do not mix field-definition metadata into record defaults.
 * Current scope:
 * - media_video stores video file records and video metadata.
 * - Video variants are embedded in video_variant_list; no media_video_variant
 *   collection is created in this version.
 */

# Variable
$created_at = '2026-07-06T00:00:00Z';
$updated_at = '2026-07-06T00:00:00Z';

/**
 * Default field-property values for this structure guide.
 * These describe field-definition metadata, not stored record defaults.
 */
$field_property_default = [
	'type_data' => 'S', // default runtime value shape is String
	'type_field' => 'TXT', // default suggested UI control is Text Box
	'format_text' => 'TXT', // default text format is Plain Text
	'is_translatable' => false, // default: stored field value is not multilingual
	'is_mandatory' => false, // default: not required
	'is_relational' => false, // default: not normally a reference field
	'is_indexed' => false, // default: no common indexing suggestion
	'status_record_lifecycle' => 'ACT', // default: Active
	'visibility_scope' => 'INH', // default: inherit visibility
	'level_nsfw' => 'N', // default: None
];

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
	'fil' => [
		'text' => 'Halimbawang teksto',
		'method_translation' => 'HUM',
		'status_review' => 'A',
	],
];

/**
 * Actual record-level defaults for media_video.
 */
$media_video_record_default = [
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
	'status_review' => 'P',
	'status_record_lifecycle' => 'ACT',
];

/**
 * media_video sample record.
 */
$media_video = [
	# Primary
	'_id' => 8001,

	# Core File
	'video_title' => [
		'eng' => ['text' => 'First Massage Consultation Demo', 'method_translation' => 'HUM', 'status_review' => 'A'],
	],
	'file_name' => 'first-massage-consultation-demo.mp4',
	'file_extension' => 'mp4',
	'mime_type' => 'video/mp4',
	'file_size_byte' => 52428800,
	'duration' => 185, // duration in seconds
	'width_pixel' => 1920,
	'height_pixel' => 1080,
	'storage_path' => 'storage/upload/media/video/2026/07/first-massage-consultation-demo.mp4',
	'storage_url' => null,

	# Description
	'caption_text' => [
		'eng' => ['text' => 'A short demonstration of asking about pressure, comfort, and allergies before a massage.', 'method_translation' => 'HUM', 'status_review' => 'A'],
	],

	# Classification / Relationship
	'tag_id_list' => [301, 602],
	'related_organization_id_list' => [201],
	'related_establishment_id_list' => [301],
	'related_practitioner_id_list' => [401],
	'related_service_id_list' => [601],
	'related_product_id_list' => [],
	'level_nsfw' => 'N',

	# Credit / Source
	'method_media_creation' => 'VD', // VD = video recorded, AI = AI generated, ED = edited/composited, IMP = imported
	'creator_user_id_list' => [507],
	'videographer_user_id_list' => [507],
	'editor_user_id_list' => [506],
	'ai_tool_name' => null,
	'source_media_video_id' => null,
	'source_url' => null,

	# Thumbnail / Variant / Recognition
	'thumbnail_media_image_id' => 7003,
	'preview_media_image_id_list' => [7004, 7005, 7006],
	'video_variant_list' => [
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
	'detected_person_list' => [
		[
			'detected_person_number' => 1,
			'confidence_level' => 0.87,
			'time_start' => 12,
			'time_end' => 38,
		],
	],
	'recognized_person_list' => [
		[
			'target_collection' => 'practitioner_main',
			'target_id' => 401,
			'confidence_level' => 0.78,
			'is_confirmed' => false,
		],
	],

	# Handling
	'visibility_scope' => 'PUB',
	'status_review' => 'A',
	'status_record_lifecycle' => 'ACT',

	# Audit
	'created_at' => $created_at,
	'created_by_user_id' => 507,
	'updated_at' => $updated_at,
	'updated_by_user_id' => 506,
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
		'target_id' => 401,
		'confidence_level' => 0.78,
		'is_confirmed' => false,
	],
];

$media_video_field_property = [
	'_id' => ['field_label' => 'Media Video ID', 'field_description' => 'Physical MongoDB identity for the media_video record.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],
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
	'source_media_video_id' => ['field_label' => 'Source Media Video ID', 'field_description' => 'Reference to an original media_video record when this video is edited or derived from another video.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
	'source_url' => ['field_label' => 'Source URL', 'field_description' => 'Original external source URL when the video is imported or externally sourced.', 'max_character' => 1000],
	'thumbnail_media_image_id' => ['field_label' => 'Thumbnail Media Image ID', 'field_description' => 'Reference to the main thumbnail image for this video.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
	'preview_media_image_id_list' => ['field_label' => 'Preview Media Image ID List', 'field_description' => 'List of media_image IDs used as preview frames or screenshots for this video.', 'type_data' => 'A', 'is_relational' => true],
	'video_variant_list' => ['field_label' => 'Video Variant List', 'field_description' => 'Embedded list of derived video files such as alternate resolutions or compressed encodes.', 'type_data' => 'A', 'type_field' => 'JSE'],
	'detected_person_list' => ['field_label' => 'Detected Person List', 'field_description' => 'Optional machine-detected person appearances in the video. Does not confirm identity by itself.', 'type_data' => 'A', 'type_field' => 'JSE'],
	'recognized_person_list' => ['field_label' => 'Recognized Person List', 'field_description' => 'Optional recognized or proposed person/entity links in the video, subject to confirmation and privacy rules.', 'type_data' => 'A', 'type_field' => 'JSE'],
	'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Visibility rule for the video record.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'status_review' => ['field_label' => 'Review Status', 'field_description' => 'Moderation or approval review status for the video.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state for the video record.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when this video record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'User ID that created this video record.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when this video record was last updated.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'User ID that last updated this video record.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
];
