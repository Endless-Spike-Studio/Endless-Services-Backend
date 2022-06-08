<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace( '_', '-', config('app.locale') ) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite
    @inertiaHead
</head>
<body class="h-full">
@inertia
</body>
</html>
