# Article Images Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Let authors embed images inline in article bodies, mark one as a "featured" image for listings, and set a separate hero image for the article page — backed by a new `MediaImage` model built to the project's existing `media_image` structure guide.

**Architecture:** A new MongoDB-backed `MediaImage` Eloquent model (mirrors `ArticleBody`'s pattern: `HasSparseDefaults`, 16-char random ID). Files are stored on Laravel's local `public` disk (Azure Blob Storage is an explicitly deferred future swap). Images are uploaded through a new endpoint on the existing `ArticleController`, embedded inline via a custom Tiptap node that matches the exact `<figure data-media-image-id>`/`<img>` shape the sanitizer already allow-lists, and served through a new public `/media/image/{id}` route.

**Tech Stack:** Laravel 13, MongoDB (`mongodb/laravel-mongodb`), Livewire (unrelated to this feature), Tiptap 3 (rich text editor, already installed), `intervention/image` (new dependency, for thumbnail generation), PHPUnit.

## Global Constraints

- Build the full `media_image` shape from `data/structure_guide/media_image.php`, **except** `detected_person_list`/`recognized_person_list` stay in the schema but are never populated by any code in this plan (always empty arrays on records this plan creates).
- Files stored on Laravel's local `public` disk. No Azure Blob Storage integration — explicitly deferred.
- Max 10 images per article (server-validated).
- Upload accepts JPEG/PNG/WebP only, max 5MB, max 4000px on the longest edge.
- One thumbnail variant per image, max 480px on the longest edge, via `intervention/image`.
- Uploading requires an already-saved article (no upload UI/endpoint works for a brand-new, never-saved article).
- "Featured image" (`Article.featured_media_image_id`) and "hero image" (`Article.cover_media_image_id`, field already exists, unused) are separate concepts — do not conflate them.
- Inline embedded markup must exactly match what `ArticleContent::sanitize()` (`apps/web/app/Support/Article/ArticleContent.php`) already allow-lists: `<figure data-media-image-id="{16charid}"><img src="/media/image/{16charid}" ...></figure>`. Do not modify the sanitizer's allow-list — it already supports this shape.
- Run tests from `apps/web/`: `php artisan test --filter <TestClass>`.
- This repo works directly on the `main` branch (no feature branches/worktrees), and every commit is auto-pushed to the real GitHub `origin/main` immediately — stage only the exact files each task names, never `git add -A`/`git add .` (this repo has other concurrent agents with real uncommitted work in the same tree).

---

### Task 1: `MediaImage` model

**Files:**
- Create: `apps/web/app/Models/Media/MediaImage.php`
- Modify: `data/structure_guide/media_image.php` (add the missing `related_article_id_list` field — the existing guide has `related_organization/establishment/practitioner/service/product_id_list` but omits an article relation, which this feature needs)
- Test: `apps/web/tests/Feature/Media/MediaImageTest.php`

**Interfaces:**
- Produces: `App\Models\Media\MediaImage` — Mongo model, collection `media_image`, 16-char string primary key (`Str::random(16)`, set on `creating`), `HasSparseDefaults` trait. Consumed by every later task in this plan.

- [ ] **Step 1: Write the failing test**

Create `apps/web/tests/Feature/Media/MediaImageTest.php`:

```php
<?php

namespace Tests\Feature\Media;

use App\Models\Media\MediaImage;
use Tests\TestCase;

class MediaImageTest extends TestCase
{
    protected function tearDown(): void
    {
        MediaImage::query()->delete();
        parent::tearDown();
    }

    public function test_creating_a_media_image_applies_sparse_defaults(): void
    {
        $image = MediaImage::query()->create([
            'file_name' => 'sample.webp',
            'file_extension' => 'webp',
            'mime_type' => 'image/webp',
            'file_size_byte' => 1024,
            'width_pixel' => 800,
            'height_pixel' => 600,
            'storage_path' => 'media/image/2026/07/sample.webp',
            'alt_text' => ['eng' => ['text' => 'A sample image.']],
            'created_by_user_id' => 'U0000000000000001',
        ]);

        $this->assertSame(16, strlen((string) $image->getKey()));
        $this->assertSame('N', $image->level_nsfw);
        $this->assertSame('PND', $image->status_review);
        $this->assertSame('ACT', $image->status_record_lifecycle);
        $this->assertSame('INH', $image->visibility_scope);
        $this->assertSame([], $image->tag_id_list);
        $this->assertSame([], $image->related_article_id_list);
        $this->assertSame(800, $image->width_pixel);
        $this->assertSame('A sample image.', $image->localized('alt_text'));
    }

    public function test_detected_and_recognized_person_lists_are_always_empty_on_new_records(): void
    {
        $image = MediaImage::query()->create([
            'file_name' => 'sample.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/sample.webp',
            'alt_text' => ['eng' => ['text' => 'A sample image.']],
            'created_by_user_id' => 'U0000000000000001',
        ]);

        $this->assertSame([], $image->detected_person_list);
        $this->assertSame([], $image->recognized_person_list);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run (from `apps/web/`): `php artisan test --filter MediaImageTest`
Expected: FAIL — `App\Models\Media\MediaImage` does not exist yet.

- [ ] **Step 3: Create the model**

Create `apps/web/app/Models/Media/MediaImage.php`:

```php
<?php

namespace App\Models\Media;

use App\Models\Concerns\HasSparseDefaults;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable([
    'image_title', 'file_name', 'file_extension', 'mime_type', 'file_size_byte',
    'width_pixel', 'height_pixel', 'storage_path', 'storage_url',
    'alt_text', 'caption_text',
    'tag_id_list', 'related_organization_id_list', 'related_establishment_id_list',
    'related_practitioner_id_list', 'related_service_id_list', 'related_product_id_list',
    'related_article_id_list', 'level_nsfw',
    'method_media_creation', 'creator_user_id_list', 'photographer_user_id_list',
    'editor_user_id_list', 'ai_tool_name', 'source_media_image_id', 'source_url',
    'image_variant_list', 'detected_person_list', 'recognized_person_list',
    'visibility_scope', 'status_review', 'status_record_lifecycle',
    'created_by_user_id', 'updated_by_user_id',
])]
class MediaImage extends Model
{
    use HasSparseDefaults;

    protected $connection = 'mongodb';

