<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite('resources/css/app.css')

    @vite('resources/js/app.js')
    @inertiaHead

    <title> Habit Tracker </title>

</head>
<body>
    @inertia
</body>
</html>
