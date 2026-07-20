<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->table('article_main', function (Blueprint $table) {
            $table->unique('article_slug.eng.text');
            $table->index(['status_publication', 'status_review', 'visibility_scope', 'status_record_lifecycle', 'published_at']);
            $table->index(['type_article_category', 'published_at']);
            $table->index(['target_audience', 'published_at']);
            $table->index('tag_id_list');
            $table->index('author_user_id_list');
        });

        Schema::connection('mongodb')->table('article_body', function (Blueprint $table) {
            $table->unique(['article_id', 'language_id']);
            $table->index('article_plain_text');
        });

        Schema::connection('mongodb')->table('article_revision', function (Blueprint $table) {
            $table->unique(['article_body_id', 'revision_number']);
            $table->index(['article_id', 'revision_number']);
            $table->index(['submitted_by_user_id', 'submitted_at']);
        });

        Schema::connection('mongodb')->table('tag_main', function (Blueprint $table) {
            $table->unique('tag_slug.eng.text');
            $table->index(['status_record_lifecycle', 'usage_count']);
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('article_revision');
        Schema::connection('mongodb')->dropIfExists('article_body');
        Schema::connection('mongodb')->dropIfExists('article_main');
        Schema::connection('mongodb')->dropIfExists('tag_main');
    }
};