    protected $table = 'media_image';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (self $image): void {
            $image->{$image->getKeyName()} ??= Str::random(16);
        });
    }

    protected function casts(): array
    {
        return [
            'file_size_byte' => 'integer',
            'width_pixel' => 'integer',
            'height_pixel' => 'integer',
        ];
    }

    public function localized(string $field, string $locale = 'eng'): string
    {
        $values = $this->getAttribute($field);

        return is_array($values) ? (string) ($values[$locale]['text'] ?? $values['eng']['text'] ?? '') : '';
    }

    /** @return array<string, mixed> */
    protected function sparseDefaults(): array
    {
        return [
            'tag_id_list' => [],
            'related_organization_id_list' => [],
            'related_establishment_id_list' => [],
            'related_practitioner_id_list' => [],
            'related_service_id_list' => [],
            'related_product_id_list' => [],
            'related_article_id_list' => [],
            'creator_user_id_list' => [],
            'photographer_user_id_list' => [],
            'editor_user_id_list' => [],
            'source_media_image_id' => null,
            'level_nsfw' => 'N',
            'image_variant_list' => [],
            'detected_person_list' => [],
            'recognized_person_list' => [],
            'visibility_scope' => 'INH',
            'status_review' => 'PND',
            'status_record_lifecycle' => 'ACT',
        ];
    }
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --filter MediaImageTest`
Expected: PASS

- [ ] **Step 5: Update the structure guide doc**

In `data/structure_guide/media_image.php`, add `related_article_id_list` alongside the other `related_*_id_list` fields in these 4 places (matching the existing pattern for `related_service_id_list` exactly, just for articles):
1. `$media_image_default` (after `'related_product_id_list' => [],`): add `'related_article_id_list' => [],`
2. `$media_image` sample record (after the `related_product_id_list` line): add `'related_article_id_list' => [], // Article IDs related to the image.`
3. `$media_image_field_order` (after `'related_product_id_list',`): add `'related_article_id_list',`
4. `$media_image_field_property` (after the `related_product_id_list` entry): add `'related_article_id_list' => ['field_label' => 'Related Article ID List', 'field_description' => 'Article IDs related to the image.', 'type_data' => 'A', 'is_relational' => true],`

Also bump the file header: `Version: 1.20` → `Version: 1.30`, and update `$updated_at = '2026-07-21T08:49:01Z';` to today's UTC timestamp (use `date -u +%Y-%m-%dT%H:%M:%SZ` to get the real current time — do not fabricate a timestamp).

- [ ] **Step 6: Commit**

```bash
git add apps/web/app/Models/Media/MediaImage.php apps/web/tests/Feature/Media/MediaImageTest.php data/structure_guide/media_image.php
git commit -m "feat(media): add MediaImage model built to the media_image structure guide"
```

---

### Task 2: Image upload endpoint

**Files:**
- Modify: `apps/web/composer.json` / `composer.lock` (via `composer require`, not manual edit)
- Create: `apps/web/app/Actions/Media/StoreUploadedArticleImage.php`
- Modify: `apps/web/app/Http/Controllers/Web/Workspace/ArticleController.php` (add `storeMedia` method)
- Modify: `apps/web/routes/web.php` (add route inside the existing `workspace/article` group)
- Modify: `apps/web/lang/eng/article.php` (add 1 message key)
- Test: `apps/web/tests/Feature/Article/ArticleMediaUploadTest.php`

**Interfaces:**
- Consumes: `App\Models\Media\MediaImage` (Task 1), `ArticleController::authorizeOwner(Request, Article): void` (private method already on the class, reused directly since this task adds a method to the same class).
- Produces: `StoreUploadedArticleImage::execute(UploadedFile $file, string $altText, Article $article, User $user): MediaImage`, route `workspace.article.media.store` (`POST /workspace/article/{article}/media`), returning JSON `{id, url, thumbnail_url}`. Consumed by Tasks 4, 5, 6.

- [ ] **Step 1: Install intervention/image**

Run (from `apps/web/`): `composer require intervention/image:^3.0`
Expected: package installs cleanly, `composer.json`/`composer.lock` updated automatically.

- [ ] **Step 2: Write the failing test**

Create `apps/web/tests/Feature/Article/ArticleMediaUploadTest.php`:

```php
<?php

namespace Tests\Feature\Article;

use App\Models\Article\Article;
use App\Models\Media\MediaImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleMediaUploadTest extends TestCase
{
    protected function tearDown(): void
    {
        MediaImage::query()->delete();
        Article::query()->delete();
        parent::tearDown();
    }

    private function createArticle(User $user): Article
    {
        $this->actingAs($user)->post('/workspace/article', [
            'article_title' => 'An Article With Images',
            'article_slug' => 'an-article-with-images',
            'short_description' => 'A concise description of this useful Massage Nexus article.',
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'level_nsfw' => 'N',
            'article_body' => '<p>'.str_repeat('Word ', 25).'</p>',
            'author_credit_list' => [['user_id' => (string) $user->getKey(), 'display_name' => $user->publicName()]],
        ]);

        return Article::query()->firstOrFail();
    }

    public function test_owner_can_upload_an_image_to_their_article(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $article = $this->createArticle($user);

        $response = $this->actingAs($user)->post("/workspace/article/{$article->getKey()}/media", [
            'image' => UploadedFile::fake()->image('photo.jpg', 800, 600),
            'alt_text' => 'A descriptive alt text.',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['id', 'url', 'thumbnail_url']);
        $image = MediaImage::query()->firstOrFail();
        $this->assertSame([(string) $article->getKey()], $image->related_article_id_list);
        $this->assertSame([(string) $user->getKey()], $image->creator_user_id_list);
        Storage::disk('public')->assertExists($image->storage_path);
        Storage::disk('public')->assertExists($image->image_variant_list[0]['storage_path']);
        // Source was 800x600 (landscape); thumbnail's long edge (width) must be scaled down to the 480px cap.
        $this->assertSame(480, $image->image_variant_list[0]['width_pixel']);
        $this->assertLessThanOrEqual(480, $image->image_variant_list[0]['height_pixel']);
    }

    public function test_non_owner_cannot_upload_an_image(): void
    {
        Storage::fake('public');
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $article = $this->createArticle($owner);

        $this->actingAs($other)->post("/workspace/article/{$article->getKey()}/media", [
            'image' => UploadedFile::fake()->image('photo.jpg', 800, 600),
            'alt_text' => 'A descriptive alt text.',
        ])->assertForbidden();
    }

    public function test_upload_rejects_a_file_over_the_size_limit(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $article = $this->createArticle($user);

        $this->actingAs($user)->post("/workspace/article/{$article->getKey()}/media", [
            'image' => UploadedFile::fake()->image('photo.jpg', 800, 600)->size(6000),
            'alt_text' => 'A descriptive alt text.',
        ])->assertSessionHasErrors('image');
    }

    public function test_upload_rejects_a_disallowed_mime_type(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $article = $this->createArticle($user);

        $this->actingAs($user)->post("/workspace/article/{$article->getKey()}/media", [
            'image' => UploadedFile::fake()->create('animation.gif', 100, 'image/gif'),
            'alt_text' => 'A descriptive alt text.',
        ])->assertSessionHasErrors('image');
    }

    public function test_upload_rejects_an_eleventh_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $article = $this->createArticle($user);
        for ($i = 0; $i < 10; $i++) {
            MediaImage::query()->create([
                'file_name' => "existing-{$i}.webp",
                'mime_type' => 'image/webp',
                'storage_path' => "media/image/existing-{$i}.webp",
                'alt_text' => ['eng' => ['text' => 'Existing image.']],
                'related_article_id_list' => [(string) $article->getKey()],
                'created_by_user_id' => (string) $user->getKey(),
            ]);
        }

        $this->actingAs($user)->post("/workspace/article/{$article->getKey()}/media", [
            'image' => UploadedFile::fake()->image('photo.jpg', 800, 600),
            'alt_text' => 'A descriptive alt text.',
        ])->assertStatus(422);
    }
}
```

