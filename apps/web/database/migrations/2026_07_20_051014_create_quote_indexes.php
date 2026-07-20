<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mongodb')->table('quote_main', function (Blueprint $collection) {
            // Rotation query index
            $collection->index([
                'is_display_enabled' => 1,
                'status_review' => 1,
                'status_record_lifecycle' => 1,
                'display_start_date' => 1,
                'display_end_date' => 1,
            ], 'quote_rotation_idx');

            $collection->index('type_quote_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->table('quote_main', function (Blueprint $collection) {
            $collection->dropIndex('quote_rotation_idx');
            $collection->dropIndex('type_quote_category_1');
        });
    }
};
