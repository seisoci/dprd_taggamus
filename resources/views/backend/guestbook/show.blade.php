@extends('layouts.master')

@section('content')
  <div class="col-lg-12">
    <div class="card custom-card overflow-hidden">
      <div class="card-header">
        <button class="btn btn-danger btn-sm" onclick="history.back()">
          <i class="fa-duotone fa-arrow-left"></i> Back
        </button>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" value="{{ $data['name'] ?? '' }}" readonly/>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Email <span class="text-danger">*</span></label>
              <input type="text" name="email" class="form-control" value="{{ $data['email'] ?? '' }}" readonly/>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label>Keterangan <span class="text-danger">*</span></label>
              <textarea name="description" class="form-control" rows="20" readonly>{{ $data['description'] ?? '' }}</textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{--Modal--}}
@endsection

@section('css')
@endsection
@section('script')
@endsection
