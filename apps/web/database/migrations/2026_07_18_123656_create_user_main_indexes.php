<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Unique indexes are the authoritative collision protection for the
 * application-generated opaque identifier and for username/email, per
 * docs/02-governance/shared-project-standards.txt section 9.4: a
 * pre-insert uniqueness check alone is not sufficient because a
 * concurrent request could insert the same value first.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->table('user_main', function (Blueprint $table) {
            $table->unique('username');
            $table->unique('email');
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->table('user_main', function (Blueprint $table) {
            $table->dropUnique('username');
            $table->dropUnique('email');
        });
    }
};
