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
        Schema::connection('mongodb')->table('service_main', function (Blueprint $collection) {
            $collection->unique('service_slug');
            $collection->index('group_service_family');
            $collection->index('status_record_lifecycle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->table('service_main', function (Blueprint $collection) {
            $collection->dropIndex('service_slug_1');
            $collection->dropIndex('group_service_family_1');
            $collection->dropIndex('status_record_lifecycle_1');
        });
    }
};
