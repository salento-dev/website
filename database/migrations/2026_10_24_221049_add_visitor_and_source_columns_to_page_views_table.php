<?php

use App\Support\Analytics\ReferrerSource;
use App\Support\Analytics\UserAgent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->char('visitor_hash', 16)->nullable()->after('url');
            $table->string('referrer_host')->nullable()->after('referrer')->index();
            $table->string('utm_source', 100)->nullable()->after('referrer_host')->index();
            $table->string('utm_medium', 100)->nullable()->after('utm_source');
            $table->string('utm_campaign', 100)->nullable()->after('utm_medium')->index();
            $table->string('device', 10)->nullable()->after('user_agent')->index();

            $table->index(['visitor_hash', 'path', 'created_at']);
        });

        $ownHost = (string) parse_url((string) config('app.url'), PHP_URL_HOST);

        DB::table('page_views')->orderBy('id')->chunkById(500, function ($rows) use ($ownHost): void {
            foreach ($rows as $row) {
                DB::table('page_views')->where('id', $row->id)->update([
                    'referrer_host' => ReferrerSource::fromReferrer($row->referrer, $ownHost),
                    'device' => UserAgent::device($row->user_agent),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->dropIndex(['visitor_hash', 'path', 'created_at']);
            $table->dropIndex(['referrer_host']);
            $table->dropIndex(['utm_source']);
            $table->dropIndex(['utm_campaign']);
            $table->dropIndex(['device']);
            $table->dropColumn(['visitor_hash', 'referrer_host', 'utm_source', 'utm_medium', 'utm_campaign', 'device']);
        });
    }
};
