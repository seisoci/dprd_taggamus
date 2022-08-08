@extends('layouts.master')

@section('content')
  <div class="col-md-12">
    <div class="card custom-card">
      <form id="formUpdate"
            action="{{ route('backend.sliders.update', $data['id']) }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @method('PUT')
        <div class="card-body">
          <div id="errorCreate" class="mb-3" style="display:none;">
            <div class="alert alert-danger" role="alert">
              <div class="alert-text">
              </div>
            </div>
          </div>
          <div class="row">
  {{--          <div class="col-md-6">
              <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ $data['title'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Sub Title</label>
                <input type="text" name="sub_title" class="form-control" value="{{ $data['sub_title'] ?? '' }}">
              </div>
            </div>--}}
            <div class="col-md-6">
              <div id="items_1" class="form-group items">
                <label class="mx-0 text-bold d-block">Gambar</label>
                <img
                  src="{{ isset($data['image']) ? asset("/storage/images/thumbnail/".$data['image']) : asset('assets/img/svgs/no-content.svg') }}"
                  style="
                     background-size: cover;
                     background-position: center center;
                     border: 1px solid #d9d9d9"
                  class="mb-2 border-2 mx-auto"
                  height="100px"
                  width="200px">
                <input type="file" class="image d-block" name="image" accept=".jpg, .jpeg, .png">
                <p class="text-muted ms-75 mt-50"><small>Allowed JPG, JPEG or PNG. Max
                    size of
                    5MB</small></p>
              </div>
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
@endsection

@section('css')
@endsection
@section('script')
  <script>
    $(document).ready(function () {
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
