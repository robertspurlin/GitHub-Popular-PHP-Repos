<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Popular PHP Github Repositories</title>

        <!-- Fonts -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Merriweather:300,700,700italic,300italic|Open+Sans:700,400|Montserrat:400,700">

        <!-- Styles -->
        <link rel="stylesheet" href="/css/app.css" rel="stylesheet">
    </head>

    <body>

        <header>
            <a class="name" href='/'>Robert Spurlin</a>
        </header>

        <div id='app'>
            <github></github>
        </div>

        <footer>
            <script src='/js/app.js' type='text/javascript'></script>
        </footer>

    </body>

</html>
