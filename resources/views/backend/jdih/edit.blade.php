@extends('layouts.master')

@section('content')
  <div class="col-md-12">
    <div class="card custom-card">
      <div class="card-header">
        <h4 class="card-title">Dokumen File</h4>
      </div>
      <div class="card-body">
        <div id="gridImage">
          @foreach($data['datastorage'] as $item)
            <div data-id="{{ $item['id'] ?? '' }}" class="grid-square">
              <a href="{{ asset("/storage/document/".$item['name']) }}" class="card bg-success img-card box-success-shadow">
                <div class="card-body">
                  <div class="d-flex">
                    <div class="text-white">
                      <h4 class="mb-0 number-font">{{ $item['name'] }}</h4>
                      <p class="text-white mb-0">Download</p>
                    </div>
                    <div class="ms-auto"><i class="fa-solid fa-download text-white fs-30 me-2 mt-2"></i></div>
                  </div>
                </div>
              </a>
              <button class="btn btn-danger btnDeletePhoto"
                      data-bs-id="{{ $item['id'] }}"
                      data-bs-toggle="modal"
                      data-bs-target="#modalDelete">
                <i class="fa fa-trash"></i>
              </button>
            </div>
          @endforeach
        </div>
      </div>
      <div id="footerImage" style="display: none" class="card-footer">
        <div class="d-flex justify-content-end">
          <button id="btnUpdateImage" type="button" class="btn ripple btn-warning">Update Sort</button>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-12 col-md-6">
    <div class="card custom-card">
      <form id="formUpdate" action="{{ route('backend.jdih.update', $data['id']) }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @method('PUT')
        <div class="card-body">
          <div id="errorEdit" class="mb-3" style="display:none;">
            <div class="alert alert-danger" role="alert">
              <div class="alert-text">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-8">
              <input type="hidden" name="type" value="jdih">
              <div class="form-group">
                <label>Judul</label>
                <input type="text" name="title" class="form-control" value="{{ $data['title'] ?? '' }}">
              </div>
              <div class="editor-container">
                <textarea name="body" id="editor">{!! $data['body'] ?? '' !!}</textarea>
              </div>
            </div>
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label for="">Tanggal</label>
                <div class="input-group">
                  <span class="input-group-text" id="publish_at"><i class="fa-regular fa-calendar"></i></span>
                  <input type="text" name="publish_at" class="form-control datePicker" aria-describedby="publish_at"
                         value="{{ $data['publish_at'] ?? \Carbon\Carbon::now()->toDateTimeString() }}"
                         readonly>
                </div>
              </div>
              <div class="form-group">
                <label>Kategori</label>
                <select id="select2PostCategories" class="form-control" multiple="multiple" name="post_categories[]">
                  @foreach($data['post_categories'] ?? array() as $item)
                    <option value="{{ $item['id'] ?? '' }}" selected>{{ $item['name'] ?? '' }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label class="mx-0 text-bold d-block">Gambar Cover</label>
                <img
                  src="{{ $data['image'] != NULL ? asset("/storage/images/original/".$data['image']) : asset('assets/img/svgs/no-content.svg') }}"
                  style="object-fit: cover; border: 1px solid #d9d9d9" class="mb-2 border-2 mx-auto"
                  height="200px"
                  width="200px" alt="">
                <input type="file" class="image d-block" name="image" accept=".jpg, .jpeg, .png">
                <p class="text-muted ms-75 mt-50"><small>Allowed JPG, JPEG or PNG. Max
                    size of
                    5MB</small></p>
              </div>
              <div class="form-group">
                <label for="">Status</label>
                <div class="custom-controls-stacked">
                  <label class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="published"
                           value="0" {{ !$data['published'] == 1 ? 'checked' : '' }}>
                    <span class="custom-control-label">Draft</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="published"
                           value="1" {{ $data['published'] == 1 ? 'checked' : '' }}>
                    <span class="custom-control-label">Publish</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 items" id="items_1">
              <div class="form-group">
                <label class="mx-0 text-bold d-block">Isi Dokumen</label>
                <input type="file" class="d-block" name="post_items[]" accept=".pdf">
                <p class="text-muted ms-75 mt-50"><small>Allowed PDF Max
                    size of
                    5MB</small></p>
              </div>
            </div>
            <div class="d-flex justify-content-start pt-2">
              <button type="button" id="btnAddPhoto" class="btn btn-sm btn-success">Tambah</button>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-secondary me-2" onclick="window.history.back();">
              Batal
            </button>
            <button type="submit" class="btn ripple btn-primary">Submit</button>
          </div>
        </div>
      </form>
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
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet"/>
  <style>
    .grid-square {
      position: relative;
      width: 225px;
      height: 150px;
      display: inline-block;
      background-color: #fff;
      margin: 12px;
    }

    .btnDeletePhoto {
      position: absolute;
      top: 4px;
      right: 4px;
    }
  </style>
@endsection
@section('script')
  <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.js') }}"></script>
  <script src="{{ asset('assets/plugins/sortable/Sortable.min.js') }}"></script>
  <script>
    $(document).ready(function () {
      let modalDelete = document.getElementById('modalDelete');
      const bsDelete = new bootstrap.Modal(modalDelete);
      initImage();

      ClassicEditor.create(document.querySelector("#editor"), {
        ckfinder: {
          uploadUrl: '{{route('backend.news.uploadimagecke').'?_token='.csrf_token()}}'
        },
        toolbar: {
          shouldNotGroupWhenFull: true
        }
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
              setTimeout(function () {
                if (!response.redirect || response.redirect === "reload") {
                  location.reload();
                } else {
                  location.href = response.redirect;
                }
              }, 1000);
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

      $("#select2PostCategories").select2({
        placeholder: 'Pilih Kategori',
        tags: true,
        dropdownPosition: 'below',
        width: '100%',
        ajax: {
          url: "{{route('backend.post-categories.select2')}}",
          dataType: 'json',
          delay: 100,
          cache: true,
          data: function (params) {
            return {
              type: 'posts',
              q: params.term,
              page: params.page || 1
            };
          },
          processResults: function (data) {
            return data

          }
        },
        createTag: function (params) {
          return undefined;
        }
      });

      $("#select2Taggables").select2({
        placeholder: 'Ketik Tags',
        tags: true,
        dropdownPosition: 'below',
        maximumSelectionLength: 5,
        width: '100%',
      });

      $('#btnAddPhoto').on('click', function () {
        let totalItems = $(".items").length;
        if (totalItems < 100) {
          let lastid = $(".items:last").attr("id");
          let split_id = lastid.split("_");
          let nextindex = Number(split_id[1]) + 1;
          $(".items:last").after($(`<div class="col-md-3 items" id="items_${nextindex}">`).append(
            $(`<div class='form-group'>`).append(
              $(`<label class="mx-0 text-bold d-block">Isi Dokumen</label>`),
              $(`<input type="file" class="image d-block" name="post_items[]" accept=".pdf">`),
              $(`<p class="text-muted ms-75 mt-50 mb-0"><small>Allowed PNG 5MB</small></p>`),
              $(`<button type="button" class="btn btn-sm btn-danger btnDelete">Hapus</button>`),
            )
          ));
        } else {
          toastr.error('Maksimal Upload 100 Gambar', 'Failed !');
        }
      });

      $('div').on('click', '.btnDelete', function () {
        let id = $(this).parent().parent().attr("id");
        let split_id = id.split("_");
        let deleteindex = split_id[1];
        $("#items_" + deleteindex).remove();
      });

      function initImage() {
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
      };

      $('input[name="publish_at"]').flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
        time_24hr: true,
      });

      let sortable = new Sortable(document.getElementById('gridImage'), {
        dataIdAttr: 'data-id',
        animation: 150,
        ghostClass: 'blue-background-class',
        onUpdate: function () {
          $('#footerImage').css('display', '');
        }
      });

      $('#btnUpdateImage').click(function (e) {
        e.preventDefault();
        let form = $(this);
        let btnHtml = form.html();
        let spinner = $("<i class='spinner-border spinner-border-sm font-size-16 align-middle me-2'></i>");
        $.ajax({
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          beforeSend: function () {
            form.text(' Loading. . .').prepend(spinner).prop("disabled", "disabled");
          },
          type: "POST",
          url: '{{ route('backend.photos.updateimage', ['gallery' => $data['id']]) }}',
          data: {
            data: sortable.toArray()
          },
          dataType: "json",
          success: function (response) {
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
              toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
            }
            form.text('Submit').html(btnHtml).removeAttr('disabled');
          }
        });
      });

      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.galleries.photos.index", ['gallery' => $data['id']]) }}/' + id);
      });

      modalDelete.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('.urlDelete').setAttribute('href', '');
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
            toastr.success(response.message, 'Success !');
            form.text('Submit').html(btnHtml).removeAttr('disabled');
            bsDelete.hide();
            if (response.status === "success") {
              setTimeout(() => {
                location.reload();
              }, 2000);
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


    });
  </script>
@endsection
