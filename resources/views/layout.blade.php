<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <base href="{{ url('/') }}" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="{{ url('/favicon.png') }}">

  {!! SEO::generate() !!}

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ mix('/css/all.css') }}">
</head>
<body>
    @include('partials.header')
  
    @yield('content')

    @include('partials.footer')
</body>
</html>
