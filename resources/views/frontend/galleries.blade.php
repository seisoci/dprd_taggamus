@extends('frontend.template-section')
@section('title_header', 'Gallery')
@section('content')
  <section class="gallery-list">
    <div class="container">
      <ul id="listGallery" class="row gallery-container-list">
        @foreach($data ?? array() as $item)
          <li class="col-sm-6 gallery-item" data-aos="fade-up" data-aos-duration="1500">
            <div class="content">
              <img src="{{ $item['image'] ? asset("/storage/images/thumbnail/".$item['image']) : '' }}"
                   class="img-fluid" alt="">
              <div class="description">
                <a href="{{ route('frontend.galleries.show', $item['slug']) }}"><h1
                    class="title">{{ $item['title'] }}</h1></a>
                <a href="{{ route('frontend.galleries.show', $item['slug']) }}" class="btn btn-sm btn-album">See
                  Album</a>
              </div>
            </div>
          </li>
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
            $('#listGallery').last().append(response.html);
            setTimeout(function () {
              $('.gallery-container-list').masonry('reloadItems');
              $('.gallery-container-list').masonry();
            }, 1000);
          } else {
            $('#loadMore').prop('disabled', true);
            $('#loadMore').parent().css('visibility', 'hidden');
          }
        });
      });


    });
  </script>
@endsection
