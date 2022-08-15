@extends('frontend.template-section')
@section('title_header', 'JDIH')
@section('content')
  <section class="download-page">
    <div class="container">
      <h1 class="title text-center" style="font-size: 32px; font-weight: bold;">{{ $data['title'] ?? '' }}</h1>
      <p class="text-center mb-5">
        {!! $data['body'] ?? '' !!}
      </p>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
          <tr>
            <th>No</th>
            <th>Title</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          @foreach($data['datastorage'] ?? array() as $item)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $item['name'] }}</td>
              <td class="text-center"><a href="{{ '/storage/document/'.$item['name'] }}" class="btn-download-file"><i class="fas fa-download"></i></a></td>
            </tr>
          @endforeach
          </tbody>
        </table>
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
