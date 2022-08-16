<!doctype html>
<html lang="en" dir="ltr">

<head>

  <!-- META DATA -->
  <meta charset="UTF-8">
  <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Sash – Bootstrap 5  Admin & Dashboard Template">
  <meta name="author" content="Spruko Technologies Private Limited">
  <meta name="keywords"
        content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

  <!-- FAVICON -->
  <link rel="shortcut icon" type="image/x-icon" href="../assets/images/brand/favicon.ico"/>

  <!-- TITLE -->
  <title>Sash – Bootstrap 5 Admin & Dashboard Template</title>

  <!-- BOOTSTRAP CSS -->
  @include('layouts.head-css')
  <style>
    .validate-input a {
      border-top-right-radius: 0 !important;
      border-bottom-right-radius: 0 !important;
    }

    .wrap-login100 {
      max-width: 400px;
      width: 400px;
    }
  </style>
</head>

<body class="app sidebar-mini ltr">

<!-- BACKGROUND-IMAGE -->
<div class="login-img">
  <!-- PAGE -->
  <div class="page">
    <div>
      <div class="col col-login mx-auto mt-7">
        <div class="text-center">
          <img src="../assets/images/brand/logo-white.png" class="header-brand-img" alt="">
        </div>
      </div>

      <div class="container-login100">
        <div class="wrap-login100 p-6">
                    <span class="login100-form-title pb-5">
                        Login
                    </span>
          <div class="panel-body tabs-menu-body p-0 pt-5">
            <form method="POST" action="{{ route('backend.login') }}">
              @csrf
              <div class="wrap-input100 validate-input input-group">
                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                  <i class="fa-solid fa-at text-muted"></i>
                </a>
                <input name="email"
                       class="input100 border-start-0 form-control ms-0 @error('email') is-invalid @enderror"
                       value="{{ old('email', '') }}" id="username"
                       placeholder="Email / Username"
                       autocomplete="email"
                       autofocus>
                @error('email')
                <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>
              <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                  <i class="fa-solid fa-eye" aria-hidden="true"></i>
                </a>
                <input
                  class="input100 border-start-0 form-control ms-0 @error('password]') is-invalid @enderror"
                  placeholder="Masukan password" name="password" type="password">
                @error('password')
                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
              </div>
              <div class="form-group text-start">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember"
                         id="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="form-check-label" for="remember">
                    Remember me
                  </label>
                </div>
              </div>
              <button type="submit" class="login100-form-btn btn btn-secondary">
                Login
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/icons/font-awesome/js/all.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
<!-- Perfect-scrollbar js -->
<script src="{{ asset('assets/js/components/perfect-scrollbar.js') }}"></script>
<!-- Custom js -->
<script src="{{ asset('assets/js/components/custom.js') }}"></script>

<!-- 3rd Party -->
<script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/components/show-password.min.js') }}"></script>

</body>

</html>
