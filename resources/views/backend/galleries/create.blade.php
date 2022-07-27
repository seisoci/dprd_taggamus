@extends('layouts.master')

@section('content')
  <div class="col-lg-12 col-md-6">
    <div class="card custom-card">
      <form id="formStore" action="{{ route('backend.galleries.store') }}">
        @csrf
        <div class="card-body">
          <div id="errorCreate" class="mb-3" style="display:none;">
            <div class="alert alert-danger" role="alert">
              <div class="alert-text">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-8">
              <input type="hidden" name="type" value="galleries">
              <div class="form-group">
                <label>Judul</label>
                <input type="text" name="title" class="form-control">
              </div>
              <div class="form-group">
                <label class="mx-0 text-bold d-block">Gambar Cover</label>
                <img src="{{ asset('assets/img/svgs/no-content.svg') }}"
                     style="object-fit: cover; border: 1px solid #d9d9d9" class="mb-2 border-2 mx-auto"
                     height="250px"
                     width="100%" alt="">
                <input type="file" class="image d-block" name="image" accept=".jpg, .jpeg, .png">
                <p class="text-muted ms-75 mt-50"><small>Allowed JPG, JPEG or PNG. Max
                    size of
                    2000kB</small></p>
              </div>
            </div>
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label for="">Tanggal</label>
                <div class="input-group">
                  <span class="input-group-text" id="publish_at"><i class="fa-regular fa-calendar"></i></span>
                  <input type="text" name="publish_at" class="form-control datePicker" aria-describedby="publish_at"
                         value="{{ \Carbon\Carbon::now()->toDateTimeString() }}"
                         readonly>
                </div>
              </div>
              <div class="form-group">
                <label for="">Status</label>
                <div class="custom-controls-stacked">
                  <label class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="published" value="0" checked>
                    <span class="custom-control-label">Draft</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="published" value="1">
                    <span class="custom-control-label">Publish</span>
                  </label>
                </div>
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
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet"/>
@endsection
@section('script')
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.js') }}"></script>
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
        thumb.attr('src', '{{ asset('assets/img/svgs/no-content.svg') }}');
        if (this.files && this.files[0]) {
          let reader = new FileReader();
          reader.onload = function (e) {
            thumb.attr('src', e.target.result);
          }
          reader.readAsDataURL(this.files[0]);
        }
      });

      $('input[name="publish_at"]').flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
        time_24hr: true,
      });

    });
  </script>
@endsection
