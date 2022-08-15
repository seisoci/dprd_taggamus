@extends('frontend.template-section')
@section('title_header', 'List Photo')
@section('content')
  <section class="gallery-detail">
    <div class="container">
      <div class="content">
        <h1 class="head-title" data-aos="fade-up" data-aos-duration="1500">{{ $post['title'] ?? '' }}</h1>
        <div id="detailGallery" class="row gallery-container gallery-container-list">
          @foreach($data ?? array() as $item)
            <a href="{{ $item['name'] ? asset("/storage/images/thumbnail/".$item['name']) : asset('assets/plugins/dist/images/header/bg-3.jpg') }}"
               class="col-sm-6 mb-4 gallery-item" data-aos="fade-up" data-aos-duration="1500">
              <div class="cage-image">
                <img src="{{ $item['name'] ? asset("/storage/images/thumbnail/".$item['name']) : '' }}"
                     class="img-fluid" alt="">
              </div>
            </a>
          @endforeach
        </div>
        @if($data->hasMorePages())
          <div class="btn-container" data-aos="fade-up" data-aos-duration="1500">
            <a href="#" id="loadMore" data-page="1" class="btn btn-sm btn-gallery">Load More</a>
          </div>
        @endif
      </div>
      <div class="more-album" data-aos="fade-up" data-aos-duration="1500">
        <h1 class="title">Album Lainnya</h1>
        <div class="swiper-container swiper2">
          <div class="swiper-wrapper">
            @foreach($anotherAlbum ?? array() as $item)
              <div class="swiper-slide">
                <img style="height: 200px; width:100%" src="{{ $item['image'] ? asset("/storage/images/thumbnail/".$item['image']) : '' }}" class="img-fluid" alt="">
                <div class="description">
                  <a href="{{ route('frontend.galleries.show', $item['slug']) }}"><h1 class="title">{{ $item['title'] ?? '' }}</h1></a>
                  <a href="{{ route('frontend.galleries.show', $item['slug']) }}" class="btn btn-sm btn-album">See Album</a>
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
      var $lg = $('.gallery-container');
      var $container = $('.gallery-container-list').masonry({
        itemSelector: '.gallery-item'
      });

      $('#loadMore').on('click', function (e) {
        e.preventDefault();
        let page = $('#loadMore').attr("data-page");
        page++;
        let form = $(this);
        let btnHtml = form.html();
        let spinner = $("<i class='spinner-border spinner-border-sm font-size-16 align-middle me-2'></i>");
        $.ajax({
          url: '?page=' + page,
          type: "GET",
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          beforeSend: function () {
            form.text(' Loading. . .').append(spinner).prop("disabled", "disabled");
          },
        }).done(function (response) {
          form.text('Load More').html(btnHtml).removeAttr('disabled');
          $('#loadMore').attr("data-page", page);
          if (response.html) {
            $('#detailGallery').last().append(response.html);
            setTimeout(function () {
              $lg.on('onBeforeClose.lg', function (event, index, fromTouch, fromThumb) {
                try {
                  $lg.data('lightGallery').destroy(true);
                } catch (ex) {
                }
                ;
              });
              $('.gallery-container-list').masonry('reloadItems');
              $('.gallery-container-list').masonry();
              initImage();
            }, 1000);
          } else {
            $('#loadMore').prop('disabled', true);
            $('#loadMore').parent().css('visibility', 'hidden');
          }
        });
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

      $lg.lightGallery();
      setTimeout(function () {
        $lg.masonry({
          itemSelector: '.gallery-item'
        });
        $('.gallery-container-list').masonry({
          itemSelector: '.gallery-item'
        });
      }, 1000);

    });
  </script>
@endsection
