<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->table('password_reset_tokens', function (Blueprint $table) {
            $table->unique('email');
            $table->expire('created_at', 3600);
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('password_reset_tokens');
    }
};
