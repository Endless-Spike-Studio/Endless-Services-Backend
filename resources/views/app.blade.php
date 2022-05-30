<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', config('app.locale')) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geometry Dash Chinese</title>
    @env('local')
        @vite
    @endenv

    @env(['stagging', 'production'])
        <link rel="stylesheet" href="{{ vite_entry('resources/scss/main.scss') }}">
        <script type="module" src="{{ vite_entry('resources/scripts/main.ts') }}"></script>

        <script nomodule src="{{ vite_entry('resources/scss/main-legacy.scss') }}"></script>
        <script nomodule src="{{ vite_entry('resources/scripts/main-legacy.ts') }}"></script>
        <script nomodule src="{{ vite_entry('vite/legacy-polyfills') }}"></script>
    @endenv
</head>
<body>
@inertia
</body>
</html>
