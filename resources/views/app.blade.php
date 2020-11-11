<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <!-- main css -->
    <link href="{{asset('css/main.css')}}" rel="stylesheet"/>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
<div id="app"></div>

<script src="{{mix('js/app.js')}}" type="text/javascript"></script>
</body>
</html>
