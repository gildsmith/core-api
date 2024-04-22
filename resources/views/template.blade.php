<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ env('APP_NAME') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if(!empty($webapp->params['google_font_path']))
        <link href="{{ $webapp->params['google_font_path'] }}" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap"
              rel="stylesheet">
    @endif

    @if(!empty($webapp->params['app_path']))
        @vite($webapp->params['app_path'])
    @endif
    @vite('resources/css/app.css')

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    @if($webapp->route)
        <meta name="base-path" content="{{ $webapp->route }}">
    @endif
</head>
<body>
<div id="app"></div>
</body>
</html>