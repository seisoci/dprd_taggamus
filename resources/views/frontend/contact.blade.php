@extends('frontend.template-section')
@section('title_header', 'Kontak')
@section('content')

  <section class="contact">
    <div class="row justify-content-center">
      <div class="col-6 col-sm-4">
        <div class="box">
          <a href="" class="content">
            <div class="icon">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="description">
              <h1>Address</h1>
              <h3>{{ Menu::settings()['address']['value'] ?? '' }}</h3>
            </div>
          </a>
        </div>
      </div>
      <div class="col-6 col-sm-4">
        <div class="box">
          <a href="" class="content">
            <div class="icon">
              <i class="fas fa-envelope"></i>
            </div>
            <div class="description">
              <h1>Email Us</h1>
              <h3>{{ Menu::settings()['email']['value'] ?? '' }}</h3>
            </div>
          </a>
        </div>
      </div>
      <div class="col-6 col-sm-4">
        <div class="box">
          <a href="" class="content">
            <div class="icon">
              <i class="fas fa-phone-alt"></i>
            </div>
            <div class="description">
              <h1>Call Us</h1>
              <h3>{{ Menu::settings()['telp']['value'] ?? '' }}</h3>
            </div>
          </a>
        </div>
      </div>
    </div>
    <div class="row no-gutters">
      <div class="col-sm-5">
        <form action="{{ route('frontend.contact.store') }}" class="form-contact" method="POST">
          @if($success = Session::get('success'))
            <div class="alert alert-success mt-2">
              <p>{{ $success }}</p>
            </div>
          @endif
          @csrf
          <h1>Send Your Messages</h1>
          <p>Lets Work Together</p>
          <div class="form-group">
            <label for="">Nama</label>
            <input name="name" type="text" class="form-control form-control-sm" value="{{ old('name') }}">
            @error('name')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="">Email</label>
            <input name="email" type="text" class="form-control form-control-sm"  value="{{ old('email') }}">
            @error('email')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="">Pesan</label>
            <textarea name="description" id="" rows="10" class="form-control form-control-sm">{{ old('description') }}</textarea>
            @error('description')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group mt-2">
            <div class="col-md-6 captcha">
              <span>{!! captcha_img('flat') !!}</span>
              <button type="button" class="btn btn-danger reload" id="reload">
                &#x21bb;
              </button>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="d-block">Tuliskan kode captcha di atas</label>
            <input name="captcha" type="text" class="form-control form-control-sm" style="width: 50%;">
          </div>
          @if ($errors->any())
            <div class="alert alert-danger mt-2">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <div class="btn-grouping mt-2">
            <button type="submit" class="btn btn-sm btn-submit">Submit</button>
          </div>
        </form>
      </div>
      <div class="col-sm-7">
        <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1016789.6329995744!2d104.70799836562497!3d-5.442084299999982!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40db42c0a19855%3A0x350bcbb5106ec5a7!2sDinas%20Perumahan%20Dan%20Kawasan%20Permukiman%20Provinsi%20Lampung!5e0!3m2!1sid!2sid!4v1598629881526!5m2!1sid!2sid" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe> -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1985.798117129901!2d104.68106595800684!3d-5.478044379046152!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e47210c9c280019%3A0xcffe45b2602716c2!2sDPRD%20Kab.Tanggamus!5e0!3m2!1sid!2sid!4v1659424614370!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </section>
@endsection
@section('front-css')
  <link rel="stylesheet" href="{{ asset('assets/plugins/aos/dist/aos.css') }}">
@endsection
@section('script')
  <script src="{{ asset('assets/plugins/aos/dist/aos.js') }}"></script>
  <script>
    $(document).ready(function () {
      $('#reload').click(function () {
        $.ajax({
          type: 'GET',
          url: '{{ route('frontend.contact.reload-captcha') }}',
          success: function (data) {
            $(".captcha span").html(data.captcha);
          }
        });
      });
    });
  </script>
@endsection
