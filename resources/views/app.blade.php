<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @vite('resources/css/app.css')

        @vite('resources/js/app.js')


        <title> Habit Tracker </title>

    </head>
    <body>
        <div class="text-2xl text-gray-500"> Habit Tracker </div>
    </body>
</html>
