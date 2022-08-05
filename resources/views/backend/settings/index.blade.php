@extends('layouts.master')

@section('content')
  <div class="col-lg-12">
    <div class="card custom-card overflow-hidden">
      <div class="card-header justify-content-between">
        <h5 class="card-title mb-0">Setting</h5>
        <div class="float-end">
          <div class="btn-group">
            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalLogoLeft"><i
                class="fas fa-image"></i> Logo Kiri
            </button>
            <button type="button" class="btn btn-warning btn-sm text-white" data-bs-toggle="modal"
                    data-bs-target="#modalLogoRight"><i class="fas fa-image"></i> Logo Kanan
            </button>
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalFavicon"><i
                class="far fa-image"></i> Favicon Image
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered border-bottom w-100" id="Datatable">
            <tbody>
            @foreach($data as $item)
              <tr>
                <td style="width:180px;">{{ $item['description'] }}</td>
                <td style="width:10px;">:</td>
                <td style="width:calc(100% - 190px);">
                  <a href="#"
                     class="editable"
                     e-style="width: 100%"
                     data-name="keyword"
                     data-type="{{ $item['type'] }}"
                     data-pk="{{ $item['id'] }}"
                     data-url="{{ route('backend.settings.store') }}">{{ $item['value'] }}</a>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  {{--Modal--}}
  <div class="modal fade" id="modalLogoLeft" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalResetLabel">Logo Kiri</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="formStore" method="POST" action="{{ route('backend.settings.store') }}">
          @csrf
          <div class="modal-body">
            <div id="errorCreate" style="display:none;">
              <div class="alert alert-danger" role="alert">
                <div class="alert-text">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="mx-0 text-bold d-block">Logo Kiri</label>
              <input type="hidden" name="name" value="logo_left_url">
              <img
                   src="{{ $image['logo_left_url'] != NULL ? asset("/storage/images/assets/".$image['logo_left_url']) : asset('assets/img/svgs/no-content.svg') }}"
                   style="object-fit: cover; border: 1px solid #d9d9d9" class="mb-2 border-2 mx-auto"
                   height="300px"
                   width="220px" alt="">
              <input type="file" class="image d-block" name="image" accept=".jpg, .jpeg, .png">
              <p class="text-muted ms-75 mt-50"><small>Allowed JPG, JPEG or PNG. Max
                  size of
                  5MB</small></p>
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
  <div class="modal fade" id="modalLogoRight" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalResetLabel">Logo Kanan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="formStore" method="POST" action="{{ route('backend.settings.store') }}">
          @csrf
          <div class="modal-body">
            <div id="errorCreate" style="display:none;">
              <div class="alert alert-danger" role="alert">
                <div class="alert-text">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="mx-0 text-bold d-block">Logo Kiri</label>
              <input type="hidden" name="name" value="logo_right_url">
              <img
                src="{{ $image['logo_right_url'] != NULL ? asset("/storage/images/assets/".$image['logo_right_url']) : asset('assets/img/svgs/no-content.svg') }}"
                style="object-fit: cover; border: 1px solid #d9d9d9" class="mb-2 border-2 mx-auto"
                height="300px"
                width="220px" alt="">
              <input type="file" class="image d-block" name="image" accept=".jpg, .jpeg, .png">
              <p class="text-muted ms-75 mt-50"><small>Allowed JPG, JPEG or PNG. Max
                  size of
                  5MB</small></p>
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
  <div class="modal fade" id="modalFavicon" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalResetLabel">Favicon</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="formStore" method="POST" action="{{ route('backend.settings.store') }}">
          @csrf
          <div class="modal-body">
            <div id="errorCreate" style="display:none;">
              <div class="alert alert-danger" role="alert">
                <div class="alert-text">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="mx-0 text-bold d-block">Logo Kiri</label>
              <input type="hidden" name="name" value="favicon_url">
              <img
                src="{{ $image['favicon_url'] != NULL ? asset("/storage/images/assets/".$image['favicon_url']) : asset('assets/img/svgs/no-content.svg') }}"
                style="object-fit: cover; border: 1px solid #d9d9d9" class="mb-2 border-2 mx-auto"
                height="100px"
                width="100px" alt="">
              <input type="file" class="image d-block" name="image" accept=".jpg, .jpeg, .png">
              <p class="text-muted ms-75 mt-50"><small>Allowed JPG, JPEG or PNG. Max
                  size of
                  5MB</small></p>
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
@endsection

@section('css')
  <link href="{{ asset('assets/plugins/editable/bootstrap-editable.css') }}" rel="stylesheet"/>
  <style>
    .editable-container.editable-inline {
      width: 100%;
    }

    .editableform .control-group {
      width: 100%;
    }

    .editableform .control-group > div {
      width: 100%;
    }

    .editable-input {
      width: calc(100% - 70px);
    }

    .editableform .form-control {
      width: 100%;
    }
  </style>
@endsection
@section('script')
  <script src="{{ asset('assets/plugins/editable/bootstrap-editable.min.js') }}"></script>
  <script>
    $(document).ready(function () {
      let modalLogoLeft = document.getElementById('modalLogoLeft');
      const bsLogoLeft = new bootstrap.Modal(modalLogoLeft);
      let modalLogoRight = document.getElementById('modalLogoRight');
      const bsLogoRight = new bootstrap.Modal(modalLogoRight);
      let modalFavicon = document.getElementById('modalFavicon');
      const bsFavicon = new bootstrap.Modal(modalFavicon);

      $.fn.editable.defaults.mode = 'inline';
      $.fn.editable.defaults.inputclass = 'form-control form-control-sm';
      $(".editable").editable({
        ajaxOptions: {
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          type: "POST",
          dataType: "json",
        },
        success: function (response) {
          if (response.status == "success") {
            toastr.success(response.message, 'Success !', {
              positionClass: "toast-top-center", closeButton: true, progressBar: true, timeOut: 1000
            });
          } else {
            toastr.error(response.message, 'Failed !', {closeButton: true, positionClass: "toast-top-center"});
          }
        }
      });

      $(".image").change(function () {
        let thumb = $(this).parent().find('img');
        thumb.attr('src', '{{ asset('assets/img/svgs/no-content.svg') }}');
        if (this.files && this.files[0]) {
          let reader = new FileReader();
          reader.onload = function (e) {
            thumb.attr('src', e.target.result);
          }
          reader.readAsDataURL(this.files[0]);
        }
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
              bsLogoLeft.hide();
              bsLogoRight.hide();
              bsFavicon.hide();
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

    });
  </script>
@endsection
