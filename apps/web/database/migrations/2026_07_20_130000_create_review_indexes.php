<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->table('review_main', function (Blueprint $table) {
            $table->unique('review_slug');
            $table->index(['status_publication', 'status_review', 'visibility_scope', 'status_record_lifecycle', 'published_at']);
            $table->index(['target_collection', 'target_record_id', 'published_at']);
            $table->index(['created_by_user_id', 'updated_at']);
            $table->index(['status_review', 'submitted_at']);
            $table->index('author_user_id_list');
        });

        Schema::connection('mongodb')->table('rating_main', function (Blueprint $table) {
            $table->unique('review_id');
            $table->index(['target_collection', 'target_record_id', 'status_rating', 'date_experience']);
            $table->index(['created_by_user_id', 'target_collection', 'target_record_id', 'date_experience']);
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('rating_main');
        Schema::connection('mongodb')->dropIfExists('review_main');
    }
};
