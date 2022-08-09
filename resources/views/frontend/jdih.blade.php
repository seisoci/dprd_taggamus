@extends('frontend.template-section')
@section('title_header', 'JDIH')
@section('content')
  <section class="download-page">
    <div class="container">
      <ul id="listJDIH" class="row">
        @foreach($data ?? array() as $item)
          <li class="col-sm-6" data-aos="fade-up" data-aos-duration="1500">
            <a href="{{ route('backend.jdih.show', $item['slug']) }}" class="cage-download">
              <div class="icon">
                <i class="fas fa-download"></i>
              </div>
              <div class="description">
                <h3>{{ $item['title'] ?? '' }}</h3>
                <p>{{ $item['synopsis'] ?? '' }}</p>
              </div>
            </a>
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
            $('#listJDIH').last().append(response.html);
          } else {
            $('#loadMore').prop('disabled', true);
            $('#loadMore').parent().css('visibility', 'hidden');
          }
        });
      });
    });
  </script>
@endsection
