<!DOCTYPE html>
<html lang="en">
@section('front-css')
  <link rel="stylesheet" href="{{ asset('assets/plugins/aos/dist/aos.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/lightgallery/dist/css/lightgallery.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/swiper/swiper-bundle.css') }}">
@endsection
@include('frontend.head-css')

<body>
<main>
  @include('frontend.top-home-header')
  @include('frontend.menu-block')
  <section class="slider">
    <div class="swiper-container swiper1">
      <div class="swiper-wrapper">
        @foreach($data['sliders'] ?? array() as $item)
          <div class="swiper-slide"
               style="background-image: url('{{ asset("/storage/images/thumbnail/".$item['image']) }}');"></div>
        @endforeach
      </div>
      <div class="swiper-controls">
        <div class="swiper-pagination"></div>
        <div class="swiper-scrollbar"></div>
        <div class="swiper-navigation">
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
        <div class="swiper-info d-none d-lg-flex justify-content-md-center">
          <a href="">
            <i class="fas fa-phone"></i>
            <div class="text">
              <div class="head">Hubungi Kami</div>
              <div class="content">{{ $data['settings']['telp']['value'] ?? '' }}</div>
            </div>
          </a>
          <a href="">
            <i class="fas fa-envelope"></i>
            <div class="text">
              <div class="head">Kirim email anda di</div>
              <div class="content">{{ $data['settings']['email']['value'] ?? '' }}</div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </section>
  <section class="ticker-headline">
    <div class="container">
      <div class="row g-0">
        <div class="col-sm-3 col-xl-2" data-aos="fade-right" data-aos-duration="1500">
          <div class="title">Agenda Hari Ini</div>
        </div>
        <div class="col-sm-9 col-xl-10" data-aos="fade-left" data-aos-duration="1500">
          <div class="ticker_slide">
            <div class="swiper-wrapper">
              @foreach($data['schedules'] ?? array() as $item)
                <div class="swiper-slide item" data-bs-toggle="modal" data-bs-target="#agendaModal" data-date="{{ isset($item['date_start']) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['date_start'])->isoFormat('DD MMMM YYYY') : '' }}" data-time="{{ isset($item['date_start']) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['date_start'])->isoFormat('HH:mm') : '' }}" data-content="{{ $item['description'] ?? '' }}">
                  <p><span class="badge bg-secondary">Terbaru</span>
                    <span class="time">{{ isset($item['date_start']) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['date_start'])->isoFormat('DD MMMM YYYY HH:mm') : '' }} WIB</span>{{ $item['title'] ?? '' }}</p>
                </div>
              @endforeach
            </div>
            <div class="container-nav">
              <div class="swiper-button-prev"></div>
              <div class="swiper-button-next"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="news-update">
    <div class="container">
      <div class="title" data-aos="fade-up" data-aos-duration="1500">
        <h1>Berita Terkini</h1>
        <p>Dewan Perwakilan Rakyat Daerah Kabupaten Tanggamus</p>
      </div>
      <div class="featured-news" data-aos="fade-up" data-aos-duration="1500">
        <div class="row">
          <div class="col-sm-6 d-flex align-items-center order-2 order-sm-1">
            <div class="description">
              <div
                class="head">{{ isset($data['news'][0]['publish_at']) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data['news'][0]['publish_at'])->isoFormat('DD MMMM YYYY') : '' }}
                | {{ $data['news'][0]['categories'] ?? '' }}</div>
              <a href="{{ route('frontend.berita.show', $data['news'][0]['slug'] ?? '') }}">
                <h1 class="title">{{  $data['news'][0]['title'] ?? '' }}</h1>
                <p>{{ $data['news'][0]['synopsis'] ?? '' }}</p>
              </a>
              <a href="{{ route('frontend.berita.show', $data['news'][0]['slug'] ?? '') }}" class="btn btn-sm btn-link">Read
                More</a>
            </div>
          </div>
          <div class="col-sm-6 d-flex align-items-center order-1 order-sm-2">
            <a href="" class="image-cage">
              <img
                src="{{ isset($data['news'][0]['image']) ? asset("/storage/images/thumbnail/".$data['news'][0]['image']) : asset('assets/plugins/dist/images/header/bg-3.jpg') }}"
                class="img-fluid" alt="">
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="swiper-container swiper2" data-aos="fade-up" data-aos-duration="1500">
      <div class="swiper-wrapper">
        @foreach($data['news'] ?? array() as $item)
          @if(!$loop->first)
            <div class="swiper-slide"
                 style="background-image: url('{{ isset($item['image']) ? asset("/storage/images/thumbnail/".$item['image']) : asset('assets/plugins/dist/images/header/bg-3.jpg') }}');">
              <div class="description">
                <div
                  class="head">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['publish_at'])->isoFormat('DD MMMM YYYY') }}
                  |
                  {{ $item['categories'] ?? '' }}</div>
                <a href="{{ route('frontend.berita.show', $item['slug'] ?? '') }}"><h1
                    class="title">{{ $item['title'] ?? '' }}</h1></a>
              </div>
            </div>
          @endif
        @endforeach
      </div>
      <div class="swiper-controls">
        <div class="swiper-pagination"></div>
        <div class="swiper-scrollbar"></div>
        <div class="swiper-navigation">
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      </div>
    </div>
  </section>
  <section class="document">
    <div class="container">
      <div class="title" data-aos="fade-up" data-aos-duration="1500">
        <h1>Dokumen</h1>
        <p>Dewan Perwakilan Rakyat Daerah Kabupaten Tanggamus</p>
      </div>
      <ul class="row" data-aos="fade-up" data-aos-duration="1500">
        @foreach($data['jdih'] ?? array() as $item)
          <li class="col-sm-6">
            <div class="icon"><i class="fa-solid fa-file-word"></i></div>
            <div class="info">
              <div
                class="date">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['publish_at'])->isoFormat('DD MMMM YYYY') }}</div>
              <a href="{{ route('backend.jdih.show', $item['slug']) }}"><h1 class="title">{{ $item['title'] ?? '' }}</h1></a>
            </div>
          </li>
        @endforeach
      </ul>
      <div class="btn-container" data-aos="fade-up" data-aos-duration="1500">
        <a href="{{ route('frontend.jdih.index') }}" class="btn btn-sm btn-doc">Lihat Dokumen Lainnya</a>
      </div>
    </div>
  </section>
  <section class="gallery">
    <div class="container">
      <div class="title" data-aos="fade-up" data-aos-duration="1500">
        <h1>Gallery</h1>
        <p>Dewan Perwakilan Rakyat Daerah Kabupaten Tanggamus</p>
      </div>
      <div class="row gallery-container" data-aos="fade-up" data-aos-duration="1500">
        @foreach($data['galleries'] as $item)
          <a href="{{ asset("/storage/images/thumbnail/".$item['image']) }}" class="col-sm-6 mb-4 gallery-item">
            <img src="{{ asset("/storage/images/thumbnail/".$item['image']) }}" class="img-fluid" alt="">
          </a>
        @endforeach
      </div>
      <div class="btn-container" data-aos="fade-up" data-aos-duration="1500">
        <a href="{{ route('frontend.galleries.index') }}" class="btn btn-sm btn-gallery">All Image</a>
      </div>
    </div>
  </section>
  @include('frontend.footer')
  <div class="modal fade" id="agendaModal" tabindex="-1" aria-labelledby="agendaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="border: none;">
          <h5 class="modal-title" id="agendaModalLabel" style="background: #c0a03f;color: #fff;padding: 0 10px;border-radius: 3px;">Modal title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h2 id="agendaModalContent" style="font-size: 18px;">...</h2>
        </div>
      </div>
    </div>
  </div>
