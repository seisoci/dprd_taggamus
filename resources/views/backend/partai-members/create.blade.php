@extends('layouts.master')

@section('content')
  <div class="col-lg-12 col-md-6">
    <div class="card custom-card">
      <form id="formStore" action="{{ route('backend.partai-member.store') }}">
        @csrf
        <div class="card-body">
          <div id="errorCreate" class="mb-3" style="display:none;">
            <div class="alert alert-danger" role="alert">
              <div class="alert-text">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label class="mx-0 text-bold d-block">Foto</label>
                <img src="{{ asset('assets/img/svgs/no-content.svg') }}"
                     style="object-fit: cover; border: 1px solid #d9d9d9" class="mb-2 border-2 mx-auto"
                     height="300px"
                     width="300px" alt="">
                <input type="file" class="image d-block" name="image" accept=".jpg, .jpeg, .png">
                <p class="text-muted ms-75 mt-50"><small>Allowed JPG, JPEG or PNG. Max
                    size of
                    5MB</small></p>
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Nama Anggota</label>
                <input type="text" name="name" class="form-control">
              </div>
              <div class="form-group">
                <label>Jabatan</label>
                <input type="text" name="position" class="form-control">
              </div>
              <div class="form-group">
                <label>Tempat, Tanggal Lahir</label>
                <input type="text" name="place_birth" class="form-control">
              </div>
              <div class="form-group">
                <label>No. Anggota</label>
                <input type="text" name="no_member" class="form-control">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Daerah Pemilihan</label>
                <select id="select2ElectionRegion" class="form-control" name="election_region_id">
                </select>
              </div>
              <div class="form-group">
                <label>Komisi</label>
                <select id="select2Komisi" class="form-control" name="komisi_id">
                </select>
              </div>
              <div class="form-group">
                <label>Agama</label>
                <select class=  "form-select" name="religion">
                  <option value="ISLAM">ISLAM</option>
                  <option value="KRISTEN">KRISTEN</option>
                  <option value="PROTESTAN">PROTESTAN</option>
                  <option value="HINDU">HINDU</option>
                  <option value="BUDHA">BUDHA</option>
                  <option value="KONGHUCU">KONGHUCU</option>
                  <option value="LAIN-LAIN">LAIN-LAIN</option>
                </select>
              </div>
              <div class="form-group">
                <label>Partai</label>
                <input type="text" name="partai" class="form-control">
              </div>
              <div class="form-group">
                <label>Priode</label>
                <input type="text" name="period" class="form-control">
              </div>
              <div class="form-group">
                <label>No Urut</label>
                <input type="text" name="sort" class="form-control">
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

      $("#select2ElectionRegion").select2({
        placeholder: 'Pilih Pemilihan Daerah',
        width: '100%',
        ajax: {
          url: "{{route('backend.election-regions.select2')}}",
          dataType: 'json',
          delay: 100,
          cache: true,
          data: function (params) {
            return {
              q: params.term,
              page: params.page || 1
            };
          },
        },
      });

      $("#select2Komisi").select2({
        placeholder: 'Pilih Komisi',
        width: '100%',
        ajax: {
          url: "{{route('backend.komisi.select2')}}",
          dataType: 'json',
          delay: 100,
          cache: true,
          data: function (params) {
            return {
              q: params.term,
              page: params.page || 1
            };
          }
        },
      });


    });
  </script>
@endsection
