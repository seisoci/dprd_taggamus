@extends('layouts.master')

@section('content')
  <div class="col-lg-12">
    <div class="card custom-card overflow-hidden">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered border-bottom w-100" id="Datatable">
            <thead>
            <tr>
              <th>Nama Judul</th>
              <th>Tgl. Dibuat</th>
              <th>Total</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
              <th colspan="2">Total</th>
              <th></th>
            </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('css')
@endsection
@section('script')
  <script>
    $(document).ready(function () {
      let dataTable = $('#Datatable').DataTable({
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[1, 'desc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: {
          url: "{{ route('backend.dashboard.show') }}",
          data: function (d) {
            d.date_start = '{{ $data['date_start'] }}';
            d.date_end = '{{ $data['date_end'] }}';
          }
        },
        columns: [
          {data: 'title', name: 'posts.title'},
          {data: 'publish_at', name: 'posts.publish_at'},
          {data: 'total', name: 'total'},
        ],
        footerCallback: function (row, data, start, end, display) {
          var api = this.api();
          var intVal = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
          };

          let total = api
            .column(2)
            .data()
            .reduce(function (a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          $(api.column(2).footer()).html(`${total}`);
        },
      });


    });
  </script>
@endsection
