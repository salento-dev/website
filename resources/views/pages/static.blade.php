<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $metaDescription }}">

        <title>{{ $pageTitle }} - {{ config('app.name') }}</title>
    </head>
    <body>
        <h1>{{ $pageTitle }}</h1>
    </body>
</html>
