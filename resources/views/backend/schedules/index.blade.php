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
        <div class="table-responsive">
          <table class="table table-bordered border-bottom w-100" id="Datatable">
            <thead>
            <tr>
              <th>Judul</th>
              <th>Deskripsi</th>
              <th>Tgl. Mulai</th>
              <th>Tgl. Selesai</th>
              <th>Tgl. Dibuat</th>
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
        <form id="formStore" method="POST" action="{{ route('backend.schedules.store') }}">
          @csrf
          <div class="modal-body">
            <div id="errorCreate" style="display:none;">
              <div class="alert alert-danger" role="alert">
                <div class="alert-text">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Judul <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control"/>
            </div>
            <div class="form-group">
              <label>Deskripsi <span class="text-danger">*</span></label>
              <textarea class="form-control" name="description" rows="5"></textarea>
            </div>
            <div class="form-group">
              <label for="">Tanggal Mulai</label>
              <div class="input-group">
                <span class="input-group-text" id="date_start"><i class="fa-regular fa-calendar"></i></span>
                <input type="text" name="date_start" class="form-control dateTimePicker" aria-describedby="date_start"
                       value="{{ \Carbon\Carbon::now()->toDateTimeString() }}"
                       readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="">Tanggal Selesai</label>
              <div class="input-group">
                <span class="input-group-text" id="date_end"><i class="fa-regular fa-calendar"></i></span>
                <input type="text" name="date_end" class="form-control dateTimePicker" aria-describedby="date_end"
                       value="{{ \Carbon\Carbon::now()->toDateTimeString() }}"
                       readonly>
              </div>
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
              <label>Judul <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control"/>
            </div>
            <div class="form-group">
              <label>Deskripsi <span class="text-danger">*</span></label>
              <textarea class="form-control" name="description" rows="5"></textarea>
            </div>
            <div class="form-group">
              <label for="">Tanggal Mulai</label>
              <div class="input-group">
                <span class="input-group-text" id="date_start"><i class="fa-regular fa-calendar"></i></span>
                <input type="text" name="date_start" class="form-control dateTimePicker" aria-describedby="date_start"
                       value="{{ \Carbon\Carbon::now()->toDateTimeString() }}"
                       readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="">Tanggal Selesai</label>
              <div class="input-group">
                <span class="input-group-text" id="date_end"><i class="fa-regular fa-calendar"></i></span>
                <input type="text" name="date_end" class="form-control dateTimePicker" aria-describedby="date_end"
                       value="{{ \Carbon\Carbon::now()->toDateTimeString() }}"
                       readonly>
              </div>
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
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet"/>
@endsection
@section('script')
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.js') }}"></script>
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
        ajax: "{{ route('backend.schedules.index') }}",
        columns: [
          {data: 'title', name: 'title', width: '100px'},
          {data: 'description', name: 'description', width: '200px'},
          {data: 'date_start', name: 'date_start'},
          {data: 'date_end', name: 'date_end'},
          {data: 'created_at', name: 'created_at'},
          {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
      });

      modalCreate.addEventListener('show.bs.modal', function (event) {
      });
      modalCreate.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=title]').value = '';
        this.querySelector('textarea[name=description]').value = '';
        this.querySelector('input[name=date_start]').value = '';
        this.querySelector('input[name=date_end]').value = '';
      });
      modalEdit.addEventListener('show.bs.modal', function (event) {
        let title = event.relatedTarget.getAttribute('data-bs-title');
        let description = event.relatedTarget.getAttribute('data-bs-description');
        let date_start = event.relatedTarget.getAttribute('data-bs-date_start');
        let date_end = event.relatedTarget.getAttribute('data-bs-date_end');
        this.querySelector('input[name=title]').value = title;
        this.querySelector('textarea[name=description]').value = description;
        this.querySelector('input[name=date_start]').value = date_start;
        this.querySelector('input[name=date_end]').value = date_end;
        this.querySelector('#formUpdate').setAttribute('action', '{{ route("backend.schedules.index") }}/' + event.relatedTarget.getAttribute('data-bs-id'));
      });
      modalEdit.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=title]').value = '';
        this.querySelector('textarea[name=description]').value = '';
        this.querySelector('input[name=date_start]').value = '';
        this.querySelector('input[name=date_end]').value = '';
        this.querySelector('#formUpdate').setAttribute('href', '');
      });
      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.schedules.index") }}/' + id);
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

      $('.dateTimePicker').flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
        time_24hr: true,
      });

    });
  </script>
@endsection
