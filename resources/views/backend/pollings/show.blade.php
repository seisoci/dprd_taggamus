@extends('layouts.master')

@section('content')
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <button class="btn btn-sm btn-primary" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered border-bottom w-100" id="Datatable">
            <thead>
            <tr>
              <th>Jawaban</th>
              <th>Total</th>
            </tr>
            </thead>
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
        order: [[1, 'title']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: {
          url: "{{ route('backend.pollings.show', $id) }}",
        },
        columns: [
          {data: 'name', name: 'name'},
          {data: 'answers_count', name: 'answers_count'},
        ],
      });
    });
  </script>
@endsection
