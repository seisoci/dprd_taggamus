@extends('layouts.master')
@section('content')
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header d-flex justify-content-end">
        <a class="btn btn-primary my-2 btn-icon-text" href="#" data-bs-toggle="modal"
           data-bs-target="#modalCreate">
          <i class="fe fe-plus"></i> Tambah
        </a>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped border-bottom w-100" id="Datatable">
            <thead>
            <tr>
              <th>Nama Kategori Post</th>
              <th>Tipe</th>
              <th>Tgl Dibuat</th>
              <th>Aksi</th>
            </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  {{--Modal--}}
  <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah</h5>
          <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="formStore" method="POST" action="{{ route('backend.post-categories.store') }}">
          @csrf
          <div class="modal-body">
            <div id="errorCreate" style="display:none;">
              <div class="alert alert-danger" role="alert">
                <div class="alert-text">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Nama Kategori Post <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control">
            </div>
            <div class="form-group">
              <label>Tipe <span class="text-danger">*</span></label>
              <select name="type" class="form-control">
                <option value="posts">Berita</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalmodalEdit" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah</h5>
          <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="formUpdate" action="#">
          @method('PUT')
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <div class="modal-body">
            <div id="errorEdit" class="form-control" style="display:none;">
              <div class="alert alert-danger" role="alert">
                <div class="alert-text">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Nama Kategori Post <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control"/>
            </div>
            <div class="form-group">
              <label>Tipe <span class="text-danger">*</span></label>
              <select name="type" class="form-control">
                <option value="posts">Berita</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDeleteLabel">Hapus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @method('DELETE')
        <div class="modal-body">
          <a href="" class="urlDelete" type="hidden"></a>
          Apa anda yakin ingin menghapus data ini?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button id="formDelete" type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('css')
@endsection
@section('script')
  <script src="{{ asset('assets/plugins/ratings-2/jquery.star-rating.js') }}"></script>
  <script src="{{ asset('assets/plugins/ratings-2/star-rating.js') }}"></script>
  <script>
    $(document).ready(function () {
      let modalCreate = document.getElementById('modalCreate');
      const bsCreate = new bootstrap.Modal(modalCreate);
      let modalEdit = document.getElementById('modalEdit');
      const bsEdit = new bootstrap.Modal(modalEdit);
      let modalDelete = document.getElementById('modalDelete');
      const bsDelete = new bootstrap.Modal(modalDelete);

      let dataTable = $('#Datatable').DataTable({
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[1, 'desc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 25,
        ajax: {
          url: "{{ route('backend.post-categories.index') }}",
          data: function (d) {
          }
        },
        columns: [
          {data: 'name', name: 'name'},
          {data: 'type', name: 'type'},
          {
            data: 'created_at',
            name: 'created_at',
            className: 'text-center'
          },
          {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
      });

      modalCreate.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=name]').value = '';
      });

      modalEdit.addEventListener('show.bs.modal', function (event) {
        let name = event.relatedTarget.getAttribute('data-bs-name');
        this.querySelector('input[name=name]').value = name;
        this.querySelector('#formUpdate').setAttribute('action', '{{ route("backend.post-categories.index") }}/' + event.relatedTarget.getAttribute('data-bs-id'));
      });

      modalEdit.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=name]').value = '';
        this.querySelector('#formUpdate').setAttribute('href', '');
      });

      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.post-categories.index") }}/' + id);
      });
      modalDelete.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('.urlDelete').setAttribute('href', '');
      });

      $("#formStore").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let btnSubmit = form.find("[type='submit']");
        let btnSubmitHtml = btnSubmit.html();
        let url = form.attr("action");
        let data = new FormData(this);
        $.ajax({
          beforeSend: function () {
            btnSubmit.addClass("disabled").html("<i class='spinner-border spinner-border-sm font-size-16 align-middle me-2'></i> Loading ...").prop("disabled", "disabled");
          },
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url: url,
          data: data,
          success: function (response) {
            let errorCreate = $('#errorCreate');
            errorCreate.css('display', 'none');
            errorCreate.find('.alert-text').html('');
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              dataTable.draw();
              bsCreate.hide();
            } else {
              toastr.error((response.message ? response.message : "Gagal menambah data"), 'Failed !');
              if (response.error !== undefined) {
                errorCreate.removeAttr('style');
                $.each(response.error, function (key, value) {
                  errorCreate.find('.alert-text').append('<span style="display: block">' + value + '</span>');
                });
              }
            }
          },
          error: function (response) {
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            toastr.error(response.responseJSON.message, 'Failed !');
          }
        });
      });
      $("#formUpdate").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let btnSubmit = form.find("[type='submit']");
        let btnSubmitHtml = btnSubmit.html();
        let url = form.attr("action");
        let data = new FormData(this);
        $.ajax({
          beforeSend: function () {
            btnSubmit.addClass("disabled").html("<i class='spinner-border spinner-border-sm font-size-16 align-middle me-2'></i> Loading ...").prop("disabled", "disabled");
          },
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url: url,
          data: data,
          success: function (response) {
            let errorEdit = $('#errorEdit');
            errorEdit.css('display', 'none');
            errorEdit.find('.alert-text').html('');
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              dataTable.draw();
              bsEdit.hide();
            } else {
              toastr.error((response.message ? response.message : "Gagal mengubah data"), 'Failed !');
              if (response.error !== undefined) {
                errorEdit.removeAttr('style');
                $.each(response.error, function (key, value) {
                  errorEdit.find('.alert-text').append('<span style="display: block">' + value + '</span>');
                });
              }
            }
          },
          error: function (response) {
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            toastr.error(response.responseJSON.message, 'Failed !');
          }
        });
      });
      $("#formDelete").click(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = modalDelete.querySelector('.urlDelete').getAttribute('href');
        let btnHtml = form.html();
        let spinner = $("<i class='spinner-border spinner-border-sm font-size-16 align-middle me-2'></i>");
        $.ajax({
          beforeSend: function () {
            form.text(' Loading. . .').prepend(spinner).prop("disabled", "disabled");
          },
          type: 'DELETE',
          url: url,
          dataType: 'json',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          success: function (response) {
            form.text('Submit').html(btnHtml).removeAttr('disabled');
            bsDelete.hide();
            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              dataTable.draw();
            } else {
              toastr.error((response.message ? response.message : "Gagal menghapus data"), 'Failed !');
            }
          },
          error: function (response) {
            toastr.error(response.responseJSON.message, 'Failed !');
            form.text('Submit').html(btnHtml).removeAttr('disabled');
            bsDelete.hide();
          }
        });
      });
    });
  </script>
@endsection
