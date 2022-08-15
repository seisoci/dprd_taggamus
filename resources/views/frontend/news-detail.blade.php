@extends('frontend.template-section')
@section('title_header', 'Berita')
@section('content')
  <section class="news-detail">
    <div class="image-featured"
         style="background-image: url('{{ $data['image'] ? asset("/storage/images/thumbnail/".$data['image']) : '' }}');">
      <div class="category">{{ $data['categories'] ?? '' }}</div>
    </div>
    <div class="container">
      <h1 class="title">{{ $data['title'] ?? '' }}</h1>
      <div
        class="head">{{ isset($data['publish_at']) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data['publish_at'])->isoFormat('DD MMMM YYYY') : '' }}</div>
      <div class="content">
        {!! $data['body'] ?? '' !!}
      </div>
      <div class="share-container">
        <ul>
          <li><span>Share</span></li>
          <li><a href="https://www.facebook.com/sharer.php?u={{ route('frontend.berita.show', $data['slug']) }}"><i class="fab fa-facebook-f"></i></a></li>
          <li><a href="https://twitter.com/share?url={{ route('frontend.berita.show', $data['slug']) }}"><i class="fab fa-twitter"></i></a></li>
        </ul>
      </div>
      <div class="more-photos" data-aos="fade-up" data-aos-duration="1500">
        <div class="swiper-container swiper2">
          <div class="swiper-wrapper gallery-container">
            @foreach($data['datastorage'] ?? array() as $item)
              <div class="swiper-slide"
                   data-src="{{ $item['name'] ? asset("/storage/images/thumbnail/".$item['name']) : '' }}">
                <img src="{{ $item['name'] ? asset("/storage/images/thumbnail/".$item['name']) : '' }}"
                     class="img-fluid" alt="">
                <div class="description">
                  <a href="" class="btn btn-sm btn-album">See Photo</a>
                </div>
              </div>
            @endforeach
          </div>
          <div class="swiper-pagination"></div>
          <div class="swiper-scrollbar"></div>
          <div class="swiper-navigation">
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('front-css')
  <link rel="stylesheet" href="{{ asset('assets/plugins/aos/dist/aos.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/lightgallery/dist/css/lightgallery.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/swiper/swiper-bundle.css') }}">
@endsection
@section('script')
  <script src="{{ asset('assets/plugins/lightgallery/dist/js/lightgallery-all.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/aos/dist/aos.js') }}"></script>
  <script src="{{ asset('assets/plugins/swiper/swiper-bundle.js') }}"></script>
  <script>
    $(document).ready(function () {
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

      $('.gallery-container').lightGallery();
    });
  </script>
@endsection
