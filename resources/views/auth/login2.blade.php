<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
  <link rel="icon" href="{{ asset('storage/images/favicon/favicon.png') }}" type="image/x-icon"/>
  <title>Login {{ config('app.name') }}</title>
  @include('layouts.head-css')
</head>
  <body class="main-body leftmenu">

  @include('layouts.preloader')

  <div class="page main-signin-wrapper">
    <div class="row signpages text-center">
      <div class="col-md-12">
        <div class="card">
          <div class="row row-sm">
            <div class="col-lg-6 col-xl-5 d-flex justify-content-center item d-lg-block text-center bg-primary details">
              <div class="mt-5 pt-4 p-2 pos-absolute">
                <img src="{{ asset('storage/images/thumbnail/logo-light.png') }}" class="header-brand-img mb-4" alt="logo">
                <div class="clearfix"></div>
                <img src="{{ asset('assets/img/svgs/user.svg') }}" class="ht-100 mb-0" alt="user">
                <h5 class="mt-4 text-white">{{ config('app.name') }}</h5>
                <span class="tx-white-6 tx-13 mb-5 mt-xl-0">Indonesia Trans Network, Say No to Slow Internet</span>
              </div>
            </div>
            <div class="col-lg-6 col-xl-7 col-xs-12 col-sm-12 login_form ">
              <div class="container-fluid">
                <div class="row row-sm">
                  <div class="card-body mt-2 mb-2">
                    <img src="{{ asset('storage/images/thumbnail/logo.png') }}"
                         class=" d-lg-none header-brand-img text-start float-start mb-4" alt="logo">
                    <div class="clearfix"></div>
                    <form method="POST" action="{{ route('backend.login') }}">
                      @csrf
                      <h5 class="text-start mb-2">Login {{ config('app.name') }}</h5>
                      <p class="mb-4 text-muted tx-13 ms-0 text-start">Akses Login {{ config('app.name') }}</p>
                      <div class="form-group text-start">
                        <label>Email</label>
                        <input name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', '') }}" id="username" placeholder="Masukan Email / Username"
                               autocomplete="email"
                               autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                      <div class="form-group text-start">
                        <label>Password</label>
                        <input class="form-control l @error('password]') is-invalid @enderror"
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
                      <button class="btn ripple btn-main-primary btn-block">Sign In</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  </body>
</html>
