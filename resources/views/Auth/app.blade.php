<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WebGIS PUPR | @yield('title') </title>
    <link href="/bootstrap5/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="/img/Banjarmasin_Logo.png" type="image/x-icon">
    {{-- @vite('resources/css/app.css') --}}
    @yield('leafletJS')
    @yield('loadCSS')
    <style>
        .primary-white {
            background-color: rgb(48, 48, 114);
            color: white;
        }

        .primary-white:hover {
            background-color: rgba(48, 48, 114, 0.712);
            color: white;
        }
    </style>
</head>

<body>

    @yield('content')


    @include('sweetalert2::index')

    <script src="/bootstrap5/js/bootstrap.bundle.min.js"></script>
    @yield('loadJS')

</body>

</html>
