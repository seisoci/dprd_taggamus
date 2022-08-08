<!DOCTYPE html>
<html lang="en">
@include('frontend.head-css')
<body>
<main>
  @include('frontend.top-home-header')
  @include('frontend.top-page-header')
  @include('frontend.menu-block')

  @yield('content')

  @include('frontend.footer')
</main>

@include('frontend.scripts')
</body>
</html>
