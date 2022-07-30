@extends('layouts.master')

@section('content')
  <div class="col-lg-12">
    <div class="card">
      <div class="d-flex align-self-end m-4">
        <div>
          <a class="btn btn-primary" href="{{ route('backend.partai-member.create') }}">
            <i class="fe fe-plus"></i> Tambah
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="activeSelect">Status Publish <span class="text-danger">*</span></label>
              <select class="form-select select2" id="selectStatus" name="status">
                <option value="">Semua</option>
                <option value="0">Draft</option>
                <option value="1">Published</option>
              </select>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered border-bottom w-100" id="Datatable">
            <thead>
            <tr>
              <th>Image</th>
              <th>Nama</th>
              <th>Jabatan</th>
              <th>TTL</th>
              <th>No Anggota</th>
              <th>Komisi</th>
              <th>Daerah Pemilihan</th>
              <th>Agama</th>
              <th>Partai</th>
              <th>Period</th>
              <th>Aksi</th>
            </tr>
            </thead>
          </table>
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
          <button id="formDelete" type="button" class="btn btn-primary">Ya</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('css')
@endsection
@section('script')
  <script>
    $(document).ready(function () {
      let modalDelete = document.getElementById('modalDelete');
      const bsDelete = new bootstrap.Modal(modalDelete);

      let dataTable = $('#Datatable').DataTable({
        responsive: false,
        scrollX: true,
        processing: true,
        serverSide: true,
        order: [[3, 'desc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: {
          url: "{{ route('backend.partai-member.index') }}",
          data: function (d) {
            d.published = $('#selectStatus').find(':selected').val();
          }
        },
        columns: [
          {
            data: 'image',
            name: 'image',
            width: '100px',
            className: 'text-center',
            render: function (data, type, full, meta) {
              if (data) {
                return `<img src="/storage/images/thumbnail/${data}" style="min-width:100px; min-height: 150px;">`
              }
              return `<img src="/assets/img/svgs/no-content.svg" style="min-width:100px; min-height: 150px;">`
            },
          },
          {data: 'name', name: 'partai_members.name'},
          {data: 'position',name: 'partai_members.position'},
          {data: 'place_birth',name: 'partai_members.place_birth'},
          {data: 'no_member',name: 'partai_members.no_member'},
          {data: 'partai_member_name',name: 'partai_members.komisi_id'},
          {data: 'election_region_name',name: 'election_regions.election_region_id'},
          {data: 'religion',name: 'partai_members.religion'},
          {data: 'partai',name: 'partai_members.partai'},
          {data: 'period',name: 'partai_members.period'},
          {data: 'action', name: 'action', className: 'text-center', orderable: false, searchable: false},
        ],
      });

      $('#selectStatus').on('change', function () {
        dataTable.draw();
      });

      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.partai-member.index") }}/' + id);
      });

      modalDelete.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('.urlDelete').setAttribute('href', '');
      });

      $("#formDelete").click(function (e) {
        e.preventDefault();
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
            form.text('Submit').html(btnHtml).removeAttr('disabled');
            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              dataTable.draw();
              bsDelete.hide();
            } else {
              toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
              bsDelete.hide();
            }
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
