@extends('frontend.template-section')
@section('title_header', 'List Berita')
@section('content')
  <section class="news-list">
    <div class="container">
      <ul id="listNews">
        @foreach($data ?? array() as $item)
          <li>
            <div class="row no-gutters">
              <div class="col-sm-6 order-2 order-sm-1" data-aos="fade-right" data-aos-duration="1500">
                <div class="description">
                  <div
                    class="head">{{ isset($item['publish_at']) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['publish_at'])->isoFormat('DD MMMM YYYY') : '' }}
                    | {{ $item['categories'] ?? '' }}</div>
                  <a href="">
                    <h1 class="title">{{ $item['title'] ?? '' }}</h1>
                    <p>{{ $item['synopsis'] ?? '' }}</p>
                  </a>
                  <div class="cage-link">
                    <a href="{{ route('frontend.news.detail', $item['slug']) }}" class="btn btn-sm btn-link">Read
                      More</a>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 order-1 order-sm-2 nli" data-aos="fade-left" data-aos-duration="1500">
                <a href="{{ route('frontend.news.detail', $item['slug']) }}" class="image-cage">
                  <img src="{{ $item['image'] ? asset("/storage/images/thumbnail/".$item['image']) : '' }}" alt="">
                </a>
              </div>
            </div>
          </li>
        @endforeach
      </ul>
      @if($data->hasMorePages())
        <div class="btn-container" data-aos="fade-up" data-aos-duration="1500">
          <a href="#" id="loadMore" data-page="1" class="btn btn-sm btn-loadmore">Load More</a>
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
            $('#listNews').last().append(response.html);
          } else {
            $('#loadMore').prop('disabled', true);
            $('#loadMore').parent().css('visibility', 'hidden');
          }
        });
      });
    });
  </script>
@endsection