- [ ] **Step 3: Run test to verify it fails**

Run: `php artisan test --filter ArticleMediaUploadTest`
Expected: FAIL — route `workspace.article.media.store` does not exist yet (404s).

- [ ] **Step 4: Add the upload message key**

In `apps/web/lang/eng/article.php`, add near the other article-editor keys:

```php
    'media_limit_reached' => 'This article already has the maximum of 10 images.',
```

- [ ] **Step 5: Create the storage action**

Create `apps/web/app/Actions/Media/StoreUploadedArticleImage.php`:

```php
<?php

namespace App\Actions\Media;

use App\Models\Article\Article;
use App\Models\Media\MediaImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class StoreUploadedArticleImage
{
    private const THUMBNAIL_MAX_DIMENSION = 480;

    public function execute(UploadedFile $file, string $altText, Article $article, User $user): MediaImage
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $datePath = now()->format('Y/m');
        $baseName = Str::random(16);
        $storedPath = "media/image/{$datePath}/{$baseName}.{$extension}";

        Storage::disk('public')->put($storedPath, file_get_contents($file->getRealPath()));

        [$width, $height] = getimagesize($file->getRealPath());

        $manager = ImageManager::gd();
        $thumbnail = $manager->read($file->getRealPath())->scaleDown(width: self::THUMBNAIL_MAX_DIMENSION);
        $thumbnailBinary = (string) $thumbnail->encodeByExtension($extension);
        $thumbnailPath = "media/image/{$datePath}/{$baseName}-thumb.{$extension}";
        Storage::disk('public')->put($thumbnailPath, $thumbnailBinary);

        return MediaImage::query()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_extension' => $extension,
            'mime_type' => $file->getMimeType(),
            'file_size_byte' => $file->getSize(),
            'width_pixel' => $width,
            'height_pixel' => $height,
            'storage_path' => $storedPath,
            'alt_text' => ['eng' => ['text' => $altText]],
            'related_article_id_list' => [(string) $article->getKey()],
            'creator_user_id_list' => [(string) $user->getKey()],
            'method_media_creation' => 'IMP',
            'image_variant_list' => [[
                'type_image_variant' => 'TH',
                'file_name' => basename($thumbnailPath),
                'file_extension' => $extension,
                'mime_type' => $file->getMimeType(),
                'file_size_byte' => strlen($thumbnailBinary),
                'width_pixel' => $thumbnail->width(),
                'height_pixel' => $thumbnail->height(),
                'storage_path' => $thumbnailPath,
                'storage_url' => null,
            ]],
            'created_by_user_id' => (string) $user->getKey(),
        ]);
    }
}
```

- [ ] **Step 6: Add the controller method**

In `apps/web/app/Http/Controllers/Web/Workspace/ArticleController.php`, add these imports near the top (alongside the existing `use App\Actions\Article\SaveArticleDraft;` line):

```php
use App\Actions\Media\StoreUploadedArticleImage;
use App\Models\Media\MediaImage;
```

Then add this method (place it right after the existing `store()` method):

```php
    public function storeMedia(Request $request, Article $article, StoreUploadedArticleImage $store): JsonResponse
    {
        $this->authorizeOwner($request, $article);

        $existingCount = MediaImage::query()->where('related_article_id_list', (string) $article->getKey())->count();
        abort_if($existingCount >= 10, 422, __('article.media_limit_reached'));

        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:5120', 'dimensions:max_width=4000,max_height=4000'],
            'alt_text' => ['required', 'string', 'max:255'],
        ]);

        $image = $store->execute($validated['image'], $validated['alt_text'], $article, $request->user());

        return response()->json([
            'id' => (string) $image->getKey(),
            'url' => route('media.image.show', ['media_image' => $image->getKey()]),
            'thumbnail_url' => route('media.image.thumbnail', ['media_image' => $image->getKey()]),
        ]);
    }
```

Note: `JsonResponse` is already imported in this file (added by a prior commit for the `lookup()` method) — verify with a quick grep before adding a duplicate `use` line.

- [ ] **Step 7: Add the route**

In `apps/web/routes/web.php`, inside the `workspace/article` group (after the `store` route, around line 146), add:

```php
        Route::post('/{article}/media', [WorkspaceArticleController::class, 'storeMedia'])->middleware('throttle:30,1')->name('media.store');
```

- [ ] **Step 8: Run test to verify it passes**

Run: `php artisan test --filter ArticleMediaUploadTest`
Expected: still FAIL at this point — the `media.image.show`/`media.image.thumbnail` routes used inside `storeMedia()` don't exist yet (Task 3). Confirm the failure is specifically a `RouteNotFoundException` for those route names, not anything else, before proceeding — this is expected and will be resolved by Task 3, not by this task. Do not add those routes here; that is Task 3's job, kept separate because it's an independently reviewable deliverable (public serving, no auth).

- [ ] **Step 9: Commit**

```bash
git add apps/web/composer.json apps/web/composer.lock apps/web/app/Actions/Media/StoreUploadedArticleImage.php apps/web/app/Http/Controllers/Web/Workspace/ArticleController.php apps/web/routes/web.php apps/web/lang/eng/article.php apps/web/tests/Feature/Article/ArticleMediaUploadTest.php
git commit -m "feat(article): add image upload endpoint for articles"
```

Note in the commit message body that `ArticleMediaUploadTest` will fail until Task 3 adds the serving routes — this is expected and intentional task sequencing, not a bug.

---

### Task 3: Image serving endpoint

**Files:**
- Create: `apps/web/app/Http/Controllers/Web/Media/MediaImageController.php`
- Modify: `apps/web/routes/web.php` (new top-level route group)
- Test: `apps/web/tests/Feature/Media/MediaImageServingTest.php`

**Interfaces:**
- Consumes: `App\Models\Media\MediaImage` (Task 1).
- Produces: routes `media.image.show` (`GET /media/image/{media_image}`) and `media.image.thumbnail` (`GET /media/image/{media_image}/thumbnail`), both public (no auth middleware) — matching the sanitizer's expected path exactly (`^/media/image/[A-Za-z0-9]{16}$`). Consumed by Task 2 (already referencing these route names) and Tasks 4/7 (rendering `<img src>`).

- [ ] **Step 1: Write the failing test**

Create `apps/web/tests/Feature/Media/MediaImageServingTest.php`:

