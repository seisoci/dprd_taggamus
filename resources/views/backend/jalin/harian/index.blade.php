@extends('layouts.master')

@section('content')
  <div class="col-lg-12">
    <div class="card custom-card overflow-hidden">
      <div class="card-body">
        <div class="row">
          <div class="col-3">
            <div class="form-group">
              <label>Tanggal <span class="text-danger">*</span></label>
              <input id="dateUploadAwal" type="text" class="form-control datePicker" readonly/>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label>Jenis <span class="text-danger">*</span></label>
              <select id="select2KodeReport" class="form-select">
                <option value=""></option>
                <option value="54"> 54 - ACQ BERHASIL</option>
                <option value="54A">54A - ACQ BERHASIL - PEMBATALAN</option>
                <option value="54B">5B - ACQ GAGAL</option>
                <option value="54C">54C -ACQ SUSPECT</option>
                <option value="54D">54D -ACQ HASIL REKON</option>
                <option value="54E">54E -ACQ HASIL REKON (SETTLEMENT MANUAL)</option>
                <option value="54F">54F -ACQ HASIL TIDAK TER-SETTLE</option>
                <option value="56">56 - ISS BERHASIL</option>
                <option value="56A">56A - ISS BERHASIL - PEMBATALAN</option>
                <option value="56B">56B - ISS GAGAL</option>
                <option value="56C">56C - ISS SUSPECT</option>
                <option value="56D">56D - ISS HASIL REKON</option>
                <option value="56E">56E - ISS HASIL REKON (SETTLEMENT MANUAL)</option>
                <option value="56F">56F - ISS HASIL TIDAK TER-SETTLE</option>
              </select>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered border-bottom w-100" id="Datatable">
            <thead>
            <tr>
              <th>Tgl Upload</th>
              <th>Kode Report</th>
              <th>Wktu Trans</th>
              <th>Tgl Trans</th>
              <th>Kode Terminal</th>
              <th>No. Kartu</th>
              <th>Jns Message</th>
              <th>Kode Proses</th>
              <th>Nom. Transaksi</th>
              <th>Kode Kesalahan</th>
              <th>Kode Bank ACQ</th>
              <th>Kode Bank ISS</th>
              <th>No. Ref</th>
              <th>Merchant Type</th>
              <th>Kode Retail</th>
              <th>Kode Approval</th>
              <th>Orig Nom Ref</th>
              <th>Fee ISS</th>
              <th>Fee Switch</th>
              <th>Fee LBG SVC</th>
              <th>Fee LBG STD</th>
              <th>Net Nominal</th>
            </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  {{--Modal--}}
@endsection

@section('css')
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('script')
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.js') }}" type="text/javascript"></script>
  <script>
    $(document).ready(function () {
      let dataTable = $('#Datatable').DataTable({
        responsive: false,
        scrollX: true,
        processing: true,
        serverSide: true,
        stateSave: true,
        order: [[0, 'desc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: {
          url: "{{ route('backend.jalin_harian.index') }}",
          data: function (d) {
            d.kode_report = $('#select2KodeReport').find(':selected').val();
            d.tgl = $('#dateUploadAwal').val();
          }
        },
        buttons: ['copy', 'excel', 'colvis'],
        columns: [
          {data: 'tgl', name: 'tgl'},
          {data: 'kode_report', name: 'kode_report'},
          {data: 'trx_time', name: 'trx_time'},
          {data: 'trx_tgl', name: 'trx_tgl'},
          {data: 'kode_terminal', name: 'kode_terminal'},
          {data: 'no_kartu', name: 'no_kartu'},
          {data: 'jenis_message', name: 'jenis_message'},
          {data: 'kode_proses', name: 'kode_proses'},
          {
            data: 'nom_transaksi',
            name: 'nom_transaksi',
            render: $.fn.dataTable.render.number(',', '.', 0, '')
          },
          {data: 'kode_kesalahan', name: 'kode_kesalahan'},
          {data: 'kode_bank_iss', name: 'kode_bank_iss'},
          {data: 'kode_bank_acq', name: 'kode_bank_acq'},
          {data: 'no_ref', name: 'no_ref'},
          {data: 'merchant_type', name: 'merchant_type'},
          {data: 'kode_retail', name: 'kode_retail'},
          {data: 'approval', name: 'approval'},
          {data: 'orig_nom', name: 'orig_nom'},
          {
            data: 'fee_iss',
            name: 'fee_iss',
            className: 'text-end',
            render: $.fn.dataTable.render.number(',', '.', 0, '')
          },
          {
            data: 'fee_switching',
            name: 'fee_switching',
            className: 'text-end',
            render: $.fn.dataTable.render.number(',', '.', 0, '')
          },
          {
            data: 'fee_lbg_svc',
            name: 'fee_lbg_svc',
            className: 'text-end',
            render: $.fn.dataTable.render.number(',', '.', 0, '')
          },
          {
            data: 'fee_lbg_std',
            name: 'fee_lbg_std',
            className: 'text-end',
            render: $.fn.dataTable.render.number(',', '.', 0, '')
          },
          {
            data: 'net_nominal',
            name: 'net_nominal',
            className: 'text-end',
            render: $.fn.dataTable.render.number(',', '.', 0, '')
          },
        ],
        initComplete: function (settings, json) {
          dataTable.buttons().container().appendTo('#Datatable_wrapper .col-md-6:eq(0)')
        }
      });

      $(".datePicker").flatpickr({
        dateFormat: "d M Y",
        disableMobile: true,
        onChange: function (selectedDates, date_str, instance) {
          dataTable.draw();
        },
        onReady: function (dateObj, dateStr, instance) {
          const $clear = $('<button class="btn btn-danger btn-sm flatpickr-clear mb-2">Clear</button>')
            .on('click', () => {
              instance.clear();
              instance.close();
            })
            .appendTo($(instance.calendarContainer));
        }
      });

      $('#select2KodeReport').select2({
        placeholder: 'Cari Kode Report',
        dropdownParent: $('#select2KodeReport').parent(),
        allowClear: true,
        width: '100%',
      }).on('change', function () {
        dataTable.draw();
      });


    });
  </script>
@endsection