</main>

@section('script')
  <script src="{{ asset('assets/plugins/lightgallery/dist/js/lightgallery-all.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/aos/dist/aos.js') }}"></script>
  <script src="{{ asset('assets/plugins/swiper/swiper-bundle.js') }}"></script>
@endsection
@include('frontend.scripts')
<script>
  var swiper = new Swiper('.swiper1', {
    loop: false,
    effect: 'fade',
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      type: 'fraction',
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    scrollbar: {
      el: '.swiper-scrollbar',
      hide: false,
    },
  });

  var swiper2 = new Swiper('.swiper2', {
    loop: false,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      type: 'fraction',
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    scrollbar: {
      el: '.swiper-scrollbar',
      hide: false,
    },
    breakpoints: {
      640: {
        slidesPerView: 1,
        spaceBetween: 0,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 0,
      }
    }
  });

  var ticker_slide = new Swiper('.ticker_slide', {
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });

  $('.gallery-container').lightGallery();
  setTimeout(function () {
    $('.gallery-container').masonry({
      itemSelector: '.gallery-item'
    });
    $('.gallery-container-list').masonry({
      itemSelector: '.gallery-item'
    });
  }, 1000);

  $('.ticker_slide > .swiper-wrapper > .item').click(function(){
    var date = $(this).data().date;
    var time = $(this).data().time;
    var content = $(this).data().content;
    $('#agendaModalLabel').html(date+' - '+time);
    $('#agendaModalContent').html(content);
  });
</script>
</body>

</html>