```php
<?php

namespace Tests\Feature\Media;

use App\Models\Media\MediaImage;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaImageServingTest extends TestCase
{
    protected function tearDown(): void
    {
        MediaImage::query()->delete();
        parent::tearDown();
    }

    private function makeImage(): MediaImage
    {
        Storage::fake('public');
        Storage::disk('public')->put('media/image/2026/07/sample.webp', 'full-size-bytes');
        Storage::disk('public')->put('media/image/2026/07/sample-thumb.webp', 'thumb-bytes');

        return MediaImage::query()->create([
            'file_name' => 'sample.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/sample.webp',
            'alt_text' => ['eng' => ['text' => 'A sample image.']],
            'created_by_user_id' => 'U0000000000000001',
            'image_variant_list' => [[
                'type_image_variant' => 'TH',
                'file_name' => 'sample-thumb.webp',
                'mime_type' => 'image/webp',
                'storage_path' => 'media/image/2026/07/sample-thumb.webp',
            ]],
        ]);
    }

    public function test_show_route_streams_the_original_file(): void
    {
        $image = $this->makeImage();

        $response = $this->get("/media/image/{$image->getKey()}");

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/webp');
        $this->assertSame('full-size-bytes', $response->getContent());
    }

    public function test_thumbnail_route_streams_the_thumbnail_variant(): void
    {
        $image = $this->makeImage();

        $response = $this->get("/media/image/{$image->getKey()}/thumbnail");

        $response->assertOk();
        $this->assertSame('thumb-bytes', $response->getContent());
    }

    public function test_show_route_404s_for_an_unknown_id(): void
    {
        $this->get('/media/image/Un0000000000wn16')->assertNotFound();
    }

    public function test_thumbnail_route_404s_when_no_thumbnail_variant_exists(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('media/image/2026/07/no-thumb.webp', 'bytes');
        $image = MediaImage::query()->create([
            'file_name' => 'no-thumb.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/no-thumb.webp',
            'alt_text' => ['eng' => ['text' => 'No thumbnail.']],
            'created_by_user_id' => 'U0000000000000001',
        ]);

        $this->get("/media/image/{$image->getKey()}/thumbnail")->assertNotFound();
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter MediaImageServingTest`
Expected: FAIL — routes don't exist (404 on all, including the "should 404" tests failing for the wrong reason if checked closely — the two "should succeed" tests are the meaningful failures here).

- [ ] **Step 3: Create the controller**

Create `apps/web/app/Http/Controllers/Web/Media/MediaImageController.php`:

```php
<?php

namespace App\Http\Controllers\Web\Media;

use App\Http\Controllers\Controller;
use App\Models\Media\MediaImage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class MediaImageController extends Controller
{
    public function show(MediaImage $media_image): Response
    {
        return $this->stream($media_image->storage_path, $media_image->mime_type);
    }

    public function thumbnail(MediaImage $media_image): Response
    {
        $variant = collect($media_image->image_variant_list)->firstWhere('type_image_variant', 'TH');
        abort_unless($variant, 404);

        return $this->stream($variant['storage_path'], $variant['mime_type']);
    }

    private function stream(string $path, string $mimeType): Response
    {
        abort_unless(Storage::disk('public')->exists($path), 404);

        return response(Storage::disk('public')->get($path), 200, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
```

- [ ] **Step 4: Add the routes**

In `apps/web/routes/web.php`, add this near the top-level public routes (directly after the closing `});` of the existing `Route::prefix('article')->name('article.')->group(...)` block — search for that block's closing line, do not place this inside any `workspace`-prefixed or `auth`-middleware group):

```php
Route::prefix('media/image')->name('media.image.')->group(function () {
    Route::get('/{media_image}', [\App\Http\Controllers\Web\Media\MediaImageController::class, 'show'])->name('show');
    Route::get('/{media_image}/thumbnail', [\App\Http\Controllers\Web\Media\MediaImageController::class, 'thumbnail'])->name('thumbnail');
});
```

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test --filter MediaImageServingTest`
Expected: PASS

- [ ] **Step 6: Re-run Task 2's test — it should now fully pass**

Run: `php artisan test --filter ArticleMediaUploadTest`
Expected: PASS (all 5 tests) — the `route('media.image.show', ...)`/`route('media.image.thumbnail', ...)` calls inside `storeMedia()` now resolve.

- [ ] **Step 7: Commit**

```bash
git add apps/web/app/Http/Controllers/Web/Media/MediaImageController.php apps/web/routes/web.php apps/web/tests/Feature/Media/MediaImageServingTest.php
git commit -m "feat(media): add public image serving routes"
```

---

### Task 4: Inline image embedding in the editor

**Files:**
- Modify: `apps/web/package.json` (add `@tiptap/extension-image`)
- Create: `apps/web/resources/js/article-image-node.js`
- Modify: `apps/web/resources/js/article-editor.js`
- Modify: `apps/web/resources/views/workspace/article/editor.blade.php`
- Test: `apps/web/tests/Feature/Article/ArticleImageEmbedTest.php`

**Interfaces:**
- Consumes: `workspace.article.media.store` route (Task 2), `media.image.show` route (Task 3), `ArticleContent::sanitize()`'s existing allow-list for `<figure data-media-image-id>`/`<img>` (no changes to the sanitizer itself).
- Produces: a "Save Draft"-compatible article body containing inline `<figure data-media-image-id="...">` markup, for Task 5's gallery (which reads the same `related_article_id_list` data, independent of whether an image is embedded inline).

- [ ] **Step 1: Install the Tiptap image extension**

Run (from `apps/web/`): `npm install @tiptap/extension-image@^3.28.0`

- [ ] **Step 2: Write the failing test**

This task's server-side contract is: an article body containing the exact markup shape the frontend will produce must save and round-trip correctly through the existing sanitizer, referencing a real uploaded image. Create `apps/web/tests/Feature/Article/ArticleImageEmbedTest.php`:

```php
<?php

namespace Tests\Feature\Article;

use App\Models\Article\Article;
use App\Models\Article\ArticleBody;
use App\Models\Media\MediaImage;
use App\Models\User;
use Tests\TestCase;

class ArticleImageEmbedTest extends TestCase
{
    protected function tearDown(): void
    {
        MediaImage::query()->delete();
        ArticleBody::query()->delete();
        Article::query()->delete();
        parent::tearDown();
    }

    public function test_a_saved_draft_containing_an_inline_image_figure_round_trips_through_the_sanitizer(): void
    {
        $user = User::factory()->create();
        $image = MediaImage::query()->create([
            'file_name' => 'inline.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/inline.webp',
            'alt_text' => ['eng' => ['text' => 'Inline article image.']],
            'created_by_user_id' => (string) $user->getKey(),
        ]);
        $imageId = (string) $image->getKey();

        $this->actingAs($user)->post('/workspace/article', [
            'article_title' => 'An Illustrated Article',
            'article_slug' => 'an-illustrated-article',
            'short_description' => 'A concise description of this useful Massage Nexus article.',
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'level_nsfw' => 'N',
            'article_body' => '<p>Some intro text.</p><figure data-media-image-id="'.$imageId.'"><img src="/media/image/'.$imageId.'" alt="Inline article image."></figure><p>More text after the image.</p>',
            'author_credit_list' => [['user_id' => (string) $user->getKey(), 'display_name' => $user->publicName()]],
        ])->assertRedirect();

        $article = Article::query()->firstOrFail();
        $body = ArticleBody::query()->where('article_id', (string) $article->getKey())->firstOrFail();
        $this->assertStringContainsString('data-media-image-id="'.$imageId.'"', $body->article_body);
        $this->assertStringContainsString('src="/media/image/'.$imageId.'"', $body->article_body);
    }
}
```

- [ ] **Step 3: Run test to verify it fails or passes**

Run: `php artisan test --filter ArticleImageEmbedTest`
Expected: this should actually PASS already, since `ArticleContent::sanitize()` already allow-lists this exact shape (confirmed in `apps/web/app/Support/Article/ArticleContent.php:26-27,208-220`) and no server-side change is needed to accept it. This step exists to prove that fact with a real uploaded image ID rather than a synthetic one — if it fails, investigate the sanitizer's regex against the real ID format (`Str::random(16)` output) before touching any editor JS.

- [ ] **Step 4: Create the custom Tiptap node**

Create `apps/web/resources/js/article-image-node.js`:

```js
import { Node, mergeAttributes } from '@tiptap/core';

export const ArticleImage = Node.create({
    name: 'articleImage',
    group: 'block',
    atom: true,

    addAttributes() {
        return {
            mediaImageId: {
                default: null,
                parseHTML: (element) => element.getAttribute('data-media-image-id'),
                renderHTML: (attributes) => ({ 'data-media-image-id': attributes.mediaImageId }),
            },
            src: { default: null },
            alt: { default: '' },
        };
    },

    parseHTML() {
        return [{ tag: 'figure[data-media-image-id]' }];
    },

    renderHTML({ node }) {
        return [
            'figure',
            mergeAttributes({ 'data-media-image-id': node.attrs.mediaImageId }),
            ['img', { src: node.attrs.src, alt: node.attrs.alt, loading: 'lazy' }],
        ];
    },
});
```

- [ ] **Step 5: Wire the node and an upload button into the editor**

In `apps/web/resources/js/article-editor.js`, add the import near the top (alongside the existing Tiptap imports):

```js
import { ArticleImage } from './article-image-node.js';
```

Locate where the Tiptap `Editor` instance is constructed (the `extensions: [...]` array) and add `ArticleImage` to that array.

Add this upload-and-insert function near the other editor-toolbar wiring in the same file (adjacent to the existing toolbar button handlers):

```js
    const imageInsertButton = document.querySelector('[data-insert-image]');
    const imageFileInput = document.querySelector('[data-image-file-input]');
    const mediaUploadUrl = form.dataset.mediaUploadUrl;

    if (imageInsertButton && imageFileInput && mediaUploadUrl) {
        imageInsertButton.addEventListener('click', () => imageFileInput.click());
        imageFileInput.addEventListener('change', async () => {
            const file = imageFileInput.files[0];
            if (!file) return;
            const altText = window.prompt(form.dataset.altTextPrompt || 'Describe this image for accessibility:');
            if (!altText) {
                imageFileInput.value = '';
                return;
            }
            const body = new FormData();
            body.append('image', file);
            body.append('alt_text', altText);
            try {
                const response = await fetch(mediaUploadUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content, Accept: 'application/json' },
                    body,
                });
                if (!response.ok) throw new Error('Upload failed');
                const payload = await response.json();
                editor.chain().focus().insertContent({
                    type: 'articleImage',
                    attrs: { mediaImageId: payload.id, src: payload.url, alt: altText },
                }).run();
            } catch (error) {
                window.alert(form.dataset.uploadErrorLabel || 'Image upload failed. Try again.');
            } finally {
                imageFileInput.value = '';
            }
        });
    }
