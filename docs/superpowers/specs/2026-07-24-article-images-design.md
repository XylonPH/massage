# Article Images — Design

## Context

This is sub-project B of a larger feedback batch covering the article authoring flow and the establishment contribution flow. Sub-project A (article form quick fixes: explicit audience/NSFW selection, NsfwLevel enum alignment, empty-body draft saves) shipped separately. This spec covers only article image support: embedding images in the article body, marking one as "featured" for listings, and an optional separate hero image.

Today, articles have no image support at all. Research found the sanitizer (`ArticleContent::sanitize()`) already allow-lists `<img>` and `<figure data-media-image-id="...">` tags with a `src` pattern restricted to `^/media/image/[A-Za-z0-9]{16}$`, and `Article` already has an unused `cover_media_image_id` fillable field — both clearly anticipating a `media_image` collection that was never actually built. A full data-shape design for that collection already exists at `data/structure_guide/media_image.php` (a documentation-only structure guide, not runtime code), and the project's architecture doc (`docs/04-architecture/database-structure.txt`) names `media_image`/`media_video` as the intended shared media collections for articles, establishments, and other entities.

There is no existing file-upload mechanism anywhere in this codebase (no `WithFileUploads`, no `Storage::disk()` calls, no `<input type="file">`) — this is greenfield. No image-processing library is installed. No Azure Blob Storage disk is configured despite Azure being the deployment target ([[deployment_target_azure]]).

## Decisions

