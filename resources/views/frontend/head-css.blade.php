<head>
  <meta charset="utf-8">
  {!! SEO::generate() !!}

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>{{ Menu::settings()['web_title']['value'] ?? '' }}</title>
  <link rel="apple-touch-icon" href="{{ asset("/storage/images/assets/".Menu::settings()['favicon_url']['value']) }}">
  <link rel="icon" type="image/x-icon"
        href="{{ asset("/storage/images/assets/".Menu::settings()['favicon_url']['value']) }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/frontend/styles.css') }}">
  @yield('front-css')
</head>