```

(This references `editor`, the existing Tiptap instance variable already in scope in this file's closure — do not redeclare it.)

- [ ] **Step 6: Add the toolbar button and hidden file input to the Blade view**

In `apps/web/resources/views/workspace/article/editor.blade.php`, add to the existing toolbar button row (alongside the other formatting buttons like bold/italic, around where the toolbar `<button>` elements are defined):

```blade
                                <button type="button" data-insert-image
                                        @if (! $article) disabled title="{{ __('article.save_draft_before_images') }}" @endif
                                        class="inline-flex items-center justify-center rounded-lg p-2 text-ink-600 transition hover:bg-ink-100 disabled:cursor-not-allowed disabled:opacity-40 dark:text-ink-300 dark:hover:bg-ink-800">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5V6a1.5 1.5 0 0 1 1.5-1.5h15A1.5 1.5 0 0 1 21 6v12a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 18Zm0 0 5-5 4 4 3-3 6 6M9 9.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" /></svg>
                                </button>
                                <input type="file" data-image-file-input accept="image/jpeg,image/png,image/webp" class="hidden" @if (! $article) disabled @endif>
```

And add the two new `data-*` attributes to the existing `<form>` tag (alongside the other `data-*` attributes already there, e.g. `data-minimum-submission-words`):

```blade
                  data-media-upload-url="{{ $article ? route('workspace.article.media.store', $article) : '' }}"
                  data-alt-text-prompt="{{ __('article.alt_text_prompt') }}"
                  data-upload-error-label="{{ __('article.upload_error') }}"
```

Add the 3 new translation keys to `apps/web/lang/eng/article.php`:

```php
    'save_draft_before_images' => 'Save this article as a draft first to add images.',
    'alt_text_prompt' => 'Describe this image for accessibility:',
    'upload_error' => 'Image upload failed. Try again.',
```

- [ ] **Step 7: Run the full article test suite to check for regressions**

Run: `php artisan test --filter ArticleWorkspaceTest` and `php artisan test --filter ArticleImageEmbedTest`
Expected: all PASS.

- [ ] **Step 8: Manual browser verification (required — cannot be automated)**

This project's established convention (per prior plans in this repo) is that real Tiptap/DOM editor behavior cannot be verified by PHPUnit's HTTP-only test harness — it requires a real browser. Before considering this task done, manually verify in a browser: clicking the image button opens a file picker, selecting a file prompts for alt text, a successful upload inserts a `<figure>` at the cursor position, and the button is disabled with the correct tooltip on the "new article" page (no `$article` yet). Note any gap found for the final review.

- [ ] **Step 9: Commit**

```bash
git add apps/web/package.json apps/web/package-lock.json apps/web/resources/js/article-image-node.js apps/web/resources/js/article-editor.js apps/web/resources/views/workspace/article/editor.blade.php apps/web/lang/eng/article.php apps/web/tests/Feature/Article/ArticleImageEmbedTest.php
git commit -m "feat(article): embed images inline in the article editor via Tiptap"
```

---

### Task 5: Featured image (gallery + selection)

**Files:**
- Modify: `apps/web/app/Models/Article/Article.php` (add `featured_media_image_id` to `#[Fillable]`)
- Modify: `apps/web/app/Http/Controllers/Web/Workspace/ArticleController.php` (add `setFeaturedMedia` method)
- Modify: `apps/web/routes/web.php`
- Modify: `apps/web/resources/views/workspace/article/editor.blade.php` (gallery strip)
- Modify: `apps/web/lang/eng/article.php`
- Test: `apps/web/tests/Feature/Article/ArticleFeaturedImageTest.php`

**Interfaces:**
- Consumes: `MediaImage` (Task 1), `ArticleController::authorizeOwner` (existing private method, same class).
- Produces: `Article.featured_media_image_id`, consumed by Task 7's listing-card display.

- [ ] **Step 1: Write the failing test**

Create `apps/web/tests/Feature/Article/ArticleFeaturedImageTest.php`:

