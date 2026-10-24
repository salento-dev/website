<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32)->index();
            $table->string('path')->index();
            $table->nullableMorphs('viewable');
            $table->text('target_url')->nullable();
            $table->string('target_host')->nullable();
            $table->string('label')->nullable();
            $table->char('visitor_hash', 16)->nullable()->index();
            $table->timestamp('created_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
