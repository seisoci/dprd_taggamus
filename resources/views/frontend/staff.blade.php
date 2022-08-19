@extends('frontend.template-section')
@section('title_header', 'Anggota')
@section('content')
  <section class="staff-list">
    <div class="container">
      <ul id="listStaff" class="row">
        @foreach($data ?? array() as $item)
          <li class="col-lg-6 col-xl-4">
            <div class="cage-staff-card">
              <div class="staff-card">
                <img src="{{ $item['image'] ? asset("/storage/images/thumbnail/".$item['image']) : asset('assets/plugins/dist/images/staff-empty.png') }}" alt="">
                <h1 class="name">{{ $item['name'] }}</h1>
                <h6 class="m-0"><b>{{ $item['komisi_name'] }}</b></h6>
                <h6 class="mb-4">{{ $item['position'] }}</h6>
                <div class="cage-button">
                  <a href="{{ route('frontend.staff.show', $item['id']) }}" class="btn-card">Lihat Profile</a>
                </div>
              </div>
            </div>
          </li>
        @endforeach
      </ul>
      @if($data->hasMorePages())
        <div class="btn-container">
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
            $('#listStaff').last().append(response.html);
          } else {
            $('#loadMore').prop('disabled', true);
            $('#loadMore').parent().css('visibility', 'hidden');
          }
        });
      });


    });
  </script>
@endsection
