@extends('frontend.template-section')
@section('title_header', 'Anggota')
@section('content')
  <section class="staff">
    <div class="container">
      <div class="cage-staff-card">
        <div class="staff-card">
          <img src="dist/images/staff-empty.png" alt="">
          <div class="head-title">
            <h1 class="name">{{ $data['name'] }}</h1>
            <p>{{ $data['komisi']['name'] ?? '' }}</p>
          </div>
          <div class="content">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="ikhtisar-tab" data-bs-toggle="tab" data-bs-target="#ikhtisar-tab-pane" type="button" role="tab" aria-controls="ikhtisar-tab-pane" aria-selected="true">Ikhtisar</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="pendidikan-tab" data-bs-toggle="tab" data-bs-target="#pendidikan-tab-pane" type="button" role="tab" aria-controls="pendidikan-tab-pane" aria-selected="false">Pendidikan</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="pekerjaan-tab" data-bs-toggle="tab" data-bs-target="#pekerjaan-tab-pane" type="button" role="tab" aria-controls="pekerjaan-tab-pane" aria-selected="false">Pekerjaan</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="organisasi-tab" data-bs-toggle="tab" data-bs-target="#organisasi-tab-pane" type="button" role="tab" aria-controls="organisasi-tab-pane" aria-selected="false">Organisasi</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="pergerakan-tab" data-bs-toggle="tab" data-bs-target="#pergerakan-tab-pane" type="button" role="tab" aria-controls="pergerakan-tab-pane" aria-selected="false">Pergerakan</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="penghargaan-tab" data-bs-toggle="tab" data-bs-target="#penghargaan-tab-pane" type="button" role="tab" aria-controls="penghargaan-tab-pane" aria-selected="false">Penghargaan</button>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="ikhtisar-tab-pane" role="tabpanel" aria-labelledby="ikhtisar-tab" tabindex="0">
                <h6 class="mt-2">Email: {{ $data['email'] ?? '' }}</h6>
                <h6 class="mt-2">Tempat dan Tanggal Lahir: {{ $data['place_birth'] ?? '' }}</h6>
                <h6 class="mt-2">Agama: {{ $data['religion'] ?? '' }}</h6>
                <h6 class="mt-2">No Anggota: {{ $data['no_member'] ?? '' }}</h6>
                <h6 class="mt-2">Partai: {{ $data['partai'] ?? '' }}</h6>
                <h6 class="mt-2">Daerah Pemilihan: {{ $data['election_region']['name'] ?? '' }}</h6>
                <h6 class="mt-2">Komisi: {{ $data['komisi']['name'] ?? '' }}</h6>
              </div>
              <div class="tab-pane fade" id="pendidikan-tab-pane" role="tabpanel" aria-labelledby="pendidikan-tab" tabindex="0">
                @foreach($data['education'] ?? array() as $item)
                  <div class="card mt-2" style="background: transparent">
                    <div class="card-body">
                      <h5 class="card-title">{{ $item['name_institution'] ?? '' }}</h5>
                      <p class="card-text">Jurusan: {{ $item['major'] ?? '' }}</p>
                      <p class="card-text">Fakultas: {{ $item['faculty'] ?? '' }}</p>
                      <p class="card-text">Tahun Masuk: {{ $item['entry_year'] ?? '' }}</p>
                      <p class="card-text">Tahun Lulus: {{ $item['graduation_year'] ?? '' }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="tab-pane fade" id="pekerjaan-tab-pane" role="tabpanel" aria-labelledby="pekerjaan-tab" tabindex="0">
                @foreach($data['profession'] ?? array() as $item)
                  <div class="card mt-2" style="background: transparent">
                    <div class="card-body">
                      <h5 class="card-title">{{ $item['name'] ?? '' }}</h5>
                      <p class="card-text">Jabatan: {{ $item['position'] ?? '' }}</p>
                      <p class="card-text">Tahun Masuk: {{ $item['entry_year'] ?? '' }}</p>
                      <p class="card-text">Tahun Keluar: {{ $item['graduation_year'] ?? '' }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="tab-pane fade" id="organisasi-tab-pane" role="tabpanel" aria-labelledby="organisasi-tab" tabindex="0">
                @foreach($data['organization'] ?? array() as $item)
                  <div class="card mt-2" style="background: transparent">
                    <div class="card-body">
                      <h5 class="card-title">{{ $item['name'] ?? '' }}</h5>
                      <p class="card-text">Tahun: {{ $item['year'] ?? '' }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="tab-pane fade" id="pergerakan-tab-pane" role="tabpanel" aria-labelledby="pergerakan-tab" tabindex="0">
                @foreach($data['movement'] ?? array() as $item)
                  <div class="card mt-2" style="background: transparent">
                    <div class="card-body">
                      <h5 class="card-title">{{ $item['name'] ?? '' }}</h5>
                      <p class="card-text">Tahun: {{ $item['year'] ?? '' }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="tab-pane fade" id="penghargaan-tab-pane" role="tabpanel" aria-labelledby="penghargaan-tab" tabindex="0">
                @foreach($data['awards'] ?? array() as $item)
                  <div class="card mt-2" style="background: transparent">
                    <div class="card-body">
                      <h5 class="card-title">{{ $item['name'] ?? '' }}</h5>
                      <p class="card-text">Lokasi: {{ $item['location'] ?? '' }}</p>
                      <p class="card-text">Tahun: {{ $item['year'] ?? '' }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
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
