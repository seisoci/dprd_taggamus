@extends('layouts.master')

@section('title'){{ $config['page_title'] }}@endsection

@section('content')
  <div class="row">
    <div class="col-12 col">
      <div class="card">
        <div class="card-header">
          <div class="col-12">
            <div class="alert alert-danger" role="alert">
              <i class="fas fa-exclamation-triangle"></i>
              Konfigurasi yang salah dapat menyebabkan pengguna tidak dapat akses masuk ke halaman
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="card" style="border-radius: 0 !important;">
                <div class="card-body">
                  <div class="mb-3 d-flex flex-row justify-content-center align-items-center">
                    <div class="me-4">Role:</div>
                    <select id="select2Role" style="width: 30% !important;" name="roleId">
                      <option value="{{ $role->id ?? '' }}">{{ $role->name ?? '' }}</option>
                    </select>
                    <button id="btnChangeRole" class="btn btn-primary ms-4">Submit</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <form id="changeHierarchy" class="formStore" action="{{ route('backend.menu.changeHierarchy') }}">
                @csrf
                <div class="card">
                  <div class="card-header">
                    <h5>Struktur Menu</h5>
                  </div>
                  <div class="card-body">
                    <div class="dd" id="menuList">
                      {!! $sortable !!}
                    </div>
                  </div>
                  <div class="card-footer d-flex justify-content-end">
                    <input type="hidden" id="output" name="hierarchy"/>
                    <button type="submit" class="btn btn-warning" style="display: none"><i
                        class="fa fa-fw fa-plus"></i> Ubah
                    </button>
                  </div>
                </div>
              </form>
            </div>
            @if($role)
              <div class="col-md-6">
                <div class="card">
                  @if(Route::currentRouteName() == 'backend.menu.index')
                    <form class="formStore" method="POST" action="{{ route('backend.menu.store') }}">
                      @csrf
                      @else
                        <form id="formUpdate" method="POST" action="{{ route('backend.menu.update', $data->id) }}">
                          <meta name="csrf-token" content="{{ csrf_token() }}">
                          @method('PUT')
                          @endif
                          <div class="card-header">
                            <h5>Tambah/Edit</h5>
                          </div>
                          <div class="card-body">
                            <div id="errorCreate" class="mb-3" style="display:none;">
                              <div class="alert alert-danger" role="alert">
                                <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
                                <div class="alert-text">
                                </div>
                              </div>
                            </div>
                            <div class="mb-3">
                              <label>Role <span class="text-danger">*</span></label>
                              <input type="hidden" name="role_id" value="{{ $role->id ?? '' }}">
                              <input type="text" class="form-control" value="{{ $role->name ?? '' }}" disabled/>
                            </div>
                            <div class="mb-2">
                              <h5 class="font-size-14 mb-2">Pilih Menu <span class="text-danger">*</span></h5>
                              <div class="d-inline ms-5 me-2">
                                <input class="form-check-input" type="radio" name="type" value="static" id="formRadios1"
                                  {{ !isset($data->menu_permission_id) || Route::currentRouteName() != 'backend.menu.edit' ? 'checked' : '' }}>
                                <label class="form-check-label" for="formRadios1">Statis</label>
                              </div>
                              <div class="d-inline ms-4">
                                <input class="form-check-input" type="radio" name="type" value="database"
                                       id="formRadios2" {{ (isset($data->menu_permission_id) ?? $data->menu_permission_id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="formRadios2">Database</label>
                              </div>
                            </div>
                            <div id="createPage" class="mb-3"
                                 style="{{ !(isset($data->menu_permission_id) ?? $data->menu_permission_id) ? 'display:none;' : '' }}">
                              <label>Halaman <span class="text-danger">*</span></label>
                              <select id="select2MenuPermission" class="form-control" style="width:100% !important"
                                      name="menu_permission_id">
                                <option
                                  value="{{  $data->menupermission->id ?? '' }}">{{  $data->menupermission->title ?? '' }}</option>
                              </select>
                            </div>
                            <div class="mb-3"
                                 style="{{ (isset($data->menu_permission_id) ?? $data->menu_permission_id) ? 'display:none;' : '' }}">
                              <label>Nama <span class="text-danger">*</span></label>
                              <input type="text" name="title" class="form-control" placeholder="Masukan nama permission"
                                     value="{{ (isset($data) ?? $data->title) ? $data->title : '' }}"/>
                            </div>
                            <div class="mb-3"
                                 style="{{ (isset($data->menu_permission_id) ?? $data->menu_permission_id) ? 'display:none;' : '' }}">
                              <label>Path Url</label>
                              <input type="text" name="path_url" class="form-control"
                                     placeholder="ex: /backend/dashboard"
                                     value="{{ (isset($data) ?? $data->path_url) ? $data->path_url : '' }}"/>
                            </div>
                            <div class="mb-3"
                                 style="{{ (isset($data->menu_permission_id) ?? $data->menu_permission_id) ? 'display:none;' : '' }}">
                              <label>Icon</label>
                              <input type="text" name="icon" class="form-control"
                                     placeholder="ex: fas fa-address-card"
                                     value="{{ (isset($data) ?? $data->icon) ? $data->icon : '' }}"/>
                            </div>
                            <div id="modalCreatePermission" class="row"
                                 style="{{ !(isset($data->menu_permission_id) ?? $data->menu_permission_id) ? 'display:none;' : '' }}">
                              <label>Daftar Permissions</label>
                              <div class="col-md-6">
                                <div class="form-check mb-3">
                                  <input class="form-check-input" name="permission[]" value="list" type="checkbox"
                                         id="modalCreateList" {{ in_array("list", ($permissions ?? array())) ? 'checked' : '' }} >
                                  <label class="form-check-label" for="modalCreateList">List</label>
                                </div>
                                <div class="form-check mb-3">
                                  <input class="form-check-input" name="permission[]" value="edit" type="checkbox"
                                         id="modalCreateEdit" {{ in_array("edit", ($permissions ?? array())) ? 'checked' : '' }}>
                                  <label class="form-check-label" for="modalCreateEdit">Edit</label>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-check mb-3">
                                  <input class="form-check-input" name="permission[]" value="create" type="checkbox"
                                         id="modalCreateCreate" {{ in_array("create", ($permissions ?? array())) ? 'checked' : '' }}>
                                  <label class="form-check-label" for="modalCreateCreate">Create</label>
                                </div>
                                <div class="form-check mb-3">
                                  <input class="form-check-input" name="permission[]" value="delete" type="checkbox"
                                         id="modalCreateDelete" {{ in_array("delete", ($permissions ?? array())) ? 'checked' : '' }}>
                                  <label class="form-check-label" for="modalCreateDelete">Delete</label>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="card-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </div>
                        </form>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  {{--Modal--}}
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
  <link href="{{ asset('assets/plugins/nestable/nestable.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('script')
  <!-- pace js -->
  <script src="{{ asset('assets/plugins/nestable/nestable.js') }}" type="text/javascript"></script>
  <script type="text/javascript">
    $(function () {
      let modalDelete = document.getElementById('modalDelete');
      const bsDelete = new bootstrap.Modal(modalDelete);
      let select2Role = $('#select2Role');
      let select2MenuPermission = $('#select2MenuPermission');
      let radioCreate = document.querySelectorAll('input[name="type"]');

      $('#btnChangeRole').on('click', function (e) {
        e.preventDefault();
        let params = new URLSearchParams({
          role_id: $("select[name=roleId]").val() ?? '',
        });
        location.href = '{{ route('backend.menu.index') }}?' + params.toString();
      });

      $('#menuList').nestable({maxDepth: 3}).on('change', function () {
        let json_values = window.JSON.stringify($(this).nestable('serialize'));
        $("#output").val(json_values);
        $("#changeHierarchy [type='submit']").fadeIn();
      }).nestable('collapseAll');

      select2Role.select2({
        placeholder: "Cari Role",
        allowClear: true,
        ajax: {
          url: "{{ route('backend.roles.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      });

      select2MenuPermission.select2({
        placeholder: "Cari Menu Halaman",
        allowClear: true,
        ajax: {
          url: "{{ route('backend.menupermissions.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              role_id: select2Role.val() ?? '',
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      });

      radioCreate.forEach(el => {
        el.addEventListener('change', () => {
          let title = document.querySelector('input[name="title"]').parentNode;
          let path_url = document.querySelector('input[name="path_url"]').parentNode;
          let icon = document.querySelector('input[name="icon"]').parentNode;
          let permission = document.querySelector('#modalCreatePermission');
          if (el.checked && el.value === 'database') {
            document.querySelector('#createPage').style.display = '';
            title.style.display = 'none';
            path_url.style.display = 'none';
            icon.style.display = 'none';
            permission.style.display = '';
            title.value = '';
            path_url.value = '';
            icon.value = '';
          } else {
            document.querySelector('#createPage').style.display = 'none';
            title.style.display = '';
            path_url.style.display = '';
            icon.style.display = '';
            permission.style.display = 'none';
            title.children.value = '';
            path_url.value = '';
            icon.value = '';
          }
        });
      });

      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        let params = new URLSearchParams({
          role_id: $("select[name=roleId]").val() ?? '',
        });
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.menu.index") }}/' + id + '?' + params.toString());
      });

      modalDelete.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('.urlDelete').setAttribute('href', '');
      });

      $(".formStore").submit(function (e) {
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
              setTimeout(function () {
                if (!response.redirect || response.redirect === "reload") {
                  location.reload();
                } else {
                  location.href = response.redirect;
                }
              }, 1000);
            } else {
              $.each(response.error, function (key, value) {
                errorCreate.css('display', 'block');
                errorCreate.find('.alert-text').append('<span style="display: block">' + value + '</span>');
              });
              toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
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
            let errorCreate = $('#errorCreate');
            errorCreate.css('display', 'none');
            errorCreate.find('.alert-text').html('');
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              setTimeout(function () {
                if (!response.redirect || response.redirect === "reload") {
                  location.reload();
                } else {
                  location.href = response.redirect;
                }
              }, 1000);
            } else {
              $.each(response.error, function (key, value) {
                errorCreate.find('.alert-text').append('<span style="display: block">' + value + '</span>');
              });
              toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
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
        let params = new URLSearchParams({
          role_id: $("select[name=roleId]").val() ?? '',
        });
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
            toastr.success(response.message, 'Success !');
            form.text('Submit').html(btnHtml).removeAttr('disabled');
            bsDelete.hide();
            setTimeout(function () {
              if (!response.redirect || response.redirect === "reload") {
                location.reload();
              } else {
                location.href = response.redirect;
              }
            }, 1000);
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
