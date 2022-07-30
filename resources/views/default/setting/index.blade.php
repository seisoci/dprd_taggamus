@extends('layouts.master')

@section('content')
  <div class="col-lg-12">
    <div class="card custom-card overflow-hidden">
      <div class="card-body">
        <form id="formStore" action="{{ route('backend.settings.store') }}">
          @csrf
          <div class="form-group">
            <label class="mx-0 text-bold d-block">Icon</label>
            <img id="avatar"
                 src="{{ $data['favicon']['value'] != NULL && !empty($data['favicon']['value']) ? asset("storage/images/original/".$data['favicon']['value']) : asset('assets/img/svgs/no-content.svg') }}"
                 style="object-fit: cover; border: 1px solid #d9d9d9" class="mb-2 border-2 mx-auto" height="150px"
                 width="150px">
            <input type="file" class="image d-block" name="favicon" accept=".jpg, .jpeg, .png">
            <p class="text-muted ml-75 mt-50"><small>Allowed JPG, JPEG or PNG. Max
                size of
                5MB</small></p>
          </div>
          <div class="form-group">
            <label class="mx-0 text-bold d-block">Logo Tema Putih</label>
            <img id="avatar"
                 src="{{ $data['logoWhite']['value'] != NULL && !empty($data['logoWhite']['value']) ? asset("storage/images/original/".$data['logoWhite']['value']) : asset('assets/img/svgs/no-content.svg') }}"
                 style="object-fit: contain; border: 1px solid #d9d9d9" class="mb-2 border-2 mx-auto" height="100px"
                 width="250px">
            <input type="file" class="image d-block" name="logo_white" accept=".jpg, .jpeg, .png">
            <p class="text-muted ml-75 mt-50"><small>Allowed JPG, JPEG or PNG. Max
                size of
                5MB</small></p>
          </div>
          <div class="form-group">
            <label class="mx-0 text-bold d-block">Logo Tema Hitam</label>
            <img id="avatar"
                 src="{{ $data['logoBlack']['value'] != NULL && !empty($data['logoBlack']['value']) ? asset("storage/images/original/".$data['logoBlack']['value']) : asset('assets/img/svgs/no-content.svg') }}"
                 style="object-fit: contain; border: 1px solid #d9d9d9" class="mb-2 border-2 mx-auto" height="100px"
                 width="250px">
            <input type="file" class="image d-block" name="logo_black" accept=".jpg, .jpeg, .png">
            <p class="text-muted ml-75 mt-50"><small>Allowed JPG, JPEG or PNG. Max
                size of
                5MB</small></p>
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
  </div>
@endsection

@section('css')
@endsection
@section('script')
  <script>
    $(document).ready(function () {
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
              setTimeout(function () {
                if (response.redirect === "" || response.redirect === "reload") {
                  location.reload();
                } else {
                  location.href = response.redirect;
                }
              }, 1000);
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

      $(".image").change(function () {
        let thumb = $(this).parent().find('img');
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
