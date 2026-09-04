@props([
    'title' => 'I AM MIESCOR',
    'description' => 'MIESCOR is a leading engineering and construction company in the Philippines.',
    'keywords' => 'MIESCOR, Engineering, Construction, Philippines',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/miescor/favicon.ico') }}">
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $keywords }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{ $head ?? '' }}
</head>
<body class="bg-gray-50 text-gray-950 antialiased">
    {{ $slot }}
</body>
</html>
