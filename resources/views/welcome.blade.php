<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/view-design/dist/styles/iview.css">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/responsive.css') }}">
    
    <link rel="shortcut icon" href="/img/duare-logo.png">

    <title>Duare admin Web!</title>
  
</head>

<body>
    <script>
        (function() {
            window.Laravel = {
                csrfToken: '{{ csrf_token() }}'
            };
            @if(Auth::check())
            window.authUser = {!! Auth::user() !!}
            @else
            window.authUser = false
            @endif
        })(); 
    </script>
    <div id="app">
        <mainapp></mainapp>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyBU7GqUxT98kSlVbD0iFMijQOQFZUbgA7Q"></script> -->
     
</body>

</html>
<style></style>