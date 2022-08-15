@extends('frontend.template-section')
@section('title_header', 'Pages')
@section('content')
  <section class="pages">
    <div class="container">
      <div class="row g-0">
        <div class="col-sm-3" data-aos="fade-right" data-aos-duration="1500">
          <ul class="side-menu">
            @foreach($data ?? array() as $item)
              <li class="{{ $item['id'] == $dataPages['id'] ? 'active' : '' }}"><a
                  href="{{ route('frontend.pages.show', $item['slug']) }}">{{ $item['title'] }}</a></li>
            @endforeach
          </ul>
        </div>
        <div class="col-sm-9" data-aos="fade-left" data-aos-duration="1500">
          <div class="content">
            <img src="{{ isset($dataPages['image']) ? asset("/storage/images/thumbnail/".$dataPages['image']) : '' }}"
                 class="img-fluid text-center mb-3" alt="">
            <h1 class="title">{{ $dataPages['title'] ?? '' }}</h1>
            {!! $dataPages['body'] ?? '' !!}
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('front-css')
@endsection
@section('script')
@endsection