```php
<?php

namespace Tests\Feature\Article;

use App\Models\Article\Article;
use App\Models\Media\MediaImage;
use App\Models\User;
use Tests\TestCase;

class ArticleFeaturedImageTest extends TestCase
{
    protected function tearDown(): void
    {
        MediaImage::query()->delete();
        Article::query()->delete();
        parent::tearDown();
    }

    private function createArticle(User $user): Article
    {
        $this->actingAs($user)->post('/workspace/article', [
            'article_title' => 'An Article With A Featured Image',
            'article_slug' => 'an-article-with-a-featured-image',
            'short_description' => 'A concise description of this useful Massage Nexus article.',
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'level_nsfw' => 'N',
            'article_body' => '<p>'.str_repeat('Word ', 25).'</p>',
            'author_credit_list' => [['user_id' => (string) $user->getKey(), 'display_name' => $user->publicName()]],
        ]);

        return Article::query()->firstOrFail();
    }

    private function createImage(Article $article, User $user): MediaImage
    {
        return MediaImage::query()->create([
            'file_name' => 'photo.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/photo.webp',
            'alt_text' => ['eng' => ['text' => 'A photo.']],
            'related_article_id_list' => [(string) $article->getKey()],
            'created_by_user_id' => (string) $user->getKey(),
        ]);
    }

    public function test_owner_can_set_a_belonging_image_as_featured(): void
    {
        $user = User::factory()->create();
        $article = $this->createArticle($user);
        $image = $this->createImage($article, $user);

        $this->actingAs($user)
            ->post("/workspace/article/{$article->getKey()}/media/{$image->getKey()}/featured")
            ->assertRedirect();

        $this->assertSame((string) $image->getKey(), $article->refresh()->featured_media_image_id);
    }

    public function test_cannot_set_an_unrelated_image_as_featured(): void
    {
        $user = User::factory()->create();
        $articleA = $this->createArticle($user);
        $this->actingAs($user)->post('/workspace/article', [
            'article_title' => 'A Second Article',
            'article_slug' => 'a-second-article',
            'short_description' => 'A concise description of this useful Massage Nexus article.',
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'level_nsfw' => 'N',
            'article_body' => '<p>'.str_repeat('Word ', 25).'</p>',
            'author_credit_list' => [['user_id' => (string) $user->getKey(), 'display_name' => $user->publicName()]],
        ]);
        $articleB = Article::query()->where('_id', '!=', (string) $articleA->getKey())->firstOrFail();
        $imageForB = $this->createImage($articleB, $user);

        $this->actingAs($user)
            ->post("/workspace/article/{$articleA->getKey()}/media/{$imageForB->getKey()}/featured")
            ->assertStatus(422);

        $this->assertNull($articleA->refresh()->featured_media_image_id);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter ArticleFeaturedImageTest`
Expected: FAIL — route doesn't exist yet.

- [ ] **Step 3: Add the field to Article's Fillable list**

In `apps/web/app/Models/Article/Article.php`, in the `#[Fillable([...])]` attribute list, add `'featured_media_image_id',` right after the existing `'cover_media_image_id',` entry.

- [ ] **Step 4: Add the message key**

In `apps/web/lang/eng/article.php`:

```php
    'featured_image_not_owned' => 'That image does not belong to this article.',
```

- [ ] **Step 5: Add the controller method**

In `apps/web/app/Http/Controllers/Web/Workspace/ArticleController.php`, add (after `storeMedia()`):

```php
    public function setFeaturedMedia(Request $request, Article $article, MediaImage $media_image): RedirectResponse
    {
        $this->authorizeOwner($request, $article);
        abort_unless(in_array((string) $article->getKey(), $media_image->related_article_id_list ?? [], true), 422, __('article.featured_image_not_owned'));

        $article->forceFill(['featured_media_image_id' => (string) $media_image->getKey()])->save();

        return back()->with('status', __('article.draft_saved'));
    }
```

- [ ] **Step 6: Add the route**

In `apps/web/routes/web.php`, inside the `workspace/article` group, after the `media` route added in Task 2:

```php
        Route::post('/{article}/media/{media_image}/featured', [WorkspaceArticleController::class, 'setFeaturedMedia'])->middleware('throttle:30,1')->name('media.featured');
```

- [ ] **Step 7: Run test to verify it passes**

Run: `php artisan test --filter ArticleFeaturedImageTest`
Expected: PASS

- [ ] **Step 8: Pass the article's images from the controller**

This app's convention (see `ArticleController::editorData()`) is that all view data is prepared in the controller, not queried directly inside a Blade `@php` block. Add the import `use App\Models\Media\MediaImage;` if not already present from Step 6, then in `editorData()`'s returned array, add:

```php
            'articleImages' => $article
                ? MediaImage::query()->where('related_article_id_list', (string) $article->getKey())->get()
                : collect(),
```

- [ ] **Step 9: Add the gallery strip to the Blade view**

In `apps/web/resources/views/workspace/article/editor.blade.php`, below the rich-text editor container, add (only rendered when editing an existing article, matching the same `@if ($article)` gate used for the image-upload button):

```blade
                @if ($article)
                    @if ($articleImages->isNotEmpty())
                        <div class="mt-4 flex flex-wrap gap-3" data-article-image-gallery>
                            @foreach ($articleImages as $galleryImage)
                                <div class="relative">
                                    <img src="{{ route('media.image.thumbnail', $galleryImage) }}" alt="{{ $galleryImage->localized('alt_text') }}" class="size-20 rounded-lg object-cover ring-2 {{ $article->featured_media_image_id === (string) $galleryImage->getKey() ? 'ring-ember-500' : 'ring-transparent' }}">
                                    <form method="post" action="{{ route('workspace.article.media.featured', [$article, $galleryImage]) }}" class="absolute inset-x-0 bottom-0">
                                        @csrf
                                        <button type="submit" class="w-full rounded-b-lg bg-ink-950/70 py-0.5 text-[10px] font-bold text-white">{{ __('article.set_as_featured') }}</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
```

Add the translation key:

```php
    'set_as_featured' => 'Set as featured',
```

- [ ] **Step 10: Run the full article test suite to check for regressions**

Run: `php artisan test --filter ArticleWorkspaceTest`
Expected: PASS

- [ ] **Step 11: Commit**

```bash
git add apps/web/app/Models/Article/Article.php apps/web/app/Http/Controllers/Web/Workspace/ArticleController.php apps/web/routes/web.php apps/web/resources/views/workspace/article/editor.blade.php apps/web/lang/eng/article.php apps/web/tests/Feature/Article/ArticleFeaturedImageTest.php
git commit -m "feat(article): let authors mark one uploaded image as featured"
```

---

### Task 6: Hero image

**Files:**
- Modify: `apps/web/app/Http/Controllers/Web/Workspace/ArticleController.php` (add `setCoverMedia` method)
- Modify: `apps/web/routes/web.php`
- Modify: `apps/web/resources/views/workspace/article/editor.blade.php` (hero upload control)
- Modify: `apps/web/lang/eng/article.php`
- Test: `apps/web/tests/Feature/Article/ArticleHeroImageTest.php`

