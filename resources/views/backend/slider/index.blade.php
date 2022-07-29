@extends('layouts.master')

@section('content')
  <div class="col-md-12">
    <div class="card custom-card">
      <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">{{ $config['page_title'] ?? '' }}</h4>
        <a class="btn btn-primary my-2 btn-icon-text" href="{{ route('backend.sliders.create') }}">
          <i class="fe fe-plus"></i> Tambah
        </a>
      </div>
      <div class="card-body">
        <div id="gridImage">
          @foreach($data ?? array() as $item)
            <div data-id="{{ $item['id'] ?? '' }}" class="grid-square">
              <img
                style="height: 100%; width: 100%; background-size: cover;"
                src="{{ isset($item['image']) ? asset("/storage/images/thumbnail/".$item['image']) : asset('assets/img/svgs/no-content.svg') }}">
              <div class="d-flex justify-content-between mt-2">
                <a href="{{ route('backend.sliders.edit', $item['id']) }}" class="btn btn-sm btn-primary w-50 me-1">
                  <i class="fa fa-pen-to-square"></i> Edit
                </a>
                <button class="btn btn-sm btn-danger btnDeletePhoto w-50 me-1"
                        data-bs-id="{{ $item['id'] }}"
                        data-bs-toggle="modal"
                        data-bs-target="#modalDelete">
                  <i class="fa fa-trash"></i> Delete
                </button>
              </div>

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
  {{-- Modal --}}
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
  <style>
    .grid-square {
      position: relative;
      width: 150px;
      height: 100px;
      display: inline-block;
      background-color: #fff;
      border: solid 1px rgb(0, 0, 0, 0.2);
      margin: 12px;
    }
  </style>
@endsection
@section('script')
  <script src="{{ asset('assets/plugins/sortable/Sortable.min.js') }}"></script>
  <script>
    $(document).ready(function () {
      let modalDelete = document.getElementById('modalDelete');
      const bsDelete = new bootstrap.Modal(modalDelete);

      $('div').on('click', '.btnDelete', function () {
        let id = $(this).parent().attr("id");
        let split_id = id.split("_");
        let deleteindex = split_id[1];
        $("#items_" + deleteindex).remove();
      });

      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.sliders.index") }}/' + id);
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
          url: '{{ route('backend.sliders.update-image') }}',
          data: {
            _method: 'PUT',
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

    });
  </script>
@endsection
