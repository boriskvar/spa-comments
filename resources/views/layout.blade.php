<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vite App</title>
    @vite('resources/css/app.css')
</head>
<body>

    @yield('content')

    @vite('resources/js/main.js')
</body>
</html>