- **Build the full `media_image` structure-guide shape now**, not a trimmed-down version — even though only a subset is used by articles today, this avoids a schema migration when establishment photos or other entities adopt the same collection later (per the architecture doc's stated intent that `media_image` is shared across entities).
- **Exception: `detected_person_list` / `recognized_person_list` are reserved but left unpopulated.** These two fields stay in the schema (so no future migration is needed) but no face-detection/recognition service is integrated in this work. Populating them is explicitly out of scope — it would require a paid external computer-vision service and carries real privacy/compliance weight that deserves its own dedicated design discussion, not a rider on this feature.
- **Storage: local disk now, Azure later.** Files go on Laravel's built-in `public` disk (`storage/app/public/media/image/...`, served via the existing `storage:link` mechanism). Swapping to Azure Blob Storage later is a disk-config change, not an application-code change, and is explicitly deferred.
- **Image count cap: 10 per article**, matching this app's existing convention of capping repeatable lists (author credits, sources, related-record lists) at similar orders of magnitude.
- **Images embedded inline via rich text**, using Tiptap's official `@tiptap/extension-image` (not currently installed) — matching how the article body editor already works (Tiptap-based), rather than a separate non-inline gallery.
- **Featured image and hero image are two distinct concepts:**
  - "Featured image" = one of the article's own uploaded/inline images, marked by the author for use in listing cards. New field `Article.featured_media_image_id`.
  - "Hero image" = a separate, larger image uploaded independently of the inline body, shown as a banner on the article's own page. Reuses the existing-but-unused `Article.cover_media_image_id` field.
- **Upload requires an existing article.** A brand-new article must be saved as a draft at least once (existing "Save Draft" flow) before the image-upload control becomes available, since a `MediaImage` record must reference an owning article ID. No "orphaned upload before first save" concept is introduced.
- **One thumbnail variant is auto-generated per upload**, via a newly-added `intervention/image` package (no image-processing library is currently installed).
- **Validation limits:** JPEG/PNG/WebP only, max 5MB per file, max 4000px on the longest edge.

## Data Model

### New collection/model: `MediaImage` (`app/Models/Media/MediaImage.php`, collection `media_image`)

Built to the shape already documented in `data/structure_guide/media_image.php`. Fields, grouped:

**File/technical** (populated by the upload handler):
- `file_name`, `file_extension`, `mime_type`, `file_size_byte`, `width_pixel`, `height_pixel`
- `storage_path` (e.g. `storage/app/public/media/image/2026/07/<random16>.webp`)
- `storage_url` (nullable — reserved for a future CDN/external-storage scenario; null for local-disk-served images, which are served via the `/media/image/{id}` route instead)

**Description** (multilingual, same `{lang: {text, method_translation, status_review}}` shape used elsewhere in this app — e.g. `Article.article_title`):
- `image_title`, `alt_text`, `caption_text` — only `alt_text` is required at upload time (accessibility minimum); `image_title`/`caption_text` are optional author-entered fields.

**Classification/relationships:**
- `tag_id_list` (nullable, reuses the existing `Tag` model/collection — same tags articles already use)
- `related_article_id_list`, `related_establishment_id_list`, `related_practitioner_id_list`, `related_service_id_list`, `related_product_id_list`, `related_organization_id_list` (nullable arrays; only `related_article_id_list` is populated by this work — the others exist per the shared-collection design but nothing in this plan writes to them)
- `level_nsfw` (nullable string, same codes as `Article.level_nsfw` — `N`/`M`/`S`/`E` — defaults to `N`)

**Credit/source:**
- `method_media_creation` (code: `PH`=photographed, `AI`=generated, `IL`=illustrated, `ED`=edited, `IMP`=imported — defaults to `IMP` since this work only supports direct upload, not in-app creation)
- `creator_user_id_list` (defaults to `[uploading user's ID]`)
- `photographer_user_id_list`, `editor_user_id_list`, `ai_tool_name`, `source_media_image_id`, `source_url` (all nullable, unpopulated by this work's UI — reserved fields per the shared shape, editable later if a credit-entry UI is added)

**Variants:**
- `image_variant_list` — embedded array, one entry per rendition. This work generates exactly one variant on upload: a thumbnail (max 480px on the long edge, same format as source), with the same per-variant shape as the structure guide (`type_image_variant`, `file_name`, `mime_type`, `file_size_byte`, `width_pixel`/`height_pixel`, `storage_path`, `storage_url`).

**Detection/recognition (reserved, unpopulated):**
- `detected_person_list`, `recognized_person_list` — present in the model's fillable/cast list per the structure guide shape, but no code in this work ever writes to them. Always empty arrays on records created by this feature.

**Lifecycle/audit** (matching the project's standard pattern used by `Article`, `Quote`, etc.):
- `visibility_scope`, `status_review`, `status_record_lifecycle`
- `created_at`/`created_by_user_id`, `updated_at`/`updated_by_user_id`

### `Article` model changes

- `featured_media_image_id` (new nullable string field, `size:16` like other ID references in this app) — added to `Article`'s `#[Fillable]` list.
- `cover_media_image_id` (already present in `Article`'s fillable list per prior research — no schema change needed, just the first real code that populates/reads it).

## Upload & Embedding Flow

1. **Upload endpoint:** `POST /workspace/article/{article}/media` (new route, owner-authorized like other article routes). Accepts one image file (`multipart/form-data`), validates type/size/dimensions, stores it on the `public` disk under `media/image/{Y}/{m}/{random16}.{ext}`, generates the thumbnail variant via `intervention/image`, creates the `MediaImage` record (`related_article_id_list` = `[article_id]`, `creator_user_id_list` = `[uploader_id]`), and returns JSON `{ id, url, thumbnail_url }` for the frontend to use.
2. **Inline insertion:** the article editor toolbar gains an "Insert image" button (Tiptap's `@tiptap/extension-image`, newly added as a package dependency). Selecting a file uploads it via the endpoint above, then inserts `<figure data-media-image-id="{id}"><img src="/media/image/{id}"></figure>` at the cursor — matching the exact tag/attribute shape `ArticleContent::sanitize()` already allow-lists, so the sanitizer needs no changes.
3. **Gallery strip:** below the rich-text editor, a strip lists every `MediaImage` whose `related_article_id_list` contains the current article's ID (thumbnail + filename), each with a "Set as featured" button that sets `Article.featured_media_image_id` to that image's ID (not required to also be embedded inline in the body — an author can upload an image, use it only as the featured/listing image, and never place it in the body text).
4. **Hero image:** a separate single-image upload control (same endpoint, same validation) sets `Article.cover_media_image_id` directly — independent of the gallery/featured mechanism.
5. **Serving:** `GET /media/image/{id}` (new route) streams the original file from the `public` disk with the correct `Content-Type`; `GET /media/image/{id}/thumbnail` streams the thumbnail variant. Both are public (no auth) since article images are meant to be publicly viewable once the article is published — the ID itself (16 random chars) is the only access control, matching this app's existing pattern for other public-facing record IDs.

## Display Changes

- **Article listing cards** (`workspace/article/index.blade.php`, and the public article listing view): render `featured_media_image_id`'s thumbnail if set; otherwise render exactly as today (no image placeholder is introduced).
- **Article show page** (public article view): if `cover_media_image_id` is set, render it as a hero banner above the title; otherwise the page is unchanged from today.

## Testing

- Feature test: uploading a valid JPEG/PNG/WebP under 5MB to an existing article's draft creates a `MediaImage` record with `related_article_id_list` containing the article's ID, and returns a usable image ID.
- Feature test: uploading to an article the user doesn't own is forbidden (403), matching existing article-ownership authorization.
- Feature test: uploading an 11th image to an article that already has 10 is rejected with a validation error.
- Feature test: uploading a file over 5MB, or a disallowed MIME type (e.g. `.gif`, `.svg`), is rejected with a validation error.
- Feature test: an uploaded image's thumbnail variant is generated and has the expected max dimension.
- Feature test: setting `featured_media_image_id` to an image not belonging to the article is rejected.
- Feature test: `GET /media/image/{id}` and `/media/image/{id}/thumbnail` serve the correct file content and `Content-Type` for an existing image, and 404 for an unknown ID.
- Feature test: an article's sanitized body containing `<figure data-media-image-id="...">` for an image that belongs to the article round-trips correctly (already-existing sanitizer behavior, verified end-to-end with a real uploaded image ID rather than a synthetic one).
- Feature test: article listing card renders the featured image's thumbnail when set, and renders unchanged (no image) when not set.
- Feature test: article show page renders the hero image when `cover_media_image_id` is set, and renders unchanged when not set.
- Unit test: `detected_person_list`/`recognized_person_list` are always empty arrays on newly created `MediaImage` records (documents the "reserved but unpopulated" decision as an explicit, testable contract).

## Out of scope

- Face detection/recognition (`detected_person_list`/`recognized_person_list` population) — reserved schema only, per decision above.
- `MediaVideo` / video support — not requested, not part of this work.
- Azure Blob Storage integration — deferred; local disk only for now.
- Establishment photos / logos (sub-project D) — a separate spec, though it will likely reuse this same `MediaImage` model once built.
- Any UI for editing the credit/source fields (`photographer_user_id_list`, `ai_tool_name`, `source_url`, etc.) — reserved fields, not editable through this work's UI.
- Cropping, rotation, or any client-side image editing before upload.
