<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/css/main.css">
        <title>Mapa de Viviendas</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    </head>
    <body class="font-sans bg-blue-900">
      <!--  <nav class="border-b border-gray-200">
            <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between px-4 py-6">
                <ul class="flex flex-col md:flex-row items-center">
                    <li>
                    <a href="">

                    </a>
                    </li>
                </ul>

            </div>

        </nav> -->
        <div class="container mx-auto  pt-8 " id="app">
                <mapa-component
                    :data="{{ json_encode($data) }}"
                    :datasat="{{ json_encode($datasat) }}"
                    :estados="{{ json_encode($estados) }}"
                    :departamento="{{ json_encode($departamentos)}}"
                    :programas="{{ json_encode($programas)}}"
                    >
                </mapa-component>
        </div>
        <div>

        </div>
    <script src="js/app.js"></script>
    </body>
</html>