**Interfaces:**
- Consumes: `MediaImage` (Task 1), `StoreUploadedArticleImage` (Task 2) via the same upload endpoint, `ArticleController::authorizeOwner`.
- Produces: `Article.cover_media_image_id` (field already exists in the Fillable list — confirmed present, no model change needed), consumed by Task 7's show-page hero banner.

- [ ] **Step 1: Write the failing test**

Create `apps/web/tests/Feature/Article/ArticleHeroImageTest.php`:

```php
<?php

namespace Tests\Feature\Article;

use App\Models\Article\Article;
use App\Models\Media\MediaImage;
use App\Models\User;
use Tests\TestCase;

class ArticleHeroImageTest extends TestCase
{
    protected function tearDown(): void
    {
        MediaImage::query()->delete();
        Article::query()->delete();
        parent::tearDown();
    }

    private function createArticle(User $user): Article
    {
        $this->actingAs($user)->post('/workspace/article', [
            'article_title' => 'An Article With A Hero Image',
            'article_slug' => 'an-article-with-a-hero-image',
            'short_description' => 'A concise description of this useful Massage Nexus article.',
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'level_nsfw' => 'N',
            'article_body' => '<p>'.str_repeat('Word ', 25).'</p>',
            'author_credit_list' => [['user_id' => (string) $user->getKey(), 'display_name' => $user->publicName()]],
        ]);

        return Article::query()->firstOrFail();
    }

    public function test_owner_can_set_a_belonging_image_as_the_hero_image(): void
    {
        $user = User::factory()->create();
        $article = $this->createArticle($user);
        $image = MediaImage::query()->create([
            'file_name' => 'hero.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/hero.webp',
            'alt_text' => ['eng' => ['text' => 'A hero image.']],
            'related_article_id_list' => [(string) $article->getKey()],
            'created_by_user_id' => (string) $user->getKey(),
        ]);

        $this->actingAs($user)
            ->post("/workspace/article/{$article->getKey()}/media/{$image->getKey()}/cover")
            ->assertRedirect();

        $this->assertSame((string) $image->getKey(), $article->refresh()->cover_media_image_id);
    }

    public function test_cannot_set_an_unrelated_image_as_the_hero_image(): void
    {
        $user = User::factory()->create();
        $article = $this->createArticle($user);
        $unrelatedImage = MediaImage::query()->create([
            'file_name' => 'unrelated.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/unrelated.webp',
            'alt_text' => ['eng' => ['text' => 'Unrelated image.']],
            'created_by_user_id' => (string) $user->getKey(),
        ]);

        $this->actingAs($user)
            ->post("/workspace/article/{$article->getKey()}/media/{$unrelatedImage->getKey()}/cover")
            ->assertStatus(422);

        $this->assertNull($article->refresh()->cover_media_image_id);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter ArticleHeroImageTest`
Expected: FAIL — route doesn't exist yet.

- [ ] **Step 3: Add the message key**

In `apps/web/lang/eng/article.php`:

```php
    'cover_image_not_owned' => 'That image does not belong to this article.',
    'hero_image_label' => 'Hero image',
```

- [ ] **Step 4: Add the controller method**

In `apps/web/app/Http/Controllers/Web/Workspace/ArticleController.php`, add (after `setFeaturedMedia()`):

```php
    public function setCoverMedia(Request $request, Article $article, MediaImage $media_image): RedirectResponse
    {
        $this->authorizeOwner($request, $article);
        abort_unless(in_array((string) $article->getKey(), $media_image->related_article_id_list ?? [], true), 422, __('article.cover_image_not_owned'));

        $article->forceFill(['cover_media_image_id' => (string) $media_image->getKey()])->save();

        return back()->with('status', __('article.draft_saved'));
    }
```

- [ ] **Step 5: Add the route**

In `apps/web/routes/web.php`, inside the `workspace/article` group, after the `media.featured` route:

```php
        Route::post('/{article}/media/{media_image}/cover', [WorkspaceArticleController::class, 'setCoverMedia'])->middleware('throttle:30,1')->name('media.cover');
```

- [ ] **Step 6: Run test to verify it passes**

Run: `php artisan test --filter ArticleHeroImageTest`
Expected: PASS

- [ ] **Step 7: Add a hero-image upload control to the Blade view**

In `apps/web/resources/views/workspace/article/editor.blade.php`, near the gallery strip added in Task 5, add (only when `$article` exists):

```blade
                @if ($article)
                    <div class="mt-4">
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ __('article.hero_image_label') }}</label>
                        <input type="file" data-hero-image-file-input accept="image/jpeg,image/png,image/webp" class="text-sm">
                        @if ($article->cover_media_image_id)
                            <img src="{{ route('media.image.thumbnail', $article->cover_media_image_id) }}" alt="" class="mt-2 h-24 rounded-lg object-cover">
                        @endif
                    </div>
                @endif
```

Add the matching JS in `apps/web/resources/js/article-editor.js`, reusing the same upload endpoint but posting to the `.cover` route on success (add near the inline-image upload wiring from Task 4):

```js
    const heroFileInput = document.querySelector('[data-hero-image-file-input]');
    if (heroFileInput && mediaUploadUrl) {
        heroFileInput.addEventListener('change', async () => {
            const file = heroFileInput.files[0];
            if (!file) return;
            const altText = window.prompt(form.dataset.altTextPrompt || 'Describe this image for accessibility:');
            if (!altText) {
                heroFileInput.value = '';
                return;
            }
            const body = new FormData();
            body.append('image', file);
            body.append('alt_text', altText);
            try {
                const response = await fetch(mediaUploadUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content, Accept: 'application/json' },
                    body,
                });
                if (!response.ok) throw new Error('Upload failed');
                const payload = await response.json();
                const coverUrl = mediaUploadUrl.replace(/\/media$/, `/media/${payload.id}/cover`);
                const coverForm = document.createElement('form');
                coverForm.method = 'POST';
                coverForm.action = coverUrl;
                coverForm.innerHTML = `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content}">`;
                document.body.append(coverForm);
                coverForm.submit();
            } catch (error) {
                window.alert(form.dataset.uploadErrorLabel || 'Image upload failed. Try again.');
            }
        });
    }
```

- [ ] **Step 8: Manual browser verification (required)**

Verify in a real browser: selecting a hero image file uploads it, sets it as the cover image, and the page reloads showing the new hero thumbnail preview.

- [ ] **Step 9: Commit**

```bash
git add apps/web/app/Http/Controllers/Web/Workspace/ArticleController.php apps/web/routes/web.php apps/web/resources/views/workspace/article/editor.blade.php apps/web/resources/js/article-editor.js apps/web/lang/eng/article.php apps/web/tests/Feature/Article/ArticleHeroImageTest.php
git commit -m "feat(article): add separate hero image upload"
```

---

### Task 7: Display featured and hero images

**Files:**
- Modify: `apps/web/app/Http/Controllers/Web/Public/ArticleController.php` (`card()` method)
- Modify: `apps/web/resources/views/article/index.blade.php`
- Modify: `apps/web/resources/views/article/show.blade.php`
- Modify: `apps/web/resources/views/workspace/article/index.blade.php`
- Test: `apps/web/tests/Feature/Article/ArticleImageDisplayTest.php`

**Interfaces:**
- Consumes: `Article.featured_media_image_id` (Task 5), `Article.cover_media_image_id` (Task 6), `media.image.thumbnail`/`media.image.show` routes (Task 3).

- [ ] **Step 1: Write the failing test**

Create `apps/web/tests/Feature/Article/ArticleImageDisplayTest.php`:

```php
<?php

