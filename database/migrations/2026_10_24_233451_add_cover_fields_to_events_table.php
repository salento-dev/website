<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('cover_title')->nullable()->after('image_path');
            $table->unsignedSmallInteger('edition')->nullable()->after('cover_title');
            $table->string('subtitle')->nullable()->after('edition');
            $table->string('cover_note')->nullable()->after('subtitle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['cover_title', 'edition', 'subtitle', 'cover_note']);
        });
    }
};
