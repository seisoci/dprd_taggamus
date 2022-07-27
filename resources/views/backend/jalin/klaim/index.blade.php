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
          <div class="col-3">
            <div class="form-group">
              <label>Jenis <span class="text-danger">*</span></label>
              <select id="select2Jenis" class="form-select">
                <option value=""></option>
                <option value="acq">ACQUIRER</option>
                <option value="iss">ISSUER</option>
              </select>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered border-bottom w-100" id="Datatable">
            <thead>
            <tr>
              <th>Tgl Upload</th>
              <th>Jenis</th>
              <th>No Report</th>
              <th>Trx Code</th>
              <th>Trx Tgl</th>
              <th>Trx Jam</th>
              <th>Ref No</th>
              <th>Trace No</th>
              <th>Term ID</th>
              <th>No Kartu</th>
              <th>Kode ISS</th>
              <th>Kode ACQ</th>
              <th>MerchantID</th>
              <th>Merchant Location</th>
              <th>Merchant Name</th>
              <th>Nominal</th>
              <th>Merchant Code</th>
              <th>Dispute Trans Code</th>
              <th>Registration Num</th>
              <th>Dispute Amount</th>
              <th>Chargeback Fee</th>
              <th>Fee Return</th>
              <th>Dispute Net Amount</th>
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
          url: "{{ route('backend.jalin_klaim.index') }}",
          data: function (d) {
            d.jenis = $('#select2Jenis').find(':selected').val();
            d.tgl = $('#dateUploadAwal').val();
          }
        },
        buttons: ['copy', 'excel', 'colvis'],
        columns: [
          {data: 'tgl', name: 'tgl'},
          {
            data: 'jenis',
            name: 'jenis',
            render: function (columnData, type, rowData, meta) {
              if (columnData === 'acq') {
                return `ACQ`;
              } else if (columnData === 'iss') {
                return `ISS`;
              }
            }
          },
          {data: 'no_report', name: 'no_report'},
          {data: 'trx_code', name: 'trx_code'},
          {data: 'trx_tgl', name: 'trx_tgl'},
          {data: 'trx_time', name: 'trx_time'},
          {data: 'ref_no', name: 'ref_no'},
          {data: 'trace_no', name: 'trace_no'},
          {data: 'term_id', name: 'term_id'},
          {data: 'no_kartu', name: 'no_kartu'},
          {data: 'kode_iss', name: 'kode_iss'},
          {data: 'kode_acq', name: 'kode_acq'},
          {data: 'marchant_id', name: 'marchant_id'},
          {data: 'marchant_location', name: 'marchant_location'},
          {data: 'marchant_name', name: 'marchant_name'},
          {
            data: 'nominal',
            name: 'nominal',
            render: $.fn.dataTable.render.number(',', '.', 0, '')
          },
          {data: 'marchant_code', name: 'marchant_code'},
          {data: 'dispute_trans_code', name: 'dispute_trans_code'},
          {data: 'registration_num', name: 'registration_num'},
          {
            data: 'dispute_amount',
            name: 'dispute_amount',
            className: 'text-end',
            render: $.fn.dataTable.render.number(',', '.', 0, '')
          },
          {
            data: 'charge_back_fee',
            name: 'charge_back_fee',
            className: 'text-end',
            render: $.fn.dataTable.render.number(',', '.', 0, '')
          },
          {
            data: 'fee_return',
            name: 'fee_return',
            className: 'text-end',
            render: $.fn.dataTable.render.number(',', '.', 0, '')
          },
          {
            data: 'dispute_net_amount',
            name: 'dispute_net_amount',
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

      $('#select2Jenis').select2({
        placeholder: 'Cari Jenis Report',
        dropdownParent: $('#select2Jenis').parent(),
        allowClear: true,
        width: '100%',
      }).on('change', function () {
        dataTable.draw();
      });


    });
  </script>
@endsection
