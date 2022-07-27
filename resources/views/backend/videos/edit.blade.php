@extends('layouts.master')

@section('content')
  <div class="col-lg-12 col-md-6">
    <div class="card custom-card">
      <form id="formUpdate" action="{{ route('backend.videos.update', $data['id']) }}">
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
              <input type="hidden" name="type" value="videos">
              <div class="form-group">
                <label>Judul</label>
                <input type="text" name="title" class="form-control" value="{{ $data['title'] ?? '' }}">
              </div>
              <div class="form-group">
                <label>Youtube Url</label>
                <input id="urlYoutube" type="text" class="form-control" value="{{ $data['body'] }}"/>
                <input type="hidden" class="form-control" name="body" value="{{ $data['body'] }}"/>
              </div>
              <iframe id="boxview" width="100%" height="315" src="{{ $data['body'] }}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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

      $('#urlYoutube').keyup(function(){
        let value = $(this).val();
        $('input[name="body"]').val('https://www.youtube.com/embed/'+getId(value));
        $('#boxview').attr('src', 'https://www.youtube.com/embed/'+getId(value));
      });

      function getId(url) {
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        var match = url.match(regExp);

        if (match && match[2].length == 11) {
          return match[2];
        } else {
          return 'error';
        }
      }

      $('input[name="publish_at"]').flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
        time_24hr: true,
      });

    });
  </script>
@endsection
