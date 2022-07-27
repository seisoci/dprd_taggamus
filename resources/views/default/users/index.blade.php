@extends('layouts.master')
@section('content')
  <div class="col-lg-12">
    <div class="card">
      <div class="d-flex align-self-end m-4">
        <a class="btn btn-primary btn-icon-text" href="{{ route('backend.users.create') }}">
          <i class="fe fe-plus"></i> Tambah
        </a>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group mx-3">
              <label for="activeSelect">Status Akun <span class="text-danger">*</span></label>
              <select class="form-select select2" id="selectActive" name="active">
                <option value="0" selected>Semua</option>
                <option value="non_active">Tidak Aktif</option>
                <option value="active">Aktif</option>
              </select>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered border-bottom w-100" id="Datatable">
            <thead>
            <tr>
              <th>Foto</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Username</th>
              <th>Status</th>
              <th>Role</th>
              <th>Aksi</th>
            </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  {{--Modal--}}
  <div class="modal fade" id="modalReset" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalResetLabel">Reset Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formReset" method="POST" action="{{ route('backend.users.resetpassword') }}">
          <div class="modal-body">
            @csrf
            <input type="hidden" name="id"></a>
            Anda yakin ingin reset password data ini? <br> (password sama dengan email)
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
          <button id="formDelete" type="button" class="btn btn-primary">Ya</button>
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
      let modalReset = document.getElementById('modalReset');
      const bsReset = new bootstrap.Modal(modalReset);
      let modalDelete = document.getElementById('modalDelete');
      const bsDelete = new bootstrap.Modal(modalDelete);

      let dataTable = $('#Datatable').DataTable({
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[1, 'asc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: {
          url: "{{ route('backend.users.index') }}",
          data: function (d) {
            d.active = $('#selectActive').find(':selected').val();
          }
        },
        columns: [
          {data: 'image', name: 'image'},
          {data: 'name', name: 'name'},
          {data: 'email', name: 'email'},
          {data: 'username', name: 'username'},
          {
            data: 'active',
            name: 'active',
            className: 'text-center',
            render: function (data, type, full, meta) {
              let status = {
                0: {'title': 'Tidak Aktif', 'class': ' bg-danger'},
                1: {'title': 'Aktif', 'class': ' bg-success'},
              };
              if (typeof status[data] === 'undefined') {
                return data;
              }
              return '<span class="badge bg-pill' + status[data].class + '">' + status[data].title +
                '</span>';
            },
          },
          {
            data: 'roles.name',
            name: 'name',
            className: 'text-center',
            orderable: false,
            searchable: false
          },
          {data: 'action', name: 'action', className:'text-center', orderable: false, searchable: false},
        ],
      });

      $('#select2Cabang, #selectActive').on('change', function (e) {
        dataTable.draw();
      });

      modalReset.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id');
        this.querySelector('input[name=id]').value = id;
      });

      modalReset.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=id]').value = '';
      });

      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.users.index") }}/' + id);
      });

      modalDelete.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('.urlDelete').setAttribute('href', '');
      });

      $("#formReset").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let btnSubmit = form.find("[type='submit']");
        let btnSubmitHtml = btnSubmit.html();
        let url = form.attr("action");
        let data = new FormData(this);
        $.ajax({
          beforeSend: function () {
                        btnSubmit.addClass("disabled").html("<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span> Loading ...").prop("disabled", "disabled");
          },
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url: url,
          data: data,
          success: function (response) {
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              bsReset.hide();
            } else {
              $("[role='alert']").parent().removeAttr("style");
              $(".alert-text").html('');
              $.each(response.error, function (key, value) {
                $(".alert-text").append('<span style="display: block">' + value + '</span>');
              });
              toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
            }
          },
          error: function (response) {
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            toastr.error(response.responseJSON.message, 'Failed !');
            bsReset.hide();
          }
        });
      });

      $("#formDelete").click(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = modalDelete.querySelector('.urlDelete').getAttribute('href');
        let btnHtml = form.html();
        let spinner = $("<i class='bx bx-hourglass bx-spin font-size-16 align-middle me-2'></i>");
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
            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              dataTable.draw();
              bsDelete.hide();
            } else {
              toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
              bsDelete.hide();
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
