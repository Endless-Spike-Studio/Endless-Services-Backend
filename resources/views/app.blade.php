<!doctype html>
<html lang="{{ str_replace('_', '-', config('app.locale')) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    @inertiaHead
    @vite(['resources/styles/main.scss', 'resources/scripts/main.ts'])
</head>
<body>
    @inertia
</body>
</html>
