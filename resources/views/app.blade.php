<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">

    <link href="{{asset('css/font-awesome.css')}}" rel="stylesheet"/>
    <link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet"/>

    <link href="{{asset('css/main.css')}}" rel="stylesheet"/>

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
<div id="app"></div>

<script src="{{asset('js/lib/jquery/jquery.js')}}"></script>

<script src="{{asset('js/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js')}}"></script>

<script src="{{asset('js/lib/moment/moment.js')}}"></script>

<script src="{{asset('js/main.js')}}" type="text/javascript"></script>

<script src="{{asset('js/ResizeSensor.js')}}"></script>

<script src="{{mix('js/app.js')}}" type="text/javascript"></script>

</body>
</html>
