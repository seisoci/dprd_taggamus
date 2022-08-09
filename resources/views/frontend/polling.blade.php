@extends('frontend.template-section')
@section('title_header', 'Polling')
@section('content')
  <section class="polling">
    <div class="container">
      <div class="row g-0">
        <div class="col-md-7 col-lg-8">
          <div class="content" data-aos="fade-up" data-aos-duration="1500">
            <h1 class="title">{{ $data['title'] ?? '' }}</h1>
            <p>{{ $data['description'] ?? '' }}</p>
          </div>
        </div>
        <div class="col-md-5 col-lg-4">
          <div class="cage-polling" data-aos="fade-left" data-aos-duration="1500">
            <form id="formStore" action="{{ route('frontend.polling.store') }}">
              @csrf
              <input type="hidden" name="polling_id" value="{{ $data['id'] }}">
              @foreach($data['options'] ?? array() as $item)
                <div class="custom-control custom-radio">
                  <input type="radio" id="customRadio{{ $item['id'] }}" name="polling_option_id" class="custom-control-input" value="{{ $item['id'] }}">
                  <label class="custom-control-label" for="customRadio{{ $item['id'] }}">{{ $item['name'] }}</label>
                </div>
              @endforeach
              <button type="submit" class="btn btn-sm btn-custom">Kirim</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('front-css')
  <link rel="stylesheet" href="{{ asset('assets/plugins/aos/dist/aos.css') }}">
  <link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css') }}">
@endsection
@section('script')
  <script src="{{ asset('assets/plugins/aos/dist/aos.js') }}"></script>
  <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>
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
    });
  </script>
@endsection
