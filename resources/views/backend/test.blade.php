@extends('layouts.master')
@section('content')
    <div class="col-xl-6 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Inputs &amp; Textareas </h3>
            </div>
            <div class="card-body pb-2">
                <i class="fa-thin fa-cow"></i>
                <p>Textual form controls like <code class="highlighter-rouge">&lt;input&gt;</code> s and <code class="highlighter-rouge">&lt;textarea&gt;</code> s an upgrade with custom styles,focus states, and more.</p>
                <div class="row row-sm">
                    <div class="col-lg">
                        <input class="form-control mb-4" placeholder="Input box" type="text">
                    </div>
                    <div class="col-lg mg-t-10 mg-lg-t-0">
                        <input class="form-control mb-4" placeholder="Input box (readonly)" readonly="" type="text">
                    </div>
                    <div class="col-lg mg-t-10 mg-lg-t-0">
                        <input class="form-control mb-4" disabled="" placeholder="Input box (disabled)" type="text">
                    </div>
                </div>
                <div class="row row-sm">
                    <div class="col-lg">
                        <textarea class="form-control mb-4" placeholder="Textarea" rows="4"></textarea>
                    </div>
                    <div class="col-lg mg-t-10">
                        <textarea class="form-control mb-4" placeholder="Textarea (readonly)" readonly="" rows="4"></textarea>
                    </div>
                    <div class="col-lg mg-t-10">
                        <textarea class="form-control mb-4" disabled="" placeholder="Texarea (disabled)" rows="4"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')

@endsection
@section('script')

@endsection
