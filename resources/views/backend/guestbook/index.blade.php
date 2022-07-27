@extends('layouts.master')

@section('content')
  <div class="col-lg-12">
    <div class="card custom-card overflow-hidden">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered border-bottom w-100" id="Datatable">
            <thead>
            <tr>
              <th>Nama</th>
              <th>Email</th>
              <th>Tgl. Buat</th>
              <th>Status</th>
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
@endsection
@section('script')
  <script>
    $(document).ready(function () {
      let dataTable = $('#Datatable').DataTable({
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[2, 'desc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: "{{ route('backend.guestbooks.index') }}",
        columns: [
          {
            data: 'name',
            name: 'name',
            render: function (data, type, row, meta) {
              return `<a href="{{ route('backend.guestbooks.index') }}/${row['id']}">${data}</a>`;
            }
          },
          {data: 'email', name: 'email'},
          {data: 'created_at', name: 'created_at', width: '100px',},
          {
            data: 'status',
            name: 'status',
            width: '100px',
            className: 'text-center',
            render: function (data, type, row, meta) {
              let status = {
                '1': {'title': 'Dibaca', 'class': ' bg-success'},
                '0': {'title': 'Belum Dibaca', 'class': ' bg-danger'},
              };
              if (typeof status[data] === 'undefined') {
                return data;
              }
              return '<span class="badge rounded-pill' + status[data].class + '">' + status[data].title +
                '</span>';
            }
          },
        ],
      });

    });
  </script>
@endsection
