@extends('frontend.template-section')
@section('title_header', 'Gallery Video')
@section('content')
  <section class="gallery-video">
    <div class="container">
      <ul id="listVideo">
        @foreach($data ?? array() as $item)
          @if($loop->odd)
          <li>
            <div class="row g-0">
              <div class="col-sm-6 order-2 order-sm-1" data-aos="fade-right" data-aos-duration="1500">
                <div class="description">
                  <h1>{{ $item['title'] ?? '' }}</h1>
                  <div class="head">{{ isset($item['publish_at']) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['publish_at'])->isoFormat('DD MMMM YYYY') : '' }}</div>
                  <div class="share-container">
                    <ul>
                      <li><span>Share</span></li>
                      <li><a href="https://www.facebook.com/sharer.php?u={{ route('frontend.videos.index') }}"><i class="fab fa-facebook-f"></i></a></li>
                      <li><a href="https://twitter.com/share?url={{ route('frontend.videos.index') }}"><i class="fab fa-twitter"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 order-1 order-sm-2 prv" data-aos="fade-left" data-aos-duration="1500">
                <div class="video-cage">
                  <iframe width="100%" height="100%" src="{{ $item['body'] ?? '' }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
              </div>
            </div>
          </li>
          @elseif($loop->even)
          <li>
            <div class="row g-0">
              <div class="col-sm-6 prv" data-aos="fade-right" data-aos-duration="1500">
                <div class="video-cage">
                  <iframe width="100%" height="100%" src="{{ $item['body'] ?? '' }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
              </div>
              <div class="col-sm-6" data-aos="fade-left" data-aos-duration="1500">
                <div class="description">
                  <h1>{{ $item['title'] ?? '' }}</h1>
                  <div class="head">{{ isset($item['publish_at']) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['publish_at'])->isoFormat('DD MMMM YYYY') : '' }}</div>
                  <div class="share-container">
                    <ul>
                      <li><span>Share</span></li>
                      <li><a href="https://www.facebook.com/sharer.php?u={{ route('frontend.videos.index') }}"><i class="fab fa-facebook-f"></i></a></li>
                      <li><a href="https://twitter.com/share?url={{ route('frontend.videos.index') }}"><i class="fab fa-twitter"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </li>
          @endif
        @endforeach
      </ul>
      @if($data->hasMorePages())
        <div class="btn-container" data-aos="fade-up" data-aos-duration="1500">
          <a href="#" id="loadMore" data-page="1" class="btn btn-sm btn-gallery">Load More</a>
        </div>
      @endif
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
            $('#listVideo').last().append(response.html);
          } else {
            $('#loadMore').prop('disabled', true);
            $('#loadMore').parent().css('visibility', 'hidden');
          }
        });
      });


    });
  </script>
@endsection
