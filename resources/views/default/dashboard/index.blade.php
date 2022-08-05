@extends('layouts.master')
@section('content')
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <div class="form-group">
            <label for="">Pilih Tanggal</label>
            <input id="reportrange" type="text" class="form-control" readonly>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="text-center">
                <h3>
                  <span class="text-muted" id="totalSeluruh">{{ $data['countPost'] + $data['countTotal'] }}</span>
                </h3>
                <h6 class="text-muted">
                  Total Seluruh Pengunjung
                </h6>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <a id="postClick" href="#">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="text-center">
                  <h3>
                    <span class="text-muted" id="totalPost">{{ $data['countPost'] }}</span>
                  </h3>
                  <h6 class="text-muted">
                    Total Post
                  </h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="text-center">
                <h3>
                  <span class="text-muted" id="totalPengunjung">{{ $data['countTotal'] }}</span>
                </h3>
                <h6 class="text-muted">
                  Total Pengunjung
                </h6>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{--Modal--}}
@endsection
@section('css')
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
@endsection
@section('script')
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
  <script>
    $(document).ready(function () {
      let start = moment();
      let end = moment();

      function cb(start, end) {
        $('#reportrange').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
      }

      $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        locale: {
          format: 'YYYY-MM-DD'
        },
        ranges: {
          'Hari Ini': [moment(), moment()],
          'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          '7 Hari Lalu': [moment().subtract(6, 'days'), moment()],
          '30 Hari Lalu': [moment().subtract(29, 'days'), moment()],
          'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
          'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
          'Tahun Ini': [moment().startOf('year'), moment().endOf('year')],
          'Seluruh': [moment('2022-01-01'), moment().endOf('year')],
        }
      }, cb);

      $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          },
          type:'GET',
          url:'{{ route('backend.dashboard.index') }}',
          data: {
            date_start: picker.startDate.format('YYYY-MM-DD'),
            date_end: picker.endDate.format('YYYY-MM-DD')
          },
          success:function(data) {
            $('#totalSeluruh').html(data.countPost + data.countTotal);
            $('#totalPost').html(data.countPost);
            $('#totalPengunjung').html(data.countTotal);
          }
        });
      });

      $('#postClick').on('click', function(e) {
        e.preventDefault();
        let data = $('#reportrange').val().split(' - ');
        window.location.href = `{{ route('backend.dashboard.show') }}?date_start=${data[0]}&date_end=${data[1]}`;
      });
    });
  </script>
@endsection
