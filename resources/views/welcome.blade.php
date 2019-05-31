<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Br-system</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    </head>
    <body>

        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Кабинет</a>
                    @else
                        <a href="{{ route('login') }}">Вход</a>

            
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Br-system
                </div>


            </div>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
