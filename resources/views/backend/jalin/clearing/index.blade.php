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
              <label>Bank <span class="text-danger">*</span></label>
              <select id="select2Bank" class="form-select">
              </select>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered border-bottom w-100" id="Datatable">
            <thead>
            <tr>
              <th>Tgl Upload</th>
              <th>Kode Bank</th>
              <th>Nama Bank</th>
              <th>Kewajiban Gross</th>
              <th>Hak Gross</th>
              <th>Kewajiban Net</th>
              <th>Hak Net</th>
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
          url: "{{ route('backend.jalin_clearing.index') }}",
          data: function (d) {
            d.tgl = $('#dateUploadAwal').val();
            d.kode_bank = $('#select2Bank').find(':selected').val();
          }
        },
        buttons: ['copy', 'excel', 'colvis'],
        columns: [
          {data: 'tgl', name: 'tgl'},
          {data: 'kode_bank', name: 'kode_bank'},
          {data: 'nama_bank', name: 'nama_bank'},
          {
            data: 'kewajiban_gross',
            name: 'kewajiban_gross',
            className: 'text-end',
            render: $.fn.dataTable.render.number(',', '.', 0, '')
          },
          {
            data: 'hak_gross',
            name: 'hak_gross',
            className: 'text-end',
            render: $.fn.dataTable.render.number(',', '.', 0, '')
          },
          {
            data: 'kewajiban_net',
            name: 'kewajiban_net',
            className: 'text-end',
            render: $.fn.dataTable.render.number(',', '.', 0, '')
          },
          {
            data: 'hak_net',
            name: 'hak_net',
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

      $('#select2Bank').select2({
        placeholder: 'Cari Bank',
        dropdownParent: $('#select2Bank').parent(),
        allowClear: true,
        width: '100%',
        ajax: {
          url: "{{ route('backend.banks.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('change', function () {
        dataTable.draw();
      });

    });
  </script>
@endsection
