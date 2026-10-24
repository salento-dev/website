<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Retention
    |--------------------------------------------------------------------------
    |
    | Raw page views, click events and 404 hits older than this number of
    | months are rolled up into daily aggregates and then deleted.
    |
    */

    'retention_months' => (int) env('ANALYTICS_RETENTION_MONTHS', 12),

    /*
    |--------------------------------------------------------------------------
    | Page view de-duplication
    |--------------------------------------------------------------------------
    |
    | A visitor reloading the same page within this window is counted once.
    |
    */

    'dedup_minutes' => 30,

    /*
    |--------------------------------------------------------------------------
    | Referrer aliases
    |--------------------------------------------------------------------------
    |
    | Referrer hosts (without "www.") mapped to a single canonical source.
    |
    */

    'referrer_aliases' => [
        'l.instagram.com' => 'instagram.com',
        'm.facebook.com' => 'facebook.com',
        'l.facebook.com' => 'facebook.com',
        'lm.facebook.com' => 'facebook.com',
        'lnkd.in' => 'linkedin.com',
        'com.linkedin.android' => 'linkedin.com',
        't.co' => 'x.com',
        'twitter.com' => 'x.com',
        'out.reddit.com' => 'reddit.com',
        'old.reddit.com' => 'reddit.com',
        'com.google.android.gm' => 'gmail',
        'mail.google.com' => 'gmail',
        'com.google.android.googlequicksearchbox' => 'google',
    ],

    /*
    |--------------------------------------------------------------------------
    | Click destinations
    |--------------------------------------------------------------------------
    |
    | Outbound hosts that identify a specific click event name.
    |
    */

    'click_hosts' => [
        'chat.whatsapp.com' => 'whatsapp',
        'wa.me' => 'whatsapp',
        'instagram.com' => 'instagram',
        'linkedin.com' => 'linkedin',
    ],

];