namespace Tests\Feature\Article;

use App\Models\Article\Article;
use App\Models\Media\MediaImage;
use App\Models\User;
use Tests\TestCase;

class ArticleImageDisplayTest extends TestCase
{
    protected function tearDown(): void
    {
        MediaImage::query()->delete();
        Article::query()->delete();
        parent::tearDown();
    }

    public function test_workspace_index_shows_featured_thumbnail_when_set(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/workspace/article', [
            'article_title' => 'Featured Thumbnail Article',
            'article_slug' => 'featured-thumbnail-article',
            'short_description' => 'A concise description of this useful Massage Nexus article.',
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'level_nsfw' => 'N',
            'article_body' => '<p>'.str_repeat('Word ', 25).'</p>',
            'author_credit_list' => [['user_id' => (string) $user->getKey(), 'display_name' => $user->publicName()]],
        ]);
        $article = Article::query()->firstOrFail();
        $image = MediaImage::query()->create([
            'file_name' => 'thumb.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/thumb.webp',
            'alt_text' => ['eng' => ['text' => 'Featured.']],
            'related_article_id_list' => [(string) $article->getKey()],
            'created_by_user_id' => (string) $user->getKey(),
        ]);
        $article->forceFill(['featured_media_image_id' => (string) $image->getKey()])->save();

        $this->actingAs($user)->get('/workspace/article')
            ->assertOk()
            ->assertSee(route('media.image.thumbnail', $image), false);
    }

    public function test_public_show_page_renders_hero_image_when_set(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/workspace/article', [
            'article_title' => 'Hero Banner Article',
            'article_slug' => 'hero-banner-article',
            'short_description' => 'A concise description of this useful Massage Nexus article.',
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'level_nsfw' => 'N',
            'article_body' => '<p>'.str_repeat('Word ', 25).'</p>',
            'author_credit_list' => [['user_id' => (string) $user->getKey(), 'display_name' => $user->publicName()]],
        ]);
        $article = Article::query()->firstOrFail();
        $image = MediaImage::query()->create([
            'file_name' => 'hero.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/hero.webp',
            'alt_text' => ['eng' => ['text' => 'Hero.']],
            'related_article_id_list' => [(string) $article->getKey()],
            'created_by_user_id' => (string) $user->getKey(),
        ]);
        $article->forceFill([
            'cover_media_image_id' => (string) $image->getKey(),
            'status_publication' => 'P',
            'visibility_scope' => 'PUB',
        ])->save();

        $this->get(route('article.show', $article->localized('article_slug')))
            ->assertOk()
            ->assertSee(route('media.image.show', $image), false);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter ArticleImageDisplayTest`
Expected: FAIL — neither view renders an image yet.

- [ ] **Step 3: Update the public listing card data**

In `apps/web/app/Http/Controllers/Web/Public/ArticleController.php`, in the `card()` method (around line 213-226), add one line to the returned array (after `'level_nsfw' => $article->level_nsfw,`):

```php
            'featured_thumbnail_url' => $article->featured_media_image_id
                ? route('media.image.thumbnail', ['media_image' => $article->featured_media_image_id])
                : null,
```

- [ ] **Step 4: Update the public listing view**

In `apps/web/resources/views/article/index.blade.php`, inside the `@foreach ($articles as $item)` loop (around line 32-33, right after the `<div class="h-2 bg-gradient-to-r ...">` accent bar), add:

```blade
                        @if ($item['featured_thumbnail_url'])
                            <img src="{{ $item['featured_thumbnail_url'] }}" alt="" class="h-40 w-full object-cover">
                        @endif
```

- [ ] **Step 5: Update the public show page**

In `apps/web/resources/views/article/show.blade.php`, right after the closing `</nav>` (line 24) and before the `<div class="lg:grid ...">` (line 25), add:

```blade
            @if ($article->cover_media_image_id)
                <img src="{{ route('media.image.show', ['media_image' => $article->cover_media_image_id]) }}" alt="" class="mt-6 aspect-[21/9] w-full rounded-2xl object-cover">
            @endif
```

- [ ] **Step 6: Update the workspace index view**

In `apps/web/resources/views/workspace/article/index.blade.php`, replace the icon-square block (lines 92-95):

```blade
                        <div class="flex min-w-0 items-start gap-3.5">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-ink-50 text-ink-500 transition group-hover:bg-white group-hover:text-ember-600 group-hover:shadow-sm dark:bg-ink-800 dark:text-ink-300 dark:group-hover:bg-ink-800 dark:group-hover:text-ember-400">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3h9l4 4v14H6V3Zm9 0v4h4M9 12h6M9 16h4" /></svg>
                            </span>
```

with:

```blade
                        <div class="flex min-w-0 items-start gap-3.5">
                            @if ($item->featured_media_image_id)
                                <img src="{{ route('media.image.thumbnail', $item->featured_media_image_id) }}" alt="" class="size-10 shrink-0 rounded-xl object-cover">
                            @else
                                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-ink-50 text-ink-500 transition group-hover:bg-white group-hover:text-ember-600 group-hover:shadow-sm dark:bg-ink-800 dark:text-ink-300 dark:group-hover:bg-ink-800 dark:group-hover:text-ember-400">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3h9l4 4v14H6V3Zm9 0v4h4M9 12h6M9 16h4" /></svg>
                                </span>
                            @endif
```

- [ ] **Step 7: Run test to verify it passes**

Run: `php artisan test --filter ArticleImageDisplayTest`
Expected: PASS

- [ ] **Step 8: Run the full article test suite to check for regressions**

Run: `php artisan test --filter ArticleWorkspaceTest` and `php artisan test --filter ArticlePagesTest`
Expected: all PASS.

- [ ] **Step 9: Commit**

```bash
git add apps/web/app/Http/Controllers/Web/Public/ArticleController.php apps/web/resources/views/article/index.blade.php apps/web/resources/views/article/show.blade.php apps/web/resources/views/workspace/article/index.blade.php apps/web/tests/Feature/Article/ArticleImageDisplayTest.php
git commit -m "feat(article): display featured and hero images on listings and article pages"
```

---

## Final verification

- [ ] Run the full test suite: `php artisan test` (from `apps/web/`). Expected: all tests pass, including all 7 new test files from this plan.
- [ ] Run `npm run build` (from `apps/web/`) to confirm the new Tiptap extension and JS changes compile cleanly.
- [ ] Manual browser verification of the full flow: create a draft, upload 2-3 inline images, mark one featured, upload a separate hero image, save, confirm the workspace index card shows the featured thumbnail, submit/publish (if permitted) and confirm the public show page renders the hero banner and the public listing shows the featured thumbnail.
