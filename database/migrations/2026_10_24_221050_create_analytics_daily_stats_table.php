<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_daily_stats', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('metric', 32);
            $table->string('path');
            $table->nullableMorphs('viewable');
            $table->unsignedInteger('count')->default(0);
            $table->unsignedInteger('visitors')->default(0);

            $table->unique(['date', 'metric', 'path']);
            $table->index(['metric', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_daily_stats');
    }
};
