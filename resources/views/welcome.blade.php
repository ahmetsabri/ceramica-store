<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Abo Rahma</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
    </head>
    <body>
      <div id="app">
        <home></home>
      </div>
      <script src="{{asset('js/app.js')}}"></script>
    </body>
</html>
