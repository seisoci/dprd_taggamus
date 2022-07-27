@extends('layouts.master')

@section('content')
  <div class="row row-sm">
    <div class="col-lg-12">
      <div class="card custom-card overflow-hidden">
        <div class="card-body">
          <div class="d-flex mb-3">
            <div class="ms-auto">
              <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">Tambah</a>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered border-bottom w-100" id="Datatable">
              <thead>
              <tr>
                <th>Nama</th>
                <th>Path Url</th>
                <th>Icon</th>
                <th>Aksi</th>
              </tr>
              </thead>
            </table>
          </div>
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
        <form id="formStore" method="POST" action="{{ route('backend.permissions.store') }}">
          @csrf
          <div class="modal-body">
            <div id="errorCreate" class="mb-3" style="display:none;">
              <div class="alert alert-danger" role="alert">
                <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
                <div class="alert-text">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label>Nama Permission <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control" placeholder="Masukan nama permission"/>
            </div>
            <div class="mb-3">
              <label>Slug <span class="text-danger">*</span></label>
              <input type="text" name="slug" class="form-control" placeholder="Masukan nama slug"/>
            </div>
            <div class="mb-3">
              <label>Path Url <span class="text-danger">*</span></label>
              <input type="text" name="path_url" class="form-control" placeholder="ex: /backend/dashboard"/>
            </div>
            <div class="mb-3">
              <label>Icon <span class="text-danger">*</span></label>
              <input type="text" name="icon" class="form-control" placeholder="ex: fas fa-address-card"/>
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
            <div id="errorEdit" class="mb-3" style="display:none;">
              <div class="alert alert-danger" role="alert">
                <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
                <div class="alert-text">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label>Nama Permission <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control" placeholder="Masukan nama permission"/>
            </div>
            <div class="mb-3">
              <label>Slug <span class="text-danger">*</span></label>
              <input type="text" name="slug" class="form-control" placeholder="Masukan nama slug"/>
            </div>
            <div class="mb-3">
              <label>Path Url <span class="text-danger">*</span></label>
              <input type="text" name="path_url" class="form-control" placeholder="ex: /backend/dashboard"/>
            </div>
            <div class="mb-3">
              <label>Icon <span class="text-danger">*</span></label>
              <input type="text" name="icon" class="form-control" placeholder="ex: fas fa-address-card"/>
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
        pageLength: 50,
        ajax: "{{ route('backend.permissions.index') }}",
        columns: [
          {data: 'title', name: 'title'},
          {data: 'path_url', name: 'path_url'},
          {data: 'icon', name: 'icon', defaultContent: ''},
          {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        columnDefs: [
          {
            className: 'text-center',
            orderable: false,
            targets: 2,
            render: function (data, type, full, meta) {
              return `<i class="`+(data ? data : '')+` fa-2x"></i>`;
            }
          }
        ],
      });
      modalCreate.addEventListener('show.bs.modal', function (event) {
      });
      modalCreate.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=title]').value = '';
        this.querySelector('input[name=path_url]').value = '';
        this.querySelector('input[name=icon]').value = '';
        this.querySelector('input[name=slug]').value = '';
      });
      modalEdit.addEventListener('show.bs.modal', function (event) {
        let title = event.relatedTarget.getAttribute('data-bs-title');
        let slug = event.relatedTarget.getAttribute('data-bs-slug');
        let pathUrl = event.relatedTarget.getAttribute('data-bs-path_url');
        let icon = event.relatedTarget.getAttribute('data-bs-icon');
        this.querySelector('input[name=title]').value = title;
        this.querySelector('input[name=slug]').value = slug;
        this.querySelector('input[name=path_url]').value = pathUrl;
        this.querySelector('input[name=icon]').value = icon;
        this.querySelector('#formUpdate').setAttribute('action', '{{ route("backend.permissions.index") }}/' + event.relatedTarget.getAttribute('data-bs-id'));
      });
      modalEdit.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=title]').value = '';
        this.querySelector('#formUpdate').setAttribute('href', '');
      });
      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.permissions.index") }}/' + id);
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
      $("#formUpdate").submit(function(e){
        e.preventDefault();
        let form 	= $(this);
        let btnSubmit = form.find("[type='submit']");
        let btnSubmitHtml = btnSubmit.html();
        let url 	= form.attr("action");
        let data 	= new FormData(this);
        $.ajax({
          beforeSend:function() {
                        btnSubmit.addClass("disabled").html("<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span> Loading ...").prop("disabled", "disabled");
          },
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url : url,
          data : data,
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
    });
  </script>
@endsection
