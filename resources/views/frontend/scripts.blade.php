<script>
  {{ Menu::settings()['google_anality']['value'] ?? '' }}
</script>
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/plugins/frontend/index.js') }}"></script>
@yield('script')
