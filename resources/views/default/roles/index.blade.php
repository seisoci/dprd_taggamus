@extends('layouts.master')

@section('content')
  <div class="col-lg-12">
    <div class="card custom-card overflow-hidden">
      <div class="card-header d-flex justify-content-end">
        <a class="btn btn-primary my-2 btn-icon-text" href="#" data-bs-toggle="modal"
           data-bs-target="#modalCreate">
          <i class="fe fe-plus"></i> Tambah
        </a>
      </div>
      <div class="card-body">
        <div class="row">
        </div>
        <div class="table-responsive">
          <table class="table table-bordered border-bottom w-100" id="Datatable">
            <thead>
            <tr>
              <th>Nama</th>
              <th>Url Dashboard</th>
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
          <h5 class="modal-title" id="modalResetLabel">Tambah</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formStore" method="POST" action="{{ route('backend.roles.store') }}">
          @csrf
          <div class="modal-body">
            <div id="errorCreate" class="form-control" style="display:none;">
              <div class="alert alert-danger" role="alert">
                <div class="alert-text">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Nama Role <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" placeholder="Masukan nama role"/>
            </div>
            <div class="form-group">
              <label for="select2MenuPermissionCreate">Pilih dari modul</label>
              <select id="select2MenuPermissionCreate">
              </select>
            </div>
            <div class="form-group">
              <label>Halaman Awal Masuk <span class="text-danger">*</span></label>
              <input type="text" name="dashboard_url" class="form-control" placeholder="Masukan Url Dashboard"/>
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
          <h5 class="modal-title">Edit {{ $config['page_title'] }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
              <label>Nama Role <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" placeholder="Masukan nama role"/>
            </div>
            <div class="form-group">
              <label for="select2MenuPermissionEdit">Pilih dari modul</label>
              <select id="select2MenuPermissionEdit">
              </select>
            </div>
            <div class="form-group">
              <label>Halaman Awal Masuk <span class="text-danger">*</span></label>
              <input type="text" name="dashboard_url" class="form-control" placeholder="Masukan Url Dashboard"/>
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
        order: [[0, 'asc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: "{{ route('backend.roles.index') }}",
        columns: [
          {data: 'name', name: 'name'},
          {data: 'dashboard_url', name: 'dashboard_url'},
          {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
      });
      modalCreate.addEventListener('show.bs.modal', function (event) {
      });
      modalCreate.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=name]').value = '';
      });
      modalEdit.addEventListener('show.bs.modal', function (event) {
        let name = event.relatedTarget.getAttribute('data-bs-name');
        let dashboard_url = event.relatedTarget.getAttribute('data-bs-dashboard_url');
        this.querySelector('input[name=name]').value = name;
        this.querySelector('input[name=dashboard_url]').value = dashboard_url;
        this.querySelector('#formUpdate').setAttribute('action', '{{ route("backend.roles.index") }}/' + event.relatedTarget.getAttribute('data-bs-id'));
      });
      modalEdit.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=name]').value = '';
        this.querySelector('input[name=dashboard_url]').value = '';
        this.querySelector('#formUpdate').setAttribute('href', '');
      });
      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.roles.index") }}/' + id);
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
            btnSubmit.addClass("disabled").html("<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span> Loading ...").prop("disabled", "disabled");
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
              toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
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
            btnSubmit.addClass("disabled").html("<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span> Loading ...").prop("disabled", "disabled");
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
              toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
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
        let spinner = $("<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span>");
        $.ajax({
          beforeSend: function () {
            form.text(' Loading. . .').prepend(spinner).prop("disabled", "disabled");
          },
          type: 'DELETE',
          url: url,
          dataType: 'json',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          success: function (response) {
            toastr.success(response.message, 'Success !');
            form.text('Submit').html(btnHtml).removeAttr('disabled');
            dataTable.draw();
            bsDelete.hide();
          },
          error: function (response) {
            toastr.error(response.responseJSON.message, 'Failed !');
            form.text('Submit').html(btnHtml).removeAttr('disabled');
            bsDelete.hide();
          }
        });
      });

      $('#select2MenuPermissionCreate').select2({
        placeholder: 'Cari Menu Permission',
        dropdownParent: $('#select2MenuPermissionCreate').parent(),
        allowClear: true,
        width: '100%',
        ajax: {
          url: "{{ route('backend.menupermissions.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
        let data = e.params.data.url;
        $(this).parent().parent().find('input[name=dashboard_url]').val(data);
      });

      $('#select2MenuPermissionEdit').select2({
        placeholder: 'Cari Menu Permission',
        dropdownParent: $('#select2MenuPermissionEdit').parent(),
        allowClear: true,
        width: '100%',
        ajax: {
          url: "{{ route('backend.menupermissions.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
        let data = e.params.data.url;
        $(this).parent().parent().find('input[name=dashboard_url]').val(data);
      });

    });
  </script>
@endsection
